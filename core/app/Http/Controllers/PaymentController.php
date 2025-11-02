<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\SenyProPaymentService;
use App\Models\Transaction;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected SenyProPaymentService $paymentService;

    public function __construct(SenyProPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display checkout page
     */
    public function checkout(Request $request, $planId): View
    {
        // Check if payment gateway is enabled
        if (!$this->paymentService->isEnabled()) {
            return redirect()->route('home')
                ->with('error', 'Payment gateway is currently unavailable. Please contact support.');
        }

        $plan = Plan::findOrFail($planId);
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        
        // Load all countries from JSON file
        $countries = json_decode(file_get_contents(storage_path('app/private/countries.json')), true);

        return view("{$activeTemplate}.checkout", compact('plan', 'countries'));
    }

    /**
     * Process checkout and create order
     */
    public function processCheckout(Request $request): RedirectResponse
    {
        // Check if payment gateway is enabled
        if (!$this->paymentService->isEnabled()) {
            return redirect()->back()
                ->with('error', 'Payment gateway is currently unavailable. Please contact support.')
                ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:2',
            'zip' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Log::info('==========================================');
            Log::info('PAYMENT CONTROLLER: Starting payment process', [
                'plan_id' => $request->plan_id,
                'customer_email' => $request->email,
            ]);
            
            DB::beginTransaction();

            $plan = Plan::findOrFail($request->plan_id);
            $externalOrderId = SenyProPaymentService::generateOrderId();

            Log::info('PAYMENT CONTROLLER: Plan and order ID generated', [
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'plan_price' => $plan->price,
                'external_order_id' => $externalOrderId,
            ]);

            // Prepare order data for SenyPro API
            $orderData = $this->paymentService->prepareOrderData([
                'external_order_id' => $externalOrderId,
                'amount' => $plan->price,
                'currency' => 'USD',
                'customer' => [
                    'email' => $request->email,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone ?? '',
                ],
                'billing_address' => [
                    'address1' => $request->address,
                    'address2' => $request->address2 ?? '',
                    'city' => $request->city,
                    'state' => $request->state ?? '',
                    'country' => $request->country,
                    'zip' => $request->zip,
                ],
                'products' => [
                    [
                        'name' => $plan->name,
                        'quantity' => 1,
                        'price' => $plan->price,
                    ]
                ],
                'return_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
                'webhook_url' => route('payment.webhook'),
            ]);

            Log::info('PAYMENT CONTROLLER: Order data prepared, calling SenyPro API...');

            // Create order via SenyPro API
            $result = $this->paymentService->createOrder($orderData);

            Log::info('PAYMENT CONTROLLER: SenyPro API response received', [
                'success' => $result['success'] ?? false,
                'has_payment_url' => isset($result['payment_url']),
                'error_message' => $result['message'] ?? 'none',
            ]);

            if (!$result['success']) {
                DB::rollBack();
                Log::error('PAYMENT CONTROLLER: Payment failed, rolling back', [
                    'error' => $result['message'],
                    'error_code' => $result['error_code'] ?? 'unknown',
                ]);
                return redirect()->back()
                    ->with('error', $result['message'])
                    ->withInput();
            }

            // Find or create user (guest checkout)
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'full_name' => $request->first_name . ' ' . $request->last_name,
                    'phone_number' => $request->phone,
                    'country' => $request->country,
                ]
            );

            // Create transaction record
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'external_order_id' => $externalOrderId,
                'request_id' => $result['request_id'],
                'transaction_number' => $result['transaction_number'],
                'payment_id' => $result['payment_id'],
                'senypro_transaction_id' => $result['transaction_id'],
                'senypro_order_id' => $result['order_id'],
                'amount' => $result['amount'],
                'currency' => $result['currency'],
                'status' => $result['status'],
                'payment_status' => 'Pending',
                'customer_email' => $request->email,
                'customer_name' => $request->first_name . ' ' . $request->last_name,
                'customer_phone' => $request->phone,
                'billing_address' => [
                    'address1' => $request->address,
                    'address2' => $request->address2 ?? '',
                    'city' => $request->city,
                    'state' => $request->state ?? '',
                    'country' => $request->country,
                    'zip' => $request->zip,
                ],
                'products' => [
                    [
                        'name' => $plan->name,
                        'quantity' => 1,
                        'price' => $plan->price,
                    ]
                ],
                'payment_url' => $result['payment_url'],
            ]);

            DB::commit();

            Log::info('PAYMENT CONTROLLER: Transaction created and committed successfully', [
                'transaction_id' => $transaction->id,
                'request_id' => $result['request_id'],
                'payment_url' => $result['payment_url'],
            ]);

            // Store transaction ID in session for success page
            session(['pending_transaction_id' => $transaction->id]);

            Log::info('PAYMENT CONTROLLER: Redirecting customer to payment URL');
            Log::info('==========================================');

            // Redirect to SenyPro payment page
            return redirect()->away($result['payment_url']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PAYMENT CONTROLLER: EXCEPTION CAUGHT', [
                'error' => $e->getMessage(),
                'type' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            Log::info('==========================================');

            return redirect()->back()
                ->with('error', 'An error occurred while processing your payment. Please try again.')
                ->withInput();
        }
    }

    /**
     * Payment success page
     */
    public function success(Request $request): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        $transactionId = session('pending_transaction_id');
        $transaction = null;

        if ($transactionId) {
            $transaction = Transaction::with(['plan', 'user'])->find($transactionId);

            // Check payment status with SenyPro API
            if ($transaction && $transaction->request_id) {
                $statusResult = $this->paymentService->getOrderStatus($transaction->request_id);

                if ($statusResult['success']) {
                    $data = $statusResult['data'];

                    // Update transaction with latest status
                    $transaction->update([
                        'status' => $data['status'],
                        'payment_status' => $data['payment_status'],
                        'completed_at' => $data['payment_status'] === 'Paid' ? now() : null,
                    ]);

                    // If payment is successful, create or update subscription and ensure user account
                    if ($data['payment_status'] === 'Paid' && !$transaction->subscription_id) {
                        // Ensure user has a password (create account if they don't have one)
                        $user = $transaction->user;
                        if ($user && !$user->password) {
                            // Generate a random password
                            $randomPassword = Str::random(12);
                            $user->update([
                                'password' => Hash::make($randomPassword),
                                'email_verified_at' => now(),
                            ]);
                            
                            // Store the plain password temporarily in session to show on success page
                            session(['new_user_password' => $randomPassword, 'new_user_email' => $user->email]);
                            
                            // Auto-login the user
                            Auth::guard('web')->login($user);
                            
                            Log::info('User account created with password after payment', [
                                'user_id' => $user->id,
                                'email' => $user->email,
                            ]);
                        }

                        $subscription = Subscription::create([
                            'user_id' => $transaction->user_id,
                            'plan_id' => $transaction->plan_id,
                            'price' => $transaction->amount,
                            'status' => 'active',
                            'payment_method' => 1, // Default to first payment method or adjust as needed
                            'transaction_id' => $transaction->id,
                            'expires_at' => now()->addDays($transaction->plan->duration),
                        ]);

                        $transaction->update(['subscription_id' => $subscription->id]);
                    }
                }
            }

            // Clear session
            session()->forget('pending_transaction_id');
        }

        return view("{$activeTemplate}.payment-success", compact('transaction'));
    }

    /**
     * Payment cancel page
     */
    public function cancel(Request $request): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        $transactionId = session('pending_transaction_id');
        $transaction = null;

        if ($transactionId) {
            $transaction = Transaction::with('plan')->find($transactionId);

            // Update transaction status to cancelled
            if ($transaction) {
                $transaction->update([
                    'status' => 'cancelled',
                    'payment_status' => 'Cancelled',
                ]);
            }

            session()->forget('pending_transaction_id');
        }

        return view("{$activeTemplate}.payment-cancel", compact('transaction'));
    }

    /**
     * Transaction history page
     */
    public function transactions(Request $request): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        
        // Get all transactions from database
        $transactions = Transaction::with(['plan', 'user', 'subscription'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view("{$activeTemplate}.transactions", compact('transactions'));
    }

    /**
     * View single transaction details
     */
    public function transactionDetail(Request $request, $id): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        $transaction = Transaction::with(['plan', 'user', 'subscription'])->findOrFail($id);

        // Fetch latest status from SenyPro API
        if ($transaction->request_id) {
            $statusResult = $this->paymentService->getOrderStatus($transaction->request_id);

            if ($statusResult['success']) {
                $data = $statusResult['data'];
                $transaction->update([
                    'status' => $data['status'],
                    'payment_status' => $data['payment_status'],
                ]);
                $transaction->refresh();
            }
        }

        return view("{$activeTemplate}.transaction-detail", compact('transaction'));
    }

    /**
     * Webhook handler for SenyPro payment notifications
     */
    public function webhook(Request $request)
    {
        try {
            Log::info('SenyPro Webhook Received', [
                'headers' => $request->headers->all(),
                'payload' => $request->all()
            ]);

            // Validate webhook payload
            $payload = $request->all();

            if (!isset($payload['request_id'])) {
                Log::error('SenyPro Webhook: Missing request_id');
                return response()->json(['error' => 'Invalid webhook payload'], 400);
            }

            // Find transaction by request_id
            $transaction = Transaction::where('request_id', $payload['request_id'])->first();

            if (!$transaction) {
                Log::error('SenyPro Webhook: Transaction not found', [
                    'request_id' => $payload['request_id']
                ]);
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            // Update transaction status
            $transaction->update([
                'status' => $payload['status'] ?? $transaction->status,
                'payment_status' => $payload['payment_status'] ?? $transaction->payment_status,
                'completed_at' => ($payload['payment_status'] ?? '') === 'Paid' ? now() : $transaction->completed_at,
            ]);

            // If payment is completed, create/update subscription and ensure user account
            if (($payload['payment_status'] ?? '') === 'Paid' && !$transaction->subscription_id) {
                // Ensure user has a password (create account if they don't have one)
                $user = $transaction->user;
                if ($user && !$user->password) {
                    // Generate a random password
                    $randomPassword = Str::random(12);
                    $user->update([
                        'password' => Hash::make($randomPassword),
                        'email_verified_at' => now(),
                    ]);
                    
                    Log::info('User account created with password after payment (webhook)', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);
                }

                $subscription = Subscription::create([
                    'user_id' => $transaction->user_id,
                    'plan_id' => $transaction->plan_id,
                    'price' => $transaction->amount,
                    'status' => 'active',
                    'payment_method' => 1,
                    'transaction_id' => $transaction->id,
                    'expires_at' => now()->addDays($transaction->plan->duration),
                ]);

                $transaction->update(['subscription_id' => $subscription->id]);

                Log::info('SenyPro Webhook: Subscription created', [
                    'subscription_id' => $subscription->id,
                    'transaction_id' => $transaction->id
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Webhook processed successfully']);
        } catch (\Exception $e) {
            Log::error('SenyPro Webhook Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }
}


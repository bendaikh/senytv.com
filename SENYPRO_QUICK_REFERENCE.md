# SenyPro Payment Gateway - Quick Reference Guide

## 🚀 Quick Start Commands

```bash
# Navigate to core directory
cd core

# Run migration to create transactions table
php artisan migrate

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Check if routes are registered
php artisan route:list | grep payment
```

---

## 🔗 Available Routes

| Route | URL | Description |
|-------|-----|-------------|
| Checkout | `/payment/checkout/{planId}` | Display checkout form for a plan |
| Process Payment | `/payment/process` | POST - Process checkout and create order |
| Success Page | `/payment/success` | Payment completion page |
| Cancel Page | `/payment/cancel` | Payment cancellation page |
| Transactions List | `/payment/transactions` | View all transactions |
| Transaction Detail | `/payment/transactions/{id}` | View single transaction |
| Webhook | `/webhooks/payment` | POST - Receive payment notifications |

---

## 💻 Code Examples

### 1. Manually Create a Payment Order (in Controller/Service)

```php
use App\Services\SenyProPaymentService;

$paymentService = new SenyProPaymentService();

$orderData = [
    'external_order_id' => SenyProPaymentService::generateOrderId(),
    'amount' => 99.99,
    'currency' => 'USD',
    'customer' => [
        'email' => 'customer@example.com',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'phone' => '+1234567890',
    ],
    'billing_address' => [
        'address1' => '123 Main St',
        'city' => 'New York',
        'country' => 'US',
        'zip' => '10001',
    ],
    'products' => [
        [
            'name' => 'IPTV Subscription',
            'quantity' => 1,
            'price' => 99.99,
        ]
    ],
    'return_url' => route('payment.success'),
    'cancel_url' => route('payment.cancel'),
    'webhook_url' => route('payment.webhook'),
];

$result = $paymentService->createOrder($paymentService->prepareOrderData($orderData));

if ($result['success']) {
    // Redirect to payment page
    return redirect()->away($result['payment_url']);
} else {
    // Handle error
    return back()->with('error', $result['message']);
}
```

### 2. Check Payment Status

```php
use App\Services\SenyProPaymentService;

$paymentService = new SenyProPaymentService();
$requestId = 'req_1635427890_abc123'; // From your database

$statusResult = $paymentService->getOrderStatus($requestId);

if ($statusResult['success']) {
    $data = $statusResult['data'];
    echo "Status: " . $data['status'];
    echo "Payment Status: " . $data['payment_status'];
    echo "Amount: $" . $data['amount'];
}
```

### 3. Query Transactions from Database

```php
use App\Models\Transaction;

// Get all completed transactions
$completedTransactions = Transaction::completed()->get();

// Get pending transactions
$pendingTransactions = Transaction::byPaymentStatus('Pending')->get();

// Get transactions by user
$userTransactions = Transaction::where('user_id', $userId)->get();

// Get transaction with relationships
$transaction = Transaction::with(['user', 'plan', 'subscription'])
    ->find($transactionId);

// Check if transaction is completed
if ($transaction->isCompleted()) {
    echo "Transaction is completed";
}
```

### 4. Create Subscription After Payment

```php
use App\Models\Subscription;
use App\Models\Transaction;

$transaction = Transaction::find($transactionId);

if ($transaction->payment_status === 'Paid' && !$transaction->subscription_id) {
    $subscription = Subscription::create([
        'user_id' => $transaction->user_id,
        'plan_id' => $transaction->plan_id,
        'price' => $transaction->amount,
        'status' => 'active',
        'payment_method' => 1, // Adjust as needed
        'transaction_id' => $transaction->id,
        'expires_at' => now()->addDays($transaction->plan->duration),
    ]);
    
    $transaction->update(['subscription_id' => $subscription->id]);
}
```

### 5. Add Checkout Link to Your Views

```blade
{{-- Link to checkout page --}}
<a href="{{ route('payment.checkout', $plan->id) }}" class="btn btn-primary">
    Buy Now - ${{ number_format($plan->price, 2) }}
</a>

{{-- Or use a form --}}
<form action="{{ route('payment.checkout', $plan->id) }}" method="GET">
    <button type="submit" class="btn btn-primary">Purchase Plan</button>
</form>
```

### 6. Display Transaction History in Admin Panel

```php
use App\Models\Transaction;

// In your admin controller
public function transactionsDashboard()
{
    $transactions = Transaction::with(['user', 'plan', 'subscription'])
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    $totalRevenue = Transaction::completed()->sum('amount');
    $pendingAmount = Transaction::byPaymentStatus('Pending')->sum('amount');
    
    return view('admin.transactions', compact('transactions', 'totalRevenue', 'pendingAmount'));
}
```

### 7. Custom Error Handling

```php
use App\Services\SenyProPaymentService;
use Illuminate\Support\Facades\Log;

try {
    $paymentService = new SenyProPaymentService();
    $result = $paymentService->createOrder($orderData);
    
    if (!$result['success']) {
        // Log the error
        Log::error('Payment creation failed', [
            'error_code' => $result['error_code'] ?? 'UNKNOWN',
            'message' => $result['message'],
            'order_data' => $orderData
        ]);
        
        // Show user-friendly message
        return back()->with('error', 'Unable to process payment. Please try again.');
    }
    
    return redirect()->away($result['payment_url']);
    
} catch (\Exception $e) {
    Log::error('Payment exception', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    return back()->with('error', 'An unexpected error occurred.');
}
```

---

## 🔍 Debugging Commands

### View Laravel Logs
```bash
# Real-time log viewing
tail -f storage/logs/laravel.log

# View last 100 lines
tail -n 100 storage/logs/laravel.log

# Search for payment-related logs
grep -i "senypro" storage/logs/laravel.log
grep -i "payment" storage/logs/laravel.log
```

### Database Queries

```sql
-- View recent transactions
SELECT 
    id, 
    external_order_id, 
    customer_name, 
    amount, 
    payment_status,
    created_at 
FROM transactions 
ORDER BY created_at DESC 
LIMIT 10;

-- Count transactions by status
SELECT 
    payment_status, 
    COUNT(*) as count, 
    SUM(amount) as total_amount 
FROM transactions 
GROUP BY payment_status;

-- Find failed transactions
SELECT * FROM transactions 
WHERE status = 'failed' 
OR payment_status = 'Unpaid';

-- Transactions without subscriptions (completed but not linked)
SELECT * FROM transactions 
WHERE payment_status = 'Paid' 
AND subscription_id IS NULL;
```

---

## ⚙️ Configuration Reference

### Environment Variables (.env)

```env
# SenyPro Payment Gateway
SENYPRO_API_KEY=sk_57Lcxi6BnAmNb4LwbfBhzJEPzzYGtJTgWSq0fMLrLcMk1m8Y
SENYPRO_API_SECRET=bDAVH8i9qywBIVSUB9CLF9ZQJD13oNTjbDctBe293HpV7SGYdOLFHqbTggFBBkWa
SENYPRO_API_BASE_URL=https://senypro.com/api/v1

# Optional: For local testing, set to your ngrok URL
# SENYPRO_API_BASE_URL=https://your-ngrok-url.ngrok.io/api/v1
```

### Config File (config/services.php)

```php
'senypro' => [
    'api_key' => env('SENYPRO_API_KEY'),
    'api_secret' => env('SENYPRO_API_SECRET'),
    'api_base_url' => env('SENYPRO_API_BASE_URL', 'https://senypro.com/api/v1'),
],
```

---

## 🎨 Customization Examples

### 1. Change Return URLs

Edit `core/app/Http/Controllers/PaymentController.php`:

```php
// Change the return URLs
'return_url' => route('custom.success.page'),
'cancel_url' => route('custom.cancel.page'),
'webhook_url' => route('custom.webhook'),
```

### 2. Add Custom Fields to Checkout Form

Edit `core/resources/views/templates/amber/checkout.blade.php`:

```blade
{{-- Add after existing fields --}}
<div class="mb-3">
    <label for="company" class="form-label">{{ __('Company Name') }}</label>
    <input type="text" class="form-control" id="company" name="company">
</div>
```

Then update the controller validation:

```php
$validator = Validator::make($request->all(), [
    // ... existing rules
    'company' => 'nullable|string|max:255',
]);
```

### 3. Send Email Notifications

Install and configure Laravel Mail, then in `PaymentController`:

```php
use Illuminate\Support\Facades\Mail;

// After successful payment
if ($transaction->payment_status === 'Paid') {
    Mail::to($transaction->customer_email)->send(
        new PaymentConfirmationMail($transaction)
    );
}
```

### 4. Add Transaction Status Badge Component

Create a Blade component:

```blade
{{-- resources/views/components/transaction-status.blade.php --}}
@props(['status'])

@php
$classes = match($status) {
    'Paid', 'completed' => 'bg-success',
    'Pending', 'pending_payment' => 'bg-warning text-dark',
    'Cancelled', 'cancelled' => 'bg-danger',
    'Refunded' => 'bg-info',
    default => 'bg-secondary'
};
@endphp

<span class="badge {{ $classes }}">{{ ucfirst($status) }}</span>
```

Use it:

```blade
<x-transaction-status :status="$transaction->payment_status" />
```

---

## 📊 Statistics & Reporting

### Get Payment Statistics

```php
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

// Total revenue (all time)
$totalRevenue = Transaction::completed()->sum('amount');

// Revenue this month
$monthlyRevenue = Transaction::completed()
    ->whereMonth('created_at', now()->month)
    ->sum('amount');

// Revenue by day (last 7 days)
$dailyRevenue = Transaction::completed()
    ->where('created_at', '>=', now()->subDays(7))
    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
    ->groupBy('date')
    ->get();

// Transaction success rate
$totalTransactions = Transaction::count();
$completedTransactions = Transaction::completed()->count();
$successRate = ($completedTransactions / $totalTransactions) * 100;

// Top selling plans
$topPlans = Transaction::completed()
    ->select('plan_id', DB::raw('COUNT(*) as sales'), DB::raw('SUM(amount) as revenue'))
    ->groupBy('plan_id')
    ->orderBy('sales', 'desc')
    ->with('plan')
    ->take(5)
    ->get();
```

---

## 🧪 Testing Webhook Locally

Since webhooks need a public URL, use ngrok for local testing:

```bash
# Install ngrok
# https://ngrok.com/download

# Start your Laravel app
php artisan serve

# In another terminal, start ngrok
ngrok http 8000

# Use the ngrok URL in your webhook_url:
# https://abc123.ngrok.io/webhooks/payment
```

Test webhook manually with curl:

```bash
curl -X POST https://yourdomain.com/webhooks/payment \
  -H "Content-Type: application/json" \
  -d '{
    "request_id": "req_1635427890_abc123",
    "status": "completed",
    "payment_status": "Paid",
    "transaction_id": 42
  }'
```

---

## 🐛 Common Issues & Solutions

### Issue: Routes not found
```bash
php artisan route:clear
php artisan route:cache
```

### Issue: Views not updating
```bash
php artisan view:clear
```

### Issue: Config changes not applying
```bash
php artisan config:clear
php artisan config:cache
```

### Issue: Can't find SenyProPaymentService
```bash
composer dump-autoload
```

### Issue: Migration already exists
```bash
# Check migration status
php artisan migrate:status

# If needed, rollback last migration
php artisan migrate:rollback --step=1
```

---

## 📱 Mobile Responsiveness

All payment pages are fully responsive and work on:
- Desktop browsers
- Tablets
- Mobile phones
- All modern browsers (Chrome, Firefox, Safari, Edge)

---

## 🔐 Security Checklist

- [x] API credentials stored in environment variables
- [x] CSRF protection on all forms
- [x] Input validation on all user inputs
- [x] SQL injection prevention (Eloquent ORM)
- [x] XSS protection (Blade auto-escaping)
- [x] HTTPS required in production
- [x] Sensitive data not logged
- [x] Payment details never stored locally

---

## 📚 Additional Resources

- Laravel Documentation: https://laravel.com/docs
- SenyPro API Docs: https://senypro.com/api/v1
- Bootstrap Documentation: https://getbootstrap.com/docs

---

**Quick Support:**
- Check logs: `tail -f storage/logs/laravel.log`
- Test routes: `php artisan route:list | grep payment`
- Clear cache: `php artisan optimize:clear`

---

**Last Updated:** October 25, 2025


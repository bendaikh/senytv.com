<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subscriptions = Subscription::with(['user', 'plan', 'paymentMethod'])
            ->when($request->search, function ($query) use ($request) {
                $query->where('transaction_id', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($query) use ($request) {
                        $query->where('full_name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%');
                    });
            })
            ->paginate($request->perPage ?? 15);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::select('id', 'full_name')->get();
        $plans = Plan::select('id', 'name', 'price', 'duration')->get();
        $active_payment_methods = PaymentMethod::select('id', 'name')->get();
        return view('admin.subscriptions.create', compact('users', 'plans', 'active_payment_methods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $validated = $request->validated();

        $plan = Plan::findOrFail($validated['plan_id']);

        // Generate the transaction ID and calculate the expiry date
        $transaction_id = uniqid('txn_' . time() . '_');
        $expires_at = now()->addDays($plan->duration);

        Subscription::create([
            'user_id' => $validated['user_id'],
            'plan_id' => $validated['plan_id'],
            'price' => $plan->price,
            'status' => $validated['status'],
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $transaction_id,
            'expires_at' => $expires_at,
        ]);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        $users = User::all();
        $plans = Plan::all();

        return view('admin.subscriptions.edit', compact('subscription', 'users', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'status' => 'required|in:pending,active,expired,canceled',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);

        $subscription->update([
            'plan_id' => $validated['plan_id'],
            'price' => $plan->price,
            'status' => $validated['status'],
            'expires_at' => now()->addMonths($plan->duration),
        ]);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete(); // Soft delete

        return redirect()->back()->with('success', 'Subscription deleted successfully.');
    }
}

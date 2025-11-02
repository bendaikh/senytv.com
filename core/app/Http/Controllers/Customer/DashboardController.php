<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Transaction;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\Ticket;

class DashboardController extends Controller
{
    /**
     * Show customer dashboard
     */
    public function index(Request $request): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        $user = Auth::guard('web')->user();
        
        // Get active subscription
        $activeSubscription = $user->activeSubscription()->with(['plan', 'paymentMethod'])->first();
        
        // Get recent transactions
        $recentTransactions = $user->transactions()
            ->with(['plan', 'subscription'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get ticket stats
        $ticketStats = [
            'total' => $user->tickets()->count(),
            'open' => $user->tickets()->where('status', 'open')->count(),
            'resolved' => $user->tickets()->where('status', 'resolved')->count(),
        ];

        return view("{$activeTemplate}.customer.dashboard", compact('user', 'activeSubscription', 'recentTransactions', 'ticketStats'));
    }

    /**
     * Show customer payments/transactions
     */
    public function payments(Request $request): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        $user = Auth::guard('web')->user();
        
        $transactions = $user->transactions()
            ->with(['plan', 'subscription'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view("{$activeTemplate}.customer.payments", compact('transactions'));
    }

    /**
     * Show customer plans/subscriptions
     */
    public function plans(Request $request): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        $user = Auth::guard('web')->user();
        
        $subscriptions = $user->subscriptions()
            ->with(['plan', 'paymentMethod', 'transaction'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Get available plans for upgrade
        $availablePlans = Plan::where('language', app()->getLocale())
            ->orWhere('language', 'en')
            ->get();

        return view("{$activeTemplate}.customer.plans", compact('subscriptions', 'availablePlans'));
    }

    /**
     * Show customer tickets
     */
    public function tickets(Request $request): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        $user = Auth::guard('web')->user();
        
        $tickets = $user->tickets()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view("{$activeTemplate}.customer.tickets", compact('tickets'));
    }

    /**
     * Show create ticket form
     */
    public function createTicket(Request $request): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        return view("{$activeTemplate}.customer.ticket-create");
    }

    /**
     * Store new ticket
     */
    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $user = Auth::guard('web')->user();

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority' => $validated['priority'] ?? 'medium',
            'status' => 'open',
        ]);

        return redirect()->route('customer.tickets')
            ->with('success', 'Support ticket created successfully.');
    }

    /**
     * Show single ticket
     */
    public function showTicket(Request $request, $id): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        $user = Auth::guard('web')->user();
        
        $ticket = $user->tickets()->findOrFail($id);

        return view("{$activeTemplate}.customer.ticket-show", compact('ticket'));
    }
}

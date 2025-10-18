<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{

    public function index()
    {
        $total_users = DB::table('users')->count();

        $total_earnings = DB::table('subscriptions')
            ->whereNull('deleted_at')
            ->sum('price');

        $active_subscriptions = DB::table('subscriptions')
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->count();

        $new_subscriptions = DB::table('subscriptions')
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->whereMonth('created_at', now()->month)
            ->count();

        return view('admin.index', compact(
            'total_users',
            'total_earnings',
            'active_subscriptions',
            'new_subscriptions'
        ));
    }


    public function clearCache(Request $request)
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        return redirect()->back()->with('success', 'Cache cleared successfully.');
    }
}

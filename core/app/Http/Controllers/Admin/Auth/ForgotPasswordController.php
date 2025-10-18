<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Admin;
use App\Notifications\AdminResetPasswordNotification;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('admin.auth.forgotpassword');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if the admin exists
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withErrors(['email' => 'Admin not found.']);
        }

       
        // Generate a password reset token
        $token = Password::broker('admins')->createToken($admin);


        // Notify the admin user with the reset password notification
        $admin->notify(new AdminResetPasswordNotification($token, $admin->email));

        return back()->with(['success' => 'Password reset link sent successfully.']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::all();
        return view('admin.payment-methods', compact('methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name',
            'status' => 'required|in:1,0',
            'img' => 'required|image:allow_svg|mimes:png,jpg,jpeg,webp,svg|max:2048',
        ]);


        $imagePath = uploadImage($request->file('img'), 'payments');

        PaymentMethod::create([
            'name' => $request->name,
            'img' => $imagePath,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Payment method added successfully.');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payment_methods')->ignore($paymentMethod->id),
            ],
            'status' => 'required|in:1,0',
            'img' => 'nullable|image:allow_svg|mimes:png,jpg,jpeg,webp,svg|max:2048',
        ]);

        // Check if an image file is uploaded
        if ($request->hasFile('img')) {
            // Delete the old image if it exists
            if ($paymentMethod->img) {
                deleteImage($paymentMethod->img);
            }

            // Upload new image
            $imagePath = uploadImage($request->file('img'), 'payments');
            $paymentMethod->img = $imagePath;
        }

        $paymentMethod->name = $request->name;
        $paymentMethod->status = $request->status;
        $paymentMethod->save();
        Cache::forget('active_payment_methods');

        return back()->with('success', 'Payment method updated successfully.');
    }


    public function destroy(PaymentMethod $paymentMethod)
    {

        if ($paymentMethod->img) {
            deleteImage($paymentMethod->img);
        }
        $paymentMethod->delete();
        Cache::forget('active_payment_methods');
        return back()->with('admin.payment-methods')->with('success', 'Payment method deleted successfully.');
    }
}

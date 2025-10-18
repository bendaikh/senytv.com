<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TermsOfServiceManagements;

class TosController extends Controller
{
    public function index()
    {
        $tos = TermsOfServiceManagements::firstOrNew([]);
        return view('admin.tos', compact('tos'));
    }

    public function savePolicy(Request $request)
    {
        $this->updateContent($request, 'privacy_policy', 'Privacy Policy');
        return redirect()->back()->with('success', 'Privacy Policy updated successfully.');
    }

    public function saveTerms(Request $request)
    {
        $this->updateContent($request, 'terms_of_use', 'Terms of Use');
        return redirect()->back()->with('success', 'Terms of Use updated successfully.');
    }

    public function saveRefund(Request $request)
    {
        $this->updateContent($request, 'refund_policy', 'Refund Policy');
        return redirect()->back()->with('success', 'Refund Policy updated successfully.');
    }

    private function updateContent(Request $request, string $field, string $fieldName)
    {
        $request->validate([
            $field => 'required|string',
        ]);

        $tos = TermsOfServiceManagements::firstOrNew([]);
        $tos->{$field} = $request->input($field);
        $tos->{$field . '_updated_at'} = now();
        $tos->save();
    }
}

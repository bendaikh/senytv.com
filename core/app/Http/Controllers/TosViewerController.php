<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TermsOfServiceManagements;

class TosViewerController extends Controller
{ 
    public function privacy(Request $request)
    {
        $activeTemplate = $request->attributes->get('activeTemplate');
        $privacy = TermsOfServiceManagements::select('privacy_policy', 'privacy_policy_updated_at')->first();
        return view($activeTemplate . '.tos.privacy', compact('privacy'));
    }

    public function terms(Request $request)
    {
        $activeTemplate = $request->attributes->get('activeTemplate');
        $terms = TermsOfServiceManagements::select('terms_of_use', 'terms_of_use_updated_at')->first();
        return view($activeTemplate . '.tos.terms', compact('terms'));
    }

    public function refund(Request $request)
    {
        $activeTemplate = $request->attributes->get('activeTemplate');
        $refund = TermsOfServiceManagements::select('refund_policy', 'refund_policy_updated_at')->first();
        return view($activeTemplate . '.tos.refund', compact('refund'));
    }
}

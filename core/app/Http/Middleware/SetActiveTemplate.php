<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;
use App\Models\Setting;


class SetActiveTemplate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $templateName = Setting::getSetting('active_template', 'amber');
        
        $activeTemplate = "templates." . $templateName;
        $activeTemplatePath = "templates/" . $templateName;
    
        View::share('activeTemplate', $activeTemplate);
        View::share('activeTemplatePath', $activeTemplatePath);
    
        $request->attributes->add([
            'activeTemplate' => $activeTemplate,
            'activeTemplatePath' => $activeTemplatePath
        ]);
    
        return $next($request);
    }
    

}

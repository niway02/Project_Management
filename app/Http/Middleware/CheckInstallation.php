<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class CheckInstallation
{
    public function handle($request, Closure $next)
    {
        $sqlDumpPath = base_path('taskify.sql');
        $installViewPath = resource_path('views/install.blade.php');
        // dd($is_installation_completed);
        // Check if the installation has been completed
        if (!file_exists($sqlDumpPath) && !file_exists($installViewPath)) {
            // The installation has not been completed, redirect to the installation page
            return $next($request);
        }

        return redirect('/install')->with('error', 'Please complete the installation first.');
        // The installation has been completed, allow access to the home page
    }
}

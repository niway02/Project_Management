<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ValidateSignature as BaseValidateSignature;

class CustomValidateSignature extends BaseValidateSignature
{
    public function handle($request, Closure $next, ...$args)
    {
        [$relative, $ignore] = $this->parseArguments($args);

        if ($request->hasValidSignatureWhileIgnoring($ignore, ! $relative)) {
            return $next($request);
        }

        return redirect()->route('verification.notice')->with('error', 'Invalid signature.');
    }
}

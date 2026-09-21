<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApprovedBorrower
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isBorrower() && !$user->is_approved) {
            return redirect()->route('dashboard')->with('error', 'Your borrower account is pending approval by BIND-Tech Tool Custodian/Admin before you can borrow tools.');
        }

        return $next($request);
    }
}

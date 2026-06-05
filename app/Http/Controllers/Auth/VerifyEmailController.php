<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $isAdmin = $request->user()->role === 'admin';
        $targetRoute = $isAdmin 
            ? route('admin.dashboard', absolute: false) 
            : route('citizen.dashboard', absolute: false);

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($targetRoute.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended($targetRoute.'?verified=1');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            $redirectRoute = $request->user()->role === 'admin'
                ? route('admin.dashboard', absolute: false)
                : route('citizen.dashboard', absolute: false);

            return redirect()->intended($redirectRoute);
        }

        return view('auth.verify-email');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Models\EmailRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }

    public function confirmRequest(Request $request)
    {
        $request->validate([
            'email' => ['email', 'required', 'string', 'max:255'],
            'token' => 'required'
        ]);

        $emailRequest = EmailRequest::where('email', $request->email)->where('token', $request->token)->firstOrFail();

        $emailRequest->verified_at = now();
        $emailRequest->save();
        

        $user = $emailRequest->user;
        $user->email = $request->email;
        $user->email_verified_at = now();
        $user->save();

        return redirect('login')->withStatus('New email!');
    }
}

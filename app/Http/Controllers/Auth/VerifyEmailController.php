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
        // Jika email sudah diverifikasi sebelumnya, arahkan ke dashboard yang sesuai
        if ($request->user()->hasVerifiedEmail()) {
            // Jika email sudah diverifikasi, arahkan ke dashboard sesuai role
            return $this->redirectBasedOnRole($request);
        }

        // Jika email belum diverifikasi, tandai sebagai verified
        if ($request->user()->markEmailAsVerified()) {
            // Trigger event verified
            event(new Verified($request->user()));
        }

        // Setelah verifikasi, arahkan ke dashboard sesuai role
        return $this->redirectBasedOnRole($request);
    }

    /**
     * Redirect user based on their role after email verification.
     */
    protected function redirectBasedOnRole($request): RedirectResponse
    {
        // Arahkan berdasarkan role pengguna (admin atau user biasa)
        if ($request->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }
}

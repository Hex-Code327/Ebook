<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Jika user adalah admin, tampilkan edit form, jika tidak hanya bisa melihat
        if (Auth::user()->role !== 'admin' && Auth::id() !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Cek apakah pengguna adalah admin atau dia sendiri
        if (Auth::user()->role !== 'admin' && Auth::id() !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Update informasi profil yang valid
        $request->user()->fill($request->validated());

        // Jika email diubah, set email_verified_at ke null
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Simpan perubahan
        $request->user()->save();

        // Redirect kembali dengan status pesan sukses
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Cek apakah pengguna adalah admin atau dia sendiri
        if (Auth::user()->role !== 'admin' && Auth::id() !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi password untuk penghapusan akun
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Ambil user yang sedang login
        $user = $request->user();

        // Logout user
        Auth::logout();

        // Hapus user
        $user->delete();

        // Invalidate session dan regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman utama
        return Redirect::to('/');
    }
}

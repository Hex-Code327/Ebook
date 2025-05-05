<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Support\Facades\Auth;

class EbookController extends Controller
{
    /**
     * Menampilkan semua ebook yang bisa dibaca user (aktif)
     */
    public function index()
    {
        // Menampilkan ebook aktif (baik gratis maupun berbayar)
        $ebooks = Ebook::where('is_active', true)->latest()->paginate(10);
        return view('user.ebooks.index', compact('ebooks'));
    }

    /**
     * Menampilkan halaman baca ebook jika user memiliki akses
     */
    public function read(Ebook $ebook)
    {
        $user = Auth::user();

        // Cek akses berdasarkan status ebook dan status user
        if (!$ebook->is_free && !$user->hasPremiumAccess()) {
            return back()->with('error', 'Anda tidak memiliki akses ke ebook ini.');
        }

        return view('user.ebooks.read', compact('ebook'));
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\ReadingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard user dengan informasi dasar
     */
    public function index()
    {
        $user = Auth::user();

        // Ebook yang dimiliki user
        $myPremiumEbooks = $user->purchasedEbooks()
            ->with('categories')
            ->latest()
            ->take(6)
            ->get();

        return view('user.dashboard', [
            'user' => $user,
            'myPremiumEbooks' => $myPremiumEbooks
        ]);
    }

    /**
     * Menampilkan koleksi ebook premium user
     */
    public function myEbooks()
    {
        $ebooks = Auth::user()
            ->purchasedEbooks()
            ->with('categories')
            ->latest()
            ->paginate(12);

        return view('user.my-ebooks', compact('ebooks'));
    }

    /**
     * Menandai ebook sebagai sedang dibaca
     */
    public function markAsReading(Request $request, Ebook $ebook)
    {
        // Validasi akses (boleh baca jika gratis atau sudah dibeli)
        if (!$ebook->is_free && !$request->user()->hasPurchased($ebook)) {
            return back()->with('error', 'Anda tidak memiliki akses ke ebook ini');
        }

        // Tambahkan ke riwayat baca
        ReadingHistory::updateOrCreate(
            ['user_id' => $request->user()->id, 'ebook_id' => $ebook->id],
            ['last_page' => 1, 'updated_at' => now()]
        );

        return redirect()->route('user.ebooks.read', $ebook->id);
    }
}

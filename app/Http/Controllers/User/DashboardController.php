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
     * Menampilkan dashboard user dengan berbagai informasi
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ebook yang sedang dibaca (3 terbaru)
        $readingHistory = ReadingHistory::where('user_id', $user->id)
            ->with('ebook')
            ->latest()
            ->take(3)
            ->get();

        // Rekomendasi ebook berdasarkan history baca
        $recommendations = Ebook::whereHas('categories', function($query) use ($user) {
                // Ambil kategori dari ebook yang pernah dibaca
                $query->whereIn('categories.id', 
                    $user->readingHistory()->with('ebook.categories')
                        ->get()
                        ->pluck('ebook.categories.*.id')
                        ->flatten()
                        ->unique()
                );
            })
            ->whereNotIn('id', $user->readingHistory()->pluck('ebook_id'))
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Ebook premium yang dimiliki user
        $myPremiumEbooks = $user->purchasedEbooks()
            ->with('categories')
            ->latest()
            ->take(4)
            ->get();

        return view('user.dashboard', [
            'user' => $user,
            'readingHistory' => $readingHistory,
            'recommendations' => $recommendations,
            'myPremiumEbooks' => $myPremiumEbooks
        ]);
    }

    /**
     * Menampilkan semua riwayat baca
     */
    public function readingHistory()
    {
        $history = Auth::user()
            ->readingHistory()
            ->with('ebook')
            ->latest()
            ->paginate(10);

        return view('user.reading-history', compact('history'));
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
        // Validasi akses
        if (!$ebook->is_free && !$request->user()->hasPurchased($ebook)) {
            return redirect()->route('payment.show', $ebook->id)
                ->with('error', 'Anda perlu membeli ebook ini terlebih dahulu');
        }

        // Tambahkan ke riwayat baca
        ReadingHistory::updateOrCreate(
            ['user_id' => $request->user()->id, 'ebook_id' => $ebook->id],
            ['last_page' => 1, 'updated_at' => now()]
        );

        return redirect()->route('user.ebooks.read', $ebook->id);
    }
}
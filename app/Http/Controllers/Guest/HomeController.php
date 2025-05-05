<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda untuk tamu
     */
    public function index()
    {
        // Ambil 6 ebook gratis terbaru
        $freeEbooks = Ebook::where('is_free', true)
            ->with('categories')
            ->latest()
            ->take(6)
            ->get();

        // Ambil 6 ebook premium terpopuler
        $premiumEbooks = Ebook::where('is_free', false)
            ->withCount('purchases')
            ->orderBy('purchases_count', 'desc')
            ->take(6)
            ->get();

        // Ambil semua kategori
        $categories = Category::withCount('ebooks')
            ->having('ebooks_count', '>', 0)
            ->orderBy('name')
            ->get();

        return view('guest.home', [
            'freeEbooks' => $freeEbooks,
            'premiumEbooks' => $premiumEbooks,
            'categories' => $categories
        ]);
    }

    /**
     * Menampilkan detail ebook
     */
    public function showEbook(Ebook $ebook)
    {
        // Rekomendasi ebook terkait
        $relatedEbooks = Ebook::whereHas('categories', function($query) use ($ebook) {
                $query->whereIn('categories.id', $ebook->categories->pluck('id'));
            })
            ->where('id', '!=', $ebook->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('guest.ebook-detail', [
            'ebook' => $ebook,
            'relatedEbooks' => $relatedEbooks
        ]);
    }

    /**
     * Mencari ebook berdasarkan keyword
     */
    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|min:3'
        ]);

        $ebooks = Ebook::where('title', 'like', '%'.$request->keyword.'%')
            ->orWhere('synopsis', 'like', '%'.$request->keyword.'%')
            ->paginate(12);

        return view('guest.search-results', [
            'ebooks' => $ebooks,
            'keyword' => $request->keyword
        ]);
    }

    /**
     * Menampilkan ebook berdasarkan kategori
     */
    public function byCategory(Category $category)
    {
        $ebooks = $category->ebooks()
            ->latest()
            ->paginate(12);

        return view('guest.category', [
            'category' => $category,
            'ebooks' => $ebooks
        ]);
    }

    /**
     * Menampilkan halaman tentang kami
     */
    public function about()
    {
        return view('guest.about');
    }

    /**
     * Menampilkan halaman kontak
     */
    public function contact()
    {
        return view('guest.contact');
    }

    /**
     * Menampilkan halaman FAQ
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'Bagaimana cara mendaftar?',
                'answer' => 'Klik tombol register di pojok kanan atas dan isi formulir pendaftaran.'
            ],
            [
                'question' => 'Apakah ada ebook gratis?',
                'answer' => 'Ya, kami menyediakan koleksi ebook gratis yang bisa dibaca tanpa biaya.'
            ]
        ];

        return view('guest.faq', compact('faqs'));
    }
}
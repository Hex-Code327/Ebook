<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $freeEbooks = Ebook::where('is_free', true)->latest()->take(6)->get();
        $premiumEbooks = Ebook::where('is_free', false)->latest()->take(6)->get();

        return view('guest.home', compact('freeEbooks', 'premiumEbooks'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|min:3'
        ]);

        $ebooks = Ebook::where('title', 'like', '%' . $request->keyword . '%')
            ->orWhere('synopsis', 'like', '%' . $request->keyword . '%')
            ->paginate(12);

        return view('guest.search-results', compact('ebooks'))->with('keyword', $request->keyword);
    }

    public function byType($type)
    {
        $isFree = $type === 'gratis' ? true : false;

        $ebooks = Ebook::where('is_free', $isFree)->latest()->paginate(12);

        return view('guest.ebook-by-type', compact('ebooks', 'type'));
    }

    public function byCategory($category)
    {
        $ebooks = Ebook::where('category', $category)->latest()->paginate(12);

        return view('guest.ebook-by-category', compact('ebooks', 'category'));
    }

    public function about()
    {
        return view('guest.about');
    }

    public function contact()
    {
        return view('guest.contact');
    }

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
            ],
            [
                'question' => 'Bagaimana cara mengakses ebook premium?',
                'answer' => 'Anda perlu login terlebih dahulu untuk mengakses ebook premium.'
            ]
        ];

        return view('guest.faq', compact('faqs'));
    }
}

<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Ebook;

class EbookController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::where('is_active', true)->latest()->paginate(10);
        return view('guest.ebooks.index', compact('ebooks'));
    }

    public function show(Ebook $ebook)
    {
        return view('guest.ebooks.show', compact('ebook'));
    }
}

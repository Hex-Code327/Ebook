<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    /**
     * Menampilkan daftar chapter untuk ebook tertentu
     */
    public function index(Ebook $ebook)
    {
        $chapters = $ebook->chapters()->latest()->paginate(10);
        return view('admin.chapters.index', compact('ebook', 'chapters'));
    }

    /**
     * Menampilkan form untuk membuat chapter baru
     */
    public function create(Ebook $ebook)
    {
        return view('admin.chapters.create', compact('ebook'));
    }

    /**
     * Menyimpan chapter baru
     */
    public function store(Request $request, Ebook $ebook)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'order' => 'nullable|integer',
        ]);

        // Menyimpan chapter baru ke ebook terkait
        $ebook->chapters()->create($validated);

        return redirect()->route('admin.ebooks.chapters.index', $ebook)
            ->with('success', 'Chapter berhasil ditambahkan');
    }

    /**
     * Menampilkan form untuk mengedit chapter
     */
    public function edit(Ebook $ebook, Chapter $chapter)
    {
        return view('admin.chapters.edit', compact('ebook', 'chapter'));
    }

    /**
     * Memperbarui chapter
     */
    public function update(Request $request, Ebook $ebook, Chapter $chapter)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'order' => 'nullable|integer',
        ]);

        // Memperbarui chapter
        $chapter->update($validated);

        return redirect()->route('admin.ebooks.chapters.index', $ebook)
            ->with('success', 'Chapter berhasil diperbarui');
    }

    /**
     * Menghapus chapter
     */
    public function destroy(Ebook $ebook, Chapter $chapter)
    {
        // Menghapus chapter
        $chapter->delete();

        return redirect()->route('admin.ebooks.chapters.index', $ebook)
            ->with('success', 'Chapter berhasil dihapus');
    }
}

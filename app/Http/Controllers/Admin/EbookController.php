<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::latest()->paginate(10);
        return view('admin.ebooks.index', compact('ebooks'));
    }

    public function create()
    {
        return view('admin.ebooks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'synopsis' => 'required',
            'grade_level' => 'required',
            'goal' => 'required',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'is_free' => 'boolean'
        ]);

        $path = $request->file('cover_image')->store('ebook_covers', 'public');
        $validated['cover_image'] = $path;

        Ebook::create($validated);

        return redirect()->route('admin.ebooks.index')
            ->with('success', 'Ebook berhasil ditambahkan');
    }

    public function edit(Ebook $ebook)
    {
        return view('admin.ebooks.edit', compact('ebook'));
    }

    public function update(Request $request, Ebook $ebook)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'synopsis' => 'required',
            'grade_level' => 'required',
            'goal' => 'required',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_free' => 'boolean'
        ]);

        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->delete($ebook->cover_image);
            $path = $request->file('cover_image')->store('ebook_covers', 'public');
            $validated['cover_image'] = $path;
        }

        $ebook->update($validated);

        return redirect()->route('admin.ebooks.index')
            ->with('success', 'Ebook berhasil diperbarui');
    }

    public function destroy(Ebook $ebook)
    {
        Storage::disk('public')->delete($ebook->cover_image);
        $ebook->delete();

        return redirect()->route('admin.ebooks.index')
            ->with('success', 'Ebook berhasil dihapus');
    }
}
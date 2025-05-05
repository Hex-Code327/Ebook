@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{ isset($ebook) ? 'Edit' : 'Tambah' }} Ebook</h5>
    </div>
    <div class="card-body">
        <form action="{{ isset($ebook) ? route('admin.ebooks.update', $ebook->id) : route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($ebook)) @method('PUT') @endif

            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" value="{{ $ebook->title ?? old('title') }}" required>
            </div>

            <div class="form-group">
                <label>Sinopsis</label>
                <textarea name="synopsis" class="form-control" rows="3" required>{{ $ebook->synopsis ?? old('synopsis') }}</textarea>
            </div>

            <div class="form-group">
                <label>Tingkat Kelas</label>
                <input type="text" name="grade_level" class="form-control" value="{{ $ebook->grade_level ?? old('grade_level') }}" required>
            </div>

            <div class="form-group">
                <label>Tujuan Pembelajaran</label>
                <textarea name="goal" class="form-control" rows="3" required>{{ $ebook->goal ?? old('goal') }}</textarea>
            </div>

            <div class="form-group">
                <label>Cover Ebook</label>
                <input type="file" name="cover_image" class="form-control-file" {{ !isset($ebook) ? 'required' : '' }}>
                @if(isset($ebook) && $ebook->cover_image)
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$ebook->cover_image) }}" width="100">
                </div>
                @endif
            </div>

            <div class="form-group form-check">
                <input type="checkbox" name="is_free" class="form-check-input" id="is_free" 
                    {{ (isset($ebook) && $ebook->is_free) || old('is_free') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_free">Gratis</label>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.ebooks.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
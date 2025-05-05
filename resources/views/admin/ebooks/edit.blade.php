@extends('layouts.sneat')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Edit Ebook</h5>
                <div class="card-body">
                    <form action="{{ route('admin.ebooks.update', $ebook->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $ebook->title }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="synopsis" class="form-label">Synopsis</label>
                            <textarea class="form-control" id="synopsis" name="synopsis" rows="3" required>{{ $ebook->synopsis }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="grade_level" class="form-label">Grade Level</label>
                                <input type="text" class="form-control" id="grade_level" name="grade_level" value="{{ $ebook->grade_level }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" class="form-control" id="author" name="author" value="{{ $ebook->author }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="goal" class="form-label">Learning Goal</label>
                            <textarea class="form-control" id="goal" name="goal" rows="3" required>{{ $ebook->goal }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Cover Image</label>
                            <input class="form-control" type="file" id="cover_image" name="cover_image">
                            @if($ebook->cover_image)
                            <div class="mt-2">
                                <img src="{{ $ebook->cover_url }}" width="100" class="img-thumbnail">
                                <p class="text-muted mt-1">Current cover</p>
                            </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_free" name="is_free" {{ $ebook->is_free ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_free">Free Ebook</label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Update</button>
                            <a href="{{ route('admin.ebooks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.sneat')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Add New Ebook</h5>
                <div class="card-body">
                    <form action="{{ route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="synopsis" class="form-label">Synopsis</label>
                            <textarea class="form-control" id="synopsis" name="synopsis" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="grade_level" class="form-label">Grade Level</label>
                                <input type="text" class="form-control" id="grade_level" name="grade_level" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" class="form-control" id="author" name="author">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="goal" class="form-label">Learning Goal</label>
                            <textarea class="form-control" id="goal" name="goal" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Cover Image</label>
                            <input class="form-control" type="file" id="cover_image" name="cover_image" required>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_free" name="is_free">
                                <label class="form-check-label" for="is_free">Free Ebook</label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Save</button>
                            <a href="{{ route('admin.ebooks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.sneat')

@section('title', 'Manage Ebooks')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ebook List</h5>
                    <a href="{{ route('admin.ebooks.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Add Ebook
                    </a>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="80px">Cover</th>
                                <th>Title</th>
                                <th>Grade Level</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($ebooks as $ebook)
                            <tr>
                                <td>
                                    <img src="{{ $ebook->cover_url }}" width="60" class="rounded">
                                </td>
                                <td>
                                    <strong>{{ $ebook->title }}</strong>
                                    <p class="text-muted mb-0">{{ Str::limit($ebook->synopsis, 50) }}</p>
                                </td>
                                <td>{{ $ebook->grade_level }}</td>
                                <td>
                                    <span class="badge {{ $ebook->is_free ? 'bg-label-success' : 'bg-label-primary' }}">
                                        {{ $ebook->is_free ? 'Free' : 'Premium' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.ebooks.edit', $ebook->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.ebooks.destroy', $ebook->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure?')">
                                                    <i class="bx bx-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No ebooks found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $ebooks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
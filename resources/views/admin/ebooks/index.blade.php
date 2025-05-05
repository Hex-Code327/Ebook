@extends('layouts.app')

@section('title', 'Manage Ebooks')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Ebook</h5>
        <a href="{{ route('admin.ebooks.create') }}" class="btn btn-primary">
            <span class="tf-icons bx bx-plus"></span> Tambah Ebook
        </a>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Judul</th>
                    <th>Tingkat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach($ebooks as $ebook)
                <tr>
                    <td>
                        <img src="{{ $ebook->cover_url }}" width="60" class="rounded">
                    </td>
                    <td><strong>{{ $ebook->title }}</strong></td>
                    <td>{{ $ebook->grade_level }}</td>
                    <td>
                        <span class="badge {{ $ebook->is_free ? 'bg-label-success' : 'bg-label-primary' }}">
                            {{ $ebook->is_free ? 'Gratis' : 'Premium' }}
                        </span>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.ebooks.edit', $ebook->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('admin.ebooks.destroy', $ebook->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item" onclick="return confirm('Hapus ebook ini?')">
                                            <i class="bx bx-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $ebooks->links() }}
    </div>
</div>
@endsection
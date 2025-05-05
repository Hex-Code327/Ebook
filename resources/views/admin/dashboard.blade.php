@extends('layouts.sneat')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0">
                    <span class="text-muted fw-light">Admin /</span> Dashboard
                </h4>
                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-cog me-1"></i> Quick Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.ebooks.create') }}"><i class="bx bx-plus me-1"></i> Add Ebook</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="bx bx-user me-1"></i> Manage Users</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Users</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Registered</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ substr($user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            @if($user->isAdmin())
                                                <span class="badge bg-label-danger ms-1">Admin</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge {{ $user->is_paid ? 'bg-label-success' : 'bg-label-secondary' }}">
                                        {{ $user->is_paid ? 'Premium' : 'Regular' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.users.edit', $user->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('toggle-premium-{{ $user->id }}').submit()">
                                                <i class="bx bx-{{ $user->is_paid ? 'minus-circle' : 'plus-circle' }} me-1"></i> 
                                                {{ $user->is_paid ? 'Remove Premium' : 'Make Premium' }}
                                            </a>
                                            <form id="toggle-premium-{{ $user->id }}" action="{{ route('admin.users.toggle-premium', $user->id) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('PUT')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

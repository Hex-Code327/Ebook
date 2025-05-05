@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-4">Tambah User</h4>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Jenis Akun</label>
                <select name="account_type" class="form-control" required>
                    <option value="free">Gratis</option>
                    <option value="premium">Premium</option>
                </select>
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection

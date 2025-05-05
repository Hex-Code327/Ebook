@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account /</span> Profile</h4>

    <div class="row">
        <!-- Profile Information Form -->
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Update Profile Information</h5>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Update Password Form -->
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Update Password</h5>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Form -->
    <div class="card">
        <h5 class="card-header text-danger">Delete Account</h5>
        <div class="card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Profile Information</h5>
        <small class="text-muted">Update your account's profile information and email address.</small>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                    autocomplete="name"
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-muted">
                            Your email address is unverified.
                            <button form="send-verification" class="btn btn-link text-primary">
                                Click here to re-send the verification email.
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="text-sm text-success">
                                A new verification link has been sent to your email address.
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-end gap-3">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>

                @if (session('status') === 'profile-updated')
                    <span class="text-success small">Saved.</span>
                @endif
            </div>
        </form>
    </div>
</div>

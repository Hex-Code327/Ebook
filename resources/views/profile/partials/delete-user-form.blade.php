<div class="card">
    <div class="card-header text-danger fw-bold">
        Delete Account
    </div>
    <div class="card-body">
        <p class="mb-3">
            Once your account is deleted, all of its resources and data will be permanently removed.
            Please download any information you want to keep before deleting your account.
        </p>

        <!-- Trigger modal -->
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
            Delete Account
        </button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('profile.destroy') }}" class="modal-content">
            @csrf
            @method('DELETE')

            <div class="modal-header">
                <h5 class="modal-title text-danger" id="confirmDeleteModalLabel">Confirm Account Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p>
                    Once your account is deleted, all of its resources and data will be permanently removed.
                    Please enter your password to confirm this action.
                </p>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                        placeholder="Enter your password"
                        required
                    >
                    @error('password', 'userDeletion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete Account</button>
            </div>
        </form>
    </div>
</div>

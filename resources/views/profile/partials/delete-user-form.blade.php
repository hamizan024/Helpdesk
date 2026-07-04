<x-app-card title="Delete Account">

    <p class="text-muted mb-4" style="font-size:0.875rem;">
        Once your account is deleted, all of its resources and data will be permanently deleted.
        Before deleting your account, please download any data or information that you wish to retain.
    </p>

    <button type="button"
            class="btn btn-danger btn-sm px-4"
            data-bs-toggle="modal"
            data-bs-target="#deleteAccountModal">
        Delete Account
    </button>

</x-app-card>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1"
     aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <p style="font-size:0.875rem;">
                        Are you sure you want to delete your account? Once your account is deleted,
                        all of its resources and data will be permanently deleted.
                        Please enter your password to confirm.
                    </p>
                    <x-form-input
                        label="Password"
                        name="password"
                        type="password"
                        placeholder="Password"
                        bag="userDeletion" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger btn-sm">
                        Delete Account
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
        });
    </script>
@endif

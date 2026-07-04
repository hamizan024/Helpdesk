<x-app-card title="Update Password" class="mb-4">

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <x-form-input
            label="Current Password"
            name="current_password"
            type="password"
            autocomplete="current-password"
            bag="updatePassword" />

        <x-form-input
            label="New Password"
            name="password"
            type="password"
            autocomplete="new-password"
            bag="updatePassword" />

        <x-form-input
            label="Confirm Password"
            name="password_confirmation"
            type="password"
            autocomplete="new-password"
            bag="updatePassword" />

        <div class="d-flex align-items-center gap-3">
            <button type="submit"
                    class="btn btn-sm px-4 py-2 text-white fw-bold bg-gradient-primary">
                Save
            </button>
            @if(session('status') === 'password-updated')
                <span class="text-success" style="font-size:0.875rem;">Saved.</span>
            @endif
        </div>

    </form>

</x-app-card>

<form id="send-verification" method="POST" action="{{ route('verification.send') }}">
    @csrf
</form>

<x-app-card title="Profile Information" class="mb-4">

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <x-form-input
            label="Name"
            name="name"
            :value="old('name', $user->name)"
            required
            autofocus
            autocomplete="name" />

        <x-form-input
            label="Email"
            name="email"
            type="email"
            :value="old('email', $user->email)"
            required
            autocomplete="username" />

        @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mb-3" style="font-size:0.875rem;">
                Your email address is unverified.
                <button form="send-verification"
                        class="btn btn-link p-0 ms-1 align-baseline"
                        style="font-size:0.875rem;">
                    Click here to re-send the verification email.
                </button>
            </div>
            @if(session('status') === 'verification-link-sent')
                <x-alert type="success" :dismissible="false" class="mb-3">
                    A new verification link has been sent to your email address.
                </x-alert>
            @endif
        @endif

        <div class="d-flex align-items-center gap-3">
            <button type="submit"
                    class="btn btn-sm px-4 py-2 text-white fw-bold bg-gradient-primary">
                Save
            </button>
            @if(session('status') === 'profile-updated')
                <span class="text-success" style="font-size:0.875rem;">Saved.</span>
            @endif
        </div>

    </form>

</x-app-card>

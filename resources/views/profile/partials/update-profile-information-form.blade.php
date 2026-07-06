<form id="send-verification" method="POST" action="{{ route('verification.send') }}">
    @csrf
</form>

{{-- Hidden avatar upload form — submitted programmatically on file input change --}}
<form id="avatar-form"
      method="POST"
      action="{{ route('profile.avatar') }}"
      enctype="multipart/form-data"
      class="d-none">
    @csrf
    <input type="file" id="avatar-input" name="avatar" accept="image/*">
</form>

<x-app-card title="Profile Information" class="mb-4">

    {{-- Avatar Section --}}
    <div class="mb-4 d-flex align-items-center gap-4">
        <div class="position-relative"
             style="cursor:pointer;"
             onclick="document.getElementById('avatar-input').click()"
             title="Click to change photo">
            <x-user-avatar :user="$user" :size="80" />
            <div style="position:absolute;bottom:0;right:0;width:26px;height:26px;border-radius:50%;background:#344767;border:2px solid #fff;display:flex;align-items:center;justify-content:center;">
                <span class="material-icons-round" style="font-size:0.75rem;color:#fff;">photo_camera</span>
            </div>
        </div>
        <div>
            <div style="font-size:0.875rem;font-weight:600;color:#344767;">{{ $user->name }}</div>
            <div style="font-size:0.75rem;color:#7b809a;margin-bottom:6px;">Click the photo to upload a new one (max 2 MB)</div>
            @if($user->avatar)
                <button type="button"
                        onclick="removeAvatar()"
                        class="btn btn-link p-0"
                        style="font-size:0.75rem;color:#E53935;text-decoration:none;">
                    Remove photo
                </button>
            @endif
            @error('avatar')
                <div class="text-danger" style="font-size:0.75rem;">{{ $message }}</div>
            @enderror
            @if(session('status') === 'avatar-updated')
                <div class="text-success" style="font-size:0.75rem;">Photo updated.</div>
            @endif
            @if(session('status') === 'avatar-removed')
                <div class="text-secondary" style="font-size:0.75rem;">Photo removed.</div>
            @endif
        </div>
    </div>

    <hr class="my-3">

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
@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        @include('profile.partials.profile-nav')
        @include('profile.partials.update-profile-information-form')
        @include('profile.partials.update-password-form')
        @include('profile.partials.api-tokens')
        @include('profile.partials.delete-user-form')
    </div>
</div>

@endsection

@section('scripts')
<script>
    const avatarInput = document.getElementById('avatar-input');
    if (avatarInput) {
        avatarInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                document.getElementById('avatar-form').submit();
            }
        });
    }

    function removeAvatar() {
        if (!confirm('Remove your profile photo?')) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("profile.avatar.destroy") }}';
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">'
                       + '<input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection
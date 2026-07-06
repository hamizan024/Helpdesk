<x-guest-layout>
    <h2>Buat Akun</h2>
    <p class="auth-subtitle">Daftarkan akun Helpdesk baru</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <span class="material-icons-round">person</span>
                </span>
                <input id="name" type="text" name="name"
                       value="{{ old('name') }}"
                       class="form-control {{ $errors->get('name') ? 'is-invalid' : '' }}"
                       placeholder="Nama lengkap Anda"
                       required autofocus autocomplete="name">
            </div>
            @foreach ($errors->get('name') as $msg)
                <div class="invalid-feedback-msg">{{ $msg }}</div>
            @endforeach
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <span class="material-icons-round">alternate_email</span>
                </span>
                <input id="email" type="email" name="email"
                       value="{{ old('email') }}"
                       class="form-control {{ $errors->get('email') ? 'is-invalid' : '' }}"
                       placeholder="nama@email.com"
                       required autocomplete="username">
            </div>
            @foreach ($errors->get('email') as $msg)
                <div class="invalid-feedback-msg">{{ $msg }}</div>
            @endforeach
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <span class="material-icons-round">lock</span>
                </span>
                <input id="password" type="password" name="password"
                       class="form-control {{ $errors->get('password') ? 'is-invalid' : '' }}"
                       placeholder="••••••••"
                       required autocomplete="new-password">
            </div>
            @foreach ($errors->get('password') as $msg)
                <div class="invalid-feedback-msg">{{ $msg }}</div>
            @endforeach
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <span class="material-icons-round">lock_outline</span>
                </span>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-control {{ $errors->get('password_confirmation') ? 'is-invalid' : '' }}"
                       placeholder="••••••••"
                       required autocomplete="new-password">
            </div>
            @foreach ($errors->get('password_confirmation') as $msg)
                <div class="invalid-feedback-msg">{{ $msg }}</div>
            @endforeach
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-auth">
            <span class="material-icons-round">person_add</span>
            Daftar
        </button>

        <p class="text-center mt-3 mb-0" style="font-size:0.82rem;color:#7b809a;">
            Sudah punya akun?
            <a href="{{ route('login') }}"
               style="color:#EC407A;font-weight:500;text-decoration:none;">Masuk</a>
        </p>
    </form>
</x-guest-layout>
<x-guest-layout>
    <h2>Selamat Datang</h2>
    <p class="auth-subtitle">Masuk ke akun Helpdesk Anda</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <span class="material-icons-round">alternate_email</span>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="form-control {{ $errors->get('email') ? 'is-invalid' : '' }}" placeholder="nama@email.com"
                    required autofocus autocomplete="username">
            </div>
            @foreach ($errors->get('email') as $msg)
                <div class="invalid-feedback-msg">{{ $msg }}</div>
            @endforeach
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                @endif
            </div>
            <div class="input-wrapper">
                <span class="input-icon">
                    <span class="material-icons-round">lock</span>
                </span>
                <input id="password" type="password" name="password"
                    class="form-control {{ $errors->get('password') ? 'is-invalid' : '' }}" placeholder="••••••••"
                    required autocomplete="current-password">
            </div>
            @foreach ($errors->get('password') as $msg)
                <div class="invalid-feedback-msg">{{ $msg }}</div>
            @endforeach
        </div>

        <!-- Remember Me -->
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label" for="remember_me" style="font-size:0.82rem; color:#7b809a;">
                    Ingat saya
                </label>
            </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-auth">
            <span class="material-icons-round">login</span>
            Masuk
        </button>
    </form>
</x-guest-layout>

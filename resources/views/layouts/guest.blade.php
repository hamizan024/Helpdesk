<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'IT Helpdesk') }} &mdash; Login</title>

    <!-- Google Fonts + Material Icons (sama dengan dashboard) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Bootstrap 5 (sama dengan dashboard) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; }

        body {
            font-family: "Inter", sans-serif;
            font-size: 0.875rem;
            color: #344767;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
        }

        /* ===== PANEL KIRI ===== */
        .auth-left {
            width: 50%;
            background: linear-gradient(195deg, #42424a, #191919);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 40px 48px;
            position: relative;
            overflow: hidden;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            top: -80px; left: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -60px; right: -60px;
            width: 260px; height: 260px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .brand-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            background: linear-gradient(195deg, #EC407A, #D81B60);
            box-shadow: 0 4px 20px 0 rgba(0,0,0,0.14), 0 7px 10px -5px rgba(233,30,99,0.4);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon .material-icons-round { font-size: 1.25rem; color: white; }

        .brand-name {
            font-size: 1rem;
            font-weight: 600;
            color: white;
        }

        .auth-left-center {
            position: relative;
            z-index: 2;
        }

        .auth-left-center h1 {
            font-size: 2.25rem;
            font-weight: 700;
            color: white;
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .auth-left-center p {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .auth-feature {
            background: rgba(255,255,255,0.07);
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
        }

        .auth-feature-icon {
            width: 38px; height: 38px;
            border-radius: 9px;
            background: linear-gradient(195deg, #EC407A, #D81B60);
            box-shadow: 0 4px 12px rgba(233,30,99,0.3);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .auth-feature-icon .material-icons-round { font-size: 1rem; color: white; }

        .auth-feature-text strong {
            display: block;
            font-size: 0.82rem;
            color: white;
            font-weight: 600;
        }

        .auth-feature-text span {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.5);
        }

        .auth-left-footer {
            position: relative;
            z-index: 2;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.3);
        }

        /* ===== PANEL KANAN ===== */
        .auth-right {
            width: 50%;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px 0 rgba(0,0,0,0.07);
            padding: 40px;
        }

        .auth-card h2 {
            font-size: 1.375rem;
            font-weight: 700;
            color: #344767;
            margin-bottom: 4px;
        }

        .auth-card .auth-subtitle {
            font-size: 0.82rem;
            color: #7b809a;
            margin-bottom: 28px;
        }

        /* Form */
        .form-label {
            font-size: 0.82rem;
            font-weight: 500;
            color: #344767;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            display: flex;
        }

        .input-icon .material-icons-round { font-size: 1.1rem; }

        .form-control {
            padding: 10px 14px 10px 38px !important;
            font-size: 0.875rem;
            border-radius: 8px;
            border: 1px solid #d2d6da;
            color: #344767;
            transition: all 0.15s;
        }

        .form-control::placeholder { color: #adb5bd; }

        .form-control:focus {
            border-color: #EC407A;
            box-shadow: 0 0 0 3px rgba(233,30,99,0.12);
            outline: none;
        }

        .form-check-input:checked {
            background-color: #EC407A;
            border-color: #EC407A;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(233,30,99,0.12);
            border-color: #EC407A;
        }

        .btn-auth {
            width: 100%;
            padding: 11px;
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            background: linear-gradient(195deg, #EC407A, #D81B60);
            box-shadow: 0 3px 6px rgba(233,30,99,0.22);
            color: white;
            cursor: pointer;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-auth:hover {
            background: linear-gradient(195deg, #D81B60, #AD1457);
            box-shadow: 0 6px 12px rgba(233,30,99,0.35);
            transform: translateY(-1px);
        }

        .btn-auth .material-icons-round { font-size: 1.1rem; }

        .forgot-link {
            font-size: 0.78rem;
            color: #EC407A;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover { color: #D81B60; }

        .invalid-feedback-msg {
            font-size: 0.75rem;
            color: #E53935;
            margin-top: 4px;
        }

        /* Mobile */
        @media (max-width: 767px) {
            .auth-left { display: none; }
            .auth-right { width: 100%; }
        }
    </style>

    @vite(['resources/js/app.js'])
</head>
<body>
<div class="auth-wrapper">

    <!-- Panel Kiri -->
    <div class="auth-left">
        <div class="brand-logo">
            <div class="brand-icon">
                <span class="material-icons-round">support_agent</span>
            </div>
            <span class="brand-name">IT Helpdesk</span>
        </div>

        <div class="auth-left-center">
            <h1>Sistem Tiket<br>Helpdesk IT</h1>
            <p>Platform terpadu untuk mengelola permintaan dukungan teknis secara efisien dan terstruktur.</p>

            <div class="auth-feature">
                <div class="auth-feature-icon">
                    <span class="material-icons-round">confirmation_number</span>
                </div>
                <div class="auth-feature-text">
                    <strong>Manajemen Tiket</strong>
                    <span>Buat, lacak & selesaikan tiket dengan mudah</span>
                </div>
            </div>

            <div class="auth-feature">
                <div class="auth-feature-icon">
                    <span class="material-icons-round">dashboard</span>
                </div>
                <div class="auth-feature-text">
                    <strong>Dashboard Real-time</strong>
                    <span>Pantau status & statistik tiket secara langsung</span>
                </div>
            </div>

            <div class="auth-feature">
                <div class="auth-feature-icon">
                    <span class="material-icons-round">group</span>
                </div>
                <div class="auth-feature-text">
                    <strong>Kolaborasi Tim</strong>
                    <span>Assign & koordinasi tiket antar tim IT</span>
                </div>
            </div>
        </div>

        <div class="auth-left-footer">
            &copy; {{ date('Y') }} IT Helpdesk. Made with ❤️ by Techmate.
        </div>
    </div>

    <!-- Panel Kanan -->
    <div class="auth-right">
        <div class="auth-card">
            {{ $slot }}
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

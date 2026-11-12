@extends('layouts.main2')

@push('styles')
<style>
    html {
        background:
            radial-gradient(ellipse 70% 55% at 12% 12%, rgba(148, 163, 184, 0.28) 0%, transparent 60%),
            radial-gradient(ellipse 55% 45% at 88% 88%, rgba(100, 116, 139, 0.12) 0%, transparent 55%),
            linear-gradient(165deg, #f8fafc 0%, #eef2f6 48%, #f5f6f8 100%);
        background-attachment: fixed;
    }

    body.d-flex.flex-column.min-vh-100 {
        position: relative;
        background: transparent;
    }

    body.d-flex.flex-column.min-vh-100::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(100, 116, 139, 0.045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(100, 116, 139, 0.045) 1px, transparent 1px);
        background-size: 48px 48px;
        mask-image: radial-gradient(ellipse 75% 65% at 50% 40%, #000 25%, transparent 80%);
    }

    body.d-flex.flex-column.min-vh-100 > .edica-loader,
    body.d-flex.flex-column.min-vh-100 > .flex-grow-1,
    body.d-flex.flex-column.min-vh-100 > .edica-footer {
        position: relative;
        z-index: 1;
    }

    body.d-flex.flex-column.min-vh-100 > .edica-header {
        z-index: 1030;
    }

    main.login-page {
        display: flex;
        align-items: center;
        flex: 1 0 auto;
        width: 100%;
        min-height: 0;
        padding: 2.85rem 0 2.5rem;
        background: transparent;
    }

    .login-shell {
        width: 100%;
        max-width: 440px;
        margin: 0 auto;
    }

    .login-panel {
        padding: 2.5rem 2.25rem 2.25rem;
        border-radius: 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.75);
        background:
            linear-gradient(
                145deg,
                rgba(255, 255, 255, 0.92) 0%,
                rgba(255, 255, 255, 0.72) 42%,
                rgba(232, 240, 250, 0.88) 100%
            );
        backdrop-filter: blur(18px) saturate(150%);
        -webkit-backdrop-filter: blur(18px) saturate(150%);
        box-shadow:
            0 24px 48px rgba(15, 23, 42, 0.1),
            0 8px 20px rgba(9, 76, 161, 0.06),
            inset 0 1px 0 rgba(255, 255, 255, 0.95);
    }

    .login-brand {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 1.75rem;
    }

    .login-brand img {
        width: 64px;
        height: 64px;
        object-fit: contain;
        margin-bottom: 1rem;
    }

    .login-brand h1 {
        margin: 0 0 0.4rem;
        font-family: Montserrat, "DM Sans", sans-serif;
        font-size: 1.65rem;
        font-weight: 700;
        line-height: 1.2;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .login-brand p {
        margin: 0;
        max-width: 28ch;
        font-size: 0.95rem;
        line-height: 1.45;
        color: #64748b;
    }

    .login-form .form-group {
        margin-bottom: 1.15rem;
    }

    .login-form .form-label {
        display: block;
        margin-bottom: 0.45rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #334155;
    }

    .login-form .form-control {
        height: 3rem;
        padding: 0.65rem 1rem;
        font-size: 1rem;
        line-height: 1.4;
        color: #0f172a;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(9, 76, 161, 0.16);
        border-radius: 0.75rem;
        box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.04);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .login-form .form-control:focus {
        border-color: #094ca1;
        box-shadow: 0 0 0 3px rgba(9, 76, 161, 0.15);
        outline: none;
    }

    .login-form .form-control.is-invalid {
        border-color: #dc3545;
    }

    .login-form .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15);
    }

    .login-form .invalid-feedback {
        display: block;
        margin-top: 0.4rem;
        font-size: 0.85rem;
    }

    .login-form .form-check {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0.25rem 0 1.5rem;
        padding-left: 0;
        min-height: 0;
    }

    .login-form .form-check-input {
        position: static;
        float: none;
        flex-shrink: 0;
        width: 1.1rem;
        height: 1.1rem;
        margin: 0;
        border: 1.5px solid rgba(9, 76, 161, 0.35);
        border-radius: 0.3rem;
        cursor: pointer;
    }

    .login-form .form-check-input:checked {
        background-color: #094ca1;
        border-color: #094ca1;
    }

    .login-form .form-check-label {
        margin: 0;
        padding: 0;
        font-size: 0.9rem;
        font-weight: 500;
        color: #475569;
        cursor: pointer;
    }

    .login-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 3.15rem;
        padding: 0 1.5rem;
        border: 1px solid #083d82;
        border-radius: 0.8rem;
        background: linear-gradient(145deg, #0b5bb8 0%, #094ca1 55%, #083d82 100%);
        color: #fff;
        font-family: Montserrat, "DM Sans", sans-serif;
        font-size: 1.05rem;
        font-weight: 600;
        letter-spacing: 0.01em;
        cursor: pointer;
        box-shadow:
            0 14px 28px rgba(9, 76, 161, 0.28),
            inset 0 1px 0 rgba(255, 255, 255, 0.22);
        transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }

    .login-submit:hover,
    .login-submit:focus {
        color: #fff;
        background: linear-gradient(145deg, #0c66c9 0%, #0a56b4 55%, #094ca1 100%);
        box-shadow:
            0 18px 34px rgba(9, 76, 161, 0.34),
            inset 0 1px 0 rgba(255, 255, 255, 0.28);
        text-decoration: none;
        outline: none;
    }

    .login-submit:active {
        transform: translateY(1px);
    }

    @media (max-width: 575.98px) {
        main.login-page {
            padding: 2.15rem 0 1.85rem;
            min-height: auto;
        }

        .login-panel {
            padding: 2rem 1.35rem 1.75rem;
            border-radius: 1rem;
        }

        .login-brand h1 {
            font-size: 1.4rem;
        }
    }
</style>
@endpush

@section('content')
<main class="login-page">
    <div class="container">
        <div class="login-shell">
            <div class="login-panel">
                <div class="login-brand">
                    <img src="{{ asset('assets/images/logo-main-little.png') }}" width="64" height="64" alt="Основание">
                    <h1>Вход в кабинет</h1>
                    <p>Авторизуйтесь, чтобы продолжить работу</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            autofocus
                            placeholder="name@example.com"
                        >

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Пароль</label>
                        <input
                            id="password"
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        >

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="remember"
                            id="remember"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="remember">Запомнить</label>
                    </div>

                    <button type="submit" class="login-submit">Войти</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

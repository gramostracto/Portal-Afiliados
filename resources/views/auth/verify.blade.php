@extends('layouts.auth_app')
@section('title')
    Verify Email
@endsection
@section('content')

<body id="kt_body" class="auth-page">
    @include('auth.partials.auth-style')

    <main class="auth-shell">
        <section class="auth-brand" aria-label="Portal de afiliados Tractocar">
            <img class="auth-brand-logo" src="{{ asset('assets/images/logos-tractocar/TCL_POS_CMYK-01.png') }}" alt="Tractocar">
            <h1>Portal de afiliados</h1>
            <p>Consulta, gestiona y actualiza tu informacion de afiliacion de forma segura.</p>
        </section>

        <section class="auth-card-wrap" aria-label="Verificar correo">
            <div class="auth-card">
                <div class="auth-card-header">
                    <div class="auth-kicker">Acceso seguro</div>
                    <h2>Verifica tu correo</h2>
                    <p class="auth-card-subtitle">Antes de continuar, confirma tu direccion de correo electronico.</p>
                </div>

                @if (session('resent'))
                    <div class="auth-status">
                        Se ha enviado un nuevo enlace de verificacion a tu direccion de correo electronico.
                    </div>
                @endif

                <p class="auth-card-subtitle" style="margin-bottom: 24px;">
                    Revisa tu bandeja de entrada y da clic en el enlace de verificacion que te enviamos.
                </p>

                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary auth-submit" tabindex="4">
                            Reenviar enlace de verificacion
                        </button>
                    </div>
                </form>

                <div class="auth-support text-center">
                    <a href="{{ route('login') }}" class="auth-link">Volver a iniciar sesion</a>
                </div>
            </div>
        </section>
    </main>
</body>
@endsection

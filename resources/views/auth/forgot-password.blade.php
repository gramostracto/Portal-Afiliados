@extends('layouts.auth_app')
@section('title')
    Forgot Password
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

        <section class="auth-card-wrap" aria-label="Recuperar contrasena">
            <div class="auth-card">
                <div class="auth-card-header">
                    <div class="auth-kicker">Acceso seguro</div>
                    <h2>Has olvidado tu contrasena</h2>
                    <p class="auth-card-subtitle">Ingresa tu correo y te enviaremos un enlace para restablecerla.</p>
                </div>

                @if (session('status'))
                    <div class="auth-status">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="auth-field">
                        <label for="email">Email</label>
                        <input id="email" type="email"
                            class="form-control auth-input{{ $errors->has('email') ? ' is-invalid' : '' }}"
                            name="email" placeholder="nombre@correo.com" tabindex="1" value="{{ old('email') }}"
                            autofocus required>
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary auth-submit" tabindex="4">
                            Solicitar Cambio
                        </button>
                    </div>
                </form>

                <div class="auth-support text-center">
                    Recordaste tu informacion de acceso?
                    <a href="{{ route('login') }}" class="auth-link">Iniciar sesion</a>
                </div>
            </div>
        </section>
    </main>
</body>
@endsection

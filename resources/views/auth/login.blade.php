@extends('layouts.auth_app')
@section('title')
    Login
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

        <section class="auth-card-wrap" aria-label="Inicio de sesion">
            <div class="auth-card">
                <div class="auth-card-header">
                    <div class="auth-kicker">Acceso seguro</div>
                    <h2>@lang('locale.Login')</h2>
                    <p class="auth-card-subtitle">Ingresa con tu correo y contrasena asignada.</p>
                </div>

                @if (session('alerta-register'))
                    <div class="auth-status">
                        Espere a que se verifique su información, esto podría tardar unos minutos, al correo registrado le estará llegando la confirmación.
                    </div>
                @endif

                @if (session('error'))
                    <div class="auth-alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('mantenimiento'))
                    <div class="auth-alert">
                        {{ session('mantenimiento') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="auth-field">
                        <label for="email">@lang('locale.Email')</label>
                        <input aria-describedby="emailHelpBlock" id="email" type="email"
                            class="form-control auth-input{{ $errors->has('email') ? ' is-invalid' : '' }}"
                            name="email" placeholder="nombre@correo.com" tabindex="1"
                            value="{{ Cookie::get('email') !== null ? Cookie::get('email') : old('email') }}"
                            autofocus required>
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    </div>

                    <div class="auth-field">
                        <label for="password">@lang('locale.Password')</label>
                        <input aria-describedby="passwordHelpBlock" id="password" type="password"
                            value="{{ Cookie::get('password') !== null ? Cookie::get('password') : null }}"
                            placeholder="Ingresa tu contrasena"
                            class="form-control auth-input{{ $errors->has('password') ? ' is-invalid' : '' }}"
                            name="password" tabindex="2" required>
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    </div>

                    <div class="auth-meta">
                        <a href="{{ route('forgot-password') }}" class="auth-link">Has olvidado tu contrasena?</a>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary auth-submit" tabindex="4">
                            @lang('locale.Login')
                        </button>
                    </div>
                </form>

                @if (Route::has('register'))
                    <div class="auth-support text-center">
                        Ya tienes una cuenta?
                        <a href="{{ route('register') }}" class="auth-link">@lang('locale.Create an Account')</a>
                    </div>
                @endif
            </div>
        </section>
    </main>
</body>
@endsection
@section('scripts')
@endsection

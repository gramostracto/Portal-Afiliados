@extends('layouts.auth_app')
@section('title')
    Usuario no vinculado
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

        <section class="auth-card-wrap" aria-label="Usuario no vinculado">
            <div class="auth-card">
                <div class="auth-card-header">
                    <div class="auth-kicker">Acceso seguro</div>
                    <h2>Usuario no vinculado</h2>
                    <p class="auth-card-subtitle">No pudimos encontrar tu proveedor en nuestro sistema de gestion de facturas.</p>
                </div>

                <div class="auth-alert">
                    No encontramos un proveedor asociado a tu documento
                    @if ($document_type)
                        ({{ $document_type }}@if ($number_id): {{ $number_id }}@endif)
                    @endif
                    en nuestro sistema de gestion de facturas (ERP).
                    <br><br>
                    Esto ocurre cuando el numero de documento registrado en tu cuenta no coincide con el que
                    tienes registrado como proveedor. Por favor comunicate con el administrador del portal
                    para que verifique y vincule tu cuenta correctamente.
                </div>

                <div class="auth-support text-center">
                    <a href="{{ url('logout') }}" class="auth-link"
                        onclick="event.preventDefault(); localStorage.clear(); document.getElementById('logout-form').submit();">
                        Volver al inicio de sesion
                    </a>

                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
@endsection

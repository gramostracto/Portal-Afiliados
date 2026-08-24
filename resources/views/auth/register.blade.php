@extends('layouts.auth_app')
@section('title')
    Register
@endsection
@section('content')

<body id="kt_body" class="auth-page">
    @include('auth.partials.auth-style')

    <main class="auth-shell auth-shell--wide">
        <section class="auth-brand" aria-label="Portal de afiliados Tractocar">
            <img class="auth-brand-logo" src="{{ asset('assets/images/logos-tractocar/TCL_POS_CMYK-01.png') }}" alt="Tractocar">
            <h1>Portal de afiliados</h1>
            <p>Consulta, gestiona y actualiza tu informacion de afiliacion de forma segura.</p>
        </section>

        <section class="auth-card-wrap" aria-label="Registrarse">
            <div class="auth-card auth-card--wide">
                <div class="auth-card-header">
                    <div class="auth-kicker">Acceso seguro</div>
                    <h2>Registrarse</h2>
                    <p class="auth-card-subtitle">Completa tus datos para solicitar la creacion de tu cuenta.</p>
                </div>

                @if ($errors->any())
                    <div class="auth-alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="auth-fields-row">
                        <div class="auth-field">
                            <label for="firstName">Nombre Completo</label>
                            <input id="firstName" type="text"
                                class="form-control auth-input{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                name="name" tabindex="1" placeholder="Nombre Completo" value="{{ old('name') }}"
                                autofocus required>
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        </div>
                        <div class="auth-field">
                            <label for="email">Email</label>
                            <input id="email" type="email"
                                class="form-control auth-input{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                placeholder="Email" name="email" tabindex="1" value="{{ old('email') }}" required>
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        </div>
                        <div class="auth-field">
                            <label for="phone">Telefono</label>
                            <input id="phone" type="text" inputmode="numeric" pattern="[0-9]{7,11}" maxlength="11"
                                class="form-control auth-input{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                name="phone" tabindex="1" placeholder="Telefono" value="{{ old('phone') }}" required>
                            <div class="invalid-feedback">
                                {{ $errors->first('phone') }}
                            </div>
                        </div>
                    </div>

                    <div class="auth-fields-row">
                        <div class="auth-field">
                            <label for="document_type">Tipo de documento</label>
                            <select class="form-select auth-input auth-select{{ $errors->has('document_type') ? ' is-invalid' : '' }}"
                                name="document_type" required>
                                <option selected value="">Seleccione tipo Documento</option>
                                <option value="NIT" {{ old('document_type') == 'NIT' ? 'selected' : '' }}>NIT</option>
                                <option value="CC" {{ old('document_type') == 'CC' ? 'selected' : '' }}>Cedula de Ciudadania</option>
                            </select>
                            <div class="invalid-feedback">
                                {{ $errors->first('document_type') }}
                            </div>
                        </div>
                        <div class="auth-field">
                            <label for="number_id">Numero Identificacion</label>
                            <input id="number_id" type="number"
                                class="form-control auth-input{{ $errors->has('number_id') ? ' is-invalid' : '' }}"
                                name="number_id" tabindex="1" placeholder="Numero Identificacion"
                                value="{{ old('number_id') }}" required>
                            <div class="invalid-feedback">
                                {{ $errors->first('number_id') }}
                            </div>
                        </div>
                        <div class="auth-field">
                            <label for="photo_id">Copia del documento (.PDF)</label>
                            <input id="photo_id" accept=".pdf" type="file" class="form-control auth-input"
                                name="photo_id">
                        </div>
                    </div>

                    <div class="auth-fields-row">
                        <div class="auth-field">
                            <label for="photo">Foto Perfil</label>
                            <input id="photo" accept="image/jpeg,image/jpg,image/png" type="file"
                                class="form-control auth-input" name="photo">
                        </div>
                        <div class="auth-field">
                            <label for="password">Contrasena</label>
                            <input id="password" type="password"
                                class="form-control auth-input{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                placeholder="Contrasena" name="password" tabindex="2" minlength="8" required>
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        </div>
                        <div class="auth-field">
                            <label for="password_confirmation">Confirmar Contrasena</label>
                            <input id="password_confirmation" type="password" placeholder="Confirmar Contrasena"
                                class="form-control auth-input{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                name="password_confirmation" minlength="8" tabindex="2">
                            <div class="invalid-feedback">
                                {{ $errors->first('password_confirmation') }}
                            </div>
                        </div>
                    </div>

                    <div class="auth-field">
                        <label>Verificacion</label>
                        <div class="auth-captcha">
                            <span>{!! captcha_img('flat') !!}</span>
                            <button type="button" class="btn-refresh-captcha" id="refresh-captcha">&#x21bb;</button>
                        </div>
                        <input id="captcha" type="text" class="form-control auth-input" placeholder="Ingresa el captcha"
                            name="captcha">
                        <div class="invalid-feedback">
                            {{ $errors->first('captcha') }}
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary auth-submit" tabindex="4">
                            Registrarse
                        </button>
                    </div>
                </form>

                <div class="auth-support text-center">
                    Ya tienes una cuenta?
                    <a href="{{ route('login') }}" class="auth-link">Iniciar sesion</a>
                </div>
            </div>
        </section>
    </main>
</body>
@endsection

@section('scripts')
<script>

    $(document).ready(function () {
        refreshCaptcha(); // Refresca el captcha al cargar la vista

        $('#refresh-captcha').click(function () {
            refreshCaptcha(); // Refresca el captcha al hacer clic
        });

        setInterval(refreshCaptcha, 120000);

    function refreshCaptcha() {
        $.ajax({
            type: 'GET',
            url: "{{ route('refresh.captcha') }}",
            success: function (data) {
                $(".auth-captcha span").html(data.captcha);
            },
            error: function () {
                alert('Error al refrescar el captcha.');
            }
        });
    }
});


</script>
@endsection

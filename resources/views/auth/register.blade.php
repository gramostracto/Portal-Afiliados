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
                                name="name" placeholder="Nombre Completo" value="{{ old('name') }}"
                                autofocus required>
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        </div>
                        <div class="auth-field">
                            <label for="email">Email</label>
                            <input id="email" type="email"
                                class="form-control auth-input{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                placeholder="Email" name="email" value="{{ old('email') }}" required>
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        </div>
                        <div class="auth-field">
                            <label for="phone">Telefono</label>
                            <input id="phone" type="text" inputmode="numeric" pattern="[0-9]{7,11}" maxlength="11"
                                class="form-control auth-input{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                name="phone" placeholder="Telefono" value="{{ old('phone') }}" required>
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
                            <input id="number_id" type="text" inputmode="numeric" pattern="[0-9]*"
                                class="form-control auth-input{{ $errors->has('number_id') ? ' is-invalid' : '' }}"
                                name="number_id" placeholder="Numero Identificacion"
                                value="{{ old('number_id') }}" required>
                            <small id="nit-hint" class="text-muted" style="display:none;">NIT de 9 digitos, sin guion ni digito de verificacion.</small>
                            <div class="invalid-feedback">
                                {{ $errors->first('number_id') }}
                            </div>
                        </div>
                        <div class="auth-field" id="dv-field" style="display:none;">
                            <label for="dv">Digito de verificacion (DV)</label>
                            <input id="dv" type="text" name="dv" readonly maxlength="1"
                                class="form-control auth-input{{ $errors->has('dv') ? ' is-invalid' : '' }}"
                                value="{{ old('dv') }}" placeholder="DV">
                            <div class="invalid-feedback">
                                {{ $errors->first('dv') }}
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
                                placeholder="Contrasena" name="password" minlength="8" required>
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        </div>
                        <div class="auth-field">
                            <label for="password_confirmation">Confirmar Contrasena</label>
                            <input id="password_confirmation" type="password" placeholder="Confirmar Contrasena"
                                class="form-control auth-input{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                name="password_confirmation" minlength="8">
                            <div class="invalid-feedback">
                                {{ $errors->first('password_confirmation') }}
                            </div>
                        </div>
                    </div>

                    <div class="auth-field">
                        <label>Verificacion</label>
                        <div class="auth-captcha">
                            <span>{!! captcha_img('flat') !!}</span>
                            <button type="button" class="btn-refresh-captcha" id="refresh-captcha" tabindex="-1">&#x21bb;</button>
                        </div>
                        <input id="captcha" type="text" class="form-control auth-input" placeholder="Ingresa el captcha"
                            name="captcha">
                        <div class="invalid-feedback">
                            {{ $errors->first('captcha') }}
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary auth-submit">
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

    // Calculo del DV (modulo 11, DIAN)
    function calcularDv(nit) {
        var pesos = [3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71];
        var suma = 0;
        for (var i = 0; i < nit.length; i++) {
            suma += parseInt(nit.charAt(nit.length - 1 - i), 10) * pesos[i];
        }
        var r = suma % 11;
        return r > 1 ? 11 - r : r;
    }

    function actualizarNit() {
        var esNit = $('select[name="document_type"]').val() === 'NIT';
        var $num = $('#number_id');
        var digitos = $num.val().replace(/\D+/g, '');

        if (esNit) {
            digitos = digitos.substring(0, 9);
            $num.attr('maxlength', 9);
        } else {
            $num.removeAttr('maxlength');
        }
        $num.val(digitos);

        $('#dv-field, #nit-hint').toggle(esNit);
        $('#dv').val(esNit && digitos.length === 9 ? calcularDv(digitos) : '');
    }

    $(document).ready(function () {
        $('select[name="document_type"]').on('change', actualizarNit);
        $('#number_id').on('input', actualizarNit);
        actualizarNit();

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

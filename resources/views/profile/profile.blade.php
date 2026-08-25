@extends('layouts.app')

@section('content')

    <body class="ltr app sidebar-mini light-mode">
        @can('/usuario.index')
            <div class="app-content main-content mt-0">
            @endcan
            <div class="side-app">
                <div class="main-container container-fluid">
                        <div class="page-header">
                            <div>
                                <h1 class="page-title">Perfil</h1>
                            </div>
                            <div class="ms-auto pageheader-btn">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Perfil</li>
                                </ol>
                            </div>
                        </div>
                    <div class="row" id="user-profile">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-lg-12 col-md-12 col-xl-6">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <div class="profile-img-main rounded">
                                                    <!-- Elemento img o div, según corresponda -->
                                                    <div id="profile-image-container">
                                                        <img id="profile-image" alt="avatar" class="avatar avatar-xl rounded">
                                                    </div>
                                                </div>
                                                <div class="ms-4">
                                                    <h4>{{ $user->name }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($user->status != 'ASOCIADO')
                                            <div class="col-lg-12 col-md-12 col-xl-6">
                                                <div class="d-md-flex flex-wrap justify-content-lg-end">
                                                    <div class="media m-3">
                                                        <div class="media-icon bg-info me-3 mt-1">
                                                            <i class="fe fe-users  fs-20 text-white"></i>
                                                        </div>
                                                        <div class="media-body">
                                                            <span class="text-muted">Usuarios asociados</span>
                                                            <div class="fw-semibold fs-25">
                                                                {{ count($user_relation) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="border-top">
                                    <div class="wideget-user-tab">
                                        <div class="tab-menu-heading">
                                            <div class="tabs-menu1">
                                                <ul class="nav">
                                                    <li><a href="#profileMain" class="active show"
                                                            data-bs-toggle="tab"><i class="fe fe-user me-2"></i>Perfil</a></li>
                                                    <li><a href="#editProfile" data-bs-toggle="tab"><i class="fe fe-edit-2 me-2"></i>Editar Perfil</a></li>
                                                    @can('/facturas')
                                                        @if ($user->status != 'ASOCIADO')
                                                            <li><a href="#friends" data-bs-toggle="tab"><i class="fe fe-users me-2"></i>Usuarios Asociados</a>
                                                            </li>
                                                            <li><a href="#accountSettings" data-bs-toggle="tab"><i class="fe fe-user-plus me-2"></i>Registrar
                                                                    Usuario</a>
                                                            </li>
                                                        @endif
                                                    @endcan
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane active show" id="profileMain">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="border-top"></div>
                                            <div class="p-5">
                                                <h3 class="card-title mb-4">Informacion personal</h3>
                                                <div class="row row-sm">
                                                    <div class="col-lg-12 col-xl-6">
                                                        <div class="media mb-4">
                                                            <div class="media-icon bg-primary me-3">
                                                                <i class="fe fe-user fs-16 text-white"></i>
                                                            </div>
                                                            <div class="media-body">
                                                                <span class="text-muted d-block">Nombre</span>
                                                                <span class="fw-semibold">{{ $user->name }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-xl-6">
                                                        <div class="media mb-4">
                                                            <div class="media-icon bg-info me-3">
                                                                <i class="fe fe-mail fs-16 text-white"></i>
                                                            </div>
                                                            <div class="media-body">
                                                                <span class="text-muted d-block">Email</span>
                                                                <span class="fw-semibold">{{ $user->email }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-xl-6">
                                                        <div class="media mb-4">
                                                            <div class="media-icon bg-success me-3">
                                                                <i class="fe fe-phone fs-16 text-white"></i>
                                                            </div>
                                                            <div class="media-body">
                                                                <span class="text-muted d-block">Telefono</span>
                                                                <span class="fw-semibold">{{ empty($user->phone) ? 'Sin registrar' : $user->phone }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="editProfile">
                                    <div class="row">
                                        <div class="card col-xs-12 col-sm-12 col-md-5">
                                            <div class="card-body border-0">
                                                <div class="card-header font-weight-bold">
                                                    {{ __('Cambiar foto de perfil') }}

                                                </div>

                                                <form id="photo-profile-form" method="POST" action="{{ route('photo-profile.updatePhoto') }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <img id="photo-profile-preview" class="avatar avatar-lg rounded me-3"
                                                                src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('img/logo.png') }}"
                                                                alt="Vista previa">
                                                            <div class="input-group">
                                                                <input type="file" class="form-control" name="profile_image" accept="image/*" id="photo-profile-input" aria-describedby="inputGroupFileAddon01">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-footer">
                                                        <button type="button" class="btn btn-primary" id="photo-profile-button">Subir imagen de perfil</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-xs-1 col-sm-1 col-md-1"></div>
                                        <div class="card col-xs-12 col-sm-12 col-md-6">
                                            <div class="card-body border-0">
                                                <div class="card-header">{{ __('Informacion General') }}</div>

                                                <form class="form-horizontal" method="post"
                                                    action="{{ route('profile.update') }}" novalidate>
                                                    @csrf
                                                    <div class="card-body">
                                                        {{ method_field('PUT') }}
                                                        <div class="row mb-4">
                                                            <div class="col-md-12 col-lg-12 col-xl-6">
                                                                <div class="form-group">
                                                                    <label for="email" class="form-label">email</label>
                                                                    <input type="text" name="pfEmail" id="pfEmail"
                                                                        class="form-control" tabindex="3"
                                                                        value="{{ $user->email }}" disabled
                                                                        onInput="validarInput()">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-12 col-xl-6">
                                                                <div class="form-group">
                                                                    <label for="phone" class="form-label">Phone</label>
                                                                    <input type="text" name="pfTelefono" id="pfTelefono"
                                                                        class="form-control" tabindex="3"
                                                                        value="{{ $user->phone }}" onInput="validarInput()">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-12 col-xl-6">
                                                                <div class="wrap-input100 validate-input">
                                                                    <label for="formFile" class="form-label">Copia del documento de identidad extension requerida(.PDF)</label>
                                                                    <input id="photo_id"
                                                                        accept=".pdf"
                                                                        type="file"
                                                                        class="form-control"
                                                                        name="photo_id"
                                                                        tabindex="1" placeholder="Enter Identification" value="{{ old('photo_id') }}"
                                                                        autofocus>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <button type="submit" class="btn btn-primary"
                                                            id="btnPrEditSave">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body border-0">
                                                <div class="card-header">{{ __('Cambiar la contraseña') }}</div>

                                                <form id="change-password-form" action="{{ route('change-password.update') }}" method="POST">
                                                    @csrf
                                                    <div class="card-body">

                                                            <div id="errors-container" class="alert alert-danger" role="alert" style="display: none">
                                                            </div>

                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-12 col-md-4">
                                                                <label for="oldPasswordInput" class="form-label">Contraseña anterior</label>
                                                                <div class="input-group">
                                                                    <input name="old_password" type="password"
                                                                        class="form-control @error('old_password') is-invalid @enderror"
                                                                        id="oldPasswordInput" placeholder="Contraseña anterior">
                                                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#oldPasswordInput" tabindex="-1"><i class="fe fe-eye"></i></button>
                                                                </div>
                                                                @error('old_password')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                            <div class="col-xs-12 col-sm-12 col-md-4">
                                                                <label for="newPasswordInput" class="form-label">Nueva contraseña</label>
                                                                <div class="input-group">
                                                                    <input name="new_password" type="password"
                                                                        class="form-control @error('new_password') is-invalid @enderror"
                                                                        id="newPasswordInput" placeholder="Nueva contraseña">
                                                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#newPasswordInput" tabindex="-1"><i class="fe fe-eye"></i></button>
                                                                </div>
                                                                @error('new_password')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                            <div class="col-xs-12 col-sm-12 col-md-4">
                                                                <label for="confirmNewPasswordInput"
                                                                    class="form-label">Confirmar nueva contraseña</label>
                                                                <div class="input-group">
                                                                    <input name="new_password_confirmation" type="password"
                                                                        class="form-control" id="confirmNewPasswordInput"
                                                                        placeholder="Confirmar nueva contraseña">
                                                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#confirmNewPasswordInput" tabindex="-1"><i class="fe fe-eye"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="card-footer">
                                                        <button type="button" id="change-password-button" class="btn btn-primary">Cambiar Contraseña</button>
                                                    </div>

                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                {{-- Lista de Usuarios Asociados --}}
                                <div class="tab-pane" id="friends">
                                    <div class="row row-sm">
                                        @foreach ($user_relation as $asociado)
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                                <div class="card">
                                                    <div class="card-header border-bottom">
                                                        <h3 class="card-title"> {{ $asociado->name }}</h3>
                                                        <div class="card-options">
                                                            <div class="dropdown text-end">
                                                                @if ($asociado->deleted_at == null)
                                                                    <a href="#" data-bs-toggle="dropdown"
                                                                        aria-haspopup="true" aria-expanded="true">
                                                                        <i class="fe fe-more-vertical text-muted"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-right shadow">
                                                                        <a class="dropdown-item clickDeleted"
                                                                            id="{{ $asociado->deleted_status }}"
                                                                            href="{{ url("profile/userAsociado/{$asociado->id}") }}">
                                                                            <i class="fe fe-trash-2 me-2"></i> Delete
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                                @if ($asociado->deleted_at != null)
                                                                    <a href="#" data-bs-toggle="dropdown"
                                                                        aria-haspopup="true" aria-expanded="true">
                                                                        <i class="fe fe-more-vertical text-muted"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-right shadow">
                                                                        <a class="dropdown-item"
                                                                            href="{{ url("profile/userAsociadoRestore/{$asociado->id}") }}">
                                                                            <i class="fa fa-retweet"
                                                                                aria-hidden="true"></i>
                                                                            Reasignar
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="card-alert alert alert-{{ $asociado->deleted_at == null ? 'success' : 'danger' }} mb-0">
                                                        {{ $asociado->deleted_at == null
                                                            ? 'Usuario vigente'
                                                            : 'Usuario
                                                                                                                                                                                                                    inabilitado' }}
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <a href="#">
                                                            <div
                                                                class="avatar avatar-md bg-{{ $user->otherColors(rand(2, 9)) }} text-white rounded-circle">
                                                                {{ substr($asociado->email, 0, 2) }}
                                                            </div>
                                                            <h4 class="fs-16 mb-0 mt-3 text-dark fw-semibold">
                                                                {{ $asociado->name }}</h4>
                                                            <span class="text-muted">{{ $asociado->phone }}</span>
                                                            <br>
                                                            <span class="text-muted">{{ $asociado->email }}</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                {{-- Registro de Asociado --}}
                                <div class="tab-pane" id="accountSettings">
                                    <div class="card">
                                        <div class="card-body">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div><br />
                                            @endif
                                            {{-- <div class="alert alert-success" id="alert" style="display: none;">&nbsp;
                                        </div> --}}
                                            <form method="POST" id="rgisterform"
                                                action="{{ route('userAsociado.create') }}" enctype="multipart/form-data"
                                                class="validate-form" data-select2-id="11">
                                                <div class="mb-4 main-content-label">Registrar usuario asociado</div>
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName" class="form-label">Nombre completo</label>
                                                            <input id="firstName" type="text"
                                                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                                name="name" tabindex="1"
                                                                placeholder="Nombre Completo" value="{{ old('name') }}"
                                                                autofocus required>
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('name') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input id="email" type="email"
                                                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                                placeholder="Correo electrónico" name="email"
                                                                tabindex="1" value="{{ old('email') }}" required>
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('email') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="telefono" class="form-label">Teléfono</label>
                                                            <input id="telefono" type="number"
                                                                class="form-control{{ $errors->has('telefono') ? ' is-invalid' : '' }}"
                                                                name="telefono" tabindex="1" placeholder="Teléfono"
                                                                value="{{ old('telefono') }}" required>
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('telefono') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="document_type" class="form-label">Tipo documento</label>
                                                            <select id="document_type" class="form-control{{ $errors->has('document_type') ? ' is-invalid' : '' }}" name="document_type"
                                                                aria-label=".form-select-sm example" required>
                                                                <option selected value="">Seleccione tipo
                                                                    Documento
                                                                </option>
                                                                <option value="NIT">NIT</option>
                                                                <option value="CC">Cedula de
                                                                    Ciudadania</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('document_type') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="identification"
                                                                class="form-label">Identificación</label>
                                                            <input id="identification" type="number"
                                                                class="form-control{{ $errors->has('identification') ? ' is-invalid' : '' }}"
                                                                name="identification" tabindex="1"
                                                                placeholder="Numero Identificacion"
                                                                value="{{ old('identification') }}" required>
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('identification') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="password" class="form-label">Contraseña</label>
                                                            <div class="input-group">
                                                                <input id="password" type="password"
                                                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                                    placeholder="Contraseña" name="password" tabindex="2"
                                                                    required>
                                                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password" tabindex="-1"><i class="fe fe-eye"></i></button>
                                                            </div>
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('password') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <button type="submit"
                                                        class="btn btn-primary" tabindex="4">
                                                        Registrar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src={{ asset('views/js/profile/profile-modifications.js') }}></script>

    <script>
        $(document).ready(function() {
            // Verificar si el usuario tiene una imagen de perfil
            var profileImage = '{{ asset('storage/' . Auth::user()->photo) }}';

            if (profileImage !== '{{ asset('storage/') }}') {
                // Si tiene una imagen, mostrarla en el elemento img
                $('#profile-image').attr('src', profileImage);
            } else {
                // Si no tiene una imagen, mostrar el div con las iniciales
                var initials = '{{ strtoupper(substr(Auth::user()->email, 0, 2)) }}';
                var backgroundColor = 'bg-' + '{{ Auth::user()->otherColors(rand(2, 9)) }}';
                var divContent = '<div class="avatar avatar-xl ' + backgroundColor + ' text-white rounded-circle">' + initials + '</div>';
                $('#profile-image-container').html(divContent);
            }
        });

        $(document).on("submit", "#rgisterform", function(e) {
            e.preventDefault(); //detemos el formluario
            $("#rgisterform").validate();
            $.ajax({
                type: $('#rgisterform').attr('method'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: $('#rgisterform').attr('action'),
                data: $('#rgisterform').serialize(),
                success: function(response) {
                    if (response.success == true) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Usuario registrado correctamente',
                            showConfirmButton: false,
                            timer: 2500
                        })
                        $("#rgisterform")[0].reset();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Solo puede registrar como máximo 4 Usuario, para más información comuníquese con el Administrador!',
                        })
                        $("#rgisterform")[0].reset();
                    }
                }
            });
        });

        // $('.clickDeleted').on('click', function(e) {
        //     e.preventDefault();
        //     let status = this.id
        //     console.log(status);
        //     if (status == "RESIGNED") {
        //         const swalWithBootstrapButtons = Swal.mixin({
        //         customClass: {
        //             confirmButton: 'btn btn-success',
        //             cancelButton: 'btn btn-danger'
        //         },
        //         buttonsStyling: false
        //         })

        //         swalWithBootstrapButtons.fire({
        //         title: 'Advertencia!',
        //         text: "El usuario ha sido eliminado y reasignado anteriormente, tenga en cuenta que si lo vuelve a eliminar no podrá volver a ser reasignado!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'Si, eliminar!',
        //         cancelButtonText: 'No, cancelar!',
        //         reverseButtons: true
        //         }).then((result) => {
        //         if (result.isConfirmed) {

        //             swalWithBootstrapButtons.fire(
        //             'Eliminado!',
        //             'El usuario eliminado correctamente',
        //             'success'
        //             )
        //         } else if (
        //             /* Read more about handling dismissals below */
        //             result.dismiss === Swal.DismissReason.cancel
        //         ) {
        //             swalWithBootstrapButtons.fire(
        //             'Cancelado',
        //             'Tu usuario está a salvo :)',
        //             'error'
        //             )
        //         }
        //         })
        //     }
        // })

        let urlChangePassword = "{{ route('change-password.update') }}"
        changePassword(urlChangePassword);

        let urlupdatePhoto = "{{ route('photo-profile.updatePhoto') }}"
        updatePhoto(urlupdatePhoto);

        // Vista previa de la nueva foto de perfil antes de subirla
        $('#photo-profile-input').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#photo-profile-preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        // Mostrar/ocultar contraseña
        $('.toggle-password').on('click', function() {
            var target = $($(this).data('target'));
            var icon = $(this).find('i');
            if (target.attr('type') === 'password') {
                target.attr('type', 'text');
                icon.removeClass('fe-eye').addClass('fe-eye-off');
            } else {
                target.attr('type', 'password');
                icon.removeClass('fe-eye-off').addClass('fe-eye');
            }
        });
    </script>

    @if (session('message'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El usuario no puede ser eliminado, ya que anteriormente fue eliminado y reasignado, por tal motivo no se puede proceder con la eliminación!',
            })
        </script>
    @endif


@endsection

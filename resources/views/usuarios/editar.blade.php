@extends('layouts.app')

@section('styles')
    <style>
        .edit-user-summary,
        .edit-user-form-card {
            border: 1px solid #eef1f6;
            border-radius: 6px;
            box-shadow: none;
        }

        .edit-user-summary .avatar {
            height: 64px;
            width: 64px;
        }

        .edit-user-meta {
            border-top: 1px solid #eef1f6;
            margin-top: 18px;
            padding-top: 18px;
        }

        .edit-user-meta-item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 9px 0;
        }

        .edit-user-meta-label {
            color: #76839a;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .edit-user-section-title {
            color: #344050;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .edit-user-section {
            border-bottom: 1px solid #eef1f6;
            margin-bottom: 22px;
            padding-bottom: 10px;
        }

        .edit-user-section:last-of-type {
            border-bottom: 0;
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')

    <body class="ltr app sidebar-mini light-mode">
        <div class="app-content main-content mt-0">
            <div class="side-app">
                <div class="main-container container-fluid">
                    <div class="page-header">
                        <div>
                            <h1 class="page-title">Editar Usuario</h1>
                        </div>
                        <div class="ms-auto pageheader-btn">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('usuario.index') }}">Usuario</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Editar</li>
                            </ol>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Revise los campos:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row row-sm">
                        <div class="col-xl-4 col-lg-5">
                            <div class="card edit-user-summary">
                                <div class="card-body text-center">
                                    @if (empty($user->photo))
                                        <div class="avatar bg-{{ $user->otherColors(($user->id % 8) + 2) }} text-white rounded-circle mx-auto mb-3">
                                            {{ strtoupper(substr($user->email, 0, 2)) }}
                                        </div>
                                    @else
                                        @php
                                            $photoPath = $user->photo;
                                            if (\Illuminate\Support\Str::startsWith($photoPath, ['http://', 'https://'])) {
                                                $photoUrl = $photoPath;
                                            } elseif (\Illuminate\Support\Str::startsWith($photoPath, ['storage/', 'public/'])) {
                                                $photoUrl = asset($photoPath);
                                            } else {
                                                $photoUrl = asset('storage/' . $photoPath);
                                            }
                                        @endphp
                                        <div class="avatar rounded-circle mx-auto mb-3" style="overflow: hidden;">
                                            <img src="{{ $photoUrl }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @endif

                                    <h4 class="mb-1">{{ $user->name }}</h4>
                                    <a class="text-muted d-block text-truncate" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                    <span class="badge font-weight-semibold bg-{{ $user->badges($user->status) }}-transparent text-{{ $user->badges($user->status) }} mt-3">
                                        {{ $user->status }}
                                    </span>

                                    <div class="edit-user-meta text-start">
                                        <div class="edit-user-meta-item">
                                            <span class="edit-user-meta-label">Documento</span>
                                            <span>{{ $user->document_type }} {{ $user->number_id }}</span>
                                        </div>
                                        <div class="edit-user-meta-item">
                                            <span class="edit-user-meta-label">Teléfono</span>
                                            <span>{{ $user->phone ?: 'Sin teléfono' }}</span>
                                        </div>
                                        <div class="edit-user-meta-item">
                                            <span class="edit-user-meta-label">Rol actual</span>
                                            <span>{{ count($userRole) ? implode(', ', $userRole) : 'Sin rol' }}</span>
                                        </div>
                                    </div>

                                    <a href="{{ route('usuario.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                                        <i class="fa fa-arrow-left me-1"></i> Volver a usuarios
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-8 col-lg-7">
                            <div class="card edit-user-form-card">
                                <div class="card-body">
                                    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['usuarios.update', $user->id]]) !!}
                                        <div class="edit-user-section">
                                            <div class="edit-user-section-title">Datos personales</div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="name" class="form-label">Nombre</label>
                                                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre completo']) !!}
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">E-mail</label>
                                                    {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'correo@empresa.com']) !!}
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="phone" class="form-label">Celular</label>
                                                    {!! Form::text('phone', null, ['class' => 'form-control', 'maxlength' => 11, 'pattern' => '[0-9]{7,11}', 'inputmode' => 'numeric', 'placeholder' => 'Número de celular']) !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-user-section">
                                            <div class="edit-user-section-title">Identificación y acceso</div>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="document_type" class="form-label">Tipo identificación</label>
                                                    {!! Form::select('document_type', ['NIT' => 'NIT', 'CC' => 'Cédula de Ciudadanía'], null, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="number_id" class="form-label">Número identificación</label>
                                                    {!! Form::text('number_id', null, ['class' => 'form-control', 'placeholder' => 'Documento']) !!}
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="status" class="form-label">Estado</label>
                                                    {!! Form::select('status', ['NUEVO' => 'NUEVO', 'CONFIRMADO' => 'CONFIRMADO', 'RECHAZADO' => 'RECHAZADO', 'ASOCIADO' => 'ASOCIADO'], null, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="roles" class="form-label">Rol</label>
                                                    {!! Form::select('roles[]', $roles, $userRole, ['class' => 'form-control']) !!}
                                                    <small class="text-muted">El rol Cliente Hijo debe mantener estado ASOCIADO.</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-user-section">
                                            <button class="btn btn-warning-light" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapsePassword" aria-expanded="false"
                                                aria-controls="collapsePassword">
                                                <i class="fa fa-key me-1"></i> Cambiar contraseña
                                            </button>

                                            <div class="collapse mt-3" id="collapsePassword">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="password" class="form-label">Nueva contraseña</label>
                                                        {!! Form::password('password', ['class' => 'form-control', 'autocomplete' => 'new-password']) !!}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="confirm-password" class="form-label">Confirmar contraseña</label>
                                                        {!! Form::password('confirm-password', ['class' => 'form-control', 'autocomplete' => 'new-password']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap justify-content-end gap-2">
                                            <a href="{{ route('usuario.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save me-1"></i> Guardar cambios
                                            </button>
                                        </div>
                                    {!! Form::close() !!}
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
    @if (Session::has('message'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El estado del usuario no concuerda con el rol asignado o viceversa!',
            })
        </script>
    @endif
    @if (Session::has('message1'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: 'Los datos se guardaron correctamente'
            })
        </script>
    @endif
@endsection

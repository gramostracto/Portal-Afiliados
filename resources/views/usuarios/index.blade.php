@extends('layouts.app')

@section('styles')
    <style>
        .users-dashboard-card {
            border: 1px solid #eef1f6;
            border-radius: 6px;
            box-shadow: none;
        }

        .users-dashboard-card .card-body {
            min-height: 112px;
        }

        .users-dashboard-label {
            color: #76839a;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .users-dashboard-value {
            color: #1f2937;
            font-size: 28px;
            font-weight: 700;
            line-height: 1.1;
        }

        .users-dashboard-icon {
            align-items: center;
            border-radius: 6px;
            display: inline-flex;
            height: 38px;
            justify-content: center;
            width: 38px;
        }

        .user-result-card {
            border: 1px solid #eef1f6;
            border-radius: 6px;
            box-shadow: none;
            height: 100%;
        }

        .user-result-card .avatar {
            flex: 0 0 auto;
        }

        .user-result-meta {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .user-result-meta-label {
            color: #76839a;
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .user-result-meta-value {
            color: #344050;
            display: block;
            font-size: 13px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-result-actions {
            border-top: 1px solid #eef1f6;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding-top: 14px;
        }

        .user-result-toolbar {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .affiliate-validation .validation-source {
            align-items: center;
            border: 1px solid #eef1f6;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            padding: 14px 16px;
        }

        .affiliate-validation .validation-source-label {
            color: #344050;
            font-weight: 700;
        }

        .affiliate-validation .validation-grid {
            border: 1px solid #eef1f6;
            border-radius: 6px;
            overflow: hidden;
        }

        .affiliate-validation .validation-row {
            display: grid;
            grid-template-columns: 180px repeat(3, minmax(0, 1fr));
        }

        .affiliate-validation .validation-row + .validation-row {
            border-top: 1px solid #eef1f6;
        }

        .affiliate-validation .validation-field,
        .affiliate-validation .validation-value {
            padding: 14px 16px;
        }

        .affiliate-validation .validation-field {
            align-items: center;
            background: #f8fafc;
            display: flex;
            gap: 10px;
        }

        .affiliate-validation .validation-icon {
            align-items: center;
            background: #fff;
            border: 1px solid #eef1f6;
            border-radius: 6px;
            color: var(--primary-bg-color);
            display: inline-flex;
            height: 34px;
            justify-content: center;
            width: 34px;
        }

        .affiliate-validation .validation-field-label,
        .affiliate-validation .validation-value-source {
            color: #76839a;
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .affiliate-validation .validation-value {
            border-left: 1px solid #eef1f6;
            min-width: 0;
        }

        .affiliate-validation .validation-value a,
        .affiliate-validation .validation-value span:not(.validation-value-source) {
            color: #344050;
            display: block;
            margin-top: 4px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (max-width: 575.98px) {
            .user-result-meta {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 991.98px) {
            .affiliate-validation .validation-row {
                grid-template-columns: 1fr;
            }

            .affiliate-validation .validation-value {
                border-left: 0;
                border-top: 1px solid #eef1f6;
            }
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
                            <h1 class="page-title">Usuarios</h1>
                        </div>
                        <div class="ms-auto pageheader-btn">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
                            </ol>
                        </div>
                    </div>

                    <div class="card-body px-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div>
                                <h5 class="mb-1">Gestión de usuarios</h5>
                                <p class="text-muted mb-0">Consulta, filtra y administra afiliados por estado, rol o documento.</p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ url('portal/usuarios/create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus me-1"></i> Crear Usuario
                                </a>
                                <button type="button" id="btnGetUserEliminated" class="btn btn-outline-primary">
                                    <i class="fa fa-history me-1"></i> Usuarios Eliminados
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-info" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fa fa-filter me-1"></i> Filtros
                        </button>
                    </div>

                    <div class="row row-sm">
                        <div class="col-sm-6 col-xl-3">
                            <div class="card users-dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="users-dashboard-label">Usuarios activos</div>
                                            <div class="users-dashboard-value mt-2">{{ $dashboard['active_total'] ?? 0 }}</div>
                                        </div>
                                        <span class="users-dashboard-icon bg-primary-transparent text-primary">
                                            <i class="fa fa-users"></i>
                                        </span>
                                    </div>
                                    <div class="text-muted tx-12 mt-3">
                                        {{ $dashboard['confirmed_total'] ?? 0 }} confirmados · {{ $dashboard['new_total'] ?? 0 }} nuevos
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card users-dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="users-dashboard-label">Usuarios eliminados</div>
                                            <div class="users-dashboard-value mt-2">{{ $dashboard['deleted_total'] ?? 0 }}</div>
                                        </div>
                                        <span class="users-dashboard-icon bg-danger-transparent text-danger">
                                            <i class="fa fa-trash"></i>
                                        </span>
                                    </div>
                                    <div class="text-muted tx-12 mt-3">Disponibles para restaurar desde el modal</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card users-dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="users-dashboard-label">Docs repetidos</div>
                                            <div class="users-dashboard-value mt-2">{{ $dashboard['duplicate_documents_total'] ?? 0 }}</div>
                                        </div>
                                        <span class="users-dashboard-icon bg-warning-transparent text-warning">
                                            <i class="fa fa-id-card"></i>
                                        </span>
                                    </div>
                                    <div class="text-muted tx-12 mt-3">Incluye usuarios activos y eliminados</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card users-dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="users-dashboard-label">Sin rol asignado</div>
                                            <div class="users-dashboard-value mt-2">{{ $dashboard['without_role_total'] ?? 0 }}</div>
                                        </div>
                                        <span class="users-dashboard-icon bg-info-transparent text-info">
                                            <i class="fa fa-user-times"></i>
                                        </span>
                                    </div>
                                    <div class="text-muted tx-12 mt-3">{{ $dashboard['rejected_total'] ?? 0 }} rechazados · {{ $dashboard['associated_total'] ?? 0 }} asociados</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (!empty($dashboard['duplicate_documents']) && $dashboard['duplicate_documents']->count() > 0)
                        <div class="card custom-card">
                            <div class="card-header border-bottom">
                                <h5 class="card-title mb-0">Análisis de documentos repetidos</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap mb-0">
                                        <thead>
                                            <tr>
                                                <th>Número de documento</th>
                                                <th>Registros encontrados</th>
                                                <th>Observación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dashboard['duplicate_documents'] as $duplicateDocument)
                                                <tr>
                                                    <td>{{ $duplicateDocument->number_id }}</td>
                                                    <td>{{ $duplicateDocument->total }}</td>
                                                    <td>
                                                        <span class="badge bg-warning-transparent text-warning">
                                                            Revisar activo/eliminado antes de crear o restaurar
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>


                <div class="collapse" id="collapseExample">
                    <div class="row row-sm">
                        <div class="col-lg-12">
                            <div class="card custom-card users-filter-card">
                                <div class="card-body">
                                    <form class="form-horizontal" id="filter" action="{{ route('user.filtros') }}"
                                        method="post" novalidate>
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-lg-3">
                                                <label for="estado" class="form-label">Filtrar por estado</label>
                                                <select type="text" name="estado" id="estado" class="form-control"
                                                    tabindex="3" autofocus>
                                                    @foreach (['Todos', 'NUEVO', 'CONFIRMADO', 'RECHAZADO', 'ASOCIADO'] as $estado)
                                                        <option value="{{ $estado }}" @selected(($filters['estado'] ?? 'Todos') === $estado)>{{ $estado }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('estado') }}
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="role" class="form-label">Filtrar por rol</label>
                                                <select name="role" id="role" class="form-control" tabindex="3">
                                                    <option value="Todos" @selected(($filters['role'] ?? 'Todos') === 'Todos')>Todos</option>
                                                    @foreach (($roles ?? []) as $roleName)
                                                        <option value="{{ $roleName }}" @selected(($filters['role'] ?? 'Todos') === $roleName)>
                                                            {{ $roleName == 'ClienteHijo' ? 'Cliente Hijo' : $roleName }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="name" class="form-label">Nombre proveedor</label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                    tabindex="3" value="{{ $filters['name'] ?? old('name') }}"
                                                    placeholder="Buscar por nombre">
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="number_id" class="form-label">Numero de Identificacion</label>
                                                <input type="text" name="number_id" id="number_id" class="form-control"
                                                    tabindex="3" value="{{ $filters['number_id'] ?? old('number_id') }}" autofocus>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="limit" class="form-label">Limite de Usuarios</label>
                                                <select name="limit" id="limit-input" class="form-control" tabindex="3"
                                                    autofocus>
                                                    @foreach ([50, 100, 200] as $limit)
                                                        <option value="{{ $limit }}" @selected((string) ($filters['limit'] ?? 50) === (string) $limit)>{{ $limit }}</option>
                                                    @endforeach
                                                    <option value="" @selected(($filters['limit'] ?? 50) === '')>Todos</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-4">
                                            <div class="text-muted" id="usersResultCount">
                                                @if (method_exists($usuarios, 'total'))
                                                    {{ $usuarios->total() }} usuarios encontrados
                                                @else
                                                    {{ $usuarios->count() }} usuarios encontrados
                                                @endif
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('usuario.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                                                <button type="submit" id="btnFilter" class="btn btn-primary">
                                                    <i class="fa fa-search me-1"></i> Filtrar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ROW OPEN -->
                <div class="row row-sm" id="usersTableSection">
                    <div class="col-12">
                        <div class="user-result-toolbar">
                            <div class="d-flex align-items-center gap-2">
                                <label class="custom-control custom-checkbox mb-0">
                                    <input type="checkbox" class="custom-control-input" id="selectAllUsers">
                                    <span class="custom-control-label">Seleccionar visibles</span>
                                </label>
                                <span class="text-muted tx-12" id="selectedUsersCount">0 seleccionados</span>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm" id="btnDeleteSelectedUsers" disabled>
                                <i class="fa fa-trash me-1"></i> Eliminar seleccionados
                            </button>
                        </div>
                    </div>
                    @forelse ($usuarios as $usuario)
                        @php
                            $photoPath = $usuario->photo ?? '';
                            if (!empty($photoPath)) {
                                if (\Illuminate\Support\Str::startsWith($photoPath, ['http://', 'https://'])) {
                                    $photoUrl = $photoPath;
                                } elseif (\Illuminate\Support\Str::startsWith($photoPath, ['storage/', 'public/'])) {
                                    $photoUrl = asset($photoPath);
                                } else {
                                    $photoUrl = asset('storage/' . $photoPath);
                                }
                            } else {
                                $photoUrl = '';
                            }

                            $docPath = $usuario->photo_id ?? '';
                            if (!empty($docPath)) {
                                if (\Illuminate\Support\Str::startsWith($docPath, ['http://', 'https://'])) {
                                    $docUrl = $docPath;
                                } elseif (\Illuminate\Support\Str::startsWith($docPath, ['storage/', 'public/'])) {
                                    $docUrl = asset($docPath);
                                } else {
                                    $docUrl = asset('storage/' . $docPath);
                                }
                            } else {
                                $docUrl = '';
                            }

                            $roleName = $usuario->rol->rol_nombre['name'] ?? 'Sin rol';
                            $roleLabel = $roleName == 'ClienteHijo' ? 'Cliente Hijo' : $roleName;
                        @endphp
                        <div class="col-md-6 col-xl-4 mb-4">
                            <div class="card user-result-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <label class="custom-control custom-checkbox mb-0 mt-1">
                                            <input type="checkbox" class="custom-control-input user-select-checkbox" value="{{ $usuario->id }}">
                                            <span class="custom-control-label"></span>
                                        </label>
                                        @if ($usuario->photo == '')
                                            <div class="avatar avatar-md bg-{{ $usuario->otherColors(($usuario->id % 8) + 2) }} text-white rounded-circle">
                                                {{ strtoupper(substr($usuario->email, 0, 2)) }}
                                            </div>
                                        @else
                                            <div class="avatar avatar-md text-white rounded-circle" style="overflow: hidden;">
                                                <img src="{{ $photoUrl }}" alt="{{ $usuario->name }}" style="width: 100%; height: 100%; object-fit: cover;"/>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="d-flex justify-content-between align-items-start gap-2">
                                                <div class="min-w-0">
                                                    <h6 class="mb-1 text-truncate">{{ $usuario->name }}</h6>
                                                    <a class="tx-13 text-muted d-block text-truncate" href="mailto:{{ $usuario->email }}">{{ $usuario->email }}</a>
                                                </div>
                                                <span class="badge font-weight-semibold bg-{{ $usuario->badges($usuario->status) }}-transparent text-{{ $usuario->badges($usuario->status) }} tx-11">
                                                    {{ $usuario->status }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="user-result-meta mb-3">
                                        <div>
                                            <span class="user-result-meta-label">Teléfono</span>
                                            <span class="user-result-meta-value">{{ $usuario->phone ?: 'Sin teléfono' }}</span>
                                        </div>
                                        <div>
                                            <span class="user-result-meta-label">Documento</span>
                                            <span class="user-result-meta-value">{{ $usuario->number_id ?: 'Sin documento' }}</span>
                                        </div>
                                        <div>
                                            <span class="user-result-meta-label">Rol</span>
                                            <span class="user-result-meta-value">{{ $roleLabel }}</span>
                                        </div>
                                        <div>
                                            <span class="user-result-meta-label">PDF Doc</span>
                                            @if (!empty($usuario->photo_id))
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalPdf" class="user-result-meta-value aPdf" data-url="{{ $docUrl }}">
                                                    <i class="fa fa-file-pdf-o me-1"></i> Ver documento
                                                </a>
                                            @else
                                                <span class="user-result-meta-value text-muted">No cargado</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="user-result-actions">
                                        <a href="#"
                                            class="btn btn-icon btn-info-light consultAfiliado"
                                            data-url="{{ route('consultar.afiliado', [$usuario->id]) }}"
                                            data-bs-toggle="tooltip"
                                            data-bs-original-title="Validar Informacion">
                                            <i class="fa fa-user"></i>
                                        </a>

                                        @switch($usuario->status)
                                            @case('ASOCIADO')
                                                <a href="#" data-bs-whatever="@mdo" id="{{ $usuario->id }}"
                                                    class="proveedor btn btn-icon btn-success-light"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-original-title="Consultar Padre">
                                                    <i class="fa fa-users"></i>
                                                </a>
                                            @break

                                            @case('NUEVO')
                                                <a href="{{ route('usuario.estado', ['usuario' => $usuario, 'estado' => 'aprobado']) }}"
                                                    class="btn btn-icon btn-primary-light"
                                                    data-bs-toggle="tooltip" data-bs-original-title="Aceptar">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                                <a href="{{ route('usuario.estado', ['usuario' => $usuario, 'estado' => 'rechazado']) }}"
                                                    class="btn btn-icon btn-warning-light"
                                                    data-bs-toggle="tooltip" data-bs-original-title="Rechazar">
                                                    <i class="fa fa-user-times"></i>
                                                </a>
                                            @break

                                            @default
                                            @break
                                        @endswitch

                                        <a href="{{ route('edit', [$usuario->id]) }}" class="btn btn-icon btn-warning-light" data-bs-toggle="tooltip" data-bs-original-title="Editar">
                                            <i class="fa fa-pencil-square"></i>
                                        </a>
                                        <a href="#" id="{{ $usuario->id }}" class="btn btn-icon btn-info-light btnEnviarContrasena" data-bs-toggle="tooltip" data-bs-original-title="Generar Contraseña">
                                            <i class="fa fa-envelope-square" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" id="{{ $usuario->id }}" class="btn btn-icon btn-danger-light deletedUser" data-bs-toggle="tooltip" data-bs-original-title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card custom-card">
                                <div class="card-body text-center py-5">
                                    <i class="fa fa-search text-muted mb-3" style="font-size: 32px;"></i>
                                    <h5 class="mb-1">No se encontraron usuarios</h5>
                                    <p class="text-muted mb-0">Ajusta los filtros e intenta nuevamente.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse

                    @if (method_exists($usuarios, 'links'))
                        <div class="col-12">
                            <div class="mt-2">
                                {{ $usuarios->links() }}
                            </div>
                        </div>
                    @endif
                </div>
                <!-- ROW CLOSED -->
                <div class="modal fade" id="consultAfiliadoModal" tabindex="-1" aria-labelledby="consultAfiliadoModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="consultAfiliadoModalLabel">Validar información</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="consultAfiliadoContent">
                                <div class="text-center py-5">
                                    <i class="fa fa-spinner fa-spin text-primary mb-3" style="font-size: 30px;"></i>
                                    <p class="text-muted mb-0">Consultando información...</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal imagen -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Foto Perfil</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <embed id="foto" src="" width="100%" height="100%">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modal pdf -->
                <div class="modal fade" id="exampleModalPdf" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Copia de documento
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <embed id="pdfdoc" src="" type="application/pdf" width="100%"
                                    height="500" alt="pdf"
                                    pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal-content proveedoer -->
                <div class="modal fade" id="exampleModalProveedor" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Proveedor Acargo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="dataProveedor">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="deletedUsersModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Usuarios Eliminados</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Aquí se agregará la tabla de usuarios eliminados -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </body>
@endsection
@section('scripts')
<script src={{ asset('views/js/users/metods.js') }}?v={{ time() }}></script>

    @if (Session::has('message'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No se encontro registro en OTM con el numero de identificacion del proveedor seleccionado!',
            })
        </script>
    @endif
    <script>
        window.onload = function() {
            swal.close();
        }

        filterUsersTable();
        consultarAfiliadoModal();

        let urlPassword = "{{ route('enviar-contrasena') }}"
        enviarContrasena(urlPassword);

        let urlGetUserElimin = "{{ route('get.users.deleted') }}"
        getUserEliminated(urlGetUserElimin);

        let urlReactivate = "{{ route('users.reactivate') }}"
        reactivate(urlReactivate);

        let urlDeletedUser = "{{ route('usuario.eliminar') }}"
        deletedUser(urlDeletedUser);

        let urlProveedor = "{{ route('proveedor.encargado') }}"
        let urlProveedorLocal = "{{ route('consultar.proveedorLocal') }}"
        proveedor(urlProveedor, urlProveedorLocal);

    </script>
@endsection

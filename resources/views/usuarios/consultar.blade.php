@extends('layouts.app')

@section('content')

    <body class="ltr app sidebar-mini light-mode">
        <div class="page">
            <div class="page-main">
                <div class="app-content main-content mt-0">
                    <div class="side-app">
                        <div class="main-container container-fluid">
                            <div class="page-header">
                                <div>
                                    <h1 class="page-title">Usuarios</h1>
                                </div>
                                <div class="ms-auto pageheader-btn">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('usuario.index') }}">Usuario</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Consultar Afiliado</li>
                                    </ol>
                                </div>
                            </div>
                            <div class="row row-sm">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            {{-- <a class="btn btn-icon btn-primary-light me-2" id="retroceder" data-bs-toggle="tooltip"
                                            data-bs-original-title="Retroceder">
                                            <i class="fa fa-reply" aria-hidden="true"></i>
                                        </a> --}}
                                            <div class="table-responsive">
                                                <table id="file-datatable"
                                                    class="table table-bordered text-nowrap key-buttons border-bottom  w-100">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-bottom-0">/</th>
                                                            <th class="border-bottom-0">PORTAL</th>
                                                            <th class="border-bottom-0">OTM</th>
                                                            <th class="border-bottom-0">ERP</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>CEDULA</td>
                                                            <td>{{ $arrayResultLocal['number_id'] }}</td>
                                                            <td>{{ $arrayResultOtm['locationXid'] }}</td>
                                                            <td>{{ $arrayResultErp['TaxpayerId'] }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>NOMBRE</td>
                                                            <td>{{ $arrayResultLocal['name'] }}</td>
                                                            <td>{{ $arrayResultOtm['fullName'] }}</td>
                                                            <td>{{ $arrayResultErp['fullName'] }}</td>

                                                        </tr>
                                                        <tr>
                                                            <td>EMAIL</td>
                                                            <td><a href="mailto:{{ $arrayResultLocal['email'] }}">{{ $arrayResultLocal['email'] }}
                                                                </a></td>
                                                            <td><a href="mailto:{{ $arrayResultOtm['emailAddress'] }}">{{ $arrayResultOtm['emailAddress'] }}
                                                                </a></td>
                                                            <td><a href="mailto:{{ $arrayResultErp['emailAddress'] }}">{{ $arrayResultErp['emailAddress'] }}
                                                                </a></td>

                                                        </tr>
                                                        <tr>
                                                            <td>TELEFONO</td>
                                                            <td>{{ $arrayResultLocal['phone'] }}</td>
                                                            <td>{{ $arrayResultOtm['phone'] }}</td>
                                                            <td>{{ $arrayResultErp['phone'] }}</td>

                                                        </tr>
                                                        <tr>
                                                            <td>ESTADO</td>
                                                            <td>
                                                                <span
                                                                    class="badge rounded-pill bg-{{ $arrayResultLocal['estado'] != 'RECHAZADO' ? 'success' : 'danger' }} my-1">{{ $arrayResultLocal['estado'] == 'CONFIRMADO' ? 'ACTIVO' : 'DESACTIVADO' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge rounded-pill bg-{{ $arrayResultOtm['isActive'] == 1 ? 'success' : 'danger' }} my-1">{{ $arrayResultOtm['isActive'] == 1 ? 'ACTIVO' : 'DESACTIVADO' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge rounded-pill bg-{{ $arrayResultErp['isActive'] == 'ACTIVE' ? 'success' : 'danger' }} my-1">{{ $arrayResultErp['isActive'] == 'ACTIVE' ? 'ACTIVO' : 'DESACTIVADO' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
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
    {{-- <script>
        $(document).on('click', '#retroceder', function (e) {

        });
    </script> --}}
@endsection

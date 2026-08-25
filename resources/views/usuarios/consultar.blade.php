@extends('layouts.app')

@section('styles')
    <style>
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
                                            @include('usuarios.partials.consulta-afiliado')
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

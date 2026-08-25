@extends('layouts.app')

@section('content')

    <body class="ltr app sidebar-mini">
        <div class="page">
            <div class="page-main">
                <div class="side-app">
                    <div class="main-container container-fluid">
                            <img src="{{ asset('assets/images/logos-tractocar/TCL_POS_CMYK-01.png') }}"
                                class="background-image background-image opacity-animation" alt="">
                            <div class="app-content main-content mt-0">
                                @php
                                    $counter = 0;
                                    $items = [
                                        0 => ['color' => 'indigo', 'icon' => 'fa fa-user'],
                                        1 => ['color' => 'primary', 'icon' => 'fa fa-ra'],
                                        2 => ['color' => 'info', 'icon' => 'fa fa-minus'],
                                        3 => ['color' => 'cyan', 'icon' => 'fa fa-info'],
                                    ];
                                @endphp

                                <style>
                                    .stat-card-admin {
                                        border: none;
                                        border-radius: .75rem;
                                        box-shadow: 0 1px 4px rgba(0,0,0,.06);
                                        transition: transform .15s ease, box-shadow .15s ease;
                                    }
                                    .stat-card-admin:hover {
                                        transform: translateY(-2px);
                                        box-shadow: 0 6px 16px rgba(0,0,0,.08);
                                    }
                                    .stat-card-admin .stat-card-title {
                                        font-size: .8125rem;
                                        font-weight: 600;
                                        color: #8a94a6;
                                        text-transform: uppercase;
                                        letter-spacing: .02em;
                                        margin-bottom: .5rem;
                                    }
                                    .stat-card-admin .stat-card-value {
                                        font-size: 1.75rem;
                                        font-weight: 700;
                                        color: #212529;
                                        margin-bottom: .25rem;
                                    }
                                    .stat-card-admin .stat-card-sub {
                                        font-size: .8125rem;
                                        color: #8a94a6;
                                        margin-bottom: 0;
                                    }
                                    .stat-card-admin .stat-card-icon {
                                        width: 46px;
                                        height: 46px;
                                        border-radius: .6rem;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        font-size: 1.15rem;
                                        color: #fff;
                                        background: #e8791a;
                                    }
                                    .stat-card-admin .stat-card-icon.alt-1 { background: #2c3e50; }
                                    .stat-card-admin .stat-card-icon.alt-2 { background: #17a2b8; }
                                    .stat-card-admin .stat-card-icon.alt-3 { background: #6c63ff; }
                                    .admin-chart-card {
                                        border: none;
                                        border-radius: .75rem;
                                        box-shadow: 0 1px 4px rgba(0,0,0,.06);
                                    }
                                    .admin-chart-card .heading {
                                        font-size: 1rem;
                                        font-weight: 600;
                                        color: #212529;
                                    }
                                </style>

                                <div class="row g-3">
                                    @foreach ($request_status as $key => $statu)
                                        <div style="display: none">
                                            {{ $counter = $counter + $statu->count }}
                                        </div>
                                        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
                                            <div class="card stat-card-admin overflow-hidden h-100">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-start justify-content-between">
                                                        <div>
                                                            <p class="stat-card-title">{{ $statu->status }}</p>
                                                            <h3 class="stat-card-value">{{ $statu->count }}</h3>
                                                            <p class="stat-card-sub">usuarios registrados</p>
                                                        </div>
                                                        <div class="stat-card-icon {{ $key == 0 ? '' : 'alt-' . $key }}">
                                                            <i class="{{ $items[$key]['icon'] }}"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
                                        <div class="card stat-card-admin overflow-hidden h-100">
                                            <div class="card-body" id="count">
                                                <div class="d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="stat-card-title">Total inicio sesión</p>
                                                        <h3 class="stat-card-value">0</h3>
                                                        <p class="stat-card-sub">accesos registrados</p>
                                                    </div>
                                                    <div class="stat-card-icon alt-2">
                                                        <i class="fe fe-trending-up"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
                                        <div class="card stat-card-admin overflow-hidden h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="stat-card-title">Total afiliados</p>
                                                        <h3 class="stat-card-value">{{ $counter }}</h3>
                                                        <p class="stat-card-sub">afiliados totales</p>
                                                    </div>
                                                    <div class="stat-card-icon alt-3">
                                                        <i class="fa fa-users"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-12">
                                        <div class="card admin-chart-card overflow-hidden">
                                            <div class="card-body">
                                                <h4 class="heading text-center mb-4">Número de inicio de sesión por mes</h4>
                                                <div id="containerActionHome" style="min-height: 320px;"></div>
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
    <script src="https://momentjs.com/downloads/moment.min.js"></script>
    <script src={{ asset('anychart-package-8.11.0/js/anychart-bundle.min.js') }}></script>
    <script src={{ asset('anychart-package-8.11.0/js/anychart-base.min.js') }}></script>
    <script src={{ asset('anychart-package-8.11.0/js/anychart-exports.min.js') }}></script>
    <script src={{ asset('anychart-package-8.11.0/js/anychart-ui.min.js') }}></script>
    <script src="{{ asset('views/js/statistics/statisticsHome.js') }}?v={{ time() }}"></script>

    @if (Session::has('message'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ Session::get('message') }}',
            })
        </script>
    @endif

        <script>
            let urlCountLogin = "{{ route('setting.statistics.countLogin') }}"
            ajaxCountLoginHome(urlCountLogin);
        </script>
@endsection

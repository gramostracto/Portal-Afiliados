@extends('layouts.app')

@section('content')

    <body class="ltr app sidebar-mini">
        <div class="page">
            <div class="page-main">
                <div class="side-app">
                    <div class="main-container container-fluid">
                        @can('/usuario.index')
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
                                <div class="row g-3">
                                    @foreach ($request_status as $key => $statu)
                                        <div style="display: none">
                                            {{ $counter = $counter + $statu->count }}
                                        </div>
                                        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
                                            <div class="card overflow-hidden h-100">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h3 class="mb-2 fw-semibold">{{ $statu->count }}</h3>
                                                            <p class="text-muted fs-13 mb-0">{{ $statu->status }}</p>
                                                        </div>
                                                        <div class="col col-auto top-icn dash">
                                                            <div
                                                                class="counter-icon bg-{{ $items[$key]['color'] }} dash ms-auto box-shadow-primary">
                                                                <i class="{{ $items[$key]['icon'] }}"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
                                        <div class="card overflow-hidden h-100">
                                            <div class="card-body" id="count">
                                                <div class="row">
                                                    <div class="col">
                                                        <h3 class="mb-2 fw-semibold">0</h3>
                                                        <p class="text-muted fs-13 mb-0">TOTAL INICIO SESSION</p>
                                                    </div>
                                                    <div class="col col-auto top-icn dash">
                                                        <div
                                                            class="counter-icon bg-danger-gradient dash ms-auto box-shadow-danger">
                                                            <i class="fe fe-trending-up text-white"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
                                        <div class="card overflow-hidden h-100">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col">
                                                        <h3 class="mb-2 fw-semibold">{{ $counter }}</h3>
                                                        <p class="text-muted fs-13 mb-0">TOTAL AFILIADOS</p>
                                                    </div>
                                                    <div class="col col-auto top-icn dash">
                                                        <div class="counter-icon bg-primary dash ms-auto box-shadow-primary">
                                                            <i class="fa fa-users"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-12">
                                        <div class="card overflow-hidden">
                                            <div class="card-body">
                                                <h4 class="heading text-center mb-4">Numero de inicio de sesion por mes</h4>
                                                <div id="containerActionHome" style="min-height: 320px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan

                        @can('/facturas')
                            <div id="global-loader2">
                                <img src={{ asset('assets/images/loader.svg') }} class="loader-img" alt="Loader">
                            </div>

                            <style>
                                .invoice-shortcut {
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 10px;
                                    width: 100%;
                                    min-height: 160px;
                                    border-radius: 12px;
                                    background: #fdf3e9;
                                    border: 1px solid #f0dcc4;
                                    color: #b85e0f;
                                    font-weight: 600;
                                    font-size: 0.85rem;
                                    text-align: center;
                                    padding: 20px 12px;
                                    transition: transform .15s ease, box-shadow .15s ease;
                                }

                                .invoice-shortcut:hover,
                                .invoice-shortcut:focus {
                                    color: #b85e0f;
                                    transform: translateY(-3px);
                                    box-shadow: 0 10px 24px rgba(184, 94, 15, .18);
                                }

                                .invoice-shortcut img {
                                    width: 64px;
                                    height: 64px;
                                    object-fit: contain;
                                }

                                .drakma-card {
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    height: 100%;
                                    min-height: 100px;
                                }

                                .drakma-card img {
                                    max-width: 140px;
                                }

                                /* --- Tarjetas de resumen (Monto/Facturas por pagar/Drakma) --- */
                                .stat-card {
                                    border: 1px solid #eef1f4 !important;
                                    box-shadow: 0 1px 3px rgba(15, 38, 64, .05) !important;
                                    border-radius: 12px !important;
                                    transition: box-shadow .15s ease, transform .15s ease;
                                }

                                .stat-card:hover {
                                    box-shadow: 0 8px 20px rgba(15, 38, 64, .08) !important;
                                }

                                .stat-icon {
                                    width: 44px;
                                    height: 44px;
                                    border-radius: 10px;
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-bottom: 18px;
                                }

                                .stat-icon svg {
                                    width: 20px;
                                    height: 20px;
                                }

                                .stat-icon-primary {
                                    background: #fdf3e9;
                                }

                                .stat-icon-primary svg {
                                    fill: #e8791a;
                                }

                                .stat-icon-dark {
                                    background: #eef1f4;
                                }

                                .stat-icon-dark svg {
                                    fill: #16202c;
                                }

                                .stat-value {
                                    display: block;
                                    font-size: 1.6rem;
                                    font-weight: 800;
                                    color: #16202c;
                                    margin: 0 0 4px;
                                    line-height: 1.2;
                                }

                                .stat-label {
                                    margin: 0;
                                    font-size: .82rem;
                                    color: #66758a;
                                }

                                /* --- Modernizacion tablas de facturas --- */
                                .invoice-table-card {
                                    border: none;
                                    box-shadow: 0 4px 16px rgba(169, 184, 200, .12);
                                }

                                .invoice-table-card>.card-header {
                                    background: transparent;
                                    border-bottom: 1px solid #eef1f4;
                                    padding: 20px 24px 16px;
                                }

                                .invoice-table-card>.card-header h3 {
                                    font-size: 1.05rem;
                                    font-weight: 700;
                                    color: #16202c;
                                    margin: 0;
                                    text-align: left;
                                    text-decoration: none;
                                }

                                .invoice-table-card .dataTables_wrapper .dt-buttons .btn,
                                .invoice-table-card .dataTables_wrapper .dt-buttons .dt-button {
                                    background: #e8791a !important;
                                    border-color: #e8791a !important;
                                    color: #fff !important;
                                    border-radius: 8px;
                                    font-weight: 600;
                                    font-size: .85rem;
                                    padding: 8px 16px;
                                }

                                .invoice-table-card .dataTables_wrapper .dt-buttons .btn:hover,
                                .invoice-table-card .dataTables_wrapper .dt-buttons .dt-button:hover {
                                    background: #b85e0f !important;
                                    border-color: #b85e0f !important;
                                }

                                .invoice-table-card .dataTables_filter input {
                                    border-radius: 8px;
                                    border: 1px solid #dbe5ef;
                                    padding: 6px 12px;
                                }

                                .invoice-table-card table.dataTable {
                                    border-collapse: separate !important;
                                    border-spacing: 0;
                                }

                                .invoice-table-card table.dataTable thead th {
                                    border: none !important;
                                    border-bottom: 2px solid #eef1f4 !important;
                                    text-transform: uppercase;
                                    font-size: .72rem;
                                    letter-spacing: .04em;
                                    color: #66758a;
                                    font-weight: 700;
                                    padding-top: 12px;
                                    padding-bottom: 12px;
                                }

                                .invoice-table-card table.dataTable tbody td {
                                    border: none !important;
                                    border-bottom: 1px solid #f2f4f7 !important;
                                    vertical-align: middle;
                                    padding-top: 14px;
                                    padding-bottom: 14px;
                                }

                                .invoice-table-card table.dataTable tbody tr:hover {
                                    background-color: #fdf3e9;
                                }

                                .invoice-table-card .dataTables_paginate .paginate_button.current {
                                    background: #e8791a !important;
                                    border-color: #e8791a !important;
                                    color: #fff !important;
                                }

                                .invoice-table-card .dataTables_paginate .paginate_button {
                                    border-radius: 6px;
                                }

                                /* --- Tabla simple con scroll infinito --- */
                                .scroll-table-toolbar {
                                    display: flex;
                                    flex-wrap: wrap;
                                    gap: 10px;
                                    align-items: center;
                                    justify-content: space-between;
                                    margin-bottom: 14px;
                                }

                                .scroll-table-search {
                                    border-radius: 8px;
                                    border: 1px solid #dbe5ef;
                                    padding: 8px 14px;
                                    font-size: .85rem;
                                    min-width: 220px;
                                }

                                .scroll-table-export {
                                    background: #e8791a;
                                    border: 1px solid #e8791a;
                                    color: #fff;
                                    border-radius: 8px;
                                    font-weight: 600;
                                    font-size: .85rem;
                                    padding: 8px 16px;
                                }

                                .scroll-table-export:hover {
                                    background: #b85e0f;
                                    border-color: #b85e0f;
                                    color: #fff;
                                }

                                .scroll-table-viewport {
                                    max-height: 460px;
                                    overflow-y: auto;
                                    border: 1px solid #eef1f4;
                                    border-radius: 10px;
                                }

                                .scroll-table-viewport table {
                                    margin-bottom: 0 !important;
                                }

                                .scroll-table-viewport thead th {
                                    position: sticky;
                                    top: 0;
                                    background: #fff;
                                    z-index: 1;
                                    border: none !important;
                                    border-bottom: 2px solid #eef1f4 !important;
                                    text-transform: uppercase;
                                    font-size: .72rem;
                                    letter-spacing: .04em;
                                    color: #66758a;
                                    font-weight: 700;
                                    padding: 12px 10px;
                                }

                                .scroll-table-viewport tbody td {
                                    border: none !important;
                                    border-bottom: 1px solid #f2f4f7 !important;
                                    vertical-align: middle;
                                    padding: 12px 10px;
                                    font-size: .85rem;
                                }

                                .scroll-table-viewport tbody tr:hover {
                                    background-color: #fdf3e9;
                                }

                                .btn-icon-action {
                                    width: 34px;
                                    height: 34px;
                                    border-radius: 8px;
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    background: #fdf3e9;
                                    color: #b85e0f !important;
                                    border: none;
                                    padding: 0;
                                }

                                .btn-icon-action:hover {
                                    background: #e8791a;
                                    color: #fff !important;
                                }

                                .status-badge {
                                    display: inline-block;
                                    padding: 5px 12px;
                                    border-radius: 999px;
                                    font-size: .75rem;
                                    font-weight: 700;
                                    white-space: nowrap;
                                }

                                .status-badge.status-pending {
                                    background: #fdecea;
                                    color: #c0392b;
                                }

                                .status-badge.status-paid {
                                    background: #eafaf1;
                                    color: #1e8449;
                                }

                                .status-badge.status-scheduled {
                                    background: #fdf3e9;
                                    color: #b85e0f;
                                }

                                .invoice-tabs {
                                    display: flex;
                                    flex-wrap: wrap;
                                    gap: 8px;
                                    padding-bottom: 16px;
                                }

                                .invoice-tab {
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 8px;
                                    border: 1px solid #eef1f4;
                                    background: #fff;
                                    color: #66758a;
                                    font-weight: 600;
                                    font-size: .82rem;
                                    border-radius: 999px;
                                    padding: 8px 16px;
                                    cursor: pointer;
                                    transition: all .15s ease;
                                }

                                .invoice-tab:hover {
                                    border-color: #e8791a;
                                    color: #b85e0f;
                                }

                                .invoice-tab.active {
                                    background: #e8791a;
                                    border-color: #e8791a;
                                    color: #fff;
                                }

                                .invoice-tab-count {
                                    background: rgba(0, 0, 0, .08);
                                    color: inherit;
                                    border-radius: 999px;
                                    font-size: .72rem;
                                    padding: 1px 8px;
                                    font-weight: 700;
                                }

                                .invoice-tab.active .invoice-tab-count {
                                    background: rgba(255, 255, 255, .25);
                                }

                                /* --- Pestanas principales (Facturas por pagar / en transporte / con novedad / todas) --- */
                                .invoice-main-tabs {
                                    display: flex;
                                    flex-wrap: wrap;
                                    gap: 10px;
                                    background: #fff;
                                    border: 1px solid #eef1f4;
                                    border-radius: 14px;
                                    padding: 10px;
                                    margin: 4px 0 20px;
                                    box-shadow: 0 1px 3px rgba(15, 38, 64, .05);
                                }

                                .invoice-main-tab {
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 8px;
                                    padding: 9px 16px;
                                    border-radius: 999px;
                                    border: 1px solid #e3e7ec;
                                    background: #fbfcfd;
                                    font-weight: 600;
                                    font-size: .85rem;
                                    color: #66758a;
                                    cursor: pointer;
                                    transition: all .15s ease;
                                    text-decoration: none;
                                }

                                .invoice-main-tab:hover,
                                .invoice-main-tab:focus {
                                    color: #b85e0f;
                                    border-color: #f0c896;
                                    background: #fdf3e9;
                                    text-decoration: none;
                                }

                                .invoice-main-tab.active {
                                    background: #e8791a;
                                    border-color: #e8791a;
                                    color: #fff;
                                    box-shadow: 0 6px 16px rgba(232, 121, 26, .28);
                                }

                                .invoice-main-tab-count {
                                    background: rgba(102, 117, 138, .12);
                                    color: inherit;
                                    border-radius: 999px;
                                    font-size: .72rem;
                                    padding: 1px 9px;
                                    font-weight: 700;
                                }

                                .invoice-main-tab.active .invoice-main-tab-count {
                                    background: rgba(255, 255, 255, .28);
                                    color: #fff;
                                }

                                /* --- Modal de factura tipo documento --- */
                                .invoice-head {
                                    padding: 0 !important;
                                }

                                .invoice-doc-header {
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: flex-start;
                                    gap: 24px;
                                    padding: 28px 32px 22px;
                                    border-bottom: 1px solid #eef1f4;
                                    position: relative;
                                }

                                .invoice-doc-header .invoice-brand-mark {
                                    display: flex;
                                    align-items: center;
                                    gap: 12px;
                                }

                                .invoice-doc-header .invoice-brand-mark img {
                                    height: 34px;
                                }

                                .invoice-doc-header .invoice-brand-meta {
                                    font-size: 15px;
                                    color: #66758a;
                                    line-height: 1.6;
                                    margin-top: 8px;
                                }

                                .invoice-doc-header .invoice-brand-name {
                                    font-size: 17px;
                                    font-weight: 700;
                                    color: #16202c;
                                }

                                .invoice-id-block {
                                    text-align: right;
                                    display: flex;
                                    flex-direction: column;
                                    align-items: flex-end;
                                    gap: 8px;
                                }

                                .invoice-id-block .label {
                                    font-size: 11px;
                                    text-transform: uppercase;
                                    letter-spacing: .08em;
                                    color: #66758a;
                                }

                                .invoice-id-block .num {
                                    font-size: 22px;
                                    font-weight: 800;
                                    color: #16202c;
                                }

                                .invoice-stamp {
                                    position: absolute;
                                    top: 90px;
                                    right: 32px;
                                    font-size: 12px;
                                    font-weight: 700;
                                    letter-spacing: .1em;
                                    text-transform: uppercase;
                                    border: 2px solid currentColor;
                                    border-radius: 8px;
                                    padding: 5px 12px;
                                    transform: rotate(-8deg);
                                    opacity: .85;
                                }

                                .invoice-stamp.status-paid {
                                    color: #1e8449;
                                }

                                .invoice-stamp.status-pending {
                                    color: #c0392b;
                                }

                                .invoice-stamp.status-scheduled {
                                    color: #b85e0f;
                                }

                                .invoice-summary-grid {
                                    display: grid;
                                    grid-template-columns: repeat(3, 1fr);
                                    gap: 1px;
                                    background: #eef1f4;
                                    margin: 0 32px;
                                    border: 1px solid #eef1f4;
                                    border-radius: 10px;
                                    overflow: hidden;
                                }

                                .invoice-summary-grid>div {
                                    background: #fbfcfd;
                                    padding: 14px 18px;
                                }

                                .invoice-summary-grid .k {
                                    font-size: 10.5px;
                                    text-transform: uppercase;
                                    letter-spacing: .06em;
                                    color: #66758a;
                                    margin-bottom: 5px;
                                }

                                .invoice-summary-grid .v {
                                    font-size: 13.5px;
                                    font-weight: 600;
                                    color: #16202c;
                                }

                                .invoice-section-title {
                                    font-size: 11px;
                                    text-transform: uppercase;
                                    letter-spacing: .08em;
                                    color: #66758a;
                                    font-weight: 700;
                                    margin: 0 0 12px;
                                }

                                table.invoice-lines {
                                    width: 100%;
                                    border-collapse: collapse;
                                    margin-bottom: 0;
                                }

                                table.invoice-lines th {
                                    text-align: left;
                                    font-size: 11px;
                                    text-transform: uppercase;
                                    letter-spacing: .05em;
                                    color: #66758a;
                                    font-weight: 700;
                                    padding: 0 0 10px;
                                    border-bottom: 1px solid #eef1f4;
                                }

                                table.invoice-lines th:last-child,
                                table.invoice-lines td:last-child {
                                    text-align: right;
                                }

                                table.invoice-lines td {
                                    padding: 12px 0;
                                    border-bottom: 1px solid #eef1f4;
                                    font-size: 14px;
                                }

                                .invoice-totals {
                                    margin-left: auto;
                                    width: 280px;
                                    padding: 16px 0 4px;
                                }

                                .invoice-totals .row {
                                    display: flex;
                                    justify-content: space-between;
                                    font-size: 13.5px;
                                    padding: 6px 0;
                                    color: #66758a;
                                }

                                .invoice-totals .row.total {
                                    color: #16202c;
                                    font-size: 17px;
                                    font-weight: 800;
                                    border-top: 1px solid #eef1f4;
                                    margin-top: 6px;
                                    padding-top: 12px;
                                }

                                .invoice-holds-title {
                                    display: flex;
                                    align-items: center;
                                    gap: 8px;
                                    font-size: 12px;
                                    text-transform: uppercase;
                                    letter-spacing: .06em;
                                    font-weight: 800;
                                    color: #c0392b;
                                    margin: 0 0 12px;
                                }

                                .invoice-hold-alert {
                                    background: #fdecea;
                                    color: #791f1f;
                                    border-left: 4px solid #c0392b;
                                    border-radius: 8px;
                                    padding: 12px 16px;
                                    font-size: 13.5px;
                                    line-height: 1.5;
                                    margin-bottom: 10px;
                                }

                                .invoice-hold-alert b {
                                    color: #a32d2d;
                                }

                                .invoice-hold-alert .status-badge {
                                    margin-right: 8px;
                                    vertical-align: middle;
                                }

                                .invoice-doc-footer {
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    gap: 16px;
                                    padding: 18px 32px 4px;
                                    margin-top: 12px;
                                    border-top: 1px solid #eef1f4;
                                }

                                .invoice-doc-footer .thanks {
                                    font-size: 12px;
                                    color: #66758a;
                                    line-height: 1.6;
                                }

                                @media (max-width: 575.98px) {
                                    .invoice-summary-grid {
                                        grid-template-columns: repeat(2, 1fr);
                                    }
                                }

                                /* --- Imprimir solo la factura, no el dashboard de fondo --- */
                                @media print {
                                    body * {
                                        visibility: hidden;
                                    }

                                    #exampleModalToggle,
                                    #exampleModalToggle * {
                                        visibility: visible;
                                    }

                                    #exampleModalToggle {
                                        position: absolute;
                                        inset: 0;
                                        width: 100%;
                                        margin: 0;
                                        padding: 0;
                                        background: #fff;
                                        display: block !important;
                                    }

                                    #exampleModalToggle .modal-dialog {
                                        max-width: 100%;
                                        margin: 0;
                                    }

                                    #exampleModalToggle .modal-footer,
                                    #exampleModalToggle .invoice-doc-footer .btn {
                                        display: none !important;
                                    }

                                    .modal-backdrop {
                                        display: none !important;
                                    }
                                }
                            </style>

                            {{-- Card de valor/cantidad de facturas --}}
                            <div class="row g-3">

                                <div class="col-lg-4 col-sm-12 col-md-6">
                                    <div class="card stat-card h-100">
                                        <div class="card-body">
                                            <div class="stat-icon stat-icon-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M19.5,7H16V5.9169922c0-2.2091064-1.7908325-4-4-4s-4,1.7908936-4,4V7H4.5C4.4998169,7,4.4996338,7,4.4993896,7C4.2234497,7.0001831,3.9998169,7.223999,4,7.5V19c0.0018311,1.6561279,1.3438721,2.9981689,3,3h10c1.6561279-0.0018311,2.9981689-1.3438721,3-3V7.5c0-0.0001831,0-0.0003662,0-0.0006104C19.9998169,7.2234497,19.776001,6.9998169,19.5,7z M9,5.9169922c0-1.6568604,1.3431396-3,3-3s3,1.3431396,3,3V7H9V5.9169922z M19,19c-0.0014038,1.1040039-0.8959961,1.9985962-2,2H7c-1.1040039-0.0014038-1.9985962-0.8959961-2-2V8h3v2.5C8,10.776123,8.223877,11,8.5,11S9,10.776123,9,10.5V8h6v2.5c0,0.0001831,0,0.0003662,0,0.0005493C15.0001831,10.7765503,15.223999,11.0001831,15.5,11c0.0001831,0,0.0003662,0,0.0006104,0C15.7765503,10.9998169,16.0001831,10.776001,16,10.5V8h3V19z" />
                                                </svg>
                                            </div>
                                            <h3 id="mtPorPagar" class="stat-value">
                                                <div class="spinner-grow text-success" role="status" id="piner">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </h3>
                                            <p class="stat-label">Monto de Facturas por Pagar</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-12 col-md-6">
                                    <div class="card stat-card h-100">
                                        <div class="card-body">
                                            <div class="stat-icon stat-icon-dark">
                                                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M9,10h2.5c0.276123,0,0.5-0.223877,0.5-0.5S11.776123,9,11.5,9H10V8c0-0.276123-0.223877-0.5-0.5-0.5S9,7.723877,9,8v1c-1.1045532,0-2,0.8954468-2,2s0.8954468,2,2,2h1c0.5523071,0,1,0.4476929,1,1s-0.4476929,1-1,1H7.5C7.223877,15,7,15.223877,7,15.5S7.223877,16,7.5,16H9v1.0005493C9.0001831,17.2765503,9.223999,17.5001831,9.5,17.5h0.0006104C9.7765503,17.4998169,10.0001831,17.276001,10,17v-1c1.1045532,0,2-0.8954468,2-2s-0.8954468-2-2-2H9c-0.5523071,0-1-0.4476929-1-1S8.4476929,10,9,10z M21.5,12H17V2.5c0.000061-0.0875244-0.0228882-0.1735229-0.0665283-0.2493896c-0.1375732-0.2393188-0.4431152-0.3217773-0.6824951-0.1842041l-3.2460327,1.8603516L9.7481079,2.0654297c-0.1536865-0.0878906-0.3424072-0.0878906-0.4960938,0l-3.256897,1.8613281L2.7490234,2.0664062C2.6731567,2.0227661,2.5871582,1.9998779,2.4996338,1.9998779C2.2235718,2.000061,1.9998779,2.223938,2,2.5v17c0.0012817,1.380188,1.119812,2.4987183,2.5,2.5H19c1.6561279-0.0018311,2.9981689-1.3438721,3-3v-6.5006104C21.9998169,12.2234497,21.776001,11.9998169,21.5,12z M4.5,21c-0.828064-0.0009155-1.4990845-0.671936-1.5-1.5V3.3623047l2.7412109,1.5712891c0.1575928,0.0872192,0.348877,0.0875854,0.5068359,0.0009766L9.5,3.0761719l3.2519531,1.8583984c0.157959,0.0866089,0.3492432,0.0862427,0.5068359-0.0009766L16,3.3623047V19c0.0008545,0.7719116,0.3010864,1.4684448,0.7803345,2H4.5z M21,19c0,1.1045532-0.8954468,2-2,2s-2-0.8954468-2-2v-6h4V19z" />
                                                </svg>
                                            </div>
                                            <h3 id="totalFt" class="stat-value">
                                                <div class="spinner-grow text-success" role="status" id="piner1">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </h3>
                                            <p class="stat-label">Facturas por Pagar</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <a id="drakma" class="card stat-card drakma-card h-100" data-bs-toggle="tooltip"
                                        data-bs-original-title="Solicita tu Credito">
                                        <img src="{{ asset('assets/images/logos-drakma/LOGO.png') }}" alt="Drakma">
                                    </a>
                                </div>

                            </div>
                            {{-- Fin --}}

                            {{-- Pestanas principales de facturas --}}
                            <div class="invoice-main-tabs" id="invoiceMainTabs">
                                <a id="por-pagar" class="invoice-main-tab" data-bs-toggle="tooltip"
                                    data-bs-original-title="Facturas por pagar">
                                    Facturas por pagar
                                    <span class="invoice-main-tab-count" id="mainTabCountPorPagar">0</span>
                                </a>
                                <a id="en-transporte" class="invoice-main-tab" data-bs-toggle="tooltip"
                                    data-bs-original-title="Facturas en transporte">
                                    Facturas en transporte
                                </a>
                                <a id="pagadas-con-novedad" class="invoice-main-tab" data-bs-toggle="tooltip"
                                    data-bs-original-title="Facturas con novedad">
                                    Facturas con novedad
                                    <span class="invoice-main-tab-count" id="mainTabCountNovedad">0</span>
                                </a>
                                <a id="Fullfacturas-all" class="invoice-main-tab" data-bs-toggle="tooltip"
                                    data-bs-original-title="Todas las facturas">
                                    Todas las facturas
                                </a>
                            </div>
                            {{-- Fin --}}

                            {{-- Card de tablas de facturas --}}
                            <div class="collapse" id="FacturasGenerales" style="display: none">

                                <body class="ltr app sidebar-mini">
                                    <div class="row row-sm">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <!-- CONTAINER -->
                                                    <div class="main-container container-fluid">
                                                        <div class="card invoice-table-card" id="facturas-all">
                                                            <div class="card-header">
                                                                <h3>Todas las facturas</h3>
                                                            </div>
                                                            <div class="card">
                                                                <div class="row">
                                                                    <button class="btn btn-info dropdown-toggle"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapseExampleFilter1"
                                                                        aria-expanded="false"
                                                                        aria-controls="collapseExampleFilter1">
                                                                        Filtros
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="card-header border-bottom"
                                                                id="collapseExampleFilter1">
                                                                <div class="row g-2">
                                                                    <h3 class="card-title">Fitros</h3>
                                                                    <div class="form-horizontal">
                                                                        <div class="row mb-2">
                                                                            <div class="col-md-3">
                                                                                <label for="InvoiceLimit" class="form-label">#
                                                                                    Factoras que desea visualizar</label>
                                                                                <select type="text" name="InvoiceLimit"
                                                                                    id="InvoiceLimit" class="form-control"
                                                                                    tabindex="3"
                                                                                    value="{{ old('InvoiceLimit') }}"
                                                                                    autofocus>
                                                                                    <option selected value="20">20</option>
                                                                                    <option value="40">40</option>
                                                                                    <option value="60">60</option>
                                                                                    <option value="80">80</option>
                                                                                    <option value="100">100</option>
                                                                                    <option value="200">200</option>
                                                                                    <option value="350">350</option>
                                                                                    <option value="500">500</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md">
                                                                                <label for="tipoFactura"
                                                                                    class="form-label">Tipo
                                                                                    de
                                                                                    factura</label>
                                                                                <select type="text" name="tipoFactura"
                                                                                    id="tipoFactura" class="form-select"
                                                                                    tabindex="3"
                                                                                    value="{{ old('tipoFactura') }}"
                                                                                    autofocus>
                                                                                    <option selected value="">Todos
                                                                                    </option>
                                                                                    <option value="Pago por adelantado">
                                                                                        Anticipo</option>
                                                                                    <option value="Estándar">Estándar
                                                                                    </option>
                                                                                    <option value="Nota de crédito">Nota
                                                                                        Crédito</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md">
                                                                                <label for="ValidationStatus"
                                                                                    class="form-label">Estado
                                                                                    Validación</label>
                                                                                <select type="text" name="ValidationStatus"
                                                                                    id="ValidationStatus" class="form-select"
                                                                                    tabindex="3"
                                                                                    value="{{ old('ValidationStatus') }}"
                                                                                    autofocus>
                                                                                    <option selected value="">Todos
                                                                                    </option>
                                                                                    <option value="Cancelada">Cancelada
                                                                                    </option>
                                                                                    <option value="Validada">Validada
                                                                                    </option>
                                                                                    <option value="Necesita revalidación">
                                                                                        Necesita revalidación</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md">
                                                                                <label for="PaidStatus"
                                                                                    class="form-label">Estado Pago</label>
                                                                                <select type="text" name="PaidStatus"
                                                                                    id="PaidStatus" class="form-select"
                                                                                    tabindex="3"
                                                                                    value="{{ old('PaidStatus') }}" autofocus>
                                                                                    <option selected value="">Todos
                                                                                    </option>
                                                                                    <option value="Pagadas">Pagadas</option>
                                                                                    <option value="Impagado">Impagado
                                                                                    </option>
                                                                                    <option value="Pagada parcialmente">
                                                                                        parcialmente</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md">
                                                                                <label for="CanceledFlag"
                                                                                    class="form-label">Canceladas</label>
                                                                                <select type="text" name="CanceledFlag"
                                                                                    id="CanceledFlag" class="form-select"
                                                                                    tabindex="3"
                                                                                    value="{{ old('CanceledFlag') }}"
                                                                                    autofocus>
                                                                                    <option selected value="false">No
                                                                                    </option>
                                                                                    <option value="true">Si</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label for="title" class="form-label">Fecha
                                                                                    Inicio y
                                                                                    Fecha Fin</label>
                                                                                <div class="input-group">
                                                                                    <input name="startDate" id="startDate"
                                                                                        class="form-control"
                                                                                        placeholder="YYYY-MM-DD"
                                                                                        data-mask="yyyy-mm-dd" tabindex="3"
                                                                                        value="{{ old('startDate') }}"
                                                                                        onKeyUp="ValidarFecha('startDate','btnPrFiltr');"
                                                                                        autofocus>
                                                                                    <input name="endDate" id="endDate"
                                                                                        placeholder="YYYY-MM-DD"
                                                                                        data-mask="yyyy-mm-dd"
                                                                                        class="form-control" tabindex="3"
                                                                                        onKeyUp="ValidarFecha('endDate','btnPrFiltr');"
                                                                                        value="{{ old('endDate') }}"
                                                                                        autofocus>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary"
                                                                            id="btnPrFiltr">Filtrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row row-sm">
                                                                    <div class="col-lg-12">
                                                                        <div class="card">
                                                                            <div class="card-body">
                                                                                <div class="scroll-table-toolbar">
                                                                                    <input type="text"
                                                                                        class="scroll-table-search"
                                                                                        id="searchTablaFacturasAll"
                                                                                        placeholder="Buscar...">
                                                                                    <button type="button"
                                                                                        class="scroll-table-export"
                                                                                        id="exportTablaFacturasAll">Exportar
                                                                                        CSV</button>
                                                                                </div>
                                                                                <div class="table-responsive">
                                                                                    <table id="TablaFacturasAll"
                                                                                        class="table table-bordered text-nowrap key-buttons border-bottom  w-100">
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
                                        </div>
                                    </div>
                                </body>
                            </div>

                            <div class="card invoice-table-card" id="oculto-por-pagar" style="display: none">
                                <div class="card-header">
                                    <h3>Facturas por pagar <span class="invoice-tab-count" id="headerCountPorPagar"
                                            style="background:#fdecea;color:#c0392b;">0</span></h3>
                                </div>
                                <div class="card-body">
                                    <div class="row row-sm">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="scroll-table-toolbar">
                                                        <input type="text" class="scroll-table-search"
                                                            id="searchTablePorPagar" placeholder="Buscar...">
                                                        <button type="button" class="scroll-table-export"
                                                            id="exportTablePorPagar">Exportar CSV</button>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table id="TablePorPagar"
                                                            class="table table-bordered text-nowrap key-buttons border-bottom  w-100">
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card invoice-table-card" id="oculto-pagadas-con-novedad" style="display: none">
                                <div class="card-header">
                                    <h3>Facturas parcialmente pagadas <span class="invoice-tab-count" id="headerCountParcial"
                                            style="background:#fdf3e9;color:#b85e0f;">0</span></h3>
                                </div>
                                <div class="card-body">
                                    <div class="row row-sm">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="scroll-table-toolbar">
                                                        <input type="text" class="scroll-table-search"
                                                            id="searchTablePagadasNovedad" placeholder="Buscar...">
                                                        <button type="button" class="scroll-table-export"
                                                            id="exportTablePagadasNovedad">Exportar CSV</button>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table id="TablePagadasNovedad"
                                                            class="table table-bordered text-nowrap key-buttons border-bottom  w-100">
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card invoice-table-card" id="facturas-en-transporte" style="display: none">
                                <div class="card-header">
                                    <h3>Facturas en transporte</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row row-sm">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card">
                                                    <div class="row">
                                                        <button class="btn btn-info dropdown-toggle" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseExampleFilter"
                                                            aria-expanded="false" aria-controls="collapseExampleFilter">
                                                            Filtros
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-header border-bottom" id="collapseExampleFilter">
                                                    <div class="row g-2">
                                                        <div class="form-horizontal">
                                                            <div class="row mb-2">
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label"># Facturas que
                                                                        desea visualizar</label>
                                                                    <select type="text" name="ShipmentsLimit"
                                                                        id="ShipmentsLimit" class="form-control"
                                                                        tabindex="3" value="{{ old('ShipmentsLimit') }}"
                                                                        autofocus>
                                                                        <option selected value="20">20</option>
                                                                        <option value="40">40</option>
                                                                        <option value="60">60</option>
                                                                        <option value="80">80</option>
                                                                        <option value="100">100</option>
                                                                        <option value="200">200</option>
                                                                        <option value="350">350</option>
                                                                        <option value="500">500</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary"
                                                                id="btnFiltr">Filtrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="scroll-table-toolbar">
                                                        <input type="text" class="scroll-table-search"
                                                            id="searchTableEnTransporte" placeholder="Buscar...">
                                                        <button type="button" class="scroll-table-export"
                                                            id="exportTableEnTransporte">Exportar CSV</button>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table id="TableEnTransporte"
                                                            class="table table-bordered text-nowrap key-buttons border-bottom  w-100">
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Fin --}}

                            {{-- Modal de visualizacionde facturas --}}
                            <div class="modal fade" id="exampleModalToggle" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-12 mx-auto">
                                                <div class="modal-content">
                                                    <div class="card">
                                                        <div class="card-body invoice-head">
                                                            <div class="invoice-doc-header" id="date">

                                                            </div>
                                                        </div>
                                                        <!--end card-body-->
                                                        <div class="card-body" id="body">
                                                            <div class="invoice-summary-grid" id="row1">

                                                            </div>

                                                            <div class="p-4">
                                                                <h4 class="invoice-section-title">Detalle</h4>
                                                                <table class="invoice-lines">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>@lang('locale.Description')</th>
                                                                            <th>@lang('locale.Amount')</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="row2">

                                                                    </tbody>
                                                                </table>

                                                                <div class="invoice-totals" id="invoiceTotals">

                                                                </div>
                                                            </div>

                                                            <div class="px-4 pb-2" id="Bloqueos" style="display:none;">
                                                                <h4 class="invoice-holds-title">⚠ Retenciones / bloqueos</h4>
                                                                <div id="row3"></div>
                                                            </div>

                                                            <div class="invoice-doc-footer">
                                                                <div class="thanks">
                                                                    Gracias por su servicio.<br>
                                                                    Tractocar Logistics SAS.
                                                                </div>
                                                                <div>
                                                                    <button type="button"
                                                                        class="btn btn-outline-primary btn-sm"
                                                                        onclick="window.print()">Imprimir / PDF</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end card-body-->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" id="closet-modal" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                                <!--end card-->
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                            {{-- Fin --}}

                            {{-- Modal de visualizacionde facturas en trasnporte --}}
                            <div class="modal fade" id="exampleModalTransporte" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-12 mx-auto">
                                                <div class="modal-content">
                                                    <div class="card">
                                                        <div class="card-body invoice-head">
                                                            <div class="row" id="date_1">

                                                            </div>
                                                            <!--end row-->
                                                        </div>
                                                        <!--end card-body-->
                                                        <div class="card-body" id="body">
                                                            <div class="row p-2">
                                                                <div class="col-lg-6">
                                                                    {{-- <h5 class="btn btn-outline-primary"
                                                                for="btn-check-outlined"> Detalles </h5> --}}
                                                                    <h5
                                                                        class="bg-primary col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                                                        Propietario</h5>
                                                                    <div class="table-responsive project-invoice">
                                                                        <table class="table table-bordered mb-0">
                                                                            <thead class="thead-light">
                                                                                <tr>
                                                                                    <th>Documento</th>
                                                                                    <th>Nombre</th>
                                                                                </tr>
                                                                                <!--end tr-->
                                                                            </thead>
                                                                            <tbody id="row1_1">


                                                                            </tbody>
                                                                        </table>
                                                                        <!--end table-->
                                                                    </div>
                                                                    <!--end /div-->
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <h5
                                                                        class="bg-primary col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                                                        Conductor</h5>
                                                                    <div class="table-responsive project-invoice">
                                                                        <table class="table table-bordered mb-0">
                                                                            <thead class="thead-light">
                                                                                <tr>
                                                                                    <th>Documento</th>
                                                                                    <th>Nombre</th>
                                                                                    <th>Telefono</th>
                                                                                </tr>
                                                                                <!--end tr-->
                                                                            </thead>
                                                                            <tbody id="row2_2">


                                                                            </tbody>
                                                                        </table>
                                                                        <!--end table-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row p-2">
                                                                <div class="col-lg-12">
                                                                    <h5
                                                                        class="bg-info col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                                                        Resumen</h5>
                                                                    <div class="table-responsive project-invoice">
                                                                        <table class="table table-bordered mb-0">
                                                                            <thead class="thead-light">
                                                                                <tr>
                                                                                    <th>Tipo de Operacion</th>
                                                                                    <th>Estado del Envío</th>
                                                                                    <th>Estado Anticipo</th>
                                                                                </tr>
                                                                                <!--end tr-->
                                                                            </thead>
                                                                            <tbody id="row3_3">


                                                                            </tbody>
                                                                        </table>
                                                                        <!--end table-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row p-2">
                                                                <div class="col-lg-12">
                                                                    <h5
                                                                        class="bg-info col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                                                        Información Adicional</h5>
                                                                    <div class="table-responsive project-invoice">
                                                                        <table class="table table-bordered mb-0">
                                                                            <thead class="thead-light">
                                                                                <tr>
                                                                                    <th>Ciudad Origen</th>
                                                                                    <th>Provincia</th>
                                                                                    <th>Direccion Origen</th>
                                                                                    <th>Ruta</th>
                                                                                    <th>Via</th>
                                                                                    <th>Ciudad Destino</th>
                                                                                    <th>Provincia</th>
                                                                                    <th>Direccion Destino</th>
                                                                                </tr>
                                                                                <!--end tr-->
                                                                            </thead>
                                                                            <tbody id="row4_4">


                                                                            </tbody>
                                                                        </table>
                                                                        <!--end table-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row p-2">
                                                                <div class="col-lg-12">
                                                                    <h5
                                                                        class="bg-success col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                                                        Información del Vehículo</h5>
                                                                    <div class="table-responsive project-invoice">
                                                                        <table class="table table-bordered mb-0">
                                                                            <thead class="thead-light">
                                                                                <tr>
                                                                                    <th>Matrícula</th>
                                                                                    <th>Marca</th>
                                                                                    <th>Color</th>
                                                                                    <th>Modelo</th>
                                                                                    <th>Numero Trailer</th>
                                                                                </tr>
                                                                                <!--end tr-->
                                                                            </thead>
                                                                            <tbody id="row5_5">


                                                                            </tbody>
                                                                        </table>
                                                                        <!--end table-->
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            {{-- <div class="row justify-content-center">
                                                        <div class="col-lg-12">
                                                            <h5 class="mt-4"><i
                                                                    class="fas fa-divide mr-2 text-info font-16"></i>@lang('locale.Installments')
                                                                :</h5>
                                                        </div>
                                                        <!--end col-->
                                                    </div> --}}
                                                            <!--end row-->
                                                            <div class="row d-flex justify-content-center">
                                                                <div class="col-lg-12 col-xl-4 ml-auto align-self-center">
                                                                    <div class="text-center"><small class="font-12">Tractocar
                                                                            Logistics SAS.</small>
                                                                    </div>
                                                                </div>
                                                                <!--end col-->
                                                            </div>
                                                            <!--end row-->
                                                        </div>
                                                        <!--end card-body-->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" id="closet-modal" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                    </div>

                                                </div>
                                                <!--end card-->
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                            {{-- Fin --}}

                            <!-- Modal Drakma -->
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title center-block" id="myModalLabel">Solicita tu Credito</h5>
                                        </div>
                                        <div class="modal-body">
                                            <p>Seleccione una opción:</p>
                                            <button class="btn btn-outline-info"
                                                onclick="window.open('https://drakma.co/solicitar-credito/', '_blank')">
                                                <a class="nav-link icon nav-link-bg">
                                                    <img src={{ asset('assets/images/logos-drakma/LOGO.png') }}
                                                        class="header-brand-img desktop-logo" alt="logo">
                                                    <h6>Solicitar Credito</h6>
                                                </a>
                                            </button>
                                            <button class="btn btn-outline-info"
                                                onclick="window.open('https://drakma.co/solicitar-pronto-pago/', '_blank')">
                                                <a class="nav-link icon nav-link-bg">
                                                    <img src={{ asset('assets/images/logos-drakma/LOGO.png') }}
                                                        class="header-brand-img desktop-logo" alt="logo">
                                                    <h6>Solicitar Pronto Pago</h6>
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Fin --}}
                        @endcan

                        @can('/facturasGeneral')
                            <div class="collapse" id="faturasGeneral" style="display: none">

                                <body class="ltr app sidebar-mini">
                                    <div class="row row-sm">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h3 class="text-center" style="text-decoration: underline">FACTURAS
                                                    </h3>
                                                    <div class="main-container container-fluid">
                                                        <div class="card">
                                                            <div class="row">
                                                                <button class="btn btn-primary" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target=".multi-collapse"
                                                                    aria-expanded="false"
                                                                    aria-controls="multiCollapseExample1">Filtros</button>
                                                            </div>
                                                            <div class="card-header border-bottom">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="collapse multi-collapse"
                                                                            id="multiCollapseExample1">
                                                                            <div class="card card-body">
                                                                                <form class="form-horizontal" id="filter"
                                                                                    action="{{ route('falturas.pagadas') }}"
                                                                                    method="post" novalidate>
                                                                                    @csrf
                                                                                    <div class="row mb-2">
                                                                                        <div class="col-md-3">
                                                                                            <label for="SupplierNumber"
                                                                                                class="form-label">Tipo
                                                                                                Factura y Numero Factura</label>
                                                                                            <div class="input-group">
                                                                                                <select type="text"
                                                                                                    name="TipoF"
                                                                                                    id="TipoF"
                                                                                                    class="form-control"
                                                                                                    tabindex="3"
                                                                                                    value="{{ old('TipoF') }}"
                                                                                                    autofocus>
                                                                                                    <option selected
                                                                                                        value="">
                                                                                                        Seleccione
                                                                                                    </option>
                                                                                                    <option value="M">
                                                                                                        Manifiesto</option>
                                                                                                    <option value="">Otro
                                                                                                    </option>
                                                                                                </select>
                                                                                                <input type="text"
                                                                                                    name="InvoiceNumber"
                                                                                                    id="InvoiceNumber"
                                                                                                    class="form-control"
                                                                                                    tabindex="3"
                                                                                                    value="{{ old('InvoiceNumber') }}"
                                                                                                    autofocus>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-9">
                                                                                            <label for="SupplierNumber"
                                                                                                class="form-label">Nombre
                                                                                                Proveedor</label>
                                                                                            <div class="form-group">
                                                                                                <input type="hidden"
                                                                                                    class="form-control"
                                                                                                    id="customer-code"
                                                                                                    name="SupplierNumber" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="InvoiceLimit"
                                                                                                class="form-label"># Factoras
                                                                                                que desea visualizar</label>
                                                                                            <select type="text"
                                                                                                name="InvoiceLimit"
                                                                                                id="InvoiceLimit"
                                                                                                class="form-control"
                                                                                                tabindex="3"
                                                                                                value="{{ old('InvoiceLimit') }}"
                                                                                                autofocus>
                                                                                                <option selected
                                                                                                    value="20">20
                                                                                                </option>
                                                                                                <option value="40">40
                                                                                                </option>
                                                                                                <option value="60">60
                                                                                                </option>
                                                                                                <option value="80">80
                                                                                                </option>
                                                                                                <option value="100">100
                                                                                                </option>
                                                                                                <option value="200">200
                                                                                                </option>
                                                                                                <option value="350">350
                                                                                                </option>
                                                                                                <option value="500">500
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="InvoiceType"
                                                                                                class="form-label">Tipo
                                                                                                de factura</label>
                                                                                            <select type="text"
                                                                                                name="InvoiceType"
                                                                                                id="InvoiceType"
                                                                                                class="form-control"
                                                                                                tabindex="3"
                                                                                                value="{{ old('InvoiceType') }}"
                                                                                                autofocus>
                                                                                                <option selected
                                                                                                    value="">Todos
                                                                                                </option>
                                                                                                <option
                                                                                                    value="Pago por adelantado">
                                                                                                    Anticipo
                                                                                                </option>
                                                                                                <option value="Estándar">
                                                                                                    Estándar</option>
                                                                                                <option
                                                                                                    value="Nota de crédito">
                                                                                                    Nota Crédito
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="ValidationStatus"
                                                                                                class="form-label">Estado
                                                                                                Validación</label>
                                                                                            <select type="text"
                                                                                                name="ValidationStatus"
                                                                                                id="ValidationStatus"
                                                                                                class="form-control"
                                                                                                tabindex="3"
                                                                                                value="{{ old('ValidationStatus') }}"
                                                                                                autofocus>
                                                                                                <option selected
                                                                                                    value="">Todos
                                                                                                </option>
                                                                                                <option value="Cancelada">
                                                                                                    Cancelada</option>
                                                                                                <option value="Validada">
                                                                                                    Validada</option>
                                                                                                <option
                                                                                                    value="Necesita revalidación">
                                                                                                    Necesita revalidación
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="PaidStatus"
                                                                                                class="form-label">Estado
                                                                                                Pago</label>
                                                                                            <select type="text"
                                                                                                name="PaidStatus"
                                                                                                id="PaidStatus"
                                                                                                class="form-control"
                                                                                                tabindex="3"
                                                                                                value="{{ old('PaidStatus') }}"
                                                                                                autofocus>
                                                                                                <option selected
                                                                                                    value="">Todos
                                                                                                </option>
                                                                                                <option value="Pagadas">Pagadas
                                                                                                </option>
                                                                                                <option value="Impagado">
                                                                                                    Impagado</option>
                                                                                                <option
                                                                                                    value="Pagada parcialmente">
                                                                                                    Parcialmente</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md">
                                                                                            <label for="CanceledFlag"
                                                                                                class="form-label">Canceladas</label>
                                                                                            <select type="text"
                                                                                                name="CanceledFlag"
                                                                                                id="CanceledFlag"
                                                                                                class="form-control"
                                                                                                tabindex="3"
                                                                                                value="{{ old('CanceledFlag') }}"
                                                                                                autofocus>
                                                                                                <option selected
                                                                                                    value="false">
                                                                                                    No</option>
                                                                                                <option value="true">Si
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="title"
                                                                                                class="form-label">Fecha
                                                                                                Factura</label>
                                                                                            <div class="input-group">
                                                                                                <select type="text"
                                                                                                    name="core"
                                                                                                    id="core"
                                                                                                    class="form-control"
                                                                                                    tabindex="3"
                                                                                                    value="{{ old('core') }}"
                                                                                                    autofocus>
                                                                                                    <option selected
                                                                                                        value="=">
                                                                                                        Igual
                                                                                                        que
                                                                                                    </option>
                                                                                                    <option value=">">
                                                                                                        Después</option>
                                                                                                    <option value="<">
                                                                                                        Antes</option>
                                                                                                </select>
                                                                                                <input name="InvoiceDate"
                                                                                                    id="InvoiceDate"
                                                                                                    class="form-control"
                                                                                                    placeholder="YYYY-MM-DD"
                                                                                                    data-mask="yyyy-mm-dd"
                                                                                                    onKeyUp="ValidarFecha('InvoiceDate','btnPrFiltr');"
                                                                                                    tabindex="3"
                                                                                                    value="{{ old('InvoiceDate') }}"
                                                                                                    autofocus>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="title"
                                                                                                class="form-label">Fecha Inicio
                                                                                                y
                                                                                                Fecha Fin</label>
                                                                                            <div class="input-group">
                                                                                                <input name="startDate"
                                                                                                    id="startDate"
                                                                                                    class="form-control"
                                                                                                    placeholder="YYYY-MM-DD"
                                                                                                    data-mask="yyyy-mm-dd"
                                                                                                    tabindex="3"
                                                                                                    value="{{ old('startDate') }}"
                                                                                                    onKeyUp="ValidarFecha('startDate','btnPrFiltr');"
                                                                                                    autofocus>
                                                                                                <input name="endDate"
                                                                                                    id="endDate"
                                                                                                    placeholder="YYYY-MM-DD"
                                                                                                    data-mask="yyyy-mm-dd"
                                                                                                    class="form-control"
                                                                                                    tabindex="3"
                                                                                                    onKeyUp="ValidarFecha('endDate','btnPrFiltr');"
                                                                                                    value="{{ old('endDate') }}"
                                                                                                    autofocus>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary"
                                                                                        id="btnPrFiltr">Filtrar</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row row-sm">
                                                                <div class="col-lg-12">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <div class="table-responsive">
                                                                                <table id="TablaFullFacturasAll"
                                                                                    class="table table-bordered text-nowrap key-buttons border-bottom  w-100">
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
                                    </div>
                                </body>
                            </div>
                            {{-- Modal de visualizacion de facturas --}}
                            <div class="modal fade" id="exampleModalToggle" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-12 mx-auto">
                                                <div class="modal-content">
                                                    <div class="card">
                                                        <div class="card-body invoice-head">
                                                            <div class="row" id="date">

                                                            </div>
                                                            <!--end row-->
                                                        </div>
                                                        <!--end card-body-->
                                                        <div class="card-body" id="body">
                                                            <div class="row p-2">
                                                                <div class="col-lg-12">
                                                                    {{-- <h5 class="btn btn-outline-primary"
                                                                for="btn-check-outlined"> Detalles </h5> --}}
                                                                    <h5
                                                                        class="bg-info col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                                                        Resumen</h5>
                                                                    <div class="table-responsive project-invoice">
                                                                        <table class="table table-bordered mb-0">
                                                                            <thead class="thead-light">
                                                                                <tr>
                                                                                    <th>Tipo de factura</th>
                                                                                    <th>Estado de pago</th>
                                                                                    <th>Metodo de pago</th>
                                                                                    <th>Estado de validación</th>
                                                                                    <th>Numero de cuenta</th>
                                                                                    {{-- <th>Categoría de documento</th>
                                                                            <th>Secuencia de documento</th> --}}
                                                                                    <th>Fecha Contable</th>
                                                                                    <th>Fecha de Vencimiento</th>
                                                                                    <th>Fecha Pago</th>
                                                                                </tr>
                                                                                <!--end tr-->
                                                                            </thead>
                                                                            <tbody id="row1">


                                                                            </tbody>
                                                                        </table>
                                                                        <!--end table-->
                                                                    </div>
                                                                    <!--end /div-->
                                                                </div>
                                                                <!--end col-->
                                                            </div>


                                                            <div class="row p-2">
                                                                <div class="col-lg-12">
                                                                    <h5
                                                                        class="bg-success col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                                                        Detalle </h5>

                                                                    <div class="table-responsive project-invoice">
                                                                        <table class="table table-bordered mb-0">
                                                                            <thead class="thead-light">
                                                                                <tr>
                                                                                    <th>@lang('locale.Description')</th>
                                                                                    <th>@lang('locale.Amount')</th>
                                                                                </tr>
                                                                                <!--end tr-->
                                                                            </thead>
                                                                            <tbody id="row2">


                                                                            </tbody>
                                                                        </table>
                                                                        <!--end table-->
                                                                    </div>
                                                                    <!--end /div-->
                                                                </div>
                                                                <!--end col-->

                                                            </div>
                                                            <!--end row-->
                                                            <div class="row p-2">
                                                                <div class="col-lg-12">
                                                                    <h5
                                                                        class="bg-danger col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                                                        Bloqueos </h5>

                                                                    <div class="table-responsive project-invoice">
                                                                        <table class="table table-bordered mb-0">
                                                                            <thead class="thead-light">
                                                                                <tr>
                                                                                    <th>Nombre Retencion</th>
                                                                                    <th>Razón Retencion</th>
                                                                                    <th>Retenida Por</th>
                                                                                    <th>Fecha Retencion</th>
                                                                                </tr>
                                                                                <!--end tr-->
                                                                            </thead>
                                                                            <tbody id="row3">


                                                                            </tbody>
                                                                        </table>
                                                                        <!--end table-->
                                                                    </div>
                                                                    <!--end /div-->
                                                                </div>
                                                                <!--end col-->

                                                            </div>
                                                            <!--end row-->

                                                            {{-- <div class="row justify-content-center">
                                                        <div class="col-lg-12">
                                                            <h5 class="mt-4"><i
                                                                    class="fas fa-divide mr-2 text-info font-16"></i>@lang('locale.Installments')
                                                                :</h5>
                                                        </div>
                                                        <!--end col-->
                                                    </div> --}}
                                                            <!--end row-->
                                                            <div class="row d-flex justify-content-center">
                                                                <div class="col-lg-12 col-xl-4 ml-auto align-self-center">
                                                                    <div class="text-center"><small class="font-12">Tractocar
                                                                            Logistics SAS.</small>
                                                                    </div>
                                                                </div>
                                                                <!--end col-->
                                                            </div>
                                                            <!--end row-->
                                                        </div>
                                                        <!--end card-body-->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" id="closet-modal" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                                <!--end card-->
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                            {{-- Fin --}}
                        @endcan

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
    <script src="{{ asset('assets/js/scroll-table.js') }}?v={{ time() }}"></script>

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
        let Loader1 = function() {
            let $yourUl = $("#global-loader2");
            $yourUl.css("display", $yourUl.css("display") === 'none' ? '' : 'none');
        }

        window.onload = function() {
            if ($("#faturasGeneral").css("display") == 'none')
                $("#faturasGeneral").show("slow");
            else
                $("#faturasGeneral").hide("slow");
            $('.multi-collapse').collapse();
            $.ajax({
                type: "POST",
                url: "{{ route('supplier.number') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: "{{ Auth::user()->id }}"
                },
                success: function(response) {
                    let data = response.data;

                    if (response.success == true) {
                        let = plantillaMtPorPagar = ''
                        let = plantillaTotalFt = ''

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('total') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                SupplierNumber: data,
                                PaidStatus: [
                                    'Impagado',
                                    'Pagada parcialmente'
                                ],
                            },

                            success: function(response) {
                                let datos = response.data;

                                if (response.success == true) {
                                    let dollarUSLocale = Intl.NumberFormat('en-US');
                                    let mtPorPagar = datos[0][
                                        'Impagado'
                                    ];

                                    let mtPagadaParcialmente = datos[1][
                                        'Pagada parcialmente'
                                    ];

                                    let montoTotal = mtPagadaParcialmente + mtPorPagar

                                    let montoTotalFormat = dollarUSLocale.format(montoTotal)

                                    let totalFt = datos[0]['count Impagado'];
                                    let totalFtpartial = datos[1]['count Pagada parcialmente'];

                                    var x = document.getElementById("piner");
                                    var y = document.getElementById("piner1");
                                    plantillaMtPorPagar =
                                        `
                                <span class="stat-value">$${ montoTotalFormat }</span>
                                `
                                    x.style.display = "none";
                                    $('#mtPorPagar').append(plantillaMtPorPagar)

                                    plantillaTotalFt =
                                        `
                                <span class="stat-value">${totalFt + totalFtpartial}</span>
                                `


                                    y.style.display = "none";
                                    $('#totalFt').append(plantillaTotalFt)

                                    $('#headerCountPorPagar').text(totalFt);
                                    $('#headerCountParcial').text(totalFtpartial);
                                    $('#mainTabCountPorPagar').text(totalFt);
                                    $('#mainTabCountNovedad').text(totalFtpartial);

                                    Loader1();
                                }
                            },
                            error: function(error) {
                                console.error(error);
                            }
                        })
                    }
                }
            })
        }

        function ValidarFecha(id, btn) {
            // Almacenamos el valor digitado en TxtFecha
            var Fecha = document.getElementById(id).value;
            const button = document.getElementById(btn)

            // Si la fecha está completa comenzamos la validación
            if (Fecha.length != 10)
                button.disabled = true
            if (Fecha.length == 10)
                button.disabled = false
            if (Fecha.length == "")
                button.disabled = false

        }
        //     const image = document.querySelector('.background-image');
        // let opacity = 0.5; // Opacidad inicial
        // const intervalDuration = 500; // Duración del intervalo en milisegundos
        // const opacityStep = 0.01; // Paso de cambio de opacidad

        // function updateOpacity() {
        //     opacity += opacityStep;
        //     if (opacity > 1) {
        //         opacity = 0.5; // Reiniciar la opacidad después de llegar a 1
        //     }
        //     image.style.opacity = opacity;
        // }

        if (typeof updateOpacity === 'function' && typeof intervalDuration !== 'undefined') {
            if (typeof updateOpacity === 'function' && typeof intervalDuration !== 'undefined') {
                setInterval(updateOpacity, intervalDuration);
            }
        }
    </script>
    @can('/usuario.index')
        <script>
            let urlCountLogin = "{{ route('setting.statistics.countLogin') }}"
            ajaxCountLoginHome(urlCountLogin);
        </script>
    @endcan
    {{-- Cliente --}}
    @can('/facturas')
        <script>
            // Funccion de consulta validaciones y carga de datos Datatable
            let estadoPagoInfo = function(d) {
                var today = new Date();
                var day = today.getDate();
                var month = today.getMonth() + 1;
                var year = today.getFullYear();

                var date1 = new Date(d.invoiceInstallments[0]["DueDate"]);
                var date2 = new Date(`${year}-${month}-${day}`);
                var dateDefined = date1 - date2;
                var dias = dateDefined / (1000 * 60 * 60 * 24);

                if (dias <= 0 && d.PaidStatus != 'Pagadas') {
                    return {
                        text: 'Pendiente de pago',
                        cls: 'status-pending'
                    };
                }
                if (d.PaidStatus == 'Pagadas') {
                    return {
                        text: 'Pagada',
                        cls: 'status-paid'
                    };
                }
                var Ndias = Math.trunc(dias)
                return {
                    text: 'El pago se le generara dentro de ' + Ndias + ' Dias',
                    cls: 'status-scheduled'
                };
            }

            let invoiceTableColumns = [{
                    title: "Accion",
                    exportable: false,
                    render: function() {
                        return "<button type='button' class='ver btn-icon-action' aria-label='Ver factura' title='Ver factura'><i class='fa fa-eye' aria-hidden='true'></i></button>";
                    }
                },
                {
                    title: "Numero Factura",
                    render: function(d) {
                        return d.InvoiceNumber;
                    }
                },
                {
                    title: "Valor Factura",
                    render: function(d) {
                        const formatterDolar = new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: 'USD',
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })
                        return formatterDolar.format(d.InvoiceAmount);
                    }
                },
                {
                    title: "Saldo",
                    render: function(d) {
                        const formatterDolar = new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: 'USD',
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })
                        return formatterDolar.format(d.invoiceInstallments[0]["UnpaidAmount"]);
                    }
                },
                {
                    title: "Monto Pagado",
                    render: function(d) {
                        const formatterDolar = new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: 'USD',
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })
                        return formatterDolar.format(d.AmountPaid);
                    }
                },
                {
                    title: "Estado Pago",
                    exportValue: function(d) {
                        return estadoPagoInfo(d).text;
                    },
                    render: function(d) {
                        var info = estadoPagoInfo(d);
                        return '<span class="status-badge ' + info.cls + '">' + info.text + '</span>';
                    }
                },
                {
                    title: "Fecha Factura",
                    render: function(d) {
                        return d.InvoiceDate;
                    }
                },
            ];

            let getScrollTable = function(TableName, columns) {
                if (!window.invoiceScrollTables) window.invoiceScrollTables = {};
                if (!window.invoiceScrollTables[TableName]) {
                    window.invoiceScrollTables[TableName] = new ScrollTable(TableName, columns);
                }
                return window.invoiceScrollTables[TableName];
            }

            let LoadData = function(PaidStatus, CanceledFlag, TableName, InvoiceType, ValidationStatus, Card, startDate,
                endDate, InvoiceLimit) {
                // let start = performance.now();
                tblColectionData = getScrollTable(TableName, invoiceTableColumns);

                let validacionButton = function(Card) {
                    if (Card == "#oculto-por-pagar") {

                        if ($("#oculto-por-pagar").css("display") == 'none')
                            $("#oculto-por-pagar").show("slow");
                        else
                            $("#oculto-por-pagar").hide("slow");

                        // validamos que no se muestren todas al tiempo
                        if ($("#oculto-pagadas-con-novedad").css("display") != 'none')
                            $("#oculto-pagadas-con-novedad").hide("slow");

                        if ($("#facturas-en-transporte").css("display") != 'none')
                            $("#facturas-en-transporte").hide("slow");

                        if ($("#FacturasGenerales").css("display") != 'none')
                            $("#FacturasGenerales").hide("slow");
                    } else if (Card == "#oculto-pagadas-con-novedad") {

                        if ($("#oculto-pagadas-con-novedad").css("display") == 'none')
                            $("#oculto-pagadas-con-novedad").show("slow");
                        else
                            $("#oculto-pagadas-con-novedad").hide("slow");

                        // validamos que no se muestren todas al tiempo
                        if ($("#oculto-por-pagar").css("display") != 'none')
                            $("#oculto-por-pagar").hide("slow");

                        if ($("#facturas-en-transporte").css("display") != 'none')
                            $("#facturas-en-transporte").hide("slow");

                        if ($("#FacturasGenerales").css("display") != 'none')
                            $("#FacturasGenerales").hide("slow");
                    } else if (Card == "#FacturasGenerales") {
                        if ($("#FacturasGenerales").css("display") == 'none')
                            $("#FacturasGenerales").show("slow");
                        else
                            $("#FacturasGenerales").hide("slow");

                        // validamos que no se muestren todas al tiempo
                        if ($("#oculto-pagadas-con-novedad").css("display") != 'none')
                            $("#oculto-pagadas-con-novedad").hide("slow");

                        if ($("#oculto-por-pagar").css("display") != 'none')
                            $("#oculto-por-pagar").hide("slow");

                        if ($("#facturas-en-transporte").css("display") != 'none')
                            $("#facturas-en-transporte").hide("slow");

                    } else if (Card == "#facturas-en-transporte") {
                        if ($("#facturas-en-transporte").css("display") == 'none')
                            $("#facturas-en-transporte").show("slow");
                        else
                            $("#facturas-en-transporte").hide("slow");

                        // validamos que no se muestren todas al tiempo
                        if ($("#oculto-pagadas-con-novedad").css("display") != 'none')
                            $("#oculto-pagadas-con-novedad").hide("slow");

                        if ($("#FacturasGenerales").css("display") != 'none')
                            $("#FacturasGenerales").hide("slow");

                        if ($("#oculto-por-pagar").css("display") != 'none')
                            $("#oculto-por-pagar").hide("slow");

                    }
                }
                $.ajax({
                    type: 'POST',
                    url: "{{ route('falturas.pagadas') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        SupplierNumber: {{ $SupplierNumber }},
                        CanceledFlag: CanceledFlag,
                        PaidStatus: PaidStatus,
                        InvoiceType: InvoiceType,
                        InvoiceLimit: InvoiceLimit,
                        core: "=",
                        ValidationStatus: ValidationStatus,
                        startDate: startDate,
                        endDate: endDate

                    },
                    success: function(response) {
                        let datos = response.data;
                        console.log(datos);
                        if (response.success == true) {

                            tblColectionData.setData(datos);

                            validacionButton(Card);

                            swal.close();
                        } else {
                            swal.close();
                            Loader();
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: datos,
                            })
                        }
                    },
                    error: function(error) {
                        swal.close();
                        Loader();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Algo fallo con la respuesta!',
                        })
                        console.error(error);
                    }
                })
            }

            let shipmentTableColumns = [{
                    title: "Accion",
                    exportable: false,
                    render: function() {
                        return "<button type='button' class='verT btn-icon-action' aria-label='Ver manifiesto' title='Ver manifiesto'><i class='fa fa-eye' aria-hidden='true'></i></button>";
                    }
                },
                {
                    title: "ID",
                    render: function(d) {
                        return d.shipmentXid;
                    }
                },
                {
                    title: "Documento proveedor",
                    render: function(d) {
                        if (typeof d.attribute9 != "undefined") {
                            let pieces = d.attribute9.split(".");
                            return pieces[1];
                        }
                        return 'Numero identificacion proveedor no definida';
                    }
                },
                {
                    title: "Placa",
                    render: function(d) {
                        if (typeof d.attribute10 != "undefined") {
                            let pieces = d.attribute10.split(".");
                            return pieces[1];
                        }
                        return 'Placa no definida';
                    }
                },
                {
                    title: "Placa Trailer",
                    render: function(d) {
                        if (typeof d.attribute11 !== "undefined") {
                            let pieces = d.attribute11.split(".");
                            if (pieces.length > 1) {
                                return pieces[1];
                            }
                            return d.attribute11;
                        }
                        return 'Placa de trailer no definida';
                    }
                },
                {
                    title: "Costo Total",
                    render: function(d) {
                        const formatterDolar = new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: 'COP',
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })
                        return formatterDolar.format(d.totalActualCost['value']);
                    }
                },
                {
                    title: "#N paradas",
                    render: function(d) {
                        return d.numStops;
                    }
                },
                {
                    title: "Estado Manifiesto",
                    render: function(d) {
                        let status = d.statuses.items;
                        let response = '';
                        status.forEach(function(status) {
                            if (status.statusTypeGid == 'TCL.MANIFIESTO_CUMPLIDO') {
                                let statusValue = status.statusValueGid.split(".");
                                response = statusValue[1];
                            }
                        });
                        return response.replace('_', ' ');
                    }
                },
                {
                    title: "Fecha de Inserción",
                    render: function(d) {
                        var insertDate = new Date(d.insertDate.value);
                        return insertDate.toLocaleString('es-ES', {
                            weekday: 'short',
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric',
                            second: 'numeric'
                        });
                    }
                }
            ];

            let LoadDataShipment = function(TableName, Card, ShipmentsLimit) {
                // let start = performance.now();
                tblColectionData = getScrollTable(TableName, shipmentTableColumns);
                let validacionButton = function(Card) {
                    if (Card == "#oculto-por-pagar") {

                        if ($("#oculto-por-pagar").css("display") == 'none')
                            $("#oculto-por-pagar").show("slow");
                        else
                            $("#oculto-por-pagar").hide("slow");

                        // validamos que no se muestren todas al tiempo
                        if ($("#oculto-pagadas-con-novedad").css("display") != 'none')
                            $("#oculto-pagadas-con-novedad").hide("slow");

                        if ($("#facturas-en-transporte").css("display") != 'none')
                            $("#facturas-en-transporte").hide("slow");

                        if ($("#FacturasGenerales").css("display") != 'none')
                            $("#FacturasGenerales").hide("slow");
                    } else if (Card == "#oculto-pagadas-con-novedad") {

                        if ($("#oculto-pagadas-con-novedad").css("display") == 'none')
                            $("#oculto-pagadas-con-novedad").show("slow");
                        else
                            $("#oculto-pagadas-con-novedad").hide("slow");

                        // validamos que no se muestren todas al tiempo
                        if ($("#oculto-por-pagar").css("display") != 'none')
                            $("#oculto-por-pagar").hide("slow");

                        if ($("#facturas-en-transporte").css("display") != 'none')
                            $("#facturas-en-transporte").hide("slow");

                        if ($("#FacturasGenerales").css("display") != 'none')
                            $("#FacturasGenerales").hide("slow");
                    } else if (Card == "#FacturasGenerales") {
                        if ($("#FacturasGenerales").css("display") == 'none')
                            $("#FacturasGenerales").show("slow");
                        else
                            $("#FacturasGenerales").hide("slow");

                        // validamos que no se muestren todas al tiempo
                        if ($("#oculto-pagadas-con-novedad").css("display") != 'none')
                            $("#oculto-pagadas-con-novedad").hide("slow");

                        if ($("#oculto-por-pagar").css("display") != 'none')
                            $("#oculto-por-pagar").hide("slow");

                        if ($("#facturas-en-transporte").css("display") != 'none')
                            $("#facturas-en-transporte").hide("slow");

                    } else if (Card == "#facturas-en-transporte") {
                        if ($("#facturas-en-transporte").css("display") == 'none')
                            $("#facturas-en-transporte").show("slow");
                        else
                            $("#facturas-en-transporte").hide("slow");

                        // validamos que no se muestren todas al tiempo
                        if ($("#oculto-pagadas-con-novedad").css("display") != 'none')
                            $("#oculto-pagadas-con-novedad").hide("slow");

                        if ($("#FacturasGenerales").css("display") != 'none')
                            $("#FacturasGenerales").hide("slow");

                        if ($("#oculto-por-pagar").css("display") != 'none')
                            $("#oculto-por-pagar").hide("slow");

                    }
                }
                $.ajax({
                    type: 'POST',
                    url: "{{ route('falturas.transporte') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        number_id: {{ $number_id }},
                        ShipmentsLimit: ShipmentsLimit,

                    },
                    success: function(response) {
                        let datos = response.data;
                        if (response.success == true) {
                            tblColectionData.setData(datos);

                            validacionButton(Card);

                            swal.close();
                        } else {
                            swal.close();
                            Loader();
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: datos,
                            })

                        }
                    },
                    error: function(error) {
                        swal.close();
                        Loader();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Algo fallo con la respuesta!',
                        })
                        console.error(error);
                    }
                })

            }
            // Fin

            // load inicial, se visualiza al seleccionar un opcion de las facturas
            let Loader = function() {
                Swal.fire({
                    title: 'Cargando las 20 facturas mas recientes!',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                    },
                })
            }
            // Fin

            // load inicial, se visualiza al seleccionar un opcion de las facturas
            let Load = function(cant) {
                Swal.fire({
                    title: 'Cargando las ' + cant + ' facturas mas recientes!',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                    },
                })
            }
            // Fin

            // load secundario, se visualiza al momento pasas de una opcion de facturas a otro siempre y cuando se estan visualizando la tabla de facturas
            let LoaderView = function() {
                Swal.fire({
                    title: 'Cargando visualización!',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                    },
                })
            }
            // Fin

            // Filtros facturas OTM transporte
            $('#btnFiltr').on('click', function(e) {
                e.preventDefault(); //detemos el formluario
                var ShipmentsLimit = document.getElementById("ShipmentsLimit").value;
                if (tblColectionData) tblColectionData.setData([]);
                // Loader();
                if (ShipmentsLimit > 20) {
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                        title: 'Advertencia',
                        text: "Tenga en cuanta que al aumentar el rango de carga de facturas la respuesta demorara un poco más.!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, Entiendo',
                        cancelButtonText: 'Mmm... mejor no',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Load(ShipmentsLimit);
                            LoadDataShipment("#TableEnTransporte", "", ShipmentsLimit);
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            swalWithBootstrapButtons.fire(
                                'Consulta Cancelada'
                            )
                        }
                    });

                } else if (ShipmentsLimit == 20) {

                    Load(ShipmentsLimit);
                    LoadDataShipment("#TableEnTransporte", "", ShipmentsLimit);
                }
            });
            // Fin

            // Filtros facturas ERP
            $('#btnPrFiltr').on('click', function(e) {
                var InvoiceLimit = document.getElementById("InvoiceLimit").value;
                var InvoiceType = document.getElementById("tipoFactura").value;
                var ValidationStatus = document.getElementById("ValidationStatus").value;
                var PaidStatus = document.getElementById("PaidStatus").value;
                var CanceledFlag = document.getElementById("CanceledFlag").value;
                var startDate = document.getElementById("startDate").value;
                var endDate = document.getElementById("endDate").value;
                if (tblColectionData) tblColectionData.setData([]);
                // Loader();
                if (InvoiceLimit > 20) {
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                        title: 'Advertencia',
                        text: "Tenga en cuanta que al aumentar el rango de carga de facturas la respuesta demorara un poco más.!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: 'Sí, Entiendo',
                        cancelButtonText: 'Mmm... mejor no',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Load(InvoiceLimit);
                            LoadData(PaidStatus, CanceledFlag, "#TablaFacturasAll", InvoiceType,
                                ValidationStatus, "", startDate, endDate, InvoiceLimit);

                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            swalWithBootstrapButtons.fire(
                                'Consulta Cancelada'
                            )
                        }
                    });

                } else if (InvoiceLimit == 20) {

                    Load(InvoiceLimit);
                    LoadData(PaidStatus, CanceledFlag, "#TablaFacturasAll", InvoiceType, ValidationStatus, "",
                        startDate, endDate, InvoiceLimit);
                }
                obtener_data("#TablaFacturasAll tbody", tblColectionData);
            });
            // Fin

            // Estado visual de las pestanas principales
            $('#invoiceMainTabs').on('click', '.invoice-main-tab', function() {
                $('#invoiceMainTabs .invoice-main-tab').removeClass('active');
                $(this).addClass('active');
            });
            // Fin

            // Buscador y exportar de las tablas con scroll infinito
            let bindScrollTableToolbar = function(tableName, searchId, exportId, exportFilename) {
                $('#' + searchId).on('input', function() {
                    let st = window.invoiceScrollTables && window.invoiceScrollTables[tableName];
                    if (st) st.filter($(this).val());
                });
                $('#' + exportId).on('click', function() {
                    let st = window.invoiceScrollTables && window.invoiceScrollTables[tableName];
                    if (st) st.exportCsv(exportFilename);
                });
            }
            bindScrollTableToolbar('#TablePorPagar', 'searchTablePorPagar', 'exportTablePorPagar', 'facturas_por_pagar');
            bindScrollTableToolbar('#TablePagadasNovedad', 'searchTablePagadasNovedad', 'exportTablePagadasNovedad',
                'facturas_parcialmente_pagadas');
            bindScrollTableToolbar('#TablaFacturasAll', 'searchTablaFacturasAll', 'exportTablaFacturasAll',
                'todas_las_facturas');
            bindScrollTableToolbar('#TableEnTransporte', 'searchTableEnTransporte', 'exportTableEnTransporte',
                'facturas_en_transporte');
            // Fin

            // Acciones botones principales
            $("#por-pagar").click(function(e) {
                e.preventDefault();
                Loader();
                LoadData("Impagado", "false", "#TablePorPagar", "", "", "#oculto-por-pagar", "", "", "500");
                obtener_data("#TablePorPagar tbody", tblColectionData);
            });

            $("#pagadas-con-novedad").click(function(e) {
                e.preventDefault();
                Loader();
                LoadData("Pagada parcialmente", "false", "#TablePagadasNovedad", "", "", "#oculto-pagadas-con-novedad",
                    "", "", "500");
                obtener_data("#TablePagadasNovedad tbody", tblColectionData);

            });


            $("#Fullfacturas-all").click(function(e) {
                e.preventDefault();
                Loader();
                LoadData("", "false", "#TablaFacturasAll", "", "", "#FacturasGenerales", "", "", "500");
                obtener_data("#TablaFacturasAll tbody", tblColectionData);

            });

            $('#en-transporte').on('click', function(e) {
                e.preventDefault();

                Loader();
                LoadDataShipment("#TableEnTransporte", "#facturas-en-transporte", 500);
                obtener_dataTransporte("#TableEnTransporte tbody", tblColectionData);
            })

            // Fin
            $('#drakma').click(function(e) {
                e.preventDefault();
                $('#myModal').modal('show');
            })

            // Cerrar modal
            $("#closet-modal").click(function(e) {
                $("#global-loader3").modal('hide'); //ocultamos el modal
            });
            // Fin

            // consulta y carga de visualizar de facturas individuales
            let obtener_data = function(tbody, table) {
                $(tbody).on("click", "button.ver", function() {
                    // Mostramos el spinner en el propio boton mientras el ERP responde,
                    // y solo abrimos el modal cuando los datos ya esten listos
                    let $verBtn = $(this);
                    let verBtnOriginalHtml = $verBtn.html();
                    $verBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
                    swal.close();
                    //Fin

                    // Cargamos los datos de la factura al modal
                    let invoice = table.getRowData($(this).parents("tr"));
                    plantillaDate = '';
                    plantiilabody = '';
                    plantillarow1 = '';
                    plantillarow2 = '';
                    plantillarow3 = '';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('invoice.lines') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            InvoiceId: invoice.InvoiceId
                        },
                        success: function(response) {
                            InvoiceHelpers.removeInvoiceLoading();
                            let invoice = response.data.invoiceData[0]
                            let lines = Array.isArray(response.data.invoiceLines) ? response.data
                                .invoiceLines : []
                            let fPago = response.data.invoiceFechaPago && response.data
                                .invoiceFechaPago[0] ?
                                response.data.invoiceFechaPago[0].PaymentDate :
                                null
                            const rawHolds = response.data.holds || [];
                            let holds = [];
                            if (Array.isArray(rawHolds)) {
                                holds = Array.isArray(rawHolds[0]) ? rawHolds[0] : rawHolds;
                            }
                            let div = document.getElementById('Bloqueos');

                            let InvoiceAmount = InvoiceHelpers.formatCurrency(invoice.InvoiceAmount,
                                'USD');

                            if (response.success == true) {
                                let statusInfo = (invoice.invoiceInstallments && invoice
                                        .invoiceInstallments[0]) ?
                                    estadoPagoInfo(invoice) :
                                    {
                                        text: InvoiceHelpers.safeText(invoice.PaidStatus),
                                        cls: 'status-scheduled'
                                    };

                                $('#date').html('')
                                plantillaDate = `
                                <div class="invoice-brand-mark">
                                    <img src="{{ asset('assets/images/logos-tractocar/TCL_POS_CMYK-01.png') }}" alt="Tractocar" height="34">
                                </div>
                                <div class="invoice-brand-meta">
                                    <div class="invoice-brand-name">Tractocar Logistics SAS</div>
                                    @lang('locale.Supplier') : ${ InvoiceHelpers.safeText(invoice.Supplier) }
                                </div>
                                <div class="invoice-id-block" style="margin-left:auto;">
                                    <div class="label">@lang('locale.Invoice Number')</div>
                                    <div class="num">${ InvoiceHelpers.safeText(invoice.InvoiceNumber) }</div>
                                    <span class="status-badge ${ statusInfo.cls }">${ statusInfo.text }</span>
                                    <div class="invoice-brand-meta" style="margin-top:2px;">
                                        ${ InvoiceHelpers.formatDateValue(invoice.InvoiceDate) } · ${ InvoiceAmount }
                                    </div>
                                </div>
                            `
                                $('#date').append(plantillaDate)

                                if (invoice.CanceledFlag == 1) {
                                    $('#body').html('')
                                    plantiilabody = `
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>@lang('locale.Canceled')!</strong> @lang('locale.The invoice has been canceled').
                                        </div>
                                `
                                    $('#body').append(plantiilabody)
                                }

                                $('#row1').html('')
                                plantillarow1 = `
                                <div>
                                    <div class="k">Tipo de factura</div>
                                    <div class="v">${ InvoiceHelpers.safeText(invoice.InvoiceType) }</div>
                                </div>
                                <div>
                                    <div class="k">Estado de validación</div>
                                    <div class="v">${ InvoiceHelpers.safeText(invoice.ValidationStatus) }</div>
                                </div>
                                <div>
                                    <div class="k">Numero de cuenta</div>
                                    <div class="v">${ InvoiceHelpers.safeText(invoice.invoiceInstallments && invoice.invoiceInstallments[0] ? invoice.invoiceInstallments[0]['BankAccount'] : null) }</div>
                                </div>
                                <div>
                                    <div class="k">Fecha contable</div>
                                    <div class="v">${ InvoiceHelpers.formatDateValue(invoice.AccountingDate) }</div>
                                </div>
                                <div>
                                    <div class="k">Fecha de vencimiento</div>
                                    <div class="v">${ InvoiceHelpers.formatDateValue(invoice.invoiceInstallments && invoice.invoiceInstallments[0] ? invoice.invoiceInstallments[0]['DueDate'] : null) }</div>
                                </div>
                                <div>
                                    <div class="k">Fecha de pago</div>
                                    <div class="v">${ InvoiceHelpers.formatDateValue(fPago) }</div>
                                </div>
                            `
                                $('#row1').append(plantillarow1)

                                $('#row2').html('')
                                let hasLines = false;
                                lines.forEach(line => {
                                    var LineAmount = InvoiceHelpers.formatCurrency(line
                                        .LineAmount, 'USD');
                                    if (line.LineAmount != 0) {
                                        hasLines = true;
                                        plantillarow2 = `
                                        <tr>
                                            <td >
                                                <h5 class="mt-0 mb-1">${ InvoiceHelpers.safeText(line.LineType) }</h5>
                                                <p class="mb-0 text-muted">${ InvoiceHelpers.safeText(line.Description) }.</p>
                                            </td>
                                            <td> ${ LineAmount }</td>
                                        </tr><!--end tr-->
                                    `
                                        if (line.Description == null) {
                                            plantillarow2 = `
                                        <tr>
                                            <td >
                                                <h5 class="mt-0 mb-1">${ InvoiceHelpers.safeText(line.LineType) }</h5>
                                            </td>
                                            <td> ${ LineAmount }</td>
                                        </tr><!--end tr-->
                                    `
                                        }
                                        $('#row2').append(plantillarow2)
                                    }
                                });

                                if (!hasLines) {
                                    $('#row2').append(`
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Sin detalles de la factura.</td>
                                        </tr>
                                    `)
                                }

                                let saldoPendiente = InvoiceHelpers.formatCurrency(
                                    (invoice.InvoiceAmount || 0) - (invoice.AmountPaid || 0), 'USD'
                                );
                                $('#invoiceTotals').html(`
                                    <div class="row"><span>Subtotal</span><span>${ InvoiceAmount }</span></div>
                                    <div class="row"><span>Monto pagado</span><span>${ InvoiceHelpers.formatCurrency(invoice.AmountPaid, 'USD') }</span></div>
                                    <div class="row total"><span>Saldo pendiente</span><span>${ saldoPendiente }</span></div>
                                `)

                                $('#row3').html('')
                                if (Array.isArray(holds) && holds.length) {
                                    holds.forEach(hold => {
                                        const date = InvoiceHelpers.formatDateValue(hold
                                            .HoldDate);
                                        const isReleased = hold.ReleaseName !== null &&
                                            typeof hold.ReleaseName !== 'undefined' && hold
                                            .ReleaseName !== '';
                                        const statusChip = isReleased ?
                                            '<span class="status-badge status-paid">Liberada</span>' :
                                            '<span class="status-badge status-pending">Activa</span>';
                                        plantillarow3 = `
                                    <div class="invoice-hold-alert">
                                        ${ statusChip }
                                        <b>${ InvoiceHelpers.safeText(hold.HoldName) }:</b>
                                        ${ InvoiceHelpers.safeText(hold.HoldReason) }
                                        — retenida por ${ InvoiceHelpers.safeText(hold.HeldBy) } el ${ date }.
                                    </div>
                                `
                                        div.style.display = ''
                                        $('#row3').append(plantillarow3)
                                    });
                                } else {
                                    div.style.display = 'none'
                                }

                                $verBtn.prop('disabled', false).html(verBtnOriginalHtml);
                                swal.close();
                                $('#exampleModalToggle').modal('show');
                            } else {
                                $verBtn.prop('disabled', false).html(verBtnOriginalHtml);
                                swal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'No fue posible cargar el detalle de la factura.',
                                });
                            }
                        },
                        error: function(error) {
                            InvoiceHelpers.removeInvoiceLoading();
                            $verBtn.prop('disabled', false).html(verBtnOriginalHtml);
                            swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Algo fallo con la respuesta del servidor.',
                            });
                            console.error(error);
                        }
                        //Fin
                    });

                });
            }
            // Fin

            // consulta y carga de visualizar de facturas en transporte
            //consultar campo estado anticipo de momento esta quemado, ya que la consulta solo esta trallendo los que estan en ANTICIPO_COMPL_NUEVO
            let obtener_dataTransporte = function(tbody, table) {
                $(tbody).on("click", "button.verT", function() {

                    // Activar el spiner de cargar al momento de visualizar la factura
                    InvoiceHelpers.setTransportModalLoading();
                    swal.close();
                    $('#exampleModalTransporte').modal('show');
                    //Fin

                    // Cargamos los datos de la factura al modal
                    let invoice = table.getRowData($(this).parents("tr"));
                    plantillaDate = '';
                    plantillarow1 = '';
                    plantillarow2 = '';
                    plantillarow3 = '';
                    plantillarow4 = '';
                    plantillarow5 = '';

                    $.ajax({
                        type: "POST",
                        url: "{{ route('falturas.transporte.detalle') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            invoice: invoice.shipmentXid
                        },
                        success: function(response) {
                            if (response.success !== true) {
                                swal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message ||
                                        'No fue posible cargar el detalle del manifiesto',
                                });
                                return;
                            }

                            let invoice = response.data
                            if (!invoice || (typeof invoice === 'object' && Object.keys(invoice)
                                    .length === 0)) {
                                swal.close();
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Sin datos',
                                    text: 'No se encontró información del manifiesto.',
                                });
                                return;
                            }

                            const traducciones = {
                                'TCL.ENROUTE_COMPLETED': 'COMPLETADO',
                                'TCL.ENROUTE_DELAYED': 'RETRASADO',
                                'TCL.ENROUTE_DIVERTED': 'DESVIADO',
                                'TCL.ENROUTE_ENROUTE': 'EN RUTA/EN TRÁNSITO',
                                'TCL.ENROUTE_MERGED': 'COMBINADO',
                                'TCL.ENROUTE_NOT STARTED': 'SIN SALIDA',
                                'TCL.ENROUTE_PARTIAL': 'PARCIAL',
                                'TCL.ENROUTE_UNLOADED - FULL': 'DESCARGADO LLENO',
                                'TCL.ENROUTE_UNLOADED - PARTIAL': 'DESCARGADO PARCIAL',
                            }

                            function traducir(dato) {
                                return traducciones[dato] || dato;
                            }

                            if (response.success == true) {
                                $('#date_1').html('')
                                plantillaDate = `
                                <div class="col-md-4 align-self-center">
                                    <img src="{{ asset('assets/images/logos-tractocar/TCL_POS_CMYK-01.png') }}" alt="logo-small" class="logo-sm mr-2" height="56">
                                    {{-- <img src="{{asset('assets/images/logos-tractocar/negative-blue-tiny.png')}}" alt="logo-large" class="logo-lg logo-light" height="16"> --}}
                                </div><!--end col-->
                                </div><!--end col-->
                                <div class="col-md-4 ms-auto">
                                    <ul class="list-inline mb-0 contact-detail float-right" >
                                        <li class="list-inline-item">
                                            <div class="pl-3">
                                                <h6 class="mb-0"><b>Fecha de creación del Manifiesto : ${InvoiceHelpers.formatDateValue(invoice.MANIFEST_CREATE_DATE)}</b> </h6>
                                                <h6><b>Numero del Manifiesto:</b> # ${InvoiceHelpers.safeText(invoice.MANIFEST_ID)}</h6>
                                            </div>
                                        </li>
                                    </ul>
                                </div><!--end col-->
                            `
                                $('#date_1').append(plantillaDate)

                                $('#row1_1').html('')
                                plantillarow1 = `
                                <tr>
                                    <td>${ InvoiceHelpers.safeText(invoice.OWNER_ID) }</td>
                                    <td>${ InvoiceHelpers.safeText(invoice.OWNER_NAME) }</td>
                                </tr>
                                `
                                $('#row1_1').append(plantillarow1)

                                $('#row2_2').html('')
                                const driverName = [invoice.DRIVER_FIRSTNAME, invoice.DRIVER_LASTNAME]
                                    .filter(Boolean).join(' ');
                                plantillarow2 = `
                                <tr>
                                    <td>${ InvoiceHelpers.safeText(driverName) }</td>
                                    <td>${ InvoiceHelpers.safeText(invoice.DRIVER_ID) }</td>
                                    <td>${ InvoiceHelpers.safeText(invoice.DRIVER_MOBILE_NUMBER) }</td>
                                </tr>
                                `
                                $('#row2_2').append(plantillarow2)

                                $('#row3_3').html('')
                                plantillarow3 = `
                                <tr>
                                    <td>${ InvoiceHelpers.safeText(invoice.MANIFEST_OPERATION_TYPE) }</td>

                                    <td>${ InvoiceHelpers.safeText(traducir(invoice.SHIPMENT_STATUS)) }</td>
                                    <td> SIN ENTREGAR </td>
                                </tr>
                                `
                                $('#row3_3').append(plantillarow3)

                                $('#row4_4').html('')
                                plantillarow4 = `
                                <tr>
                                    <td> ${ InvoiceHelpers.safeText(invoice.ORIGIN_CITY) } </td>
                                    <td> ${ InvoiceHelpers.safeText(invoice.ORIGIN_PROVINCE) } </td>
                                    <td> ${ InvoiceHelpers.safeText(invoice.ORIGIN_ADDRESS) } </td>
                                    <td> ${ InvoiceHelpers.safeText(invoice.ROUTE_NAME) } </td>
                                    <td> ${ InvoiceHelpers.safeText(invoice.ROUTE_VIA) } </td>
                                    <td> ${ InvoiceHelpers.safeText(invoice.DESTINATION_CITY) } </td>
                                    <td> ${ InvoiceHelpers.safeText(invoice.DESTINATION_PROVINCE) } </td>
                                    <td> ${ InvoiceHelpers.safeText(invoice.DESTINATION_ADDRESS) } </td>
                                </tr>
                                `
                                $('#row4_4').append(plantillarow4)

                                $('#row5_5').html('')
                                plantillarow5 = `
                                <tr>
                                    <td> ${ InvoiceHelpers.safeText(invoice.VEHICLE_LICENSE_PLATE) } </td>
                                    <td> ${  InvoiceHelpers.safeText(invoice.VEHICLE_MAKE) } </td>
                                    <td> ${ InvoiceHelpers.safeText(invoice.VEHICLE_COLOR) } </td>
                                    <td> ${ InvoiceHelpers.safeText(invoice.VEHICLE_MODEL) } </td>
                                    <td> ${ InvoiceHelpers.safeText(invoice.VEHICLE_TRAILER_NUMBER) } </td>
                                </tr>
                                `
                                $('#row5_5').append(plantillarow5)

                            }
                            swal.close();
                            $('#exampleModalTransporte').modal('show');
                        },
                        error: function(error) {
                            swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: (error.responseJSON && error.responseJSON.message) ? error
                                    .responseJSON.message : 'Algo falló con la respuesta',
                            });
                            console.error(error);
                        }
                        //Fin
                    });

                });
            }
            // Fin
        </script>
    @endcan

    {{-- Consultor --}}
    @can('/facturasGeneral')
        <script>
            // Funccion de consulta validaciones y carga de datos Datatable
            let LoadDataAll = function(table, form) {
                tblColectionData = $(table).DataTable({

                    retrieve: true,

                    dom: 'Bfrtip',
                    "buttons": [{
                        extend: 'collection',
                        text: 'Exportar',
                        buttons: [{
                                extend: 'excel',
                                className: 'btn',
                                text: "Excel",
                                exportOptions: {
                                    columns: ":not(.no-exportar)"
                                }
                            },
                            {
                                extend: 'csv',
                                className: 'btn',
                                text: "CSV",
                                exportOptions: {
                                    columns: ":not(.no-exportar)"
                                }
                            },
                            {
                                extend: 'pdf',
                                className: 'btn',
                                text: "PDF",
                                exportOptions: {
                                    columns: ":not(.no-exportar)"
                                }
                            },
                            {
                                extend: 'print',
                                className: 'btn',
                                text: "Imprimir",
                                exportOptions: {
                                    columns: ":not(.no-exportar)"
                                }
                            },
                        ],
                    }],

                    language: {
                        "sProcessing": "Procesando...",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",

                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },

                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }

                    },

                    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        if (aData.InvoiceAmount < 0) {
                            $('td:eq(2)', nRow).css('background-color', '#ed5c42');
                        }
                        if (aData.invoiceInstallments[0].UnpaidAmount < 0) {
                            $('td:eq(3)', nRow).css('background-color', '#ed5c42');
                        }
                        if (aData.AmountPaid < 0) {
                            $('td:eq(4)', nRow).css('background-color', '#ed5c42');
                        }
                    },

                    columns: [{
                            title: "Accion",
                            data: null,
                            defaultContent: "<button type='button' class='verY btn-icon-action' aria-label='Ver factura' title='Ver factura'><i class='fa fa-eye' aria-hidden='true'></i></button>"
                        },
                        {
                            title: "ID Factura",
                            data: "InvoiceId"
                        },
                        {
                            title: "Numero Factura",
                            data: "InvoiceNumber"
                        },
                        {
                            title: "Valor Factura",
                            data: function(d) {

                                const formatterDolar = new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'USD',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })
                                // if(d.InvoiceAmount < 0){
                                //     let valor = formatterDolar.format(d.InvoiceAmount);
                                //     return valor.style.color = "red";
                                // }
                                return formatterDolar.format(d.InvoiceAmount);
                            }
                        },
                        // {title: "Descripción", data: "Description" },
                        {
                            title: "Saldo",
                            data: function(d) {

                                const formatterDolar = new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'USD',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })

                                return formatterDolar.format(d.invoiceInstallments[0]["UnpaidAmount"]);
                            }
                        },
                        // {title: "ValidationStatus", data: "ValidationStatus"},

                        {
                            title: "Monto Pagado",
                            data: function(d) {
                                const formatterDolar = new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'USD',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })

                                return formatterDolar.format(d.AmountPaid);

                            }
                        },

                        // {title: "Cuenta bancaria",
                        //     data: function ( d ) {
                        //         return d.invoiceInstallments[0]["BankAccount"]}
                        // },
                        {
                            title: "Estado Pago",
                            data: function(d) {

                                // create a new `Date` object
                                var today = new Date();

                                // `getDate()` returns the day of the month (from 1 to 31)
                                var day = today.getDate();

                                // `getMonth()` returns the month (from 0 to 11)
                                var month = today.getMonth() + 1;

                                // `getFullYear()` returns the full year
                                var year = today.getFullYear();

                                var date1 = new Date(d.invoiceInstallments[0]["DueDate"]);
                                var date2 = new Date(`${year}-${month}-${day}`);
                                var dateDefined = date1 - date2;
                                var dias = dateDefined / (1000 * 60 * 60 * 24);
                                if (d.CanceledFlag == true) {
                                    return 'Cancelada'
                                }
                                if (dias <= 0 && d.PaidStatus != 'Pagadas') {
                                    return '<span class="status-badge status-pending">Pendiente de pago</span>';
                                }
                                if (d.PaidStatus == 'Pagadas') {
                                    return '<span class="status-badge status-paid">Pagada</span>';
                                }
                                var Ndias = Math.trunc(dias)
                                return '<span class="status-badge status-scheduled">El pago se le generara dentro de ' +
                                    Ndias + ' Dias</span>';
                            }
                        },

                        {
                            title: "Fecha Factura",
                            data: "InvoiceDate"
                        },

                        // {title: "Tipo de Factura", data: "InvoiceType" },
                        // {title: "Pago realizado", data: "AccountingDate" }

                    ],

                    columnDefs: [{
                            responsivePriority: 1,
                            targets: 0
                        },
                        {
                            responsivePriority: 1,
                            targets: 1
                        },
                        {
                            responsivePriority: 1,
                            targets: 2
                        },
                        {
                            responsivePriority: 1,
                            targets: 3
                        },
                        {
                            responsivePriority: 1,
                            targets: 4
                        },
                        {
                            responsivePriority: 1,
                            targets: 5
                        },
                        // { responsivePriority: 1, targets: 6 },
                    ],

                });
                tblColectionData.column(1).visible(false);
                $.ajax({
                    type: $(form).attr('method'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: $(form).attr('action'),
                    data: $(form).serialize(),
                    success: function(response) {
                        let datos = response.data;
                        // var invoiceInstallments = datos[0].invoiceInstallments;
                        if (response.success == true) {

                            tblColectionData.clear().draw();
                            tblColectionData.rows.add(datos).draw();
                            // validacionButton(Card);

                            swal.close();
                        } else {
                            swal.close();
                            Loader();
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: datos,
                            })
                        }
                    },
                    error: function(error) {
                        swal.close();
                        Loader();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Algo fallo con la respuesta!',
                        })
                        console.error(error);
                    }
                });
            }
            // Fin

            function mayus(e) {
                e.value = e.value.toUpperCase();
            }
            // load inicial, se visualiza al seleccionar un opcion de las facturas
            let Loader = function(cant) {
                Swal.fire({
                    title: 'Cargando las ' + cant + ' facturas mas recientes!',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                    },
                })
            }
            // Fin

            // load secundario, se visualiza al momento pasas de una opcion de facturas a otro siempre y cuando se estan visualizando la tabla de facturas
            let LoaderView = function() {
                Swal.fire({
                    title: 'Cargando visualización de la factura!',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                    },
                })
            }
            // Fin

            $("#closet-modal").click(function(e) {
                $("#global-loader3").modal('hide'); //ocultamos el modal
            });

            $('#customer-code').select2({
                placeholder: "Buscar un cliente en OTM",
                minimumInputLength: 3,
                ajax: {
                    url: "{{ route('selectSupplier.number') }}",
                    dataType: 'json',
                    delay: 300,
                    data: function(term, page) {
                        cadena = term.toUpperCase();
                        return {
                            q: cadena
                        };
                    },
                    results: function(data) {

                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.Supplier,
                                    id: item.SupplierNumber
                                }
                            })
                        };
                    },
                    cache: false
                }
            });


            $(document).on("submit", "#filter", function(e) {
                e.preventDefault(); //detemos el formluario

                let InvoiceLimit = document.getElementById('InvoiceLimit').value

                if (InvoiceLimit > 20) {
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                        title: 'Advertencia',
                        text: "Tenga en cuanta que al aumentar el rango de carga de facturas la respuesta demorara un poco más.!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, Entiendo',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {

                            Loader(InvoiceLimit);
                            LoadDataAll('#TablaFullFacturasAll', '#filter');
                            tblColectionData.clear().draw();
                            obtener_data("#TablaFullFacturasAll tbody", tblColectionData);

                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            swalWithBootstrapButtons.fire(
                                'Consulta Cancelada'
                            )
                        }
                    })
                } else if (InvoiceLimit == 20) {
                    Loader(InvoiceLimit);
                    LoadDataAll('#TablaFullFacturasAll', '#filter');
                    tblColectionData.clear().draw();
                    obtener_data("#TablaFullFacturasAll tbody", tblColectionData);

                }
            });

            // consulta y carga de visualizar de facturas individuales
            let obtener_data = function(tbody, table) {
                $(tbody).on("click", "button.verY", function(e) {

                    // Activar el spiner de cargar al momento de visualizar la factura
                    // document.getElementById("global-loader3").style.display = "";
                    InvoiceHelpers.setInvoiceModalLoading();
                    swal.close();
                    $('#exampleModalToggle').modal('show');
                    //Fin

                    // Cargamos los datos de la factura al modal
                    let invoice = table.row($(this).parents("tr")).data();
                    plantillaDate = '';
                    plantiilabody = '';
                    plantillarow1 = '';
                    plantillarow2 = '';
                    plantillarow3 = '';

                    $.ajax({
                        type: "POST",
                        url: "{{ route('invoice.lines') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            InvoiceId: invoice.InvoiceId
                        },
                        success: function(response) {
                            // console.log(response.data);
                            InvoiceHelpers.removeInvoiceLoading();
                            let invoice = response.data.invoiceData[0]
                            let lines = Array.isArray(response.data.invoiceLines) ? response.data
                                .invoiceLines : []
                            let fPago = response.data.invoiceFechaPago && response.data
                                .invoiceFechaPago[0] ?
                                response.data.invoiceFechaPago[0]['PaymentDate'] :
                                null
                            const rawHolds = response.data.holds || [];
                            let holds = [];
                            if (Array.isArray(rawHolds)) {
                                holds = Array.isArray(rawHolds[0]) ? rawHolds[0] : rawHolds;
                            }

                            let InvoiceAmount = InvoiceHelpers.formatCurrency(invoice.InvoiceAmount,
                                'USD');

                            if (response.success == true) {
                                $('#date').html('')
                                plantillaDate = `
                                        <div class="col-md-4 align-self-center">
                                            <img src="{{ asset('assets/images/logos-tractocar/TCL_POS_CMYK-01.png') }}" alt="logo-small" class="logo-sm mr-2" height="56">
                                            {{-- <img src="{{asset('assets/images/logos-tractocar/negative-blue-tiny.png')}}" alt="logo-large" class="logo-lg logo-light" height="16"> --}}
                                            <p class="mt-2 mb-0 text-muted">@lang('locale.Description') : ${ InvoiceHelpers.safeText(invoice.Description) }.</p>                                                             </div><!--end col-->
                                        </div><!--end col-->
                                        <div class="col-md-4 ms-auto">
                                            <ul class="list-inline mb-0 contact-detail float-right">
                                                <li class="list-inline-item">
                                                    <div class="pl-3">
                                                        <h6 class="mb-0"><b>@lang('locale.Supplier') : </b>${InvoiceHelpers.safeText(invoice.Supplier)} </h6>
                                                    </div>
                                                </li>
                                                <li class="list-inline-item">
                                                    <div class="pl-3">
                                                        <h6 class="mb-0"><b>@lang('locale.Invoice Number') : </b>${InvoiceHelpers.safeText(invoice.InvoiceNumber)} </h6>
                                                    </div>
                                                </li>
                                                <li class="list-inline-item">
                                                    <div class="pl-3">
                                                        <h6 class="mb-0"><b>@lang('locale.Invoice Date') : </b>${InvoiceHelpers.formatDateValue(invoice.InvoiceDate)} </h6>
                                                    </div>
                                                </li>
                                                <li class="list-inline-item">
                                                    <div class="pl-3">
                                                        <h5><i class="mdi mdi-cash-multiple"></i><b> :</b> ${ InvoiceAmount }</h5>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div><!--end col-->

                                    `
                                $('#date').append(plantillaDate)

                                if (invoice.CanceledFlag == 1) {
                                    $('#body').html('')
                                    plantiilabody = `
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <strong>@lang('locale.Canceled')!</strong> @lang('locale.The invoice has been canceled').
                                                </div>
                                        `
                                    $('#body').append(plantiilabody)
                                }

                                $('#row1').html('')
                                plantillarow1 = `

                                        <tr>
                                            <td >
                                                <p class="mb-0 text-muted">${ InvoiceHelpers.safeText(invoice.InvoiceType) }</p>
                                            </td>
                                            <td >
                                                <p class="mb-0 text-muted">${ InvoiceHelpers.safeText(invoice.PaidStatus) }</p>
                                            </td>
                                            <td >
                                                <p class="mb-0 text-muted">${ InvoiceHelpers.safeText(invoice.PaymentMethod) }</p>
                                            </td>
                                            <td >
                                                <p class="mb-0 text-muted">${ InvoiceHelpers.safeText(invoice.ValidationStatus) }</p>
                                            </td>
                                            <td >
                                                <p class="mb-0 text-muted">${ InvoiceHelpers.safeText(invoice.invoiceInstallments && invoice.invoiceInstallments[0] ? invoice.invoiceInstallments[0]['BankAccount'] : null) }</p>
                                            </td>
                                            <td >
                                                <p class="mb-0 text-muted">${ InvoiceHelpers.formatDateValue(invoice.AccountingDate) }</p>
                                            </td>
                                            <td >
                                                <p class="mb-0 text-muted">${ InvoiceHelpers.formatDateValue(invoice.invoiceInstallments && invoice.invoiceInstallments[0] ? invoice.invoiceInstallments[0]['DueDate'] : null) }</p>
                                            </td>
                                            <td >
                                                <p class="mb-0 text-muted">${ InvoiceHelpers.formatDateValue(fPago) }</p>
                                            </td>
                                        </tr><!--end tr-->
                                    `
                                $('#row1').append(plantillarow1)

                                $('#row2').html('')
                                let hasLines = false;
                                lines.forEach(line => {
                                    var LineAmount = InvoiceHelpers.formatCurrency(line
                                        .LineAmount, 'USD');
                                    if (line.LineAmount != 0) {
                                        hasLines = true;
                                        plantillarow2 = `
                                                <tr>
                                                    <td >
                                                        <h5 class="mt-0 mb-1">${ InvoiceHelpers.safeText(line.LineType) }</h5>
                                                        <p class="mb-0 text-muted">${ InvoiceHelpers.safeText(line.Description) }.</p>
                                                    </td>
                                                    <td> ${ LineAmount }</td>
                                                </tr><!--end tr-->
                                            `
                                        $('#row2').append(plantillarow2)
                                    }
                                });

                                if (!hasLines) {
                                    $('#row2').append(`
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Sin detalles de la factura.</td>
                                        </tr>
                                    `)
                                }

                                $('#row3').html('')
                                if (Array.isArray(holds) && holds.length) {
                                    holds.forEach(hold => {
                                        const date = InvoiceHelpers.formatDateValue(hold
                                            .HoldDate);

                                        plantillarow3 = `
                                            <tr>
                                                <td >${ InvoiceHelpers.safeText(hold.HoldName) }</td>
                                                <td> ${ InvoiceHelpers.safeText(hold.HoldReason) }</td>
                                                <td> ${ InvoiceHelpers.safeText(hold.HeldBy) }</td>
                                                <td> ${ date }</td>
                                            </tr><!--end tr-->
                                        `
                                        $('#row3').append(plantillarow3)
                                    });
                                } else {
                                    $('#row3').append(`
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Sin bloqueos registrados.</td>
                                        </tr>
                                    `)
                                }

                            }
                            swal.close();

                            $('#exampleModalToggle').modal('show');
                        },
                        error: function(error) {
                            InvoiceHelpers.removeInvoiceLoading();
                            console.error(error);
                        }
                        //Fin
                    });

                });
            }
            // Fin
        </script>
    @endcan
@endsection

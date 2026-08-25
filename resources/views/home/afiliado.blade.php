@extends('layouts.app')

@section('content')

    <body class="ltr app sidebar-mini">
        <div class="page">
            <div class="page-main">
                <div class="side-app">
                    <div class="main-container container-fluid">
                        <div id="global-loader2">
                            <img src={{ asset('assets/images/loader.svg') }} class="loader-img" alt="Loader">
                        </div>

                        @include('home.partials.invoice-styles')

                        {{-- Card de valor/cantidad de facturas --}}
                        <div class="row g-3">

                            <div class="col-lg-3 col-sm-6 col-md-6">
                                <div class="card stat-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div class="stat-icon stat-icon-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M19.5,7H16V5.9169922c0-2.2091064-1.7908325-4-4-4s-4,1.7908936-4,4V7H4.5C4.4998169,7,4.4996338,7,4.4993896,7C4.2234497,7.0001831,3.9998169,7.223999,4,7.5V19c0.0018311,1.6561279,1.3438721,2.9981689,3,3h10c1.6561279-0.0018311,2.9981689-1.3438721,3-3V7.5c0-0.0001831,0-0.0003662,0-0.0006104C19.9998169,7.2234497,19.776001,6.9998169,19.5,7z M9,5.9169922c0-1.6568604,1.3431396-3,3-3s3,1.3431396,3,3V7H9V5.9169922z M19,19c-0.0014038,1.1040039-0.8959961,1.9985962-2,2H7c-1.1040039-0.0014038-1.9985962-0.8959961-2-2V8h3v2.5C8,10.776123,8.223877,11,8.5,11S9,10.776123,9,10.5V8h6v2.5c0,0.0001831,0,0.0003662,0,0.0005493C15.0001831,10.7765503,15.223999,11.0001831,15.5,11c0.0001831,0,0.0003662,0,0.0006104,0C15.7765503,10.9998169,16.0001831,10.776001,16,10.5V8h3V19z" />
                                                </svg>
                                            </div>
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

                            <div class="col-lg-3 col-sm-6 col-md-6">
                                <div class="card stat-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div class="stat-icon stat-icon-dark">
                                                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M9,10h2.5c0.276123,0,0.5-0.223877,0.5-0.5S11.776123,9,11.5,9H10V8c0-0.276123-0.223877-0.5-0.5-0.5S9,7.723877,9,8v1c-1.1045532,0-2,0.8954468-2,2s0.8954468,2,2,2h1c0.5523071,0,1,0.4476929,1,1s-0.4476929,1-1,1H7.5C7.223877,15,7,15.223877,7,15.5S7.223877,16,7.5,16H9v1.0005493C9.0001831,17.2765503,9.223999,17.5001831,9.5,17.5h0.0006104C9.7765503,17.4998169,10.0001831,17.276001,10,17v-1c1.1045532,0,2-0.8954468,2-2s-0.8954468-2-2-2H9c-0.5523071,0-1-0.4476929-1-1S8.4476929,10,9,10z M21.5,12H17V2.5c0.000061-0.0875244-0.0228882-0.1735229-0.0665283-0.2493896c-0.1375732-0.2393188-0.4431152-0.3217773-0.6824951-0.1842041l-3.2460327,1.8603516L9.7481079,2.0654297c-0.1536865-0.0878906-0.3424072-0.0878906-0.4960938,0l-3.256897,1.8613281L2.7490234,2.0664062C2.6731567,2.0227661,2.5871582,1.9998779,2.4996338,1.9998779C2.2235718,2.000061,1.9998779,2.223938,2,2.5v17c0.0012817,1.380188,1.119812,2.4987183,2.5,2.5H19c1.6561279-0.0018311,2.9981689-1.3438721,3-3v-6.5006104C21.9998169,12.2234497,21.776001,11.9998169,21.5,12z M4.5,21c-0.828064-0.0009155-1.4990845-0.671936-1.5-1.5V3.3623047l2.7412109,1.5712891c0.1575928,0.0872192,0.348877,0.0875854,0.5068359,0.0009766L9.5,3.0761719l3.2519531,1.8583984c0.157959,0.0866089,0.3492432,0.0862427,0.5068359-0.0009766L16,3.3623047V19c0.0008545,0.7719116,0.3010864,1.4684448,0.7803345,2H4.5z M21,19c0,1.1045532-0.8954468,2-2,2s-2-0.8954468-2-2v-6h4V19z" />
                                                </svg>
                                            </div>
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

                            <div class="col-lg-3 col-sm-6 col-md-6">
                                <div class="card stat-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div class="stat-icon stat-icon-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2zm1 15h-2v-2h2zm0-4h-2V7h2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <h3 id="mtNovedad" class="stat-value">
                                            <div class="spinner-grow text-success" role="status" id="piner2">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </h3>
                                        <p class="stat-label">Monto Facturas Pagadas Parcialmente</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6 col-md-6">
                                <div class="card stat-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div class="stat-icon stat-icon-dark">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path
                                                        d="M20 6h-4V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2H4a1 1 0 0 0 0 2h1v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h1a1 1 0 0 0 0-2zM10 4h4v2h-4zm7 16H7V8h10z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <h3 id="totalNovedad" class="stat-value">
                                            <div class="spinner-grow text-success" role="status" id="piner3">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </h3>
                                        <p class="stat-label">Facturas Pagadas Parcialmente</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {{-- Fin --}}

                        {{-- Pestanas principales de facturas (selector unificado tipo pill) --}}
                        <ul class="nav nav-pills invoice-main-tabs" id="invoiceMainTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link invoice-main-tab active" id="pill-por-pagar"
                                    data-filter="por-pagar" data-bs-toggle="tooltip"
                                    data-bs-original-title="Facturas por pagar">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    Por pagar
                                    <span class="invoice-main-tab-count" id="mainTabCountPorPagar">0</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link invoice-main-tab" id="pill-en-transporte"
                                    data-filter="en-transporte" data-bs-toggle="tooltip"
                                    data-bs-original-title="Facturas en transporte">
                                    <i class="fa fa-truck" aria-hidden="true"></i>
                                    En transporte
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link invoice-main-tab" id="pill-pagadas-con-novedad"
                                    data-filter="pagadas-con-novedad" data-bs-toggle="tooltip"
                                    data-bs-original-title="Facturas pagadas parcialmente">
                                    <i class="fa fa-money" aria-hidden="true"></i>
                                    Pagadas parcialmente
                                    <span class="invoice-main-tab-count" id="mainTabCountNovedad">0</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link invoice-main-tab" id="pill-todas"
                                    data-filter="todas" data-bs-toggle="tooltip"
                                    data-bs-original-title="Todas las facturas">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    Buscar facturas
                                </button>
                            </li>
                        </ul>
                        {{-- Fin --}}

                        {{-- Card unico de facturas/envios paginado --}}
                        <div class="card invoice-table-card" id="InvoiceCardsSection">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h3 class="mb-0" id="invoiceCardsTitle">Facturas por pagar</h3>
                                <button class="btn invoice-filter-toggle" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseInvoiceFilters" aria-expanded="false"
                                    aria-controls="collapseInvoiceFilters">
                                    <i class="fa fa-sliders" aria-hidden="true"></i>
                                    Filtros
                                </button>
                            </div>
                            <div class="collapse" id="collapseInvoiceFilters">
                                <div class="invoice-filter-panel">
                                    <div class="row g-3">
                                        <div class="col-md-2">
                                            <label for="RecordsLimit" class="form-label">
                                                <i class="fa fa-list-ol" aria-hidden="true"></i>
                                                # a visualizar por página</label>
                                            <select name="RecordsLimit" id="RecordsLimit" class="form-select"
                                                tabindex="3" value="{{ old('RecordsLimit') }}">
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
                                        <div class="col-md-2 invoice-filter-erp" id="groupTipoFactura">
                                            <label for="tipoFactura" class="form-label">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                                Tipo de factura</label>
                                            <select name="tipoFactura" id="tipoFactura" class="form-select"
                                                tabindex="3" value="{{ old('tipoFactura') }}">
                                                <option selected value="">Todos</option>
                                                <option value="Adelanto">Anticipo</option>
                                                <option value="Estándar">Estándar</option>
                                                <option value="Nota de crédito">Nota Crédito</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 invoice-filter-erp" id="groupValidationStatus">
                                            <label for="ValidationStatus" class="form-label">
                                                <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                                Estado validación</label>
                                            <select name="ValidationStatus" id="ValidationStatus" class="form-select"
                                                tabindex="3" value="{{ old('ValidationStatus') }}">
                                                <option selected value="">Todos</option>
                                                <option value="Cancelada">Cancelada</option>
                                                <option value="Validada">Validada</option>
                                                <option value="Necesita revalidación">Necesita revalidación</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 invoice-filter-erp" id="groupCanceledFlag">
                                            <label for="CanceledFlag" class="form-label">
                                                <i class="fa fa-ban" aria-hidden="true"></i>
                                                Canceladas</label>
                                            <select name="CanceledFlag" id="CanceledFlag" class="form-select"
                                                tabindex="3" value="{{ old('CanceledFlag') }}">
                                                <option selected value="false">No</option>
                                                <option value="true">Si</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 invoice-filter-erp" id="groupDateRange">
                                            <label class="form-label">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                                Rango de fechas</label>
                                            <div class="input-group">
                                                <input name="startDate" id="startDate" class="form-control"
                                                    placeholder="YYYY-MM-DD" data-mask="yyyy-mm-dd" tabindex="3"
                                                    value="{{ old('startDate') }}"
                                                    onKeyUp="validateDate('startDate','btnAplicarFiltros');">
                                                <span class="input-group-text">a</span>
                                                <input name="endDate" id="endDate" placeholder="YYYY-MM-DD"
                                                    data-mask="yyyy-mm-dd" class="form-control" tabindex="3"
                                                    onKeyUp="validateDate('endDate','btnAplicarFiltros');"
                                                    value="{{ old('endDate') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invoice-filter-actions">
                                        <button type="submit" class="btn btn-primary" id="btnAplicarFiltros">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                            Filtrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3" id="invoiceCardsGrid"></div>
                                <div class="invoice-cards-empty text-center text-muted py-5" id="invoiceCardsEmpty"
                                    style="display:none;">
                                    <i class="fa fa-inbox fa-2x mb-2" aria-hidden="true"></i>
                                    <p class="mb-0">No se encontraron resultados.</p>
                                </div>
                                <nav aria-label="Paginación de facturas" class="mt-3">
                                    <ul class="pagination justify-content-center" id="invoiceCardsPagination"></ul>
                                </nav>
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
                                                        <div class="nav invoice-tabs" id="invoiceModalTabs"
                                                            role="tablist">
                                                            <button type="button" class="invoice-tab active"
                                                                data-bs-toggle="tab"
                                                                data-bs-target="#tab-general">General</button>
                                                            <button type="button" class="invoice-tab"
                                                                data-bs-toggle="tab"
                                                                data-bs-target="#tab-detalle">Detalle</button>
                                                            <button type="button" class="invoice-tab"
                                                                id="BloqueosTabItem" data-bs-toggle="tab"
                                                                data-bs-target="#tab-bloqueos" style="display:none;">⚠
                                                                Bloqueos</button>
                                                        </div>

                                                        <div class="tab-content">
                                                            <div class="tab-pane fade show active" id="tab-general">
                                                                <div class="invoice-summary-grid" id="row1">

                                                                </div>
                                                                <div class="p-4 pt-0">
                                                                    <div class="invoice-totals" id="invoiceTotals">

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="tab-detalle">
                                                                <div class="p-4">
                                                                    <table class="invoice-lines">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Descripción</th>
                                                                                <th>Monto</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="row2">

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="tab-bloqueos">
                                                                <div class="px-4 pb-2" id="Bloqueos">
                                                                    <h4 class="invoice-holds-title">⚠ Retenciones /
                                                                        bloqueos</h4>
                                                                    <div id="row3"></div>
                                                                </div>
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
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection
@section('scripts')
    <script src="https://momentjs.com/downloads/moment.min.js"></script>
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
        window.AfiliadoConfig = {
            userId: {{ Auth::user()->id }},
            supplierNumber: {{ $SupplierNumber ?? 'null' }},
            numberId: {{ $number_id ?? 'null' }},
            logoUrl: "{{ asset('assets/images/logos-tractocar/TCL_POS_CMYK-01.png') }}",
            routes: {
                supplierNumber: "{{ route('supplier.number') }}",
                total: "{{ route('invoices.totalsByStatus') }}",
                searchInvoices: "{{ route('invoices.search') }}",
                facturasTransporte: "{{ route('facturas.transporte') }}",
                invoiceLines: "{{ route('invoice.lines') }}",
                facturasTransporteDetalle: "{{ route('facturas.transporte.detalle') }}",
                selectSupplierNumber: "{{ route('selectSupplier.number') }}",
            },
            lang: {
                supplier: @json(__('locale.Supplier')),
                invoiceNumber: @json(__('locale.Invoice Number')),
                canceled: @json(__('locale.Canceled')),
                invoiceCanceledMsg: @json(__('locale.The invoice has been canceled')),
            }
        };
    </script>
    <script src="{{ asset('views/js/home/afiliado-dashboard.js') }}?v={{ time() }}"></script>
@endsection

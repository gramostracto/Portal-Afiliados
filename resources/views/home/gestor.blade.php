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

                        {{-- Card "Todas las facturas" (todos los proveedores) --}}
                        <div class="card invoice-table-card" id="facturas-all-gestor">
                            <div class="card-header">
                                <h3>Todas las facturas</h3>
                            </div>
                            <div class="card">
                                <div class="row">
                                    <button class="btn btn-info dropdown-toggle" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseExampleFilterGestor"
                                        aria-expanded="false" aria-controls="collapseExampleFilterGestor">
                                        Filtros
                                    </button>
                                </div>
                            </div>
                            <div class="card-header border-bottom invoice-filter-panel" id="collapseExampleFilterGestor">
                                <div class="row g-2">
                                    <h3 class="card-title">Fitros</h3>
                                    <div class="form-horizontal">
                                        <div class="gestor-filter-grid">
                                            <div class="gestor-filter-provider">
                                                <label for="supplierNumberFilter" class="form-label">Proveedor</label>
                                                <input type="hidden" id="supplierNumberFilter" name="SupplierNumber" />
                                            </div>
                                            <div class="gestor-filter-limit">
                                                <label for="InvoiceLimit" class="form-label"># Factoras que desea
                                                    visualizar</label>
                                                <select type="text" name="InvoiceLimit" id="InvoiceLimit"
                                                    class="form-control" tabindex="3"
                                                    value="{{ old('InvoiceLimit') }}" autofocus>
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
                                            <div class="gestor-filter-control">
                                                <label for="tipoFactura" class="form-label">Tipo de
                                                    factura</label>
                                                <select type="text" name="tipoFactura" id="tipoFactura"
                                                    class="form-select" tabindex="3"
                                                    value="{{ old('tipoFactura') }}" autofocus>
                                                    <option selected value="">Todos</option>
                                                    <option value="Pago por adelantado">Anticipo</option>
                                                    <option value="Estándar">Estándar</option>
                                                    <option value="Nota de crédito">Nota Crédito</option>
                                                </select>
                                            </div>
                                            <div class="gestor-filter-control">
                                                <label for="ValidationStatus" class="form-label">Estado
                                                    Validación</label>
                                                <select type="text" name="ValidationStatus" id="ValidationStatus"
                                                    class="form-select" tabindex="3"
                                                    value="{{ old('ValidationStatus') }}" autofocus>
                                                    <option selected value="">Todos</option>
                                                    <option value="Cancelada">Cancelada</option>
                                                    <option value="Validada">Validada</option>
                                                    <option value="Necesita revalidación">Necesita revalidación
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="gestor-filter-control">
                                                <label for="PaidStatus" class="form-label">Estado Pago</label>
                                                <select type="text" name="PaidStatus" id="PaidStatus"
                                                    class="form-select" tabindex="3"
                                                    value="{{ old('PaidStatus') }}" autofocus>
                                                    <option selected value="">Todos</option>
                                                    <option value="Pagadas">Pagadas</option>
                                                    <option value="Impagado">Impagado</option>
                                                    <option value="Pagada parcialmente">parcialmente</option>
                                                </select>
                                            </div>
                                            <div class="gestor-filter-control">
                                                <label for="CanceledFlag" class="form-label">Canceladas</label>
                                                <select type="text" name="CanceledFlag" id="CanceledFlag"
                                                    class="form-select" tabindex="3"
                                                    value="{{ old('CanceledFlag') }}" autofocus>
                                                    <option selected value="false">No</option>
                                                    <option value="true">Si</option>
                                                </select>
                                            </div>
                                            <div class="gestor-filter-dates">
                                                <label for="title" class="form-label">Fecha Inicio y
                                                    Fecha Fin</label>
                                                <div class="input-group">
                                                    <input name="startDate" id="startDate" class="form-control"
                                                        placeholder="YYYY-MM-DD" data-mask="yyyy-mm-dd"
                                                        tabindex="3" value="{{ old('startDate') }}"
                                                        onKeyUp="ValidarFecha('startDate','btnPrFiltr');"
                                                        autofocus>
                                                    <input name="endDate" id="endDate" placeholder="YYYY-MM-DD"
                                                        data-mask="yyyy-mm-dd" class="form-control" tabindex="3"
                                                        onKeyUp="ValidarFecha('endDate','btnPrFiltr');"
                                                        value="{{ old('endDate') }}" autofocus>
                                                </div>
                                            </div>
                                            <div class="gestor-filter-action">
                                                <button type="submit" class="btn btn-primary" id="btnPrFiltr">Filtrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-sm">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="scroll-table-toolbar">
                                                    <input type="text" class="scroll-table-search"
                                                        id="searchTablaFacturasGestor" placeholder="Buscar...">
                                                    <button type="button" class="scroll-table-export"
                                                        id="exportTablaFacturasGestor">Exportar CSV</button>
                                                </div>
                                                <div class="table-responsive">
                                                    <table id="TablaFacturasGestor"
                                                        class="table table-bordered text-nowrap key-buttons border-bottom w-100">
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Fin --}}

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
                                                                data-bs-target="#tab-bloqueos"
                                                                style="display:none;">⚠
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
        window.GestorConfig = {
            userId: {{ Auth::user()->id }},
            supplierNumber: null,
            numberId: null,
            logoUrl: "{{ asset('assets/images/logos-tractocar/TCL_POS_CMYK-01.png') }}",
            routes: {
                searchInvoices: "{{ route('invoices.search') }}",
                invoiceLines: "{{ route('invoice.lines') }}",
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
    <script src="{{ asset('views/js/home/gestor-dashboard.js') }}?v={{ time() }}"></script>
@endsection

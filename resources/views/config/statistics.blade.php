@extends('layouts.app')

@section('styles')
<style>
    .stats-header {
        background: linear-gradient(135deg, #f5f7ff 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 16px;
        border: 1px solid #edf0f7;
    }

    .stats-card {
        border: 1px solid #edf0f7;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(31, 45, 61, 0.06);
    }

    .stats-card .card-header {
        border-bottom: 1px solid #edf0f7;
        background-color: #fff;
    }

    .stats-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 999px;
        background: #eef2ff;
        color: #3b5bdb;
        font-weight: 600;
        font-size: 12px;
    }

    .stats-summary-card {
        border-radius: 14px;
        padding: 16px 18px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .stats-summary-card::after {
        content: '';
        position: absolute;
        right: -30px;
        bottom: -30px;
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
    }

    .stats-summary-card h3 {
        font-size: 26px;
        margin-bottom: 4px;
    }

    .stats-summary-card p {
        margin-bottom: 0;
        opacity: 0.9;
    }

    .stats-toolbar {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .stats-table thead th {
        font-weight: 600;
        color: #475467;
        background: #f8fafc;
        border-bottom: none;
    }

    .stats-table tbody tr:hover {
        background: #f5f7ff;
    }

    .stats-chart-box {
        background: #fff;
        border-radius: 12px;
        border: 1px dashed #e5e7eb;
        padding: 12px;
    }
    /* Estilos para Select2 */
    .select2-container .select2-selection--single {
        height: 44px !important;
        border: 1px solid #e1e5ef !important;
        border-radius: 5px !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px !important;
        color: #5b6e88 !important;
        font-size: 14px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
    }

    .select2-container {
        min-height: 44px !important;
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
        padding-left: 8px;
    }

    .select2-container--default {
        width: 100% !important;
    }

    .select2-container--open .select2-dropdown {
        min-width: 100% !important;
    }
    
    .select2-dropdown {
        border: 1px solid #e1e5ef !important;
        border-radius: 5px !important;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #467fcf !important;
    }
    
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #e1e5ef !important;
        border-radius: 5px !important;
    }
</style>
@endsection

@section('disableSelectPlugins', true)

@section('content')
    <body class="ltr app sidebar-mini light-mode">
        <div class="app-content main-content mt-0">
            <div class="side-app">
                <div class="main-container container-fluid">
                    <div class="page-header stats-header">
                        <div>
                            <div class="stats-chip"><i class="fe fe-activity"></i> Panel de uso</div>
                            <h1 class="page-title mt-2 mb-1">Estadísticas</h1>
                            <p class="text-muted mb-0">Visualización de datos de uso del sistema</p>
                        </div>
                        <div class="ms-auto pageheader-btn">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Estadísticas</li>
                            </ol>
                        </div>
                    </div>
                    
                    <!-- Pestañas de navegación -->
                    <div class="card stats-card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="statsTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="general-tab" data-bs-toggle="tab" href="#general" role="tab">Información General</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="user-tab" data-bs-toggle="tab" href="#user" role="tab">Seguimiento por Usuario</a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="card-body">
                            <div class="tab-content" id="statsTabsContent">
                                <!-- Pestaña Información General -->
                                <div class="tab-pane fade show active" id="general" role="tabpanel">
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" id="filterCountLoginDay" method="GET" novalidate>
                                                @csrf
                                                <div class="row g-3 align-items-end stats-toolbar">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Fecha Inicio</label>
                                                        <input name="startDate" id="startDate1" class="form-control js-date" 
                                                            placeholder="YYYY-MM-DD" autocomplete="off" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Fecha Fin</label>
                                                        <input name="endDate" id="endDate1" class="form-control js-date" 
                                                            placeholder="YYYY-MM-DD" autocomplete="off" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Año</label>
                                                        <select class="form-select" name="year" id="yearSelect"></select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="submit" class="btn btn-primary w-100">
                                                            <i class="fe fe-filter"></i> Filtrar
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Tarjetas resumen -->
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <div class="stats-summary-card bg-primary">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h3 class="mb-1 fw-semibold" id="totalLogins">0</h3>
                                                        <p class="mb-0">Total Inicios de Sesión</p>
                                                    </div>
                                                    <i class="fe fe-users fs-1 opacity-50"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="stats-summary-card bg-info">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h3 class="mb-1 fw-semibold" id="activeUsers">0</h3>
                                                        <p class="mb-0">Usuarios Activos</p>
                                                    </div>
                                                    <i class="fe fe-activity fs-1 opacity-50"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="stats-summary-card bg-success">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h3 class="mb-1 fw-semibold" id="invoiceConsultations">0</h3>
                                                        <p class="mb-0">Consultas de Facturas</p>
                                                    </div>
                                                    <i class="fe fe-file-text fs-1 opacity-50"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Gráficos -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card stats-card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Inicios de Sesión por Mes</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="stats-chart-box">
                                                        <div id="loginChart" style="height: 400px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Pestaña Seguimiento por Usuario -->
                                <div class="tab-pane fade" id="user" role="tabpanel">
                                    <div class="row mb-4">
                                        <div class="col-md-8">
                                            <form class="form-horizontal" id="userTrackingForm" method="GET" novalidate>
                                                @csrf
                                                <div class="row g-3 align-items-end stats-toolbar">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Usuario</label>
                                                        {{-- <input type="hidden" class="form-control" id="customerCode" name="numberId"> --}}
                                                                    <select id="customerCode" name="numberId" class="form-control select2" style="width: 100%;"></select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Fecha Inicio</label>
                                                        <input type="text" class="form-control js-date" name="startDateUser" 
                                                            placeholder="YYYY-MM-DD" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Fecha Fin</label>
                                                        <input type="text" class="form-control js-date" name="endDateUser" 
                                                            placeholder="YYYY-MM-DD" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button type="submit" class="btn btn-primary w-100">
                                                            <i class="fe fe-filter"></i> Filtrar
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card stats-card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Actividad del Usuario</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="stats-chart-box">
                                                        <div id="userActivityChart" style="height: 400px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="card stats-card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Últimas Acciones</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover stats-table" id="userActionsTable">
                                                            <thead>
                                                                <tr>
                                                                    <th>Fecha</th>
                                                                    <th>Usuario</th>
                                                                    <th>Acción</th>
                                                                    <th>Detalle</th>
                                                                    <th>IP</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Datos se cargarán via AJAX -->
                                                            </tbody>
                                                        </table>
                                                        <nav class="mt-3" aria-label="Paginación últimas acciones">
                                                            <ul class="pagination justify-content-end" id="userActionsPagination"></ul>
                                                        </nav>
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
@endsection

@section('scripts')
    <!-- Librerías para gráficos -->
    {{-- <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script> --}}

    <script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src={{ asset('anychart-package-8.11.0/js/anychart-bundle.min.js') }}></script>
    <script src={{ asset('anychart-package-8.11.0/js/anychart-base.min.js') }}></script>
    <script src={{ asset('anychart-package-8.11.0/js/anychart-exports.min.js') }}></script>
    <script src={{ asset('anychart-package-8.11.0/js/anychart-ui.min.js') }}></script>
    <script src="{{ asset('views/js/statistics/statistics.js') }}?v={{ time() }}"></script>
    <script>
        let userActionsPage = 1;
        const userActionsPerPage = 10;

        $(document).ready(function() {
            // Verificar que Select2 esté disponible
            let select2Available = typeof $.fn.select2 !== 'undefined';
            if (!select2Available) {
                console.error('Select2 no está cargado');
            }

            // Desactivar selectpicker si está activo en este select
            if ($.fn.selectpicker && $('#customerCode').hasClass('selectpicker')) {
                $('#customerCode').selectpicker('destroy');
            }

            // Inicializar año actual
            const select = document.getElementById('yearSelect');
            const currentYear = new Date().getFullYear();

            const placeholderOption = document.createElement('option');
            placeholderOption.value = '';
            placeholderOption.text = 'Seleccionar año';
            select.appendChild(placeholderOption);

            for (let year = 2023; year <= currentYear; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.text = year;
                if (year === currentYear) {
                    option.selected = true;
                }
                select.appendChild(option);
            }
            
            // Cargar datos iniciales
            loadGeneralStats();
            
            // Variable para controlar si Select2 ya fue inicializado
            let select2Initialized = false;

            function initUserSelect() {
                if (select2Initialized) {
                    return;
                }

                if (select2Available) {
                    console.log('Inicializando Select2 para #customerCode');
                    setTimeout(function() {
                        listAffiliate("{{ route('setting.affiliate') }}");
                        select2Initialized = true;
                        $('#customerCode').select2('open');
                    }, 200);
                } else {
                    console.warn('Select2 no disponible, cargando opciones básicas');
                    $.ajax({
                        url: "{{ route('setting.affiliate') }}",
                        type: "GET",
                        success: function(response) {
                            const escapeHtml = function (value) {
                                return String(value === null || typeof value === 'undefined' ? '' : value)
                                    .replace(/&/g, '&amp;')
                                    .replace(/</g, '&lt;')
                                    .replace(/>/g, '&gt;')
                                    .replace(/"/g, '&quot;')
                                    .replace(/'/g, '&#39;');
                            };
                            let options = '<option value="">Seleccione un usuario</option>';
                            $.each(response, function(index, user) {
                                options += '<option value="' + escapeHtml(user.id) + '">' + escapeHtml(user.name) + ' (' + escapeHtml(user.number_id) + ')</option>';
                            });
                            $('#customerCode').html(options);
                        }
                    });
                }
            }
            
            // Manejar cambio de pestañas
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                if (e.target.id === 'user-tab') {
                    // Limpiar datos anteriores
                    $('#userActivityChart').html('<div class="text-center text-muted py-5">Seleccione un usuario para ver la actividad</div>');
                    $('#userActionsTable tbody').html('<tr><td colspan="4" class="text-center text-muted">Seleccione un usuario para ver las acciones</td></tr>');
                    
                    // Inicializar Select2 solo una vez cuando se muestra la pestaña
                    initUserSelect();
                }
            });

            // Inicializar al enfocar el select si aún no se hizo
            $('#customerCode').on('focus click', function() {
                initUserSelect();
            });
            
            // Manejar envío de formularios
            $('#filterCountLoginDay').submit(function(e) {
                e.preventDefault();
                loadGeneralStats();
            });

            // Si cambia el año, limpiar fechas y recargar
            $('#yearSelect').on('change', function() {
                $('#startDate1').val('');
                $('#endDate1').val('');
                loadGeneralStats();
            });

            // Si se seleccionan fechas, limpiar año
            $('#startDate1, #endDate1').on('change', function() {
                if ($('#startDate1').val() || $('#endDate1').val()) {
                    $('#yearSelect').val('');
                }
            });
            
            $('#userTrackingForm').submit(function(e) {
                e.preventDefault();
                const startUser = $('input[name="startDateUser"]').val();
                const endUser = $('input[name="endDateUser"]').val();

                if (!startUser || !endUser) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: 'Seleccione fecha inicio y fecha fin.',
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    return;
                }

                // Construir dateRange compatible con backend
                const rangeValue = `${startUser} - ${endUser}`;
                if ($('#userTrackingForm input[name="dateRange"]').length === 0) {
                    $('#userTrackingForm').append('<input type="hidden" name="dateRange">');
                }
                $('#userTrackingForm input[name="dateRange"]').val(rangeValue);

                userActionsPage = 1;
                loadUserActivity();
            });

            if (window.jSuites) {
                document.querySelectorAll('.js-date').forEach(function(el) {
                    jSuites.calendar(el, {
                        format: 'YYYY-MM-DD',
                        time: false
                    });
                });

                // Calendarios para fecha inicio/fin usuario
                document.querySelectorAll('input[name="startDateUser"], input[name="endDateUser"]').forEach(function(el) {
                    jSuites.calendar(el, {
                        format: 'YYYY-MM-DD',
                        time: false
                    });
                });
            }
        });

        // Forzar inicialización al cargar la página si no quedó activa
        window.addEventListener('load', function() {
            if (typeof $.fn.select2 !== 'undefined' && !$('#customerCode').hasClass('select2-hidden-accessible')) {
                console.log('Forzando inicialización de Select2 en window.load');
                listAffiliate("{{ route('setting.affiliate') }}");
            }
        });

        

    function loadGeneralStats() {
        const startDate = $('#startDate1').val();
        const endDate = $('#endDate1').val();
        const year = $('#yearSelect').val();

        if ((startDate && !endDate) || (!startDate && endDate)) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Seleccione ambas fechas para filtrar por rango.',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        if (startDate && endDate && year) {
            Swal.fire({
                icon: 'info',
                title: 'Filtro aplicado',
                text: 'Se usará el año seleccionado y se ignorará el rango de fechas.',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false
            });
            $('#startDate1').val('');
            $('#endDate1').val('');
        }

        $.ajax({
            url: "{{ route('setting.statistics.countLogin') }}",
            type: "GET",
            data: $('#filterCountLoginDay').serialize(),
            success: function(response) {
                
                if (response.success) {
                    // Actualizar tarjetas
                    $('#totalLogins').text(response.data.totalLogins);
                    $('#activeUsers').text(response.data.activeUsers);
                    $('#invoiceConsultations').text(response.data.invoiceConsultations);
                    
                    // Actualizar gráfico
                    updateLoginChart(response.data.loginStats);
                }
            }
        });
    }

    function loadUserActivity() {
        const userId = $('#customerCode').val();
        
        $.ajax({
            url: "{{ route('setting.statistics.filter') }}",
            type: "GET",
            data: $('#userTrackingForm').serialize() + `&page=${userActionsPage}&perPage=${userActionsPerPage}`,
            success: function(response) {
                if (response.success) {
                    updateUserActivityChart(response.data.activityStats);
                    updateUserActionsTable(response.data.recentActions, response.data.pagination);
                }
            },
            error: function(xhr) {
                console.error('Error al cargar actividad del usuario:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar la actividad del usuario',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
    }

    function updateLoginChart(data) {
        if (!data) {
            console.warn('No hay datos para mostrar en el gráfico');
            return;
        }

        anychart.onDocumentReady(function() {
            // Limpiar contenedor
            document.getElementById('loginChart').innerHTML = '';

            let chartData = [];
            if (data.byUser && data.byUser.length) {
                chartData = data.byUser.map((item) => [item.name, item.count]);
            } else if (data.months && data.monthlyCounts) {
                chartData = data.months.map((month, index) => [month, data.monthlyCounts[index]]);
            }

            // Crear gráfico de columnas
            var chart = anychart.column();
            
            // Configurar datos
            chart.data(chartData);
            
            // Activar animación
            chart.animation(true);
            
            // Configurar formato de labels
            chart.yAxis().labels().format('{%Value}{groupsSeparator: }');
            
            // Configurar labels en las barras
            chart.labels()
                .enabled(true)
                .position('center')
                .anchor('center-bottom')
                .format('{%Value}{groupsSeparator: }');
            
            // Configurar tooltip
            chart.tooltip()
                .positionMode('point')
                .position('center-top')
                .anchor('center-bottom')
                .format('Inicios de sesión: {%Value}{groupsSeparator: }');
            
            // Configurar colores
            chart.palette(['#467fcf', '#5eba00', '#f1c40f', '#e74c3c']);
            
            // Configurar interactividad
            chart.interactivity().hoverMode('single');
            
            // Configurar título
            chart.title(false);
            
            // Configurar contenedor
            chart.container('loginChart');
            
            // Dibujar
            chart.draw();
        });
    }

    function initUserActivityChart() {
        // Similar a updateLoginChart pero para actividad de usuario
    }

    function updateUserActivityChart(data) {
        if (!data || !data.labels || !data.data) {
            console.warn('No hay datos de actividad para mostrar');
            $('#userActivityChart').html('<div class="text-center text-muted py-5">No hay datos disponibles para este rango</div>');
            return;
        }

        anychart.onDocumentReady(function() {
            // Limpiar contenedor
            document.getElementById('userActivityChart').innerHTML = '';

            // Preparar datos
            const chartData = data.labels.map((label, index) => {
                return {x: label, value: data.data[index]};
            });

            // Crear gráfico de pie
            var chart = anychart.pie(chartData);
            
            // Activar animación
            chart.animation(true);
            
            // Configurar título
            chart.title(false);
            
            // Configurar labels
            chart.labels()
                .position('outside')
                .format('{%x}: {%value}');
            
            // Configurar tooltip
            chart.tooltip()
                .format(data.mode === 'users' ? 'Acciones: {%value}' : 'Acciones: {%value}');
            
            // Configurar leyenda
            chart.legend()
                .enabled(true)
                .position('right')
                .itemsLayout('vertical');
            
            // Configurar colores
            chart.palette(['#467fcf', '#5eba00', '#f1c40f', '#e74c3c', '#9b59b6', '#1abc9c']);
            
            // Configurar contenedor
            chart.container('userActivityChart');
            
            // Dibujar
            chart.draw();
        });
    }

    function updateUserActionsTable(actions, pagination) {
        const $tbody = $('#userActionsTable tbody');
        $tbody.html('');

        if (!actions || actions.length === 0) {
            $tbody.html('<tr><td colspan="5" class="text-center text-muted">No hay acciones para este rango</td></tr>');
            $('#userActionsPagination').html('');
            return;
        }

        actions.forEach(action => {
            $tbody.append(`
                <tr>
                    <td>${action.date}</td>
                    <td>${action.user || '-'}</td>
                    <td>${action.type}</td>
                    <td>${action.detail}</td>
                    <td>${action.ip}</td>
                </tr>
            `);
        });

        renderUserActionsPagination(pagination);
    }

    function renderUserActionsPagination(pagination) {
        const $pagination = $('#userActionsPagination');
        $pagination.html('');

        if (!pagination || pagination.totalPages <= 1) {
            return;
        }

        const prevDisabled = pagination.page <= 1 ? 'disabled' : '';
        const nextDisabled = pagination.page >= pagination.totalPages ? 'disabled' : '';

        $pagination.append(`
            <li class="page-item ${prevDisabled}">
                <button class="page-link" data-page="${pagination.page - 1}">Anterior</button>
            </li>
        `);

        const maxPagesToShow = 5;
        let start = Math.max(1, pagination.page - Math.floor(maxPagesToShow / 2));
        let end = Math.min(pagination.totalPages, start + maxPagesToShow - 1);
        if (end - start < maxPagesToShow - 1) {
            start = Math.max(1, end - maxPagesToShow + 1);
        }

        for (let page = start; page <= end; page++) {
            const active = page === pagination.page ? 'active' : '';
            $pagination.append(`
                <li class="page-item ${active}">
                    <button class="page-link" data-page="${page}">${page}</button>
                </li>
            `);
        }

        $pagination.append(`
            <li class="page-item ${nextDisabled}">
                <button class="page-link" data-page="${pagination.page + 1}">Siguiente</button>
            </li>
        `);

        $('#userActionsPagination .page-link').off('click').on('click', function() {
            const targetPage = Number($(this).data('page'));
            if (Number.isNaN(targetPage)) return;
            userActionsPage = targetPage;
            loadUserActivity();
        });
    }

    </script>
@endsection

const UI_BLOCK_SELECTORS = [
    "#btnFiltr",
    "#btnPrFiltr",
    "#por-pagar",
    "#pagadas-con-novedad",
    "#Fullfacturas-all",
    "#en-transporte"
];

let setLoadingState = function (isLoading) {
    UI_BLOCK_SELECTORS.forEach((selector) => {
        const $el = $(selector);
        if ($el.length) {
            $el.prop('disabled', isLoading);
            $el.toggleClass('disabled', isLoading);
        }
    });
}

let showLoader = function (message) {
    setLoadingState(true);
    Swal.fire({
        title: message,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
            const b = Swal.getHtmlContainer().querySelector('b')
        },
    })
}

let hideLoader = function () {
    setLoadingState(false);
    swal.close();
}

let toggleCardView = function (cardSelector) {
    if (!cardSelector) return;
    const sections = [
        "#oculto-por-pagar",
        "#oculto-pagadas-con-novedad",
        "#FacturasGenerales",
        "#facturas-en-transporte"
    ];

    sections.forEach((section) => {
        if ($(section).length === 0) return;

        if (section === cardSelector) {
            if ($(section).css("display") == 'none')
                $(section).show("slow");
            else
                $(section).hide("slow");
        } else if ($(section).css("display") != 'none') {
            $(section).hide("slow");
        }
    });
}

const formatDateValue = (value, fallback = 'N/D') => window.InvoiceHelpers.formatDateValue(value, fallback);
const safeText = (value, fallback = 'N/D') => window.InvoiceHelpers.safeText(value, fallback);
const formatCurrency = (value, currency = 'USD', locale = 'en-US') => window.InvoiceHelpers.formatCurrency(value, currency, locale);
const setInvoiceModalLoading = () => window.InvoiceHelpers.setInvoiceModalLoading();
const setTransportModalLoading = () => window.InvoiceHelpers.setTransportModalLoading();
const removeInvoiceLoading = () => window.InvoiceHelpers.removeInvoiceLoading();

// Funccion de consulta validaciones y carga de datos Datatable
let LoadData = function (PaidStatus, CanceledFlag, TableName, InvoiceType, ValidationStatus, Card, startDate,
    endDate, InvoiceLimit) {
    // let start = performance.now();
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
    if (response.success == true) {

        tblColectionData.clear().draw();
        tblColectionData.rows.add(datos).draw();

        toggleCardView(Card);

        hideLoader();
    } else {
        hideLoader();
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: datos,
        })
    }
},
error: function(error) {
    hideLoader();
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Algo fallo con la respuesta!',
    })
    console.error(error);
}
                })
            }

let LoadDataShipment = function (TableName, Card, ShipmentsLimit) {
    // let start = performance.now();
    if ($.fn.dataTable.isDataTable(TableName)) {
        tblColectionData = $(TableName).DataTable();
    } else {
        tblColectionData = $(TableName).DataTable({

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

        columns: [{
            title: "Accion",
            data: null,
            defaultContent: "<button type='button' class='verT btn btn-success' width='25px' aria-label='Ver manifiesto' title='Ver manifiesto'><i class='fa fa-eye' aria-hidden='true'></i></button>"
        },
        {
            title: "ID",
            data: "shipmentXid"
        },
        {
            title: "Numero identificacion proveedor",
            data: function (d) {

                if (typeof d.attribute9 != "undefined") {
                    let pieces = d.attribute9.split(".");
                    return pieces[1];

                } else {
                    return 'Numero identificacion proveedor no definida';
                }

            }
        },
        {
            title: "Placa",
            data: function (d) {

                if (typeof d.attribute10 != "undefined") {
                    let pieces = d.attribute10.split(".");
                    return pieces[1];
                } else {
                    return 'Placa no definida';
                }


            }
        },
        {
            title: "Placa Trailer",
            data: function (d) {
                // console.log(typeof d.attribute11);
                if (typeof d.attribute11 != "undefined") {
                    let pieces = d.attribute11.split(".");
                    return pieces[1];
                } else {
                    return 'Placa de trailer no definida';
                }
            }
        },
        {
            title: "Costo Total",
            data: function (d) {
                return formatCurrency(d.totalActualCost['value'], 'COP');
            }
        },
        {
            title: "Numero de paradas",
            data: "numStops"
        },

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
        ],

        });
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

        tblColectionData.clear().draw();
        tblColectionData.rows.add(datos).draw();

        toggleCardView(Card);

        hideLoader();
    } else {
        hideLoader();
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: datos,
        })

    }
},
error: function(error) {
    hideLoader();
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
let Loader = function () {
    showLoader('Cargando las 20 facturas mas recientes!');
}
// Fin

// load inicial, se visualiza al seleccionar un opcion de las facturas
let Load = function (cant) {
    showLoader('Cargando las ' + cant + ' facturas mas recientes!');
}
// Fin

// load secundario, se visualiza al momento pasas de una opcion de facturas a otro siempre y cuando se estan visualizando la tabla de facturas
let LoaderView = function () {
    showLoader('Cargando visualización!');
}
// Fin

// Filtros facturas OTM transporte
$('#btnFiltr').on('click', function (e) {
    e.preventDefault(); //detemos el formluario
    var ShipmentsLimit = document.getElementById("ShipmentsLimit").value;
    tblColectionData.clear().draw();
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
$('#btnPrFiltr').on('click', function (e) {
    var InvoiceLimit = document.getElementById("InvoiceLimit").value;
    var InvoiceType = document.getElementById("tipoFactura").value;
    var ValidationStatus = document.getElementById("ValidationStatus").value;
    var PaidStatus = document.getElementById("PaidStatus").value;
    var CanceledFlag = document.getElementById("CanceledFlag").value;
    var startDate = document.getElementById("startDate").value;
    var endDate = document.getElementById("endDate").value;
    tblColectionData.clear().draw();
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

// Acciones botones principales
$("#por-pagar").click(function (e) {
    e.preventDefault();
    Loader();
    LoadData("Impagado", "false", "#TablePorPagar", "", "", "#oculto-por-pagar", "", "", "");
    obtener_data("#TablePorPagar tbody", tblColectionData);
});

$("#pagadas-con-novedad").click(function (e) {
    e.preventDefault();
    Loader();
    LoadData("Pagada parcialmente", "true", "#TablePagadasNovedad", "", "", "#oculto-pagadas-con-novedad",
        "", "", "");
    obtener_data("#TablePagadasNovedad tbody", tblColectionData);

});


$("#Fullfacturas-all").click(function (e) {
    e.preventDefault();
    Loader();
    LoadData("", "false", "#TablaFacturasAll", "", "", "#FacturasGenerales", "", "", "");
    obtener_data("#TablaFacturasAll tbody", tblColectionData);

});

$('#en-transporte').on('click', function (e) {
    e.preventDefault();

    Loader();
    LoadDataShipment("#TableEnTransporte", "#facturas-en-transporte", 20);
    obtener_dataTransporte("#TableEnTransporte tbody", tblColectionData);
})
// Fin

// Cerrar modal
$("#closet-modal").click(function (e) {
    $("#global-loader3").modal('hide'); //ocultamos el modal
});
// Fin


// consulta y carga de visualizar de facturas individuales
let obtener_data = function (tbody, table) {
    $(tbody).on("click", "button.ver", function () {
        // Activar el spiner de cargar al momento de visualizar la factura
        // document.getElementById("global-loader3").style.display = "";
        setInvoiceModalLoading();
        hideLoader();
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
                InvoiceNumber: invoice.InvoiceNumber
            },
            success: function (response) {
                let invoice = response.data.invoiceData[0]
                let lines = Array.isArray(response.data.invoiceLines) ? response.data.invoiceLines : []
                let fPago = response.data.invoiceFechaPago && response.data.invoiceFechaPago[0]
                    ? response.data.invoiceFechaPago[0].PaymentDate
                    : null
                const rawHolds = response.data.holds || [];
                let holds = [];
                if (Array.isArray(rawHolds)) {
                    holds = Array.isArray(rawHolds[0]) ? rawHolds[0] : rawHolds;
                }

                let InvoiceAmount = formatCurrency(invoice.InvoiceAmount, 'USD');

                if (response.success == true) {
                    removeInvoiceLoading();
                    $('#date').html('')
                    plantillaDate = `
                                <div class="col-md-4 align-self-center">
                                    <img src="{{ asset('assets/images/logos-tractocar/negative-blue-small.png') }}" alt="logo-small" class="logo-sm mr-2" height="56">
                                    {{-- <img src="{{asset('assets/images/logos-tractocar/negative-blue-tiny.png')}}" alt="logo-large" class="logo-lg logo-light" height="16"> --}}
                                    <p class="mt-2 mb-0 text-muted">@lang('locale.Description') : ${safeText(invoice.Description)}.</p>                                                             </div><!--end col-->
                                </div><!--end col-->
                                <div class="col-md-4 ms-auto">
                                    <ul class="list-inline mb-0 contact-detail float-right">
                                        <li class="list-inline-item">
                                            <div class="pl-3">
                                                <h6 class="mb-0"><b>@lang('locale.Supplier') : </b>${safeText(invoice.Supplier)}</h6>
                                            </div>
                                        </li>
                                        <li class="list-inline-item">
                                            <div class="pl-3">
                                                <h6 class="mb-0"><b>@lang('locale.Invoice Number') : </b>${safeText(invoice.InvoiceNumber)} </h6>
                                            </div>
                                        </li>
                                        <li class="list-inline-item">
                                            <div class="pl-3">
                                                <h6 class="mb-0"><b>@lang('locale.Invoice Date') : </b>${formatDateValue(invoice.InvoiceDate)} </h6>
                                            </div>
                                        </li>
                                        <li class="list-inline-item">
                                            <div class="pl-3">
                                                <h5><i class="mdi mdi-cash-multiple"></i><b> :</b> ${InvoiceAmount}</h5>
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
                                        <p class="mb-0 text-muted">${safeText(invoice.InvoiceType)}</p>
                                    </td>
                                    <td >
                                        <p class="mb-0 text-muted">${safeText(invoice.PaidStatus)}</p>
                                    </td>
                                    <td >
                                        <p class="mb-0 text-muted">${safeText(invoice.ValidationStatus)}</p>
                                    </td>
                                    <td >
                                        <p class="mb-0 text-muted">${safeText(invoice.invoiceInstallments && invoice.invoiceInstallments[0] ? invoice.invoiceInstallments[0]['BankAccount'] : null)}</p>
                                    </td>
                                    <td >
                                        <p class="mb-0 text-muted">${formatDateValue(invoice.AccountingDate)}</p>
                                    </td>
                                    <td >
                                        <p class="mb-0 text-muted">${formatDateValue(invoice.invoiceInstallments && invoice.invoiceInstallments[0] ? invoice.invoiceInstallments[0]['DueDate'] : null)}</p>
                                    </td>
                                    <td >
                                        <p class="mb-0 text-muted">${formatDateValue(fPago)}</p>
                                    </td>
                                </tr><!--end tr-->
                            `
                    $('#row1').append(plantillarow1)

                    $('#row2').html('')
                    let hasLines = false;
                    lines.forEach(line => {
                        var LineAmount = formatCurrency(line.LineAmount, 'USD');
                        if (line.LineAmount != 0) {
                            hasLines = true;
                            plantillarow2 = `
                                        <tr>
                                            <td >
                                                <h5 class="mt-0 mb-1">${safeText(line.LineType)}</h5>
                                                <p class="mb-0 text-muted">${safeText(line.Description)}.</p>
                                            </td>
                                            <td> ${LineAmount}</td>
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
                            const date = formatDateValue(hold.HoldDate);

                            plantillarow3 = `
                                    <tr>
                                        <td >${safeText(hold.HoldName)}</td>
                                        <td> ${safeText(hold.HoldReason)}</td>
                                        <td> ${safeText(hold.HeldBy)}</td>
                                        <td> ${date}</td>
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
                hideLoader();
                $('#exampleModalToggle').modal('show');
            },
            error: function (error) {
                removeInvoiceLoading();
                hideLoader();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: (error.responseJSON && error.responseJSON.message) ? error.responseJSON.message : 'No fue posible cargar el detalle de la factura',
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
let obtener_dataTransporte = function (tbody, table) {
    $(tbody).on("click", "button.verT", function () {

        // Activar el spiner de cargar al momento de visualizar la factura
        setTransportModalLoading();
        hideLoader();
        $('#exampleModalTransporte').modal('show');
        //Fin

        // Cargamos los datos de la factura al modal
        let invoice = table.row($(this).parents("tr")).data();
        plantillaDate = '';
        plantillarow1 = '';
        $.ajax({
            type: "POST",
            url: "{{ route('falturas.transporte.detalle') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                invoice: invoice.shipmentXid
            },
            success: function (response) {
                if (response.success !== true) {
                    hideLoader();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message || 'No fue posible cargar el detalle del manifiesto',
                    });
                    return;
                }
                const data = response.data || {};
                const detalle = Array.isArray(data) ? data[0] : data;

                if (!detalle || (typeof detalle === 'object' && Object.keys(detalle).length === 0)) {
                    hideLoader();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin datos',
                        text: 'No se encontró información del manifiesto.',
                    });
                    return;
                }

                const safe = (value, fallback = 'No definido') => {
                    if (value === null || typeof value === 'undefined' || value === '') {
                        return fallback;
                    }
                    return window.InvoiceHelpers.escapeHtml(value);
                };

                const driverName = [detalle.DRIVER_FIRSTNAME, detalle.DRIVER_LASTNAME].filter(Boolean).join(' ');
                const advanceStatus = detalle.ADVANCE_STATUS || detalle.ADVANCE_STATUS_GID || 'MANIFIESTO_CUMPLIDO_NUEVO';

                $('#date_1').html('');
                plantillaDate = `
                            <div class="col-md-4 align-self-center">
                                <img src="{{ asset('assets/images/logos-tractocar/negative-blue-small.png') }}" alt="logo-small" class="logo-sm mr-2" height="56">
                            </div><!--end col-->
                            <div class="col-md-4 ms-auto">
                                <ul class="list-inline mb-0 contact-detail float-right">
                                    <li class="list-inline-item">
                                        <div class="pl-3">
                                            <h6 class="mb-0"><b>Fecha de creación del Manifiesto:</b> ${formatDateValue(detalle.MANIFEST_CREATE_DATE)}</h6>
                                            <h6><b>Numero del Manifiesto:</b> # ${safe(detalle.MANIFEST_ID, 'N/D')}</h6>
                                        </div>
                                    </li>
                                </ul>
                            </div><!--end col-->
                        `;
                $('#date_1').append(plantillaDate);

                $('#row1_1').html(`
                        <tr>
                            <td>${safe(detalle.OWNER_ID, 'N/D')}</td>
                            <td>${safe(detalle.OWNER_NAME, 'N/D')}</td>
                        </tr>
                    `);

                $('#row2_2').html(`
                        <tr>
                            <td>${safe(detalle.DRIVER_ID, 'N/D')}</td>
                            <td>${safe(driverName, 'N/D')}</td>
                            <td>${safe(detalle.DRIVER_MOBILE_NUMBER, 'N/D')}</td>
                        </tr>
                    `);

                $('#row3_3').html(`
                        <tr>
                            <td>${safe(detalle.MANIFEST_OPERATION_TYPE, 'N/D')}</td>
                            <td>${safe(detalle.SHIPMENT_STATUS, 'N/D')}</td>
                            <td>${safe(advanceStatus, 'N/D')}</td>
                        </tr>
                    `);

                $('#row4_4').html(`
                        <tr>
                            <td>${safe(detalle.ORIGIN_CITY, 'N/D')}</td>
                            <td>${safe(detalle.ORIGIN_PROVINCE, 'N/D')}</td>
                            <td>${safe(detalle.ORIGIN_ADDRESS, 'N/D')}</td>
                            <td>${safe(detalle.ROUTE_NAME, 'N/D')}</td>
                            <td>${safe(detalle.ROUTE_VIA, 'N/D')}</td>
                            <td>${safe(detalle.DESTINATION_CITY, 'N/D')}</td>
                            <td>${safe(detalle.DESTINATION_PROVINCE, 'N/D')}</td>
                            <td>${safe(detalle.DESTINATION_ADDRESS, 'N/D')}</td>
                        </tr>
                    `);

                $('#row5_5').html(`
                        <tr>
                            <td>${safe(detalle.VEHICLE_LICENSE_PLATE, 'N/D')}</td>
                            <td>${safe(detalle.VEHICLE_MAKE, 'N/D')}</td>
                            <td>${safe(detalle.VEHICLE_COLOR, 'N/D')}</td>
                            <td>${safe(detalle.VEHICLE_MODEL, 'N/D')}</td>
                            <td>${safe(detalle.VEHICLE_TRAILER_NUMBER, 'N/D')}</td>
                        </tr>
                    `);
                hideLoader();
                $('#exampleModalTransporte').modal('show');
            },
            error: function (error) {
                hideLoader();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: (error.responseJSON && error.responseJSON.message) ? error.responseJSON.message : 'Algo falló con la respuesta',
                });
                console.error(error);
            }
            //Fin
        });

    });
}
// Fin

// Dashboard del gestor: unica tarjeta "Todas las facturas" (todos los proveedores).
// Copiado y simplificado a partir de public/views/js/home/afiliado-dashboard.js:
// aqui solo existe una tabla, asi que no se necesita la logica de mostrar/ocultar
// otras tarjetas que si aplica en el dashboard de afiliado.

function ValidarFecha(id, btn) {
    var Fecha = document.getElementById(id).value;
    const button = document.getElementById(btn)

    if (Fecha.length != 10)
        button.disabled = true
    if (Fecha.length == 10)
        button.disabled = false
    if (Fecha.length == "")
        button.disabled = false
}

let tblColectionData;

// Funcion de estado de pago, identica a la de afiliado-dashboard.js
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

// load inicial, se visualiza al momento de aplicar los filtros
let Loader = function() {
    Swal.fire({
        title: 'Cargando las 20 facturas mas recientes!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    })
}

let Load = function(cant) {
    Swal.fire({
        title: 'Cargando las ' + cant + ' facturas mas recientes!',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
        },
    })
}

// Trae todas las facturas (todos los proveedores) via searchInvoices; el backend
// omite el filtro de SupplierNumber cuando el usuario autenticado es gestor.
let LoadData = function(PaidStatus, CanceledFlag, TableName, InvoiceType, ValidationStatus, startDate,
    endDate, InvoiceLimit) {
    tblColectionData = getScrollTable(TableName, invoiceTableColumns);

    $.ajax({
        type: 'POST',
        url: GestorConfig.routes.searchInvoices,
        data: {
            "_token": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            SupplierNumber: GestorConfig.supplierNumber,
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
                tblColectionData.setData(datos);
                swal.close();
            } else {
                swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: datos,
                })
            }
        },
        error: function(error) {
            swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Algo fallo con la respuesta!',
            })
            console.error(error);
        }
    })
}

// consulta y carga de visualizar de facturas individuales
let obtener_data = function(tbody, table) {
    $(tbody).on("click", "button.ver", function() {
        let $verBtn = $(this);
        let verBtnOriginalHtml = $verBtn.html();
        $verBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
        swal.close();

        let invoice = table.getRowData($(this).parents("tr"));

        $.ajax({
            type: "POST",
            url: GestorConfig.routes.invoiceLines,
            data: {
                "_token": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                InvoiceId: invoice.InvoiceId
            },
            success: function(response) {
                InvoiceHelpers.removeInvoiceLoading();
                let invoice = response.data.invoiceData[0]
                let lines = Array.isArray(response.data.invoiceLines) ? response.data.invoiceLines : []
                let fPago = response.data.invoiceFechaPago && response.data.invoiceFechaPago[0] ?
                    response.data.invoiceFechaPago[0].PaymentDate :
                    null
                const rawHolds = response.data.holds || [];
                let holds = [];
                if (Array.isArray(rawHolds)) {
                    holds = Array.isArray(rawHolds[0]) ? rawHolds[0] : rawHolds;
                }
                let InvoiceAmount = InvoiceHelpers.formatCurrency(invoice.InvoiceAmount, 'USD');

                if (response.success == true) {
                    let statusInfo = (invoice.invoiceInstallments && invoice.invoiceInstallments[0]) ?
                        estadoPagoInfo(invoice) : {
                            text: InvoiceHelpers.safeText(invoice.PaidStatus),
                            cls: 'status-scheduled'
                        };

                    $('#date').html('')
                    $('#date').append(`
                        <div class="invoice-brand-mark">
                            <img src="${GestorConfig.logoUrl}" alt="Tractocar" height="34">
                        </div>
                        <div class="invoice-brand-meta">
                            <div class="invoice-brand-name">Tractocar Logistics SAS</div>
                            ${GestorConfig.lang.supplier} : ${ InvoiceHelpers.safeText(invoice.Supplier) }
                        </div>
                        <div class="invoice-id-block" style="margin-left:auto;">
                            <div class="label">${GestorConfig.lang.invoiceNumber}</div>
                            <div class="num">${ InvoiceHelpers.safeText(invoice.InvoiceNumber) }</div>
                            <span class="status-badge ${ statusInfo.cls }">${ statusInfo.text }</span>
                            <div class="invoice-brand-meta" style="margin-top:2px;">
                                ${ InvoiceHelpers.formatDateValue(invoice.InvoiceDate) } · ${ InvoiceAmount }
                            </div>
                        </div>
                    `)

                    if (invoice.CanceledFlag == 1) {
                        $('#body').html('')
                        $('#body').append(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>${GestorConfig.lang.canceled}!</strong> ${GestorConfig.lang.invoiceCanceledMsg}.
                                </div>
                        `)
                    }

                    $('#row1').html('')
                    $('#row1').append(`
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
                    `)

                    $('#row2').html('')
                    let hasLines = false;
                    lines.forEach(line => {
                        var LineAmount = InvoiceHelpers.formatCurrency(line.LineAmount, 'USD');
                        if (line.LineAmount != 0) {
                            hasLines = true;
                            let rowHtml = `
                                <tr>
                                    <td>
                                        <h5 class="mt-0 mb-1">${ InvoiceHelpers.safeText(line.LineType) }</h5>
                                        <p class="mb-0 text-muted">${ InvoiceHelpers.safeText(line.Description) }.</p>
                                    </td>
                                    <td> ${ LineAmount }</td>
                                </tr>
                            `
                            if (line.Description == null) {
                                rowHtml = `
                                <tr>
                                    <td>
                                        <h5 class="mt-0 mb-1">${ InvoiceHelpers.safeText(line.LineType) }</h5>
                                    </td>
                                    <td> ${ LineAmount }</td>
                                </tr>
                            `
                            }
                            $('#row2').append(rowHtml)
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
                            const date = InvoiceHelpers.formatDateValue(hold.HoldDate);
                            const isReleased = hold.ReleaseName !== null &&
                                typeof hold.ReleaseName !== 'undefined' && hold.ReleaseName !== '';
                            const statusChip = isReleased ?
                                '<span class="status-badge status-paid">Liberada</span>' :
                                '<span class="status-badge status-pending">Activa</span>';
                            $('#row3').append(`
                            <div class="invoice-hold-alert">
                                ${ statusChip }
                                <b>${ InvoiceHelpers.safeText(hold.HoldName) }:</b>
                                ${ InvoiceHelpers.safeText(hold.HoldReason) }
                                — retenida por ${ InvoiceHelpers.safeText(hold.HeldBy) } el ${ date }.
                            </div>
                        `)
                        });
                        $('#BloqueosTabItem').show();
                    } else {
                        $('#BloqueosTabItem').hide();
                    }

                    $verBtn.prop('disabled', false).html(verBtnOriginalHtml);
                    swal.close();
                    document.querySelector('#invoiceModalTabs .invoice-tab')?.click();
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
        });
    });
}

$(function() {
    bindScrollTableToolbar('#TablaFacturasGestor', 'searchTablaFacturasGestor', 'exportTablaFacturasGestor',
        'todas_las_facturas');

    obtener_data("#TablaFacturasGestor tbody", getScrollTable('#TablaFacturasGestor', invoiceTableColumns));

    // Cerrar modal
    $("#closet-modal").click(function(e) {
        $("#global-loader3").modal('hide');
    });

    // Filtros
    $('#btnPrFiltr').on('click', function(e) {
        e.preventDefault();
        var InvoiceLimit = document.getElementById("InvoiceLimit").value;
        var InvoiceType = document.getElementById("tipoFactura").value;
        var ValidationStatus = document.getElementById("ValidationStatus").value;
        var PaidStatus = document.getElementById("PaidStatus").value;
        var CanceledFlag = document.getElementById("CanceledFlag").value;
        var startDate = document.getElementById("startDate").value;
        var endDate = document.getElementById("endDate").value;
        if (tblColectionData) tblColectionData.setData([]);

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
                    LoadData(PaidStatus, CanceledFlag, "#TablaFacturasGestor", InvoiceType,
                        ValidationStatus, startDate, endDate, InvoiceLimit);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire('Consulta Cancelada')
                }
            });
        } else if (InvoiceLimit == 20) {
            Load(InvoiceLimit);
            LoadData(PaidStatus, CanceledFlag, "#TablaFacturasGestor", InvoiceType, ValidationStatus,
                startDate, endDate, InvoiceLimit);
        }
    });

    // Carga inicial de la tabla
    Loader();
    LoadData("", "false", "#TablaFacturasGestor", "", "", "", "", "500");
});

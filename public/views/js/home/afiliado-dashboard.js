let Loader1 = function () {
    let $yourUl = $("#global-loader2");
    $yourUl.css("display", $yourUl.css("display") === "none" ? "" : "none");
};

// Shared eye-catching loading modal used by every "fetching invoices" state below
let showInvoiceLoadingModal = function (title, subtitle) {
    Swal.fire({
        html: `
                    <div class="tcl-loading-icon">🚚</div>
                    <div class="tcl-loading-title">${title}</div>
                    <div class="tcl-loading-subtitle">${subtitle || "This will only take a moment..."}</div>
                `,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        timerProgressBar: true,
        customClass: {
            popup: "tcl-loading-popup",
        },
    });
};

window.onload = function () {
    if ($("#faturasGeneral").css("display") == "none")
        $("#faturasGeneral").show("slow");
    else $("#faturasGeneral").hide("slow");
    $(".multi-collapse").collapse();

    $.ajax({
        type: "POST",
        url: AfiliadoConfig.routes.supplierNumber,
        data: {
            _token: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            id: AfiliadoConfig.userId,
        },
        success: function (response) {
            let data = response.data;

            if (response.success == true) {
                let plantillaMtPorPagar = "";
                let plantillaTotalFt = "";

                $.ajax({
                    type: "POST",
                    url: AfiliadoConfig.routes.total,
                    data: {
                        _token: document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                        SupplierNumber: data,
                        PaidStatus: ["Impagado", "Pagada parcialmente"],
                    },

                    success: function (response) {
                        let responseData = response.data;

                        if (response.success == true) {
                            let dollarUSLocale = Intl.NumberFormat("en-US");
                            let mtPorPagar = responseData[0]["Impagado"];

                            let mtPagadaParcialmente =
                                responseData[1]["Pagada parcialmente"];

                            let montoTotal = mtPagadaParcialmente + mtPorPagar;

                            let montoTotalFormat =
                                dollarUSLocale.format(montoTotal);

                            let totalFt = responseData[0]["count Impagado"];
                            let totalFtpartial =
                                responseData[1]["count Pagada parcialmente"];

                            var x = document.getElementById("piner");
                            var y = document.getElementById("piner1");
                            plantillaMtPorPagar = `
                                <span class="stat-value">$${montoTotalFormat}</span>
                                `;
                            x.style.display = "none";
                            $("#mtPorPagar").append(plantillaMtPorPagar);

                            plantillaTotalFt = `
                                <span class="stat-value">${totalFt + totalFtpartial}</span>
                                `;

                            y.style.display = "none";
                            $("#totalFt").append(plantillaTotalFt);

                            var xn = document.getElementById("piner2");
                            var yn = document.getElementById("piner3");
                            if (xn) {
                                xn.style.display = "none";
                                $("#mtNovedad").append(
                                    `<span class="stat-value">$${dollarUSLocale.format(mtPagadaParcialmente)}</span>`,
                                );
                            }
                            if (yn) {
                                yn.style.display = "none";
                                $("#totalNovedad").append(
                                    `<span class="stat-value">${totalFtpartial}</span>`,
                                );
                            }

                            $("#mainTabCountPorPagar").text(totalFt);
                            $("#mainTabCountNovedad").text(totalFtpartial);

                            Loader1();
                        }
                    },
                    error: function (error) {
                        console.error(error);
                    },
                });
            }
        },
    });
};

function validateDate(id, btn) {
    // Store the value entered in TxtFecha
    var date = document.getElementById(id).value;
    const button = document.getElementById(btn);

    // If the date is complete, start validation
    if (date.length != 10) button.disabled = true;
    if (date.length == 10) button.disabled = false;
    if (date.length == "") button.disabled = false;
}

if (
    typeof updateOpacity === "function" &&
    typeof intervalDuration !== "undefined"
) {
    if (
        typeof updateOpacity === "function" &&
        typeof intervalDuration !== "undefined"
    ) {
        setInterval(updateOpacity, intervalDuration);
    }
}

// Function to query validations and load Datatable data
let getPaymentStatusInfo = function (d) {
    var today = new Date();
    var day = today.getDate();
    var month = today.getMonth() + 1;
    var year = today.getFullYear();

    var date1 = new Date(d.invoiceInstallments[0]["DueDate"]);
    var date2 = new Date(`${year}-${month}-${day}`);
    var dateDefined = date1 - date2;
    var dias = dateDefined / (1000 * 60 * 60 * 24);

    if (dias <= 0 && d.PaidStatus != "Pagadas") {
        return {
            text: "Por pagar",
            cls: "status-pending",
        };
    }
    if (d.PaidStatus == "Pagadas") {
        return {
            text: "Pagadas",
            cls: "status-paid",
        };
    }
    var Ndias = Math.trunc(dias);
    return {
        text: "El pago se generará en " + Ndias + " días",
        cls: "status-scheduled",
    };
};

// Formats a numeric amount as currency; defaults to USD like the rest of the invoice UI
let formatDolar = function (value, currency) {
    const formatter = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: currency || "USD",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
    return formatter.format(value || 0);
};

// Shared state for the unified pill selector + paginated card grid
window.InvoiceCards = {
    activeFilter: "por-pagar",
    page: 1,
    pageSize: 20,
    lastConfirmedLimit: 20,
    total: 0,
    hasMore: false,
    currentData: [],
    currentType: "invoice", // "invoice" | "shipment"
};

// Builds one invoice card (used for "por-pagar" / "pagadas-con-novedad" / "todas")
let invoiceCardHtml = function (d, idx) {
    var info = getPaymentStatusInfo(d);
    var unpaid =
        d.invoiceInstallments && d.invoiceInstallments[0]
            ? d.invoiceInstallments[0]["UnpaidAmount"]
            : 0;
    return `
        <div class="col-6 col-lg-4 col-xl-3">
            <div class="card invoice-item-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="invoice-item-avatar">
                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                            </div>
                            <div class="ms-2">
                                <div class="invoice-item-title">${InvoiceHelpers.safeText(d.InvoiceNumber)}</div>
                                <div class="invoice-item-date">${InvoiceHelpers.safeText(d.InvoiceDate)}</div>
                            </div>
                        </div>
                        <span class="status-badge ${info.cls}">${info.text}</span>
                    </div>
                    <div class="invoice-item-meta mt-3">
                        <div class="invoice-item-meta-row">
                            <div class="k">Valor</div>
                            <div class="v">${formatDolar(d.InvoiceAmount)}</div>
                        </div>
                        <div class="invoice-item-meta-row">
                            <div class="k">Pagado</div>
                            <div class="v">${formatDolar(d.AmountPaid)}</div>
                        </div>
                        <div class="invoice-item-meta-row invoice-item-meta-row-balance">
                            <div class="k">Saldo</div>
                            <div class="v v-balance">${formatDolar(unpaid)}</div>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="ver btn-icon-action" data-index="${idx}" aria-label="Ver factura" title="Ver factura">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
};

// Builds one shipment/manifest card (used for "en-transporte")
let shipmentCardHtml = function (d, idx) {
    var supplierDoc = "Supplier identification number not defined";
    if (typeof d.attribute9 != "undefined") {
        let pieces = d.attribute9.split(".");
        supplierDoc = pieces[1];
    }
    var licensePlate = "License plate not defined";
    if (typeof d.attribute10 != "undefined") {
        let pieces = d.attribute10.split(".");
        licensePlate = pieces[1];
    }
    var trailerPlate = "Trailer plate not defined";
    if (typeof d.attribute11 !== "undefined") {
        let pieces = d.attribute11.split(".");
        trailerPlate = pieces.length > 1 ? pieces[1] : d.attribute11;
    }
    var manifestStatus = "";
    if (d.statuses && Array.isArray(d.statuses.items)) {
        d.statuses.items.forEach(function (status) {
            if (status.statusTypeGid == "TCL.MANIFIESTO_CUMPLIDO") {
                let statusValue = status.statusValueGid.split(".");
                manifestStatus = statusValue[1];
            }
        });
    }
    manifestStatus = manifestStatus.replace("_", " ");
    var insertionDate = "";
    if (d.insertDate && d.insertDate.value) {
        insertionDate = new Date(d.insertDate.value).toLocaleString("es-ES", {
            weekday: "short",
            day: "numeric",
            month: "short",
            year: "numeric",
            hour: "numeric",
            minute: "numeric",
            second: "numeric",
        });
    }
    var totalCost = d.totalActualCost
        ? formatDolar(d.totalActualCost["value"], "COP")
        : formatDolar(0, "COP");

    return `
        <div class="col-6 col-lg-4 col-xl-3">
            <div class="card invoice-item-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="invoice-item-avatar">
                                <i class="fa fa-truck" aria-hidden="true"></i>
                            </div>
                            <div class="ms-2">
                                <div class="invoice-item-title">${InvoiceHelpers.safeText(d.shipmentXid)}</div>
                                <div class="invoice-item-date">${InvoiceHelpers.safeText(supplierDoc)}</div>
                            </div>
                        </div>
                        <span class="status-badge status-scheduled">${InvoiceHelpers.safeText(manifestStatus)}</span>
                    </div>
                    <div class="invoice-item-meta mt-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="k">Placa</div>
                                <div class="v">${InvoiceHelpers.safeText(licensePlate)}</div>
                            </div>
                            <div class="col-6">
                                <div class="k">Trailer</div>
                                <div class="v">${InvoiceHelpers.safeText(trailerPlate)}</div>
                            </div>
                            <div class="col-6">
                                <div class="k">Costo total</div>
                                <div class="v">${totalCost}</div>
                            </div>
                            <div class="col-6">
                                <div class="k"># Paradas</div>
                                <div class="v">${InvoiceHelpers.safeText(d.numStops)}</div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="k">Fecha de inserción</div>
                            <div class="v">${insertionDate}</div>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="verT btn-icon-action" data-index="${idx}" aria-label="Ver manifiesto" title="Ver manifiesto">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
};

// Renders one page of cards (invoices or shipments) into the grid and stores the raw
// records so the view handlers can look them up by index (replaces ScrollTable.getRowData)
let renderInvoiceCards = function (data, type) {
    window.InvoiceCards.currentData = Array.isArray(data) ? data : [];
    window.InvoiceCards.currentType = type;

    let $grid = $("#invoiceCardsGrid");
    $grid.empty();

    if (!window.InvoiceCards.currentData.length) {
        $("#invoiceCardsEmpty").show();
        return;
    }
    $("#invoiceCardsEmpty").hide();

    let html = "";
    window.InvoiceCards.currentData.forEach(function (d, idx) {
        html +=
            type === "shipment"
                ? shipmentCardHtml(d, idx)
                : invoiceCardHtml(d, idx);
    });
    $grid.append(html);
};

// Renders Bootstrap pagination based on the last known total/pageSize/page
let renderInvoiceCardsPagination = function () {
    let $pagination = $("#invoiceCardsPagination");
    $pagination.empty();

    let pageSize = window.InvoiceCards.pageSize;
    let total = window.InvoiceCards.total;
    let page = window.InvoiceCards.page;
    let totalPages = Math.max(1, Math.ceil(total / pageSize));

    if (totalPages <= 1) return;

    let addItem = function (label, targetPage, disabled, active) {
        let li = $(
            '<li class="page-item' +
                (disabled ? " disabled" : "") +
                (active ? " active" : "") +
                '"></li>',
        );
        let a = $(
            '<a href="javascript:void(0);" class="page-link">' + label + "</a>",
        );
        if (!disabled && !active) {
            a.on("click", function () {
                loadInvoiceCardsPage(targetPage);
            });
        }
        li.append(a);
        $pagination.append(li);
    };

    addItem("&laquo;", page - 1, page <= 1, false);

    let start = Math.max(1, page - 2);
    let end = Math.min(totalPages, start + 4);
    start = Math.max(1, end - 4);
    for (let p = start; p <= end; p++) {
        addItem(p, p, false, p === page);
    }

    addItem("&raquo;", page + 1, page >= totalPages, false);
};

// Fetches a page of invoices from the ERP-backed search endpoint using the current
// pill filter + filter-panel values, then renders the cards and pagination
let loadInvoices = function (page) {
    let PaidStatus = "";
    if (window.InvoiceCards.activeFilter === "por-pagar")
        PaidStatus = "Impagado";
    if (window.InvoiceCards.activeFilter === "pagadas-con-novedad")
        PaidStatus = "Pagada parcialmente";

    let InvoiceType = $("#tipoFactura").val() || "";
    let ValidationStatus = $("#ValidationStatus").val() || "";
    let CanceledFlag = $("#CanceledFlag").val() || "false";
    let startDate = $("#startDate").val() || "";
    let endDate = $("#endDate").val() || "";

    $.ajax({
        type: "POST",
        url: AfiliadoConfig.routes.searchInvoices,
        data: {
            _token: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            SupplierNumber: AfiliadoConfig.supplierNumber,
            CanceledFlag: CanceledFlag,
            PaidStatus: PaidStatus,
            InvoiceType: InvoiceType,
            InvoiceLimit: window.InvoiceCards.pageSize,
            core: "=",
            ValidationStatus: ValidationStatus,
            startDate: startDate,
            endDate: endDate,
            page: page,
        },
        success: function (response) {
            if (response.success == true) {
                window.InvoiceCards.page = page;
                window.InvoiceCards.total = response.total || 0;
                window.InvoiceCards.hasMore = !!response.hasMore;
                renderInvoiceCards(response.data, "invoice");
                renderInvoiceCardsPagination();
                swal.close();
            } else {
                window.InvoiceCards.total = 0;
                renderInvoiceCards([], "invoice");
                renderInvoiceCardsPagination();
                swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: response.data,
                });
            }
        },
        error: function (error) {
            swal.close();
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong with the response!",
            });
            console.error(error);
        },
    });
};

// Fetches a page of shipments/manifests from OTM, then renders the cards and pagination
let loadShipments = function (page) {
    $.ajax({
        type: "POST",
        url: AfiliadoConfig.routes.facturasTransporte,
        data: {
            _token: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            number_id: AfiliadoConfig.numberId,
            ShipmentsLimit: window.InvoiceCards.pageSize,
            page: page,
        },
        success: function (response) {
            if (response.success == true) {
                window.InvoiceCards.page = page;
                window.InvoiceCards.total = response.total || 0;
                window.InvoiceCards.hasMore = !!response.hasMore;
                renderInvoiceCards(response.data, "shipment");
                renderInvoiceCardsPagination();
                swal.close();
            } else {
                window.InvoiceCards.total = 0;
                renderInvoiceCards([], "shipment");
                renderInvoiceCardsPagination();
                swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: response.data,
                });
            }
        },
        error: function (error) {
            swal.close();
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong with the response!",
            });
            console.error(error);
        },
    });
};

// Dispatches to the invoice or shipment loader based on the active pill, showing the
// loading modal first. Used both for pill switches/filter submits and page navigation.
let loadInvoiceCardsPage = function (page) {
    Loader();
    if (window.InvoiceCards.activeFilter === "en-transporte") {
        loadShipments(page);
    } else {
        loadInvoices(page);
    }
};
// End

// initial load, shown when selecting an invoice option
let Loader = function () {
    showInvoiceLoadingModal(
        "Cargando facturas",
        "Mostrando las facturas más recientes...",
    );
};
// End

// initial load, shown when selecting an invoice option
let Load = function (cant) {
    showInvoiceLoadingModal(
        "Cargando tus facturas",
        "Mostrando las " + cant + " facturas más recientes...",
    );
};
// End

// secondary load, shown when switching between invoice options while the invoice table is visible
let LoaderView = function () {
    showInvoiceLoadingModal("Cargando vista", "Preparando todo...");
};
// End

// Titles + which filter-panel groups are relevant for each pill (invoice-only fields
// are hidden while "En transporte" is active, since shipments don't use them)
const INVOICE_PILL_TITLES = {
    "por-pagar": "Facturas por pagar",
    "en-transporte": "Facturas en transporte",
    "pagadas-con-novedad": "Facturas pagadas parcialmente",
    todas: "Buscar facturas",
};

let applyInvoicePillUi = function (filter) {
    $("#invoiceCardsTitle").text(INVOICE_PILL_TITLES[filter] || "Facturas");
    if (filter === "en-transporte") {
        $(".invoice-filter-erp").addClass("d-none");
    } else {
        $(".invoice-filter-erp").removeClass("d-none");
    }
};

// Runs the requested page-size warning flow (only used when the page SIZE changes,
// i.e. from the "Filtrar" button — page navigation reuses the already-confirmed size)
let confirmAndLoadFirstPage = function () {
    var limit = parseInt($("#RecordsLimit").val(), 10) || 20;
    window.InvoiceCards.pageSize = limit;

    if (limit > 20) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger",
            },
            buttonsStyling: false,
        });

        swalWithBootstrapButtons
            .fire({
                title: "Warning",
                text: "Keep in mind that increasing the invoice load range will make the response take a bit longer.!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, I understand",
                cancelButtonText: "Mmm... better not",
                reverseButtons: true,
            })
            .then((result) => {
                if (result.isConfirmed) {
                    window.InvoiceCards.lastConfirmedLimit = limit;
                    Load(limit);
                    loadInvoiceCardsPage(1);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Revert the select back to the last confirmed page size
                    $("#RecordsLimit").val(
                        window.InvoiceCards.lastConfirmedLimit,
                    );
                    window.InvoiceCards.pageSize =
                        window.InvoiceCards.lastConfirmedLimit;
                    swalWithBootstrapButtons.fire("Query Canceled");
                }
            });
    } else {
        window.InvoiceCards.lastConfirmedLimit = limit;
        Load(limit);
        loadInvoiceCardsPage(1);
    }
};

// "Filtrar" button — only fires the >20 warning here, never on page navigation
$("#btnAplicarFiltros").on("click", function (e) {
    e.preventDefault();
    confirmAndLoadFirstPage();
});
// End

// Unified pill selector — switches the active filter, resets to page 1, and re-fetches
$("#invoiceMainTabs").on("click", ".invoice-main-tab", function () {
    $("#invoiceMainTabs .invoice-main-tab").removeClass("active");
    $(this).addClass("active");

    let filter = $(this).data("filter");
    window.InvoiceCards.activeFilter = filter;
    window.InvoiceCards.page = 1;
    applyInvoicePillUi(filter);
    Loader();
    loadInvoiceCardsPage(1);
});
// End
$("#drakma").click(function (e) {
    e.preventDefault();
    $("#myModal").modal("show");
});

// Close modal
$("#closet-modal").click(function (e) {
    $("#global-loader3").modal("hide"); // hide the modal
});
// End

// query and load to view individual invoices
let bindInvoiceViewHandler = function (grid) {
    $(grid).on("click", "button.ver", function () {
        // Show the spinner on the button itself while the ERP responds,
        // and only open the modal once the data is ready
        let $verBtn = $(this);
        let verBtnOriginalHtml = $verBtn.html();
        $verBtn
            .prop("disabled", true)
            .html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
        swal.close();
        // End

        // Load the invoice data into the modal (looked up by index from the last
        // rendered page of cards, since the card grid replaces ScrollTable)
        let invoice = window.InvoiceCards.currentData[$verBtn.data("index")];
        invoiceDateTemplate = "";
        invoiceBodyTemplate = "";
        invoiceRow1Template = "";
        invoiceRow2Template = "";
        invoiceRow3Template = "";
        $.ajax({
            type: "POST",
            url: AfiliadoConfig.routes.invoiceLines,
            data: {
                _token: document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                InvoiceId: invoice.InvoiceId,
            },
            success: function (response) {
                InvoiceHelpers.removeInvoiceLoading();
                let invoice = response.data.invoiceData[0];
                let lines = Array.isArray(response.data.invoiceLines)
                    ? response.data.invoiceLines
                    : [];
                let fPago =
                    response.data.invoiceFechaPago &&
                    response.data.invoiceFechaPago[0]
                        ? response.data.invoiceFechaPago[0].PaymentDate
                        : null;
                const rawHolds = response.data.holds || [];
                let holds = [];
                if (Array.isArray(rawHolds)) {
                    holds = Array.isArray(rawHolds[0]) ? rawHolds[0] : rawHolds;
                }
                let InvoiceAmount = InvoiceHelpers.formatCurrency(
                    invoice.InvoiceAmount,
                    "USD",
                );

                if (response.success == true) {
                    let statusInfo =
                        invoice.invoiceInstallments &&
                        invoice.invoiceInstallments[0]
                            ? getPaymentStatusInfo(invoice)
                            : {
                                  text: InvoiceHelpers.safeText(
                                      invoice.PaidStatus,
                                  ),
                                  cls: "status-scheduled",
                              };

                    $("#date").html("");
                    invoiceDateTemplate = `
                                <div class="invoice-brand-mark">
                                    <img src="${AfiliadoConfig.logoUrl}" alt="Tractocar" height="34">
                                </div>
                                <div class="invoice-brand-meta">
                                    <div class="invoice-brand-name">Tractocar Logistics SAS</div>
                                    ${AfiliadoConfig.lang.supplier} : ${InvoiceHelpers.safeText(invoice.Supplier)}
                                </div>
                                <div class="invoice-id-block" style="margin-left:auto;">
                                    <div class="label">${AfiliadoConfig.lang.invoiceNumber}</div>
                                    <div class="num">${InvoiceHelpers.safeText(invoice.InvoiceNumber)}</div>
                                    <span class="status-badge ${statusInfo.cls}">${statusInfo.text}</span>
                                    <div class="invoice-brand-meta" style="margin-top:2px;">
                                        ${InvoiceHelpers.formatDateValue(invoice.InvoiceDate)} · ${InvoiceAmount}
                                    </div>
                                </div>
                            `;
                    $("#date").append(invoiceDateTemplate);

                    if (invoice.CanceledFlag == 1) {
                        $("#body").html("");
                        invoiceBodyTemplate = `
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>${AfiliadoConfig.lang.canceled}!</strong> ${AfiliadoConfig.lang.invoiceCanceledMsg}.
                                        </div>
                                `;
                        $("#body").append(invoiceBodyTemplate);
                    }

                    $("#row1").html("");
                    invoiceRow1Template = `
                                <div>
                                    <div class="k">Tipo Factura</div>
                                    <div class="v">${InvoiceHelpers.safeText(invoice.InvoiceType)}</div>
                                </div>
                                <div>
                                    <div class="k">Estado de validación</div>
                                    <div class="v">${InvoiceHelpers.safeText(invoice.ValidationStatus)}</div>
                                </div>
                                <div>
                                    <div class="k">Número de cuenta</div>
                                    <div class="v">${InvoiceHelpers.safeText(invoice.invoiceInstallments && invoice.invoiceInstallments[0] ? invoice.invoiceInstallments[0]["BankAccount"] : null)}</div>
                                </div>
                                <div>
                                    <div class="k">Fecha contable</div>
                                    <div class="v">${InvoiceHelpers.formatDateValue(invoice.AccountingDate)}</div>
                                </div>
                                <div>
                                    <div class="k">Fecha de vencimiento</div>
                                    <div class="v">${InvoiceHelpers.formatDateValue(invoice.invoiceInstallments && invoice.invoiceInstallments[0] ? invoice.invoiceInstallments[0]["DueDate"] : null)}</div>
                                </div>
                                <div>
                                    <div class="k">Fecha de pago</div>
                                    <div class="v">${InvoiceHelpers.formatDateValue(fPago)}</div>
                                </div>
                            `;
                    $("#row1").append(invoiceRow1Template);

                    $("#row2").html("");
                    let hasLines = false;
                    lines.forEach((line) => {
                        var LineAmount = InvoiceHelpers.formatCurrency(
                            line.LineAmount,
                            "USD",
                        );
                        if (line.LineAmount != 0) {
                            hasLines = true;
                            invoiceRow2Template = `
                                        <tr>
                                            <td >
                                                <h5 class="mt-0 mb-1">${InvoiceHelpers.safeText(line.LineType)}</h5>
                                                <p class="mb-0 text-muted">${InvoiceHelpers.safeText(line.Description)}.</p>
                                            </td>
                                            <td> ${LineAmount}</td>
                                        </tr><!--end tr-->
                                    `;
                            if (line.Description == null) {
                                invoiceRow2Template = `
                                        <tr>
                                            <td >
                                                <h5 class="mt-0 mb-1">${InvoiceHelpers.safeText(line.LineType)}</h5>
                                            </td>
                                            <td> ${LineAmount}</td>
                                        </tr><!--end tr-->
                                    `;
                            }
                            $("#row2").append(invoiceRow2Template);
                        }
                    });

                    if (!hasLines) {
                        $("#row2").append(`
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No invoice details.</td>
                                        </tr>
                                    `);
                    }

                    let saldoPendiente = InvoiceHelpers.formatCurrency(
                        (invoice.InvoiceAmount || 0) -
                            (invoice.AmountPaid || 0),
                        "USD",
                    );
                    $("#invoiceTotals").html(`
                                    <div class="row"><span>Subtotal</span><span>${InvoiceAmount}</span></div>
                                    <div class="row"><span>Monto Pagado</span><span>${InvoiceHelpers.formatCurrency(invoice.AmountPaid, "USD")}</span></div>
                                    <div class="row total"><span>Saldo Pendiente</span><span>${saldoPendiente}</span></div>
                                `);

                    $("#row3").html("");
                    if (Array.isArray(holds) && holds.length) {
                        holds.forEach((hold) => {
                            const date = InvoiceHelpers.formatDateValue(
                                hold.HoldDate,
                            );
                            const isReleased =
                                hold.ReleaseName !== null &&
                                typeof hold.ReleaseName !== "undefined" &&
                                hold.ReleaseName !== "";
                            const statusChip = isReleased
                                ? '<span class="status-badge status-paid">Released</span>'
                                : '<span class="status-badge status-pending">Active</span>';
                            invoiceRow3Template = `
                                    <div class="invoice-hold-alert">
                                        ${statusChip}
                                        <b>${InvoiceHelpers.safeText(hold.HoldName)}:</b>
                                        ${InvoiceHelpers.safeText(hold.HoldReason)}
                                        — held by ${InvoiceHelpers.safeText(hold.HeldBy)} on ${date}.
                                    </div>
                                `;
                            $("#row3").append(invoiceRow3Template);
                        });
                        $("#BloqueosTabItem").show();
                    } else {
                        $("#BloqueosTabItem").hide();
                    }

                    $verBtn.prop("disabled", false).html(verBtnOriginalHtml);
                    swal.close();
                    document
                        .querySelector("#invoiceModalTabs .invoice-tab")
                        ?.click();
                    $("#exampleModalToggle").modal("show");
                } else {
                    $verBtn.prop("disabled", false).html(verBtnOriginalHtml);
                    swal.close();
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "It was not possible to load the invoice detail.",
                    });
                }
            },
            error: function (error) {
                InvoiceHelpers.removeInvoiceLoading();
                $verBtn.prop("disabled", false).html(verBtnOriginalHtml);
                swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong with the server response.",
                });
                console.error(error);
            },
            // End
        });
    });
};
// End

// query and load to view transport invoices
// note: the advance status field is hardcoded for now, since the query only returns records in ANTICIPO_COMPL_NUEVO
let bindTransportViewHandler = function (grid) {
    $(grid).on("click", "button.verT", function () {
        // Activate the loading spinner when viewing the invoice
        InvoiceHelpers.setTransportModalLoading();
        swal.close();
        $("#exampleModalTransporte").modal("show");
        // End

        // Load the invoice data into the modal (looked up by index from the last
        // rendered page of cards, since the card grid replaces ScrollTable)
        let invoice = window.InvoiceCards.currentData[$(this).data("index")];
        invoiceDateTemplate = "";
        invoiceRow1Template = "";
        invoiceRow2Template = "";
        invoiceRow3Template = "";
        invoiceRow4Template = "";
        invoiceRow5Template = "";

        $.ajax({
            type: "POST",
            url: AfiliadoConfig.routes.facturasTransporteDetalle,
            data: {
                _token: document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                invoice: invoice.shipmentXid,
            },
            success: function (response) {
                if (response.success !== true) {
                    swal.close();
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text:
                            response.message ||
                            "It was not possible to load the manifest detail",
                    });
                    return;
                }

                let invoice = response.data;
                if (
                    !invoice ||
                    (typeof invoice === "object" &&
                        Object.keys(invoice).length === 0)
                ) {
                    swal.close();
                    Swal.fire({
                        icon: "warning",
                        title: "No data",
                        text: "No manifest information was found.",
                    });
                    return;
                }

                const statusTranslations = {
                    "TCL.ENROUTE_COMPLETED": "COMPLETADO",
                    "TCL.ENROUTE_DELAYED": "RETRASADO",
                    "TCL.ENROUTE_DIVERTED": "DESVIADO",
                    "TCL.ENROUTE_ENROUTE": "EN RUTA/EN TRÁNSITO",
                    "TCL.ENROUTE_MERGED": "COMBINADO",
                    "TCL.ENROUTE_NOT STARTED": "SIN SALIDA",
                    "TCL.ENROUTE_PARTIAL": "PARCIAL",
                    "TCL.ENROUTE_UNLOADED - FULL": "DESCARGADO LLENO",
                    "TCL.ENROUTE_UNLOADED - PARTIAL": "DESCARGADO PARCIAL",
                };

                function translateStatus(dato) {
                    return statusTranslations[dato] || dato;
                }

                if (response.success == true) {
                    $("#date_1").html("");
                    invoiceDateTemplate = `
                                <div class="col-md-4 align-self-center">
                                    <img src="${AfiliadoConfig.logoUrl}" alt="logo-small" class="logo-sm mr-2" height="56">
                                    <!-- alternate logo (unused) -->
                                </div><!--end col-->
                                </div><!--end col-->
                                <div class="col-md-4 ms-auto">
                                    <ul class="list-inline mb-0 contact-detail float-right" >
                                        <li class="list-inline-item">
                                            <div class="pl-3">
                                                <h6 class="mb-0"><b>Manifest Creation Date: ${InvoiceHelpers.formatDateValue(invoice.MANIFEST_CREATE_DATE)}</b> </h6>
                                                <h6><b>Manifest Number:</b> # ${InvoiceHelpers.safeText(invoice.MANIFEST_ID)}</h6>
                                            </div>
                                        </li>
                                    </ul>
                                </div><!--end col-->
                            `;
                    $("#date_1").append(invoiceDateTemplate);

                    $("#row1_1").html("");
                    invoiceRow1Template = `
                                <tr>
                                    <td>${InvoiceHelpers.safeText(invoice.OWNER_ID)}</td>
                                    <td>${InvoiceHelpers.safeText(invoice.OWNER_NAME)}</td>
                                </tr>
                                `;
                    $("#row1_1").append(invoiceRow1Template);

                    $("#row2_2").html("");
                    const driverName = [
                        invoice.DRIVER_FIRSTNAME,
                        invoice.DRIVER_LASTNAME,
                    ]
                        .filter(Boolean)
                        .join(" ");
                    invoiceRow2Template = `
                                <tr>
                                    <td>${InvoiceHelpers.safeText(driverName)}</td>
                                    <td>${InvoiceHelpers.safeText(invoice.DRIVER_ID)}</td>
                                    <td>${InvoiceHelpers.safeText(invoice.DRIVER_MOBILE_NUMBER)}</td>
                                </tr>
                                `;
                    $("#row2_2").append(invoiceRow2Template);

                    $("#row3_3").html("");
                    invoiceRow3Template = `
                                <tr>
                                    <td>${InvoiceHelpers.safeText(invoice.MANIFEST_OPERATION_TYPE)}</td>

                                    <td>${InvoiceHelpers.safeText(translateStatus(invoice.SHIPMENT_STATUS))}</td>
                                    <td> NOT DELIVERED </td>
                                </tr>
                                `;
                    $("#row3_3").append(invoiceRow3Template);

                    $("#row4_4").html("");
                    invoiceRow4Template = `
                                <tr>
                                    <td> ${InvoiceHelpers.safeText(invoice.ORIGIN_CITY)} </td>
                                    <td> ${InvoiceHelpers.safeText(invoice.ORIGIN_PROVINCE)} </td>
                                    <td> ${InvoiceHelpers.safeText(invoice.ORIGIN_ADDRESS)} </td>
                                    <td> ${InvoiceHelpers.safeText(invoice.ROUTE_NAME)} </td>
                                    <td> ${InvoiceHelpers.safeText(invoice.ROUTE_VIA)} </td>
                                    <td> ${InvoiceHelpers.safeText(invoice.DESTINATION_CITY)} </td>
                                    <td> ${InvoiceHelpers.safeText(invoice.DESTINATION_PROVINCE)} </td>
                                    <td> ${InvoiceHelpers.safeText(invoice.DESTINATION_ADDRESS)} </td>
                                </tr>
                                `;
                    $("#row4_4").append(invoiceRow4Template);

                    $("#row5_5").html("");
                    invoiceRow5Template = `
                                <tr>
                                    <td> ${InvoiceHelpers.safeText(invoice.VEHICLE_LICENSE_PLATE)} </td>
                                    <td> ${InvoiceHelpers.safeText(invoice.VEHICLE_MAKE)} </td>
                                    <td> ${InvoiceHelpers.safeText(invoice.VEHICLE_COLOR)} </td>
                                    <td> ${InvoiceHelpers.safeText(invoice.VEHICLE_MODEL)} </td>
                                    <td> ${InvoiceHelpers.safeText(invoice.VEHICLE_TRAILER_NUMBER)} </td>
                                </tr>
                                `;
                    $("#row5_5").append(invoiceRow5Template);
                }
                swal.close();
                $("#exampleModalTransporte").modal("show");
            },
            error: function (error) {
                swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text:
                        error.responseJSON && error.responseJSON.message
                            ? error.responseJSON.message
                            : "Something went wrong with the response",
                });
                console.error(error);
            },
            // End
        });
    });
};
// End

// General query
// Function to query validations and load Datatable data
let LoadDataAll = function (table, form) {
    tblColectionData = $(table).DataTable({
        retrieve: true,

        dom: "Bfrtip",
        buttons: [
            {
                extend: "collection",
                text: "Export",
                buttons: [
                    {
                        extend: "excel",
                        className: "btn",
                        text: "Excel",
                        exportOptions: {
                            columns: ":not(.no-exportar)",
                        },
                    },
                    {
                        extend: "csv",
                        className: "btn",
                        text: "CSV",
                        exportOptions: {
                            columns: ":not(.no-exportar)",
                        },
                    },
                    {
                        extend: "pdf",
                        className: "btn",
                        text: "PDF",
                        exportOptions: {
                            columns: ":not(.no-exportar)",
                        },
                    },
                    {
                        extend: "print",
                        className: "btn",
                        text: "Print",
                        exportOptions: {
                            columns: ":not(.no-exportar)",
                        },
                    },
                ],
            },
        ],

        language: {
            sProcessing: "Processing...",
            sZeroRecords: "No matching records found",
            sEmptyTable: "No data available in this table",
            sInfo: "Showing _START_ to _END_ of _TOTAL_ entries",
            sInfoEmpty: "Showing 0 to 0 of 0 entries",
            sInfoFiltered: "(filtered from _MAX_ total entries)",
            sInfoPostFix: "",
            sSearch: "Search:",
            sUrl: "",
            sInfoThousands: ",",
            sLoadingRecords: "Loading...",

            oPaginate: {
                sFirst: "First",
                sLast: "Last",
                sNext: "Next",
                sPrevious: "Previous",
            },

            oAria: {
                sSortAscending:
                    ": activate to sort the column in ascending order",
                sSortDescending:
                    ": activate to sort the column in descending order",
            },
        },

        fnRowCallback: function (
            nRow,
            aData,
            iDisplayIndex,
            iDisplayIndexFull,
        ) {
            if (aData.InvoiceAmount < 0) {
                $("td:eq(2)", nRow).css("background-color", "#ed5c42");
            }
            if (aData.invoiceInstallments[0].UnpaidAmount < 0) {
                $("td:eq(3)", nRow).css("background-color", "#ed5c42");
            }
            if (aData.AmountPaid < 0) {
                $("td:eq(4)", nRow).css("background-color", "#ed5c42");
            }
        },

        columns: [
            {
                title: "Action",
                data: null,
                defaultContent:
                    "<button type='button' class='verY btn-icon-action' aria-label='View invoice' title='View invoice'><i class='fa fa-eye' aria-hidden='true'></i></button>",
            },
            {
                title: "Invoice ID",
                data: "InvoiceId",
            },
            {
                title: "Invoice Number",
                data: "InvoiceNumber",
            },
            {
                title: "Invoice Amount",
                data: function (d) {
                    const formatterDolar = new Intl.NumberFormat("en-US", {
                        style: "currency",
                        currency: "USD",
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    });
                    // if(d.InvoiceAmount < 0){
                    //     let valor = formatterDolar.format(d.InvoiceAmount);
                    //     return valor.style.color = "red";
                    // }
                    return formatterDolar.format(d.InvoiceAmount);
                },
            },
            // {title: "Description", data: "Description" },
            {
                title: "Balance",
                data: function (d) {
                    const formatterDolar = new Intl.NumberFormat("en-US", {
                        style: "currency",
                        currency: "USD",
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    });

                    return formatterDolar.format(
                        d.invoiceInstallments[0]["UnpaidAmount"],
                    );
                },
            },
            // {title: "ValidationStatus", data: "ValidationStatus"},

            {
                title: "Amount Paid",
                data: function (d) {
                    const formatterDolar = new Intl.NumberFormat("en-US", {
                        style: "currency",
                        currency: "USD",
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    });

                    return formatterDolar.format(d.AmountPaid);
                },
            },

            // {title: "Bank account",
            //     data: function ( d ) {
            //         return d.invoiceInstallments[0]["BankAccount"]}
            // },
            {
                title: "Payment Status",
                data: function (d) {
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
                        return "Canceled";
                    }
                    if (dias <= 0 && d.PaidStatus != "Pagadas") {
                        return '<span class="status-badge status-pending">Payment Pending</span>';
                    }
                    if (d.PaidStatus == "Pagadas") {
                        return '<span class="status-badge status-paid">Paid</span>';
                    }
                    var Ndias = Math.trunc(dias);
                    return (
                        '<span class="status-badge status-scheduled">Payment will be generated in ' +
                        Ndias +
                        " days</span>"
                    );
                },
            },

            {
                title: "Invoice Date",
                data: "InvoiceDate",
            },

            // {title: "Invoice Type", data: "InvoiceType" },
            // {title: "Payment made", data: "AccountingDate" }
        ],

        columnDefs: [
            {
                responsivePriority: 1,
                targets: 0,
            },
            {
                responsivePriority: 1,
                targets: 1,
            },
            {
                responsivePriority: 1,
                targets: 2,
            },
            {
                responsivePriority: 1,
                targets: 3,
            },
            {
                responsivePriority: 1,
                targets: 4,
            },
            {
                responsivePriority: 1,
                targets: 5,
            },
            // { responsivePriority: 1, targets: 6 },
        ],
    });
    tblColectionData.column(1).visible(false);
    $.ajax({
        type: $(form).attr("method"),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: $(form).attr("action"),
        data: $(form).serialize(),
        success: function (response) {
            let responseData = response.data;
            // var invoiceInstallments = responseData[0].invoiceInstallments;
            if (response.success == true) {
                tblColectionData.clear().draw();
                tblColectionData.rows.add(responseData).draw();
                // toggleActiveCard(Card);

                swal.close();
            } else {
                swal.close();
                LoaderGeneral();
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: responseData,
                });
            }
        },
        error: function (error) {
            swal.close();
            LoaderGeneral();
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong with the response!",
            });
            console.error(error);
        },
    });
};
// End

function toUpperCaseInput(e) {
    e.value = e.value.toUpperCase();
}
// initial load, shown when selecting an invoice option
let LoaderGeneral = function (cant) {
    showInvoiceLoadingModal(
        "Loading your invoices",
        "Fetching the " + cant + " most recent invoices...",
    );
};
// End

// secondary load, shown when switching between invoice options while the invoice table is visible
let LoaderGeneralView = function () {
    showInvoiceLoadingModal("Loading invoice", "Getting the details ready...");
};
// End

$("#closet-modal").click(function (e) {
    $("#global-loader3").modal("hide"); // hide the modal
});

$("#customer-code").select2({
    placeholder: "Search for a customer in OTM",
    minimumInputLength: 3,
    ajax: {
        url: AfiliadoConfig.routes.selectSupplierNumber,
        dataType: "json",
        delay: 300,
        data: function (term, page) {
            query = term.toUpperCase();
            return {
                q: query,
            };
        },
        results: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.Supplier,
                        id: item.SupplierNumber,
                    };
                }),
            };
        },
        cache: false,
    },
});

$(document).on("submit", "#filter", function (e) {
    e.preventDefault(); // stop the form submission

    let InvoiceLimit = document.getElementById("InvoiceLimit").value;

    if (InvoiceLimit > 20) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger",
            },
            buttonsStyling: false,
        });

        swalWithBootstrapButtons
            .fire({
                title: "Advertencia",
                text: "Tu consulta puede tardar varios minutos en completarse. ¿Deseas continuar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, entiendo",
                cancelButtonText: "Cancelar",
                reverseButtons: true,
            })
            .then((result) => {
                if (result.isConfirmed) {
                    LoaderGeneral(InvoiceLimit);
                    LoadDataAll("#TablaFullFacturasAll", "#filter");
                    tblColectionData.clear().draw();
                    bindGeneralViewHandler(
                        "#TablaFullFacturasAll tbody",
                        tblColectionData,
                    );
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire("Consulta Cancelada");
                }
            });
    } else if (InvoiceLimit == 20) {
        LoaderGeneral(InvoiceLimit);
        LoadDataAll("#TablaFullFacturasAll", "#filter");
        tblColectionData.clear().draw();
        bindGeneralViewHandler("#TablaFullFacturasAll tbody", tblColectionData);
    }
});

// query and load to view individual invoices
let bindGeneralViewHandler = function (tbody, table) {
    $(tbody).on("click", "button.verY", function (e) {
        // Activate the loading spinner when viewing the invoice
        // document.getElementById("global-loader3").style.display = "";
        InvoiceHelpers.setInvoiceModalLoading();
        swal.close();
        document.querySelector("#invoiceModalTabs .invoice-tab")?.click();
        $("#exampleModalToggle").modal("show");
        // End

        // Load the invoice data into the modal
        let invoice = table.row($(this).parents("tr")).data();
        invoiceDateTemplate = "";
        invoiceBodyTemplate = "";
        invoiceRow1Template = "";
        invoiceRow2Template = "";
        invoiceRow3Template = "";

        $.ajax({
            type: "POST",
            url: AfiliadoConfig.routes.invoiceLines,
            data: {
                _token: document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                InvoiceId: invoice.InvoiceId,
            },
            success: function (response) {
                // console.log(response.data);
                InvoiceHelpers.removeInvoiceLoading();
                let invoice = response.data.invoiceData[0];
                let lines = Array.isArray(response.data.invoiceLines)
                    ? response.data.invoiceLines
                    : [];
                let fPago =
                    response.data.invoiceFechaPago &&
                    response.data.invoiceFechaPago[0]
                        ? response.data.invoiceFechaPago[0]["PaymentDate"]
                        : null;
                const rawHolds = response.data.holds || [];
                let holds = [];
                if (Array.isArray(rawHolds)) {
                    holds = Array.isArray(rawHolds[0]) ? rawHolds[0] : rawHolds;
                }

                let InvoiceAmount = InvoiceHelpers.formatCurrency(
                    invoice.InvoiceAmount,
                    "USD",
                );

                if (response.success == true) {
                    let statusInfo =
                        invoice.invoiceInstallments &&
                        invoice.invoiceInstallments[0]
                            ? getPaymentStatusInfo(invoice)
                            : {
                                  text: InvoiceHelpers.safeText(
                                      invoice.PaidStatus,
                                  ),
                                  cls: "status-scheduled",
                              };

                    $("#date").html("");
                    invoiceDateTemplate = `
                                <div class="invoice-brand-mark">
                                    <img src="${AfiliadoConfig.logoUrl}" alt="Tractocar" height="34">
                                </div>
                                <div class="invoice-brand-meta">
                                    <div class="invoice-brand-name">Tractocar Logistics SAS</div>
                                    ${AfiliadoConfig.lang.supplier} : ${InvoiceHelpers.safeText(invoice.Supplier)}
                                </div>
                                <div class="invoice-id-block" style="margin-left:auto;">
                                    <div class="label">${AfiliadoConfig.lang.invoiceNumber}</div>
                                    <div class="num">${InvoiceHelpers.safeText(invoice.InvoiceNumber)}</div>
                                    <span class="status-badge ${statusInfo.cls}">${statusInfo.text}</span>
                                    <div class="invoice-brand-meta" style="margin-top:2px;">
                                        ${InvoiceHelpers.formatDateValue(invoice.InvoiceDate)} · ${InvoiceAmount}
                                    </div>
                                </div>
                            `;
                    $("#date").append(invoiceDateTemplate);

                    if (invoice.CanceledFlag == 1) {
                        $("#body").html("");
                        invoiceBodyTemplate = `
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>${AfiliadoConfig.lang.canceled}!</strong> ${AfiliadoConfig.lang.invoiceCanceledMsg}.
                                        </div>
                                `;
                        $("#body").append(invoiceBodyTemplate);
                    }

                    $("#row1").html("");
                    invoiceRow1Template = `
                                <div>
                                    <div class="k">Invoice Type</div>
                                    <div class="v">${InvoiceHelpers.safeText(invoice.InvoiceType)}</div>
                                </div>
                                <div>
                                    <div class="k">Payment Status</div>
                                    <div class="v">${InvoiceHelpers.safeText(invoice.PaidStatus)}</div>
                                </div>
                                <div>
                                    <div class="k">Payment Method</div>
                                    <div class="v">${InvoiceHelpers.safeText(invoice.PaymentMethod)}</div>
                                </div>
                                <div>
                                    <div class="k">Validation Status</div>
                                    <div class="v">${InvoiceHelpers.safeText(invoice.ValidationStatus)}</div>
                                </div>
                                <div>
                                    <div class="k">Account Number</div>
                                    <div class="v">${InvoiceHelpers.safeText(invoice.invoiceInstallments && invoice.invoiceInstallments[0] ? invoice.invoiceInstallments[0]["BankAccount"] : null)}</div>
                                </div>
                                <div>
                                    <div class="k">Accounting Date</div>
                                    <div class="v">${InvoiceHelpers.formatDateValue(invoice.AccountingDate)}</div>
                                </div>
                                <div>
                                    <div class="k">Due Date</div>
                                    <div class="v">${InvoiceHelpers.formatDateValue(invoice.invoiceInstallments && invoice.invoiceInstallments[0] ? invoice.invoiceInstallments[0]["DueDate"] : null)}</div>
                                </div>
                                <div>
                                    <div class="k">Payment Date</div>
                                    <div class="v">${InvoiceHelpers.formatDateValue(fPago)}</div>
                                </div>
                            `;
                    $("#row1").append(invoiceRow1Template);

                    $("#row2").html("");
                    let hasLines = false;
                    lines.forEach((line) => {
                        var LineAmount = InvoiceHelpers.formatCurrency(
                            line.LineAmount,
                            "USD",
                        );
                        if (line.LineAmount != 0) {
                            hasLines = true;
                            invoiceRow2Template = `
                                        <tr>
                                            <td >
                                                <h5 class="mt-0 mb-1">${InvoiceHelpers.safeText(line.LineType)}</h5>
                                                <p class="mb-0 text-muted">${InvoiceHelpers.safeText(line.Description)}.</p>
                                            </td>
                                            <td> ${LineAmount}</td>
                                        </tr><!--end tr-->
                                    `;
                            if (line.Description == null) {
                                invoiceRow2Template = `
                                        <tr>
                                            <td >
                                                <h5 class="mt-0 mb-1">${InvoiceHelpers.safeText(line.LineType)}</h5>
                                            </td>
                                            <td> ${LineAmount}</td>
                                        </tr><!--end tr-->
                                    `;
                            }
                            $("#row2").append(invoiceRow2Template);
                        }
                    });

                    if (!hasLines) {
                        $("#row2").append(`
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No invoice details.</td>
                                        </tr>
                                    `);
                    }

                    let saldoPendiente = InvoiceHelpers.formatCurrency(
                        (invoice.InvoiceAmount || 0) -
                            (invoice.AmountPaid || 0),
                        "USD",
                    );
                    $("#invoiceTotals").html(`
                                    <div class="row"><span>Subtotal</span><span>${InvoiceAmount}</span></div>
                                    <div class="row"><span>Amount Paid</span><span>${InvoiceHelpers.formatCurrency(invoice.AmountPaid, "USD")}</span></div>
                                    <div class="row total"><span>Outstanding Balance</span><span>${saldoPendiente}</span></div>
                                `);

                    $("#row3").html("");
                    if (Array.isArray(holds) && holds.length) {
                        holds.forEach((hold) => {
                            const date = InvoiceHelpers.formatDateValue(
                                hold.HoldDate,
                            );
                            const isReleased =
                                hold.ReleaseName !== null &&
                                typeof hold.ReleaseName !== "undefined" &&
                                hold.ReleaseName !== "";
                            const statusChip = isReleased
                                ? '<span class="status-badge status-paid">Released</span>'
                                : '<span class="status-badge status-pending">Active</span>';
                            invoiceRow3Template = `
                                    <div class="invoice-hold-alert">
                                        ${statusChip}
                                        <b>${InvoiceHelpers.safeText(hold.HoldName)}:</b>
                                        ${InvoiceHelpers.safeText(hold.HoldReason)}
                                        — held by ${InvoiceHelpers.safeText(hold.HeldBy)} on ${date}.
                                    </div>
                                `;
                            $("#row3").append(invoiceRow3Template);
                        });
                        $("#BloqueosTabItem").show();
                    } else {
                        $("#BloqueosTabItem").hide();
                    }
                }
                swal.close();

                $("#exampleModalToggle").modal("show");
            },
            error: function (error) {
                InvoiceHelpers.removeInvoiceLoading();
                console.error(error);
            },
            // End
        });
    });
};
// End

// Bind the invoice/shipment view handlers once, delegated on the always-present grid
// container (cards inside it are re-rendered on every page/filter/pill change), and
// load the first page for the default pill ("Por pagar")
$(function () {
    bindInvoiceViewHandler("#invoiceCardsGrid");
    bindTransportViewHandler("#invoiceCardsGrid");
    applyInvoicePillUi(window.InvoiceCards.activeFilter);
    loadInvoiceCardsPage(1);
});

// Bootstrap sets aria-hidden="true" on a modal as soon as it starts hiding, but if the
// element that triggered the close (e.g. #closet-modal) still has focus at that instant,
// screen readers flag it as hiding a focused element. Move focus out first.
$(document).on("hide.bs.modal", ".modal", function () {
    var $focused = $(this).find(":focus");
    if ($focused.length) {
        $focused.trigger("blur");
    }
});

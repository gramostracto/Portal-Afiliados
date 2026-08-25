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

    .stat-card .card-body {
        padding: .9rem 1.1rem;
    }

    .stat-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }

    .stat-icon svg {
        width: 16px;
        height: 16px;
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
        font-size: 1.25rem;
        font-weight: 800;
        color: #16202c;
        margin: 0 0 2px;
        line-height: 1.2;
    }

    .stat-value .spinner-grow {
        width: 1.1rem;
        height: 1.1rem;
    }

    .stat-label {
        margin: 0;
        font-size: .78rem;
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
        list-style: none;
    }

    ul.invoice-main-tabs {
        margin: 4px 0 20px;
    }

    .invoice-main-tabs .nav-item {
        display: flex;
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

    /* --- Grid de tarjetas de facturas/envios (reemplaza las tablas con scroll infinito) --- */
    .invoice-filter-erp.d-none {
        display: none !important;
    }

    .invoice-item-card {
        border: 1px solid #c3ccd6 !important;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(15, 38, 64, .05);
        transition: box-shadow .15s ease, transform .15s ease, border-color .15s ease;
    }

    .invoice-item-card:hover {
        border-color: #98a5b3 !important;
        box-shadow: 0 8px 20px rgba(15, 38, 64, .08);
        transform: translateY(-2px);
    }

    .invoice-item-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #fdf3e9;
        color: #b85e0f;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .invoice-item-title {
        font-weight: 700;
        font-size: .92rem;
        color: #16202c;
        line-height: 1.3;
    }

    .invoice-item-date {
        font-size: .74rem;
        color: #66758a;
    }

    .invoice-item-meta-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        padding: 4px 0;
    }

    .invoice-item-meta-row + .invoice-item-meta-row {
        border-top: 1px dashed #eef1f4;
    }

    .invoice-item-meta .k {
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #66758a;
        margin-bottom: 2px;
    }

    .invoice-item-meta .v {
        font-size: .84rem;
        font-weight: 600;
        color: #16202c;
    }

    .invoice-item-meta-row-balance {
        margin-top: 2px;
        padding: 6px 8px;
        border-top: none !important;
        border-radius: 8px;
        background: #fdecea;
    }

    .invoice-item-meta-row-balance .k {
        color: #c0392b;
    }

    .invoice-item-meta .v-balance {
        color: #c0392b;
        font-size: .92rem;
    }

    #invoiceCardsPagination .page-link {
        border-radius: 8px;
        margin: 0 3px;
        color: #66758a;
        border-color: #e3e7ec;
    }

    #invoiceCardsPagination .page-item.active .page-link {
        background: #e8791a;
        border-color: #e8791a;
        color: #fff;
    }

    #invoiceCardsPagination .page-item.disabled .page-link {
        color: #c3ccd6;
    }

    /* --- Panel de filtros de "Buscar facturas" --- */
    .invoice-filter-toggle {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fdf3e9;
        border: 1px solid #f0dcc4;
        color: #b85e0f;
        font-weight: 600;
        font-size: .85rem;
        border-radius: 999px;
        padding: 6px 16px;
        transition: all .15s ease;
    }

    .invoice-filter-toggle:hover,
    .invoice-filter-toggle:focus {
        background: #f0dcc4;
        color: #b85e0f;
    }

    .invoice-filter-panel {
        background: #fbfcfd;
        border-top: 1px solid #eef1f4;
        border-bottom: 1px solid #eef1f4;
        padding: 18px 20px;
    }

    .invoice-filter-panel .form-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: .78rem;
        font-weight: 600;
        color: #66758a;
        margin-bottom: 6px;
    }

    .invoice-filter-panel .form-label i {
        color: #b85e0f;
        font-size: .8rem;
    }

    .invoice-filter-panel .form-select,
    .invoice-filter-panel .form-control {
        border-radius: 8px;
        border-color: #e3e7ec;
        font-size: .85rem;
    }

    .invoice-filter-actions {
        margin-top: 16px;
        display: flex;
        justify-content: flex-end;
    }

    .invoice-filter-actions .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        padding: 8px 22px;
        font-weight: 600;
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

    /* --- Loading modal (Swal) shown while fetching invoices --- */
    .tcl-loading-popup {
        border-radius: 18px !important;
        padding: 2.25rem 2rem 1.85rem !important;
        background: #ffffff !important;
        text-align: center !important;
    }

    .tcl-loading-icon {
        font-size: 3rem;
        line-height: 1;
        margin: 0 auto .6rem;
        display: block;
        width: fit-content;
        animation: tcl-loading-drive 1.4s ease-in-out infinite;
    }

    @keyframes tcl-loading-drive {

        0%,
        100% {
            transform: translateX(0);
        }

        50% {
            transform: translateX(10px);
        }
    }

    .tcl-loading-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2d3d !important;
        margin-bottom: .3rem;
    }

    .tcl-loading-subtitle {
        font-size: .85rem;
        color: #7c8a9a !important;
    }

    .tcl-loading-popup .swal2-timer-progress-bar {
        background: linear-gradient(90deg, #b85e0f, #f0a04b) !important;
    }
</style>

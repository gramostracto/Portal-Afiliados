(function (window, $) {
    const invoiceDateFormatter = new Intl.DateTimeFormat('es-CO', {
        year: 'numeric',
        month: 'short',
        day: '2-digit'
    });

    const escapeHtml = (value) => String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');

    const safeText = (value, fallback = 'N/D') => {
        if (value === null || typeof value === 'undefined' || value === '') {
            return fallback;
        }
        if (value === 'null' || value === 'undefined') {
            return fallback;
        }
        return escapeHtml(value);
    };

    const formatDateValue = (value, fallback = 'N/D') => {
        if (value === null || typeof value === 'undefined' || value === '') {
            return fallback;
        }
        if (value === '0000-00-00' || value === '0000-00-00 00:00:00') {
            return fallback;
        }
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return value;
        }
        return invoiceDateFormatter.format(date);
    };

    const formatCurrency = (value, currency = 'USD', locale = 'en-US') => {
        const numberValue = Number(value);
        if (Number.isNaN(numberValue)) {
            return safeText(value);
        }
        return new Intl.NumberFormat(locale, {
            style: 'currency',
            currency: currency,
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(numberValue);
    };

    const setInvoiceModalLoading = () => {
        if (!$) return;
        $('#date').html('');

        const $body = $('#body');
        if ($body.length && $body.find('#invoice-loading').length === 0) {
            $body.prepend(`
            <div id="invoice-loading" class="alert alert-info" role="alert">
                Cargando detalle de la factura...
            </div>
            `);
        }

        if ($('#row1').length) {
            if ($('#row1').is('tbody')) {
                $('#row1').html(`
                <tr>
                    <td colspan="7" class="text-center text-muted">Cargando información...</td>
                </tr>
                `);
            } else {
                $('#row1').html('<div class="col-12 text-center text-muted">Cargando información...</div>');
            }
        }

        if ($('#row2').length) {
            $('#row2').html(`
            <tr>
                <td colspan="2" class="text-center text-muted">Cargando conceptos...</td>
            </tr>
            `);
        }

        if ($('#row3').length) {
            $('#row3').html(`
            <tr>
                <td colspan="4" class="text-center text-muted">Cargando bloqueos...</td>
            </tr>
            `);
        }
    };

    const setTransportModalLoading = () => {
        if (!$) return;
        $('#date_1').html('');

        if ($('#row1_1').length) {
            $('#row1_1').html(`
            <tr>
                <td colspan="2" class="text-center text-muted">Cargando propietario...</td>
            </tr>
            `);
        }

        if ($('#row2_2').length) {
            $('#row2_2').html(`
            <tr>
                <td colspan="3" class="text-center text-muted">Cargando conductor...</td>
            </tr>
            `);
        }

        if ($('#row3_3').length) {
            $('#row3_3').html(`
            <tr>
                <td colspan="3" class="text-center text-muted">Cargando resumen...</td>
            </tr>
            `);
        }

        if ($('#row4_4').length) {
            $('#row4_4').html(`
            <tr>
                <td colspan="8" class="text-center text-muted">Cargando ruta...</td>
            </tr>
            `);
        }

        if ($('#row5_5').length) {
            $('#row5_5').html(`
            <tr>
                <td colspan="5" class="text-center text-muted">Cargando vehículo...</td>
            </tr>
            `);
        }
    };

    const removeInvoiceLoading = () => {
        if (!$) return;
        $('#invoice-loading').remove();
    };

    window.InvoiceHelpers = {
        escapeHtml,
        safeText,
        formatDateValue,
        formatCurrency,
        setInvoiceModalLoading,
        setTransportModalLoading,
        removeInvoiceLoading
    };
})(window, window.jQuery);

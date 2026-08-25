/**
 * ScrollTable: tabla simple con scroll infinito sobre un dataset ya cargado en memoria.
 * No pagina contra el servidor: recibe el arreglo completo (setData) y va revelando
 * filas en bloques (pageSize) a medida que el contenedor hace scroll hacia el fondo.
 * Incluye filtro de texto simple y exportacion a CSV.
 */
(function(window, $) {
    'use strict';

    function escapeHtml(value) {
        if (value === null || value === undefined) return '';
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function ScrollTable(tableSelector, columns, options) {
        options = options || {};
        this.$table = $(tableSelector);
        this.columns = columns;
        this.pageSize = options.pageSize || 20;
        this.emptyMessage = options.emptyMessage || 'No se encontraron resultados';
        this.allData = [];
        this.filteredData = [];
        this.rendered = 0;

        this._buildHead();
        this._ensureTbody();
        this._wrapScrollContainer(options.maxHeight || 460);
        this._bindScroll();
    }

    ScrollTable.prototype._buildHead = function() {
        var $thead = this.$table.find('thead');
        if (!$thead.length) {
            $thead = $('<thead></thead>').prependTo(this.$table);
        }
        var headHtml = '<tr>' + this.columns.map(function(col) {
            return '<th>' + escapeHtml(col.title) + '</th>';
        }).join('') + '</tr>';
        $thead.html(headHtml);
    };

    ScrollTable.prototype._ensureTbody = function() {
        this.$tbody = this.$table.find('tbody');
        if (!this.$tbody.length) {
            this.$tbody = $('<tbody></tbody>').appendTo(this.$table);
        }
    };

    ScrollTable.prototype._wrapScrollContainer = function(maxHeight) {
        if (this.$table.parent().hasClass('scroll-table-viewport')) {
            this.$scrollBox = this.$table.parent();
            return;
        }
        this.$scrollBox = this.$table.wrap(
            '<div class="scroll-table-viewport" style="max-height:' + maxHeight + 'px;"></div>'
        ).parent();
    };

    ScrollTable.prototype._bindScroll = function() {
        var self = this;
        this.$scrollBox.off('scroll.scrolltable').on('scroll.scrolltable', function() {
            var el = self.$scrollBox[0];
            if (el.scrollTop + el.clientHeight >= el.scrollHeight - 80) {
                self._renderNextChunk();
            }
        });
    };

    ScrollTable.prototype.setData = function(data) {
        this.allData = Array.isArray(data) ? data : [];
        this.filter(this._lastTerm || '');
    };

    ScrollTable.prototype.filter = function(term) {
        this._lastTerm = term || '';
        var normalized = this._lastTerm.toLowerCase().trim();
        if (!normalized) {
            this.filteredData = this.allData;
        } else {
            this.filteredData = this.allData.filter(function(row) {
                return JSON.stringify(row).toLowerCase().indexOf(normalized) !== -1;
            });
        }
        this.rendered = 0;
        this.$tbody.empty();
        this.$scrollBox.scrollTop(0);
        this._renderNextChunk();
    };

    ScrollTable.prototype._renderNextChunk = function() {
        if (!this.filteredData.length) {
            this.$tbody.html(
                '<tr><td colspan="' + this.columns.length +
                '" class="text-center text-muted py-4">' + escapeHtml(this.emptyMessage) + '</td></tr>'
            );
            return;
        }
        if (this.rendered >= this.filteredData.length) return;

        var self = this;
        var chunk = this.filteredData.slice(this.rendered, this.rendered + this.pageSize);
        var rowsHtml = chunk.map(function(rowData, i) {
            var idx = self.rendered + i;
            var cells = self.columns.map(function(col) {
                var value = col.render ? col.render(rowData) : '';
                return '<td>' + value + '</td>';
            }).join('');
            return '<tr data-row-index="' + idx + '">' + cells + '</tr>';
        }).join('');

        this.$tbody.append(rowsHtml);
        this.rendered += chunk.length;
    };

    ScrollTable.prototype.getRowData = function(trElement) {
        var idx = parseInt($(trElement).attr('data-row-index'), 10);
        return this.filteredData[idx];
    };

    ScrollTable.prototype.exportCsv = function(filename) {
        var self = this;
        var exportableColumns = this.columns.filter(function(col) {
            return col.exportable !== false;
        });
        var header = exportableColumns.map(function(col) {
            return '"' + String(col.title).replace(/"/g, '""') + '"';
        }).join(',');
        var rows = this.filteredData.map(function(rowData) {
            return exportableColumns.map(function(col) {
                var raw = col.exportValue ? col.exportValue(rowData) : (col.render ? col.render(rowData) : '');
                var plain = String(raw).replace(/<[^>]*>/g, '').replace(/"/g, '""');
                return '"' + plain + '"';
            }).join(',');
        });
        var csv = [header].concat(rows).join('\r\n');
        var blob = new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8;' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = (filename || 'export') + '.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(link.href);
    };

    window.ScrollTable = ScrollTable;
})(window, jQuery);

(function ($) {
    'use strict';

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function downloadCsv(csvContent, filename) {
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);

        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }

    function initDeoDashboardStats() {
        const $root = $('#deo-dashboard-stats');
        if (!$root.length) {
            return;
        }

        const cardsUrl = $root.data('cards-url');
        const exportUrl = $root.data('export-url');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const $range = $('#deo-ds-range');
        const $from = $('#deo-ds-from');
        const $to = $('#deo-ds-to');
        const $grid = $('#deo-ds-grid');

        function applyPreset(range) {
            const today = new Date();
            let from = today;
            let to = today;

            if (range === 'last_7_days') {
                from = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 6);
            } else if (range === 'last_30_days') {
                from = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 29);
            } else if (range === 'custom') {
                return;
            }

            $from.val(formatDate(from));
            $to.val(formatDate(to));
        }

        function ensureDefaultDates() {
            if (!$from.val() || !$to.val()) {
                $range.val('today');
                applyPreset('today');
            }
        }

        function validateDates(required) {
            ensureDefaultDates();

            const from = $from.val();
            const to = $to.val();

            if (required && (!from || !to)) {
                if (window.toastr) {
                    toastr.error('Please select both from and to dates', 'Validation Error');
                }
                return false;
            }

            if (from && to && from > to) {
                if (window.toastr) {
                    toastr.error('From date cannot be greater than to date', 'Validation Error');
                }
                return false;
            }

            return {
                date_from: from || '',
                date_to: to || ''
            };
        }

        function renderCards(items) {
            if (!items.length) {
                $grid.html('<div class="deo-ds-empty"><i class="ri-user-unfollow-line me-1" aria-hidden="true"></i>No DEO records found for this period.</div>');
                return;
            }

            const tones = ['primary', 'info', 'success', 'warning', 'secondary'];
            const html = items.map(function (deo, index) {
                const tone = tones[index % tones.length];
                const statusClass = deo.is_active ? 'is-active' : 'is-inactive';
                const statusLabel = deo.is_active ? 'Active' : 'Inactive';
                const statusIcon = deo.is_active ? 'ri-checkbox-circle-line' : 'ri-close-circle-line';
                const avatar = deo.image_url
                    ? '<img class="deo-ds-avatar" src="' + escapeHtml(deo.image_url) + '" alt="' + escapeHtml(deo.name) + '">'
                    : '<i class="ri-user-star-line" aria-hidden="true"></i>';

                return [
                    '<article class="pos-glass-card pos-tone-' + tone + ' h-100 deo-ds-card">',
                    '  <div class="pos-stat-body">',
                    '    <div class="pos-stat-head">',
                    '      <span class="pos-stat-icon' + (deo.image_url ? ' has-avatar' : '') + '">' + avatar + '</span>',
                    '      <h6 class="pos-stat-label">' + escapeHtml(deo.name) + '</h6>',
                    '    </div>',
                    '    <p class="deo-ds-email">' + escapeHtml(deo.email) + '</p>',
                    '    <div class="deo-ds-metrics">',
                    '      <div class="deo-ds-metric pos-tone-warning">',
                    '        <p class="deo-ds-metric-label">Defect Reports</p>',
                    '        <p class="deo-ds-metric-value">' + deo.defect_reports_count + '</p>',
                    '      </div>',
                    '      <div class="deo-ds-metric pos-tone-success">',
                    '        <p class="deo-ds-metric-label">Purchase Orders</p>',
                    '        <p class="deo-ds-metric-value">' + deo.purchase_orders_count + '</p>',
                    '      </div>',
                    '    </div>',
                    '    <p class="deo-ds-status ' + statusClass + '">',
                    '      <i class="' + statusIcon + '" aria-hidden="true"></i>' + statusLabel,
                    '    </p>',
                    '  </div>',
                    '</article>'
                ].join('');
            }).join('');

            $grid.html(html);
        }

        function updateTotals(totals) {
            $('#deo-ds-total-deos span').text((totals.deos || 0) + ' DEOs');
            $('#deo-ds-total-dr span').text((totals.defect_reports || 0) + ' Defect Reports');
            $('#deo-ds-total-po span').text((totals.purchase_orders || 0) + ' Purchase Orders');
        }

        function loadCards() {
            const dates = validateDates(true);
            if (!dates) {
                return;
            }

            $grid.html('<div class="deo-ds-loading"><i class="ri-loader-4-line me-1" aria-hidden="true"></i>Loading DEO stats...</div>');

            $.ajax({
                url: cardsUrl,
                type: 'GET',
                cache: false,
                data: dates,
                success: function (response) {
                    if (!response.success) {
                        if (window.toastr) {
                            toastr.error(response.message || 'Failed to load DEO stats', 'Error');
                        }
                        $grid.html('<div class="deo-ds-empty">Unable to load DEO stats.</div>');
                        return;
                    }

                    updateTotals(response.totals || {});
                    renderCards(response.data || []);
                },
                error: function () {
                    if (window.toastr) {
                        toastr.error('Failed to load DEO stats', 'Error');
                    }
                    $grid.html('<div class="deo-ds-empty">Unable to load DEO stats.</div>');
                }
            });
        }

        function exportCsv() {
            // Empty dates → current day (server defaults); UI unchanged
            const dates = validateDates(false);
            if (!dates || !exportUrl) {
                return;
            }

            if (window.toastr) {
                toastr.info('Preparing CSV export...', 'Please wait');
            }

            $.ajax({
                url: exportUrl,
                type: 'POST',
                data: dates,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    if (!response.success) {
                        if (window.toastr) {
                            toastr.error(response.message || 'Export failed', 'Export Error');
                        }
                        return;
                    }

                    downloadCsv(response.data, response.filename || 'deo_performance.csv');
                    if (window.toastr) {
                        toastr.success('Export completed successfully!', 'Export');
                    }
                },
                error: function (xhr) {
                    const message = xhr.status === 403
                        ? 'You do not have permission to export data'
                        : 'Export failed. Please try again.';
                    if (window.toastr) {
                        toastr.error(message, 'Export Error');
                    }
                }
            });
        }

        $range.on('change', function () {
            const range = $(this).val();
            applyPreset(range);
            if (range !== 'custom') {
                // Force reload with the newly filled From/To
                loadCards();
            }
        });

        $from.add($to).on('change', function () {
            $range.val('custom');
        });

        $('#deo-ds-apply').on('click', loadCards);
        $('#deo-ds-export').on('click', exportCsv);

        $('#deo-ds-clear').on('click', function () {
            $range.val('today');
            applyPreset('today');
            loadCards();
        });

        $from.add($to).on('keypress', function (e) {
            if (e.which === 13) {
                loadCards();
            }
        });

        applyPreset('today');
        loadCards();
    }

    $(document).ready(initDeoDashboardStats);
})(jQuery);

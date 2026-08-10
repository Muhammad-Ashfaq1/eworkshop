(function ($) {
    'use strict';

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function initDeoDashboardStats() {
        const $root = $('#deo-dashboard-stats');
        if (!$root.length) {
            return;
        }

        const cardsUrl = $root.data('cards-url');
        const $range = $('#deo-ds-range');
        const $from = $('#deo-ds-from');
        const $to = $('#deo-ds-to');
        const $grid = $('#deo-ds-grid');

        function applyPreset(range) {
            const today = new Date();
            let from = today;
            let to = today;

            if (range === 'last_7_days') {
                from = new Date(today.getTime() - (7 * 24 * 60 * 60 * 1000));
            } else if (range === 'last_30_days') {
                from = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
            } else if (range === 'custom') {
                return;
            }

            $from.val(formatDate(from));
            $to.val(formatDate(to));
        }

        function toggleCustom(isCustom) {
            $from.prop('readonly', !isCustom);
            $to.prop('readonly', !isCustom);
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
                    '      <div class="deo-ds-metric is-dr">',
                    '        <p class="deo-ds-metric-label">Defect Reports</p>',
                    '        <p class="deo-ds-metric-value">' + deo.defect_reports_count + '</p>',
                    '      </div>',
                    '      <div class="deo-ds-metric is-po">',
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
            if (!$from.val() || !$to.val()) {
                if (window.toastr) {
                    toastr.error('Please select both from and to dates', 'Validation Error');
                }
                return;
            }

            if ($from.val() > $to.val()) {
                if (window.toastr) {
                    toastr.error('From date cannot be greater than to date', 'Validation Error');
                }
                return;
            }

            $grid.html('<div class="deo-ds-loading"><i class="ri-loader-4-line me-1" aria-hidden="true"></i>Loading DEO stats...</div>');

            $.ajax({
                url: cardsUrl,
                type: 'GET',
                data: {
                    date_from: $from.val(),
                    date_to: $to.val()
                },
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

        $range.on('change', function () {
            const range = $(this).val();
            toggleCustom(range === 'custom');
            applyPreset(range);
            if (range !== 'custom') {
                loadCards();
            }
        });

        $('#deo-ds-apply').on('click', loadCards);

        $('#deo-ds-clear').on('click', function () {
            $range.val('today');
            toggleCustom(false);
            applyPreset('today');
            loadCards();
        });

        applyPreset('today');
        toggleCustom(false);
        loadCards();
    }

    $(document).ready(initDeoDashboardStats);
})(jQuery);

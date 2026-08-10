<div class="deo-ds-section" id="deo-dashboard-stats" data-cards-url="{{ route('admin.deo-stats.cards') }}">
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="pos-glass-card pos-tone-primary">
                <div class="pos-glass-intro">
                    <div class="pos-glass-intro-copy">
                        <h4 class="pos-glass-intro-title">DEO Performance</h4>
                        <p class="pos-glass-intro-subtitle mb-0">
                            Defect reports and purchase orders created by each DEO for the selected date range.
                        </p>
                    </div>
                    <div class="pos-glass-intro-actions">
                        <span class="pos-glass-pill pos-tone-info" id="deo-ds-total-deos">
                            <i class="ri-team-line" aria-hidden="true"></i>
                            <span>0 DEOs</span>
                        </span>
                        <span class="pos-glass-pill pos-tone-warning" id="deo-ds-total-dr">
                            <i class="ri-file-damage-line" aria-hidden="true"></i>
                            <span>0 Defect Reports</span>
                        </span>
                        <span class="pos-glass-pill pos-tone-success" id="deo-ds-total-po">
                            <i class="ri-shopping-cart-line" aria-hidden="true"></i>
                            <span>0 Purchase Orders</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="pos-glass-card pos-tone-secondary">
                <div class="pos-glass-intro">
                    <div class="deo-ds-filters w-100">
                        <div class="pos-glass-control">
                            <label class="pos-glass-control-label" for="deo-ds-range">Date Range</label>
                            <select class="form-select form-select-sm" id="deo-ds-range">
                                <option value="today" selected>Today</option>
                                <option value="last_7_days">Last 7 Days</option>
                                <option value="last_30_days">Last 30 Days</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="pos-glass-control">
                            <label class="pos-glass-control-label" for="deo-ds-from">From</label>
                            <input type="date" class="form-control form-control-sm" id="deo-ds-from" readonly>
                        </div>
                        <div class="pos-glass-control">
                            <label class="pos-glass-control-label" for="deo-ds-to">To</label>
                            <input type="date" class="form-control form-control-sm" id="deo-ds-to" readonly>
                        </div>
                        <div class="deo-ds-filter-actions">
                            <button type="button" class="btn btn-sm btn-primary" id="deo-ds-apply">
                                <i class="ri-search-line me-1" aria-hidden="true"></i>Filter
                            </button>
                            <button type="button" class="btn btn-sm btn-soft-secondary" id="deo-ds-clear">
                                <i class="ri-refresh-line me-1" aria-hidden="true"></i>Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="deo-ds-grid" id="deo-ds-grid" aria-live="polite">
        <div class="deo-ds-loading">
            <i class="ri-loader-4-line me-1" aria-hidden="true"></i>Loading DEO stats...
        </div>
    </div>
</div>

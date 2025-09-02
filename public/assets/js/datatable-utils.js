/**
 * DataTable Utilities for eworkshop
 * Provides reusable functions for consistent DataTable behavior
 */

/**
 * Fix DataTable controls layout by moving them outside the scroll area
 * @param {string} tableId - The table ID (e.g., '#js-purchase-order-table')
 * @param {string} topWrapperSelector - The selector for top controls wrapper (default: '#datatable-controls-wrapper')
 * @param {string} bottomWrapperSelector - The selector for bottom controls wrapper (default: '#datatable-bottom-wrapper')
 */
function fixDataTableControlsLayout(tableId, topWrapperSelector = '#datatable-controls-wrapper', bottomWrapperSelector = '#datatable-bottom-wrapper') {
    setTimeout(function() {
        // Get the DataTable wrapper for this specific table
        var $tableWrapper = $(tableId).closest('.dataTables_wrapper');
        
        // Move search and length controls to top wrapper
        var $lengthControl = $tableWrapper.find('.dataTables_length').detach();
        var $filterControl = $tableWrapper.find('.dataTables_filter').detach();
        var $topWrapper = $(topWrapperSelector);
        
        if ($topWrapper.length && ($lengthControl.length || $filterControl.length)) {
            // Create a row for top controls
            $topWrapper.html('<div class="row mb-3"><div class="col-sm-6"></div><div class="col-sm-6 text-end"></div></div>');
            
            if ($lengthControl.length) {
                $topWrapper.find('.col-sm-6:first').append($lengthControl);
            }
            if ($filterControl.length) {
                $topWrapper.find('.col-sm-6:last').append($filterControl);
            }
        }
        
        // Move info and pagination to bottom wrapper
        var $infoControl = $tableWrapper.find('.dataTables_info').detach();
        var $paginateControl = $tableWrapper.find('.dataTables_paginate').detach();
        var $bottomWrapper = $(bottomWrapperSelector);
        
        if ($bottomWrapper.length && ($infoControl.length || $paginateControl.length)) {
            // Create a row for bottom controls
            $bottomWrapper.html('<div class="row mt-3"><div class="col-sm-6"></div><div class="col-sm-6 text-end"></div></div>');
            
            if ($infoControl.length) {
                $bottomWrapper.find('.col-sm-6:first').append($infoControl);
            }
            if ($paginateControl.length) {
                $bottomWrapper.find('.col-sm-6:last').append($paginateControl);
            }
        }
        
        // Clean up empty DataTable wrapper elements
        $tableWrapper.find('> .row').each(function() {
            var $row = $(this);
            var hasControls = $row.find('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').length > 0;
            var hasContent = $row.children().length > 0 && $row.text().trim().length > 0;
            
            if (!hasControls && !hasContent) {
                $row.hide();
            }
        });
        
        // Ensure the table wrapper doesn't interfere with layout
        $tableWrapper.css('overflow', 'visible');
        
    }, 100);
}

/**
 * Initialize a clean DataTable with fixed controls
 * @param {string} tableId - The table ID
 * @param {object} options - DataTable options
 * @param {string} topWrapper - Top controls wrapper selector
 * @param {string} bottomWrapper - Bottom controls wrapper selector
 * @returns {object} DataTable instance
 */
function initCleanDataTable(tableId, options = {}, topWrapper = '#datatable-controls-wrapper', bottomWrapper = '#datatable-bottom-wrapper') {
    // Default options
    const defaultOptions = {
        pageLength: 20,
        searching: true,
        lengthMenu: [
            [20, 30, 50, 100],
            ["20 entries", "30 entries", "50 entries", "100 entries"]
        ],
        processing: true,
        serverSide: false,
        responsive: false,
        autoWidth: false
    };
    
    // Merge options
    const finalOptions = { ...defaultOptions, ...options };
    
    // Initialize DataTable
    const table = $(tableId).DataTable(finalOptions);
    
    // Fix controls layout
    fixDataTableControlsLayout(tableId, topWrapper, bottomWrapper);
    
    return table;
}

/**
 * Add the required HTML structure for fixed controls to a card body
 * @param {string} cardBodySelector - The card body selector
 * @param {string} tableHtml - The table HTML
 * @returns {string} Complete HTML with fixed controls structure
 */
function addFixedControlsStructure(tableHtml) {
    return `
        <!-- DataTable Controls Area (Fixed) -->
        <div id="datatable-controls-wrapper">
            <!-- DataTable controls will be moved here -->
        </div>
        
        <!-- Table Scroll Area -->
        <div class="table-responsive">
            ${tableHtml}
        </div>
        
        <!-- DataTable Info and Pagination Area (Fixed) -->
        <div id="datatable-bottom-wrapper">
            <!-- DataTable info and pagination will be moved here -->
        </div>
    `;
} 
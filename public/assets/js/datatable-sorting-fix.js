/**
 * DataTable Sorting Icons Fix
 * Ensures proper sorting class application and icon visibility
 */

$(document).ready(function() {
    // Fix for DataTable sorting icons
    $(document).on('init.dt', function(e, settings) {
        var api = new $.fn.dataTable.Api(settings);
        var table = api.table();
        
        // Ensure sorting classes are properly applied
        setTimeout(function() {
            $(table.header()).find('th').each(function() {
                var $th = $(this);
                
                // Add sorting class if column is orderable and doesn't have it
                if (api.column($th.index()).orderable() && 
                    !$th.hasClass('sorting') && 
                    !$th.hasClass('sorting_asc') && 
                    !$th.hasClass('sorting_desc')) {
                    $th.addClass('sorting');
                }
                
                // Ensure proper cursor for sortable columns
                if ($th.hasClass('sorting') || $th.hasClass('sorting_asc') || $th.hasClass('sorting_desc')) {
                    $th.css('cursor', 'pointer');
                }
            });
        }, 100);
    });
    
    // Handle sorting state changes
    $(document).on('order.dt', function(e, settings) {
        var api = new $.fn.dataTable.Api(settings);
        var table = api.table();
        
        setTimeout(function() {
            $(table.header()).find('th').each(function() {
                var $th = $(this);
                var colIdx = $th.index();
                
                // Get current order
                var order = api.order();
                var isCurrentlySorted = false;
                var sortDirection = '';
                
                // Check if this column is currently sorted
                for (var i = 0; i < order.length; i++) {
                    if (order[i][0] === colIdx) {
                        isCurrentlySorted = true;
                        sortDirection = order[i][1];
                        break;
                    }
                }
                
                // Remove all sorting classes
                $th.removeClass('sorting sorting_asc sorting_desc');
                
                // Add appropriate class
                if (isCurrentlySorted) {
                    if (sortDirection === 'asc') {
                        $th.addClass('sorting_asc');
                    } else {
                        $th.addClass('sorting_desc');
                    }
                } else if (api.column(colIdx).orderable()) {
                    $th.addClass('sorting');
                }
            });
        }, 50);
    });
    
    // Additional fix for tables that are already initialized
    setTimeout(function() {
        $('.dataTable').each(function() {
            if ($.fn.DataTable.isDataTable(this)) {
                var api = $(this).DataTable();
                
                $(this).find('thead th').each(function() {
                    var $th = $(this);
                    var colIdx = $th.index();
                    
                    if (api.column(colIdx).orderable()) {
                        if (!$th.hasClass('sorting') && 
                            !$th.hasClass('sorting_asc') && 
                            !$th.hasClass('sorting_desc')) {
                            $th.addClass('sorting');
                        }
                        $th.css('cursor', 'pointer');
                    }
                });
            }
        });
    }, 500);
});

/**
 * Force refresh sorting icons for a specific table
 * @param {string} tableId - The table ID (e.g., '#js-vehicle-table')
 */
function refreshSortingIcons(tableId) {
    if ($.fn.DataTable.isDataTable(tableId)) {
        var api = $(tableId).DataTable();
        
        $(tableId).find('thead th').each(function() {
            var $th = $(this);
            var colIdx = $th.index();
            
            if (api.column(colIdx).orderable()) {
                if (!$th.hasClass('sorting') && 
                    !$th.hasClass('sorting_asc') && 
                    !$th.hasClass('sorting_desc')) {
                    $th.addClass('sorting');
                }
                $th.css('cursor', 'pointer');
            }
        });
        
        console.log('Sorting icons refreshed for', tableId);
    }
} 
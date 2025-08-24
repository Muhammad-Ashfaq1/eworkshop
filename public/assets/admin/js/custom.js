function getDynamicDropdownData(url, target) {
    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            if (!response.success) {
                console.error('Failed to fetch data:', response.message);
                return;
            }
            $(target).empty();
            $(target).append($('<option></option>').attr('value', '').text('Select...').prop('disabled', true).prop('selected', true));
            $.each(response.data, function(index, item) {
                $(target).append($('<option></option>').attr('value', item.id).text(item.name));
            });
            
            // Initialize Select2 after populating options
            if ($(target).hasClass('select2-hidden-accessible')) {
                $(target).select2('destroy');
            }
            $(target).select2({
                placeholder: 'Select...',
                allowClear: true,
                width: '100%',
                dropdownParent: $(target).closest('.modal').length ? $(target).closest('.modal') : 'body'
            });
        },
        error: function(xhr) {
            console.error('Error fetching dynamic dropdown data:', xhr);
        }
    });
}

// Function to initialize Select2 for existing dropdowns
function initializeSelect2(selector) {
    $(selector).select2({
        placeholder: 'Select...',
        allowClear: true,
        width: '100%',
        dropdownParent: $(selector).closest('.modal').length ? $(selector).closest('.modal') : 'body'
    });
}

// Function to destroy Select2
function destroySelect2(selector) {
    if ($(selector).hasClass('select2-hidden-accessible')) {
        $(selector).select2('destroy');
    }
}

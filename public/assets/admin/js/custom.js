
// Function to get dynamic dropdown data
function getDynamicDropdownData(url, target, callback, useSelect2 = true) {
    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            console.log('custom js response' + response)
            if (!response.success) {
                console.error('Failed to fetch data:', response.message);
                return;
            }
            $(target).empty();
            $(target).append($('<option></option>').attr('value', '').text('Select...').prop('disabled', true).prop('selected', true));
            $.each(response.data, function(index, item) {
                $(target).append($('<option></option>').attr('value', item.id).text(item.name));
            });

            // Some forms use native selects for reliable browser Tab navigation.
            if (!useSelect2) {
                if (typeof callback === 'function') callback();
                return;
            }

            // Initialize Select2 after populating options
            if ($(target).hasClass('select2-hidden-accessible')) {
                $(target).select2('destroy');
            }
            // Determine the correct dropdownParent (use modal if inside one, otherwise use parent)
            var $modal = $(target).closest('.modal');
            var dropdownParent = $modal.length ? $modal : $(target).parent();

            $(target).select2({
                placeholder: 'Select...',
                allowClear: false,
                width: '100%',
                dropdownParent: dropdownParent
            });


            // Execute callback if provided
            if (typeof callback === 'function') {
                callback();
            }
        },
        error: function(xhr) {
            console.error('Error fetching dynamic dropdown data:', xhr);
        }
    });
}

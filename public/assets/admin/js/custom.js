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
        },
        error: function(xhr) {
            console.error('Error fetching dynamic dropdown data:', xhr);
        }
    });
};

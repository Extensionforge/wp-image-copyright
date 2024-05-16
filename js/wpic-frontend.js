jQuery(document).ready(function($) {
    // Search functionality with input validation
    $('#wpic-search').on('input', function() {
        var searchTerm = $(this).val().toLowerCase();
        // Remove special characters
        searchTerm = searchTerm.replace(/[^a-zA-Z0-9 ]/g, '');
        $(this).val(searchTerm);

        if (searchTerm.length >= 3) {
            $('#wpic-entries .wpic-column-item, #wpic-entries .wpic-list-item').each(function() {
                var text = $(this).text().toLowerCase();
                if (text.indexOf(searchTerm) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        } else {
            $('#wpic-entries .wpic-column-item, #wpic-entries .wpic-list-item').show();
        }
    });
});

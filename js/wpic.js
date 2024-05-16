jQuery(document).ready(function($) {
    // Move the custom fields container to be after the table
    $('.compat-attachment-fields').each(function() {
        var $table = $(this);
        var $container = $table.find('.wpic-field-container');
        $table.after($container);
    });

    $('#wpic_showpage_checkbox').change(function() {
        if (!this.checked) {
            var result = confirm("Möchten Sie die gefundenen Daten behalten oder löschen?");
            if (!result) {
                $('#wpic_main_settings').append('<input type="hidden" name="wpic_delete_scanned_data" value="1">');
            }
        }
    });
});

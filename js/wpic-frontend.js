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

    // Anzahl der Einträge filtern
    $('#wpic-entries-per-page').on('change', function() {
        var selectedValue = $(this).val();
        $('.wpic-list-item, .wpic-column-item').slice(selectedValue).hide();
        $('.wpic-list-item, .wpic-column-item').slice(0, selectedValue).show();
    });

    // Portal filtern
    $('#wpic-portal-filter').on('change', function() {
        var selectedPortal = $(this).val().toLowerCase();
        if (selectedPortal === '') {
            $('.wpic-list-item, .wpic-column-item').show();
        } else {
            $('.wpic-list-item, .wpic-column-item').each(function() {
                var portal = $(this).data('portal').toLowerCase();
                if (portal === selectedPortal) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });

    // Trigger change event to initialize the view
    $('#wpic-entries-per-page').trigger('change');
});

jQuery(document).ready(function($) {
    let currentPage = 1;
    let entriesPerPage = 20;

    // Initial load
    loadEntries();

    // Search functionality with input validation
    $('#wpic-search').on('input', function() {
        loadEntries();
    });

    // Anzahl der Einträge filtern
    $('#wpic-entries-per-page').on('change', function() {
        entriesPerPage = parseInt($(this).val());
        currentPage = 1; // Reset to first page on entries per page change
        loadEntries();
    });

    // Portal filtern
    $('#wpic-portal-filter').on('change', function() {
        currentPage = 1; // Reset to first page on portal filter change
        loadEntries();
    });

    // Pagination
    $('#wpic-prev-page').on('click', function() {
        if (currentPage > 1) {
            currentPage--;
            loadEntries();
        }
    });

    $('#wpic-next-page').on('click', function() {
        currentPage++;
        loadEntries();
    });

    function loadEntries() {
        let searchTerm = $('#wpic-search').val().toLowerCase();
        let selectedPortal = $('#wpic-portal-filter').val().toLowerCase();

        let visibleEntries = 0;

        $('#wpic-entries .wpic-entry').each(function(index) {
            let text = $(this).text().toLowerCase();
            let portal = $(this).data('portal').toLowerCase();
            let showEntry = true;

            if (searchTerm.length >= 3 && text.indexOf(searchTerm) === -1) {
                showEntry = false;
            }

            if (selectedPortal && portal !== selectedPortal) {
                showEntry = false;
            }

            if (showEntry) {
                visibleEntries++;
            }

            $(this).toggle(showEntry);
        });

        paginateEntries(visibleEntries);
    }

    function paginateEntries(visibleEntries) {
        let totalPages = Math.ceil(visibleEntries / entriesPerPage);
        if (totalPages < currentPage) {
            currentPage = totalPages;
        }

        $('#wpic-page-numbers').text(`Seite ${currentPage} von ${totalPages}`);

        let start = (currentPage - 1) * entriesPerPage;
        let end = start + entriesPerPage;

        let visibleCount = 0;
        $('#wpic-entries .wpic-entry').each(function(index) {
            if ($(this).is(':visible')) {
                if (visibleCount >= start && visibleCount < end) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
                visibleCount++;
            }
        });

        $('#wpic-prev-page').prop('disabled', currentPage === 1);
        $('#wpic-next-page').prop('disabled', currentPage === totalPages);
    }
});

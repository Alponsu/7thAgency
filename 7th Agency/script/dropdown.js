$(document).ready(function() {
    $('#schoolName').on('input', function() {
        let query = $(this).val();
        if (query.length > 0) {
            $.ajax({
                url: 'search.php',
                method: 'POST',
                data: { query: query },
                success: function(data) {
                    $('#schoolDropdown').html(data).show();
                }
            });
        } else {
            $('#schoolDropdown').hide();
        }
    });

    $(document).on('click', '.dropdown-item', function() {
        $('#schoolName').val($(this).data('schoolname'));
        $('#location').val($(this).data('location'));
        $('#schoolDropdown').hide();
    });
});
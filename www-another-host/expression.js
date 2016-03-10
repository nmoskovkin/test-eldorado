$(function(){
    $('form').on('submit', function(e) {
        $.ajax({
            url: $(this).attr('action'),
            type: 'GET',
            jsonp: "jsonp_callback",
            dataType: "jsonp",
            data: $(this).serialize(),
            success: function(response) {
                $('#evaluating-result').html(response.status ? response.result : response.error);
            }
            // Error handling will be here.
            // And maybe stop request if previous request are processing.
        });

        e.preventDefault();
    });
});
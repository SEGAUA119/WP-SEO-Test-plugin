(function($) {

    $(document).ready(function() {

        let search = '';

        $('#wpss-search-form').on( 'submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#update-success').hide();

            var input_text = $(this).find('#post-search-input').val()
            $.ajax({
                type : "get",
                url : ajax.url,
                data : {
                    nonce: ajax.nonce,
                    action: "search_posts",
                    search: input_text
                },
                success: function(response) {
                    search = input_text;
                    $('#wpss-posts-list').html(response);
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });

        $('.wpss-change-form').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#update-success').hide();

            jQuery.ajax({
                type : "post",
                url : ajax.url,
                data : {
                    nonce: ajax.nonce,
                    action: "update_posts",
                    search: search,
                    update: $(this).find('#post-update-input').val(),
                    field: $(this).find('input[name="field"]').val()
                },
                success: function(response) {
                    $('#wpss-posts-list').html(response);
                    $('#update-success').show();
                },
                error: function(e) {

                }
            });
        });
    });

})(jQuery);
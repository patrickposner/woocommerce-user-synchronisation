jQuery(document).ready(function( $ ) {

    $('#wc_settings_tab_user_synchronisation_run_transfer').on('click', function( event ) {
        event.preventDefault();
        $('.lds-ellipsis').show();
        $.ajax({
            url: ajax_object.ajax_url,
            type : 'post',
            data : {
                action : 'send_users',
            },
            success: function( data ) {
                $('#wc_settings_tab_user_synchronisation_run_transfer').after( "<p>Users were sucessfully transfered!</p>" );
            },
            complete: function() {
                $('.lds-ellipsis').hide();
            }
        })

    });

});
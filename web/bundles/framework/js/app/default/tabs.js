jQuery( document ).ready(function() {

});
$(function() {

    $('#tabs-list a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show')
    })

    function showAllErrorMessages() {

        // Restore tabs to normal
        $( ".card-header-tabs" ).find( "a" ).each( function( index, node ) {
            // if ( $( this ).hasClass( "active" ) )
            // {
            //     $( this ).css("background-color", "rgba(255,255,255,1)");
            //     $( this ).css("color", "rgba(73,80,87,1)");
            // }
            // else
            // {
                $( this ).css("background-color", "#f8f8f8 !important");
                $( this ).css("color", "#495057");
            // }
        });

        var $form_OK = true;

        $( "#item-form" ).find( ":invalid" ).each( function( index, node ) {

            $form_OK = false;
            $tab_to_show = '';
            // Find the tab-pane that this element is inside, and get the id
            var $closest_tab = $(this).closest('.tab-pane');
            var $closest_tab_id = $closest_tab.attr('id');

            // Find the link that corresponds to the pane
            // mark tab in red
            // and have it shown
            var $tab_a = $( '#'+$closest_tab_id + '-tab' );
            $( $tab_a ).css("background-color", "rgba(255,0,0,0.60)");
            $( $tab_a ).css("color", "rgba(255,255,255,1)");
            if ( $tab_to_show == '' ) $tab_to_show = $closest_tab_id;

            // Add class invalid -> border color red
            $(this).addClass( "is-invalid" );

        });
        // Show the latest tab with errors
        if ( $tab_to_show != '' )
        {
            $( "#"+$tab_to_show+"-tab" ).css("background-color", "rgba(255,0,0,1)");
            $( "#"+$tab_to_show+"-tab" ).tab('show');
        }

        // Only want to do it once
        return $form_OK;
    };

    $( "#btn_submit" ).on("click", function ( e ){
        //e.preventDefault();
        if ( showAllErrorMessages() )
        {
            $('#item-form').submit();
        }
        else
        {
            $("html, body").animate({ scrollTop: $("#scroolto").offset().top }, 1000);
        }
    });

});
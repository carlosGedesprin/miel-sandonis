$(document).ready(function() {

});

$(function() {
    'use strict';

    $(document).on('keyup',function(e) {
        //console.log('Key :'+e.which);
        if(e.which == 13) {
            // Prevent using key Enter
            e.preventDefault();
        }
        if(e.which == 27) {
            // Go to list on escape
            //console.log('Escape 27');
            $("#register_form").attr('method', 'get');
            $("#register_form").attr('action', '/');
            $("#register_form").submit();
        }
    });
});

function scrool_to_top_of_page() {
    $("html, body").animate({scrollTop: $("#topofthepage").offset().top}, 1000);
}

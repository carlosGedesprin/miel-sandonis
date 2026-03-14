$(document).ready(function(){

});
(function($) {
    'use strict';

    $(".switch_input").click(function(event){
        event.stopPropagation();
        //console.log('This', $(this).attr('name'));
        if ( $(this).val() === '0' )
        {
            $(this).val('1');
        }
        else
        {
            $(this).val('0');
        }
        //console.log('This value', $(this).attr('value'));
    });

})(jQuery);

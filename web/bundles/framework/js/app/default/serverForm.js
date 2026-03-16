$(document).ready(function() {

});
(function($) {
    'use strict';

    $('#product_setup').change(function() {

        // console.log('Product setup has changed: id', $(this).val());
        // console.log('url_to_call product_setup', url_get_product_call, 'product_setup', $(this).val(), 'api_key', api_key);
        var api_data = {data: {'product': $(this).val(),'api_key': api_key}};
        //console.log('api_data', api_data);
        $.ajax({
            type: 'post',
            url: url_get_product_call,
            data: JSON.stringify(api_data),
            contentType: "application/json",
            dataType: 'json',
            //traditional: true,
            beforeSend: function(){
                //$('#submit_profile').attr("disabled","disabled");
                //$('#profileForm').css("opacity",".5");
            },
        })
            .done(function(response) {
                // console.log('From api', internal_pages);
                if( response.status === 'OK' )
                {
                    // console.log('Response is OK');
                    // console.log('Product from API', response.result);
                    $('#price_setup').val( response.result.price);
                    $('#price_setup').trigger('input');
                }
            });
    });

    $('#product_renewal').change(function() {

        // console.log('Product renewal has changed: id', $(this).val());
        // console.log('url_to_call product_renewal', url_get_product_call, 'product_renewal', $(this).val(), 'api_key', api_key);
        var api_data = {data: {'product': $(this).val(),'api_key': api_key}};
        //console.log('api_data', api_data);
        $.ajax({
            type: 'post',
            url: url_get_product_call,
            data: JSON.stringify(api_data),
            contentType: "application/json",
            dataType: 'json',
            //traditional: true,
            beforeSend: function(){
                //$('#submit_profile').attr("disabled","disabled");
                //$('#profileForm').css("opacity",".5");
            },
        })
            .done(function(response) {
                // console.log('From api', internal_pages);
                if( response.status === 'OK' )
                {
                    // console.log('Response is OK');
                    // console.log('Product from API', response.result);
                    $('#price_renewal').val( response.result.price);
                    $('#price_renewal').trigger('input');
                }
            });
    });
})(jQuery);

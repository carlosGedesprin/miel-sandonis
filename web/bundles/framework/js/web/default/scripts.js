$(document).ready(function(){

});
(function($) {
    'use strict';

    //console.log('This page', this_page);
    $( "#terms" ).click(function()
    {
        if ( this.checked )
        {
            this.value = '1';
            //console.log( 'Terms clicked', 'checked', this.value );
        }
        else
        {
            this.value = '0';
            //console.log( 'Terms clicked', 'un-checked', this.value );
        }
    });

    var fd = new FormData();

    $("#footer_contact_form_submit_button").click(function(e) {

        e.preventDefault();
        //console.log( 'Submit clicked' );

        cleanMessagesAndErrors();

        $("#footer_contact_form_submit_button").css('display', 'none');
        $("#footer_contact_form_submit_sending").css('display', 'flex');

        $( "form :input" ).each(function()
        {
            //console.log( 'Id '+this.id+' Name '+this.name+' -> '+this.type+' -> '+this.value );
            if ( this.type === 'radio' && !this.checked )
            {
                // console.log( this.id, 'Not appended' );
                return;
            }
            if ( this.type === 'checkbox' )
            {
                fd.append($(this).attr('name'), this.checked ? $(this).val() : '0');
                return;
            }

            fd.append( $(this).attr('name'), $(this).val());
        });

        var url_form = '/'+contact_link;
        //console.log( 'Form action ', url_form );

        $.ajax({
            url: url_form,
            // headers:  { 'charset': 'utf-8' },
            type: 'POST',
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
        })
            .done( function( response ) {
                //console.log( response );
                var json_resp;
                try{
                    json_resp = $.parseJSON(response);
                }catch(err){
                    //TODO-Carlos: Logging system
                    console.log('Issue: Parsing XMLHttp JSON reponse');
                    console.log('Response', response);
                    console.log('err ->', err);
                    console.log('message ->', message);
                    json_resp.status = 'KO';
                }
                //console.log('json_resp :'+json_resp.status);

                if ( json_resp.status === 'OK' )
                {
                    //console.log('------------> OK :'+json_resp.msg);
                    location.href = json_resp.action;
                }
                else if( json_resp.status === 'KO' )
                {
                    //console.log('------------> KO');
                    //console.log('Errors '+json_resp.errors);

                    $("#footer_contact_form_submit_button").css('display', 'flex');
                    $("#footer_contact_form_submit_sending").css('display', 'none');

                    $('#footer_contact_form_errors').css( 'width', '100%');
                    $('#footer_contact_form_errors').css( 'display', 'flex');
                    $('#footer_contact_form_errors').find( 'div' ).text(l_form_has_errors);

                    $.each(json_resp.errors, function(index, error)
                    {
                        //console.log('Index ('+index+': '+error.dom_object+' -> '+error.msg+')');

                        var bad_objects = error.dom_object;
                        // console.log('Bad objects ('+bad_objects+')');
                        $.each(bad_objects, function(popo, bad_object)
                        {
                            //console.log('Popo ', error.msg);
                            $("#"+bad_object).addClass( 'is_wrong' );
                            $("#input_errors_"+bad_object).text( error.msg );
                        });
                    });
                }
                else if( json_resp.status === 'CONTINUE' )
                {
                    //console.log('Continue');
                    var url_to_continue = json_resp.url;
                    //console.log( window.location.hostname + url_to_continue );
                    window.location.assign( url_to_continue );
                }
                else
                {
                    //console.log('json_resp.status != OK, KO, Continue');
                }
            })
            .fail(function()
                {
                    //console.log('Ajax failed');
                }
            );
    });

    function cleanMessagesAndErrors()
    {
        //console.log('Cleaning messages and errors function');

        $('#footer_contact_form_errors').find( 'div' ).empty();
        $('.input_errors').empty();
        $('#footer_contact_form_form').find( 'input' ).removeClass('is_wrong');
        $('#footer_contact_form_form').find( 'textarea' ).removeClass('is_wrong');
    }
})(jQuery);

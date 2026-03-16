$(document).ready(function() {

    // Clean message boxes
    //c leanMessagesAndErrors();

    if ( $('input[type="file"]').length )
    {

        // Show file preview or dropBox
        for (i = 1; i <= num_files; i++)
        {
            //console.log( 'Image ' + i + ' name is ' + files_array[i][files_array_file_name]);
            if ( files_array[i][files_array_file_name] == '' )
            {
                $("#dropBox_" + i).show();
                $("#file-holder-" + i).hide();
            }
            else
            {
                // In function of file extension we show the image, the doc document, pdf, ...
                var image_allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
                var file_name = files_array[i][files_array_file_name];  // name
                var file_extension = files_array[i][files_array_file_extension]; // extension
                //console.log('On load - Image ' + i + ': ' + fil +'.' + file_extension);
                if ( image_allowed_extensions.includes(file_extension) )
                {
                    $("#file-holder-" + i).append($('<img>', {
                        src: files_array[i][files_array_file_link],
                        class: "img-fluid",
                        style: 'max-width: 100%;'
                    }));
                    $("#dropBox_" + i).hide();
                    $("#file-holder-" + i).show();
                }
                /*
                elseif ( file_extension == 'pdf' )
                {
                    var file_holder_link = '/app/charge/pdf_viewer/{{ reg.charge_doc }}';
                    $("#file-holder").append($('<embed>', {    id:'file',
                                                                src:file_holder_link,
                                                                style:'width: 100%; padding: 10px;'}
                    ));
                }
                */
                /*
                elseif ( file_extension = 'doc' or file_extension = 'docx' )
                {
                    {# This does not work per path internet unaccesible
                    {# https://stackoverflow.com/questions/27957766/how-do-i-render-a-word-document-doc-docx-in-the-browser-using-javascript
                        {
                            # < iframe
                            src = "https://docs.google.com/gview?url=http://remote.url.tld/path/to/document.doc&embedded=true" > < /iframe>
                            {
                                # < iframe
                                src = 'https://view.officeapps.live.com/op/embed.aspx?src=http://remote.url.tld/path/to/document.doc'
                                width = '1366px'
                                height = '623px'
                                frameborder = '0' > This
                                is
                                an
                                embedded < a
                                target = '_blank'
                                href = 'http://office.com' > Microsoft
                                Office < /a> document, powered by <a target='_blank' href='http:/
                                /office.com/
                                webapps
                                '>Office Online</a>.</iframe>
                                var file_holder_link = 'https://docs.google.com/gview?url={{ env.protocol }}://{{ env.domain }}/documents/charges/{{ charge.id }}/{{ reg.charge_doc|getChargeDocFileName }}&embedded=true';
                                $("#file-holder").append($('<iframe>', {
                                    id: 'file',
                                    src: file_holder_link,
                                    style: 'width: 90%; height: 120px;'
                                }));
                }
                */
                else
                {
                    //console.log('Image {{ i }}: Not suitable to be shown');
                    $("#file-holder").append($('<p>'+l_file_not_suitable+'</p>'));
                }
            }
        }

        //TODO-Carlos: What to do if FileReader is not supported
        if (typeof (window.FileReader) != "undefined")
        {
        }
        else
        {
        }
    }

});

$(function() {
    'use strict';

    var fd = new FormData();    // Form data object to be used to send

    if ( $(".card").length )
    {
        //Form with tabs

        $('#tabs-list a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
            //var target = e.target.attributes.href.value;
            //TODO:Carlos When a tab changes to active focusing first element is not working
            //var $first_input = $( $(this).attr('href') + ' :input:enabled:not([readonly]):not([type=hidden]):not([type=file]):first');
            //$first_input.focus(); // Doesn't works
            //console.log( $first_input.attr('id') );
            //' :input:enabled:visible:not([readonly]):first'
        })

    }
    else
    {
        //$(':input:enabled:not([readonly]):not([type=hidden]):not([type=file]):first').focus();
    }

    if ( $('input[type="file"]').length )
    {
        //Form with files
    }

    // prevent browsers from opening the file when its dragged and dropped
    // drag dragstart dragend dragover dragenter dragleave drop
    $("html")
        .on('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $("#dropBox p").html(l_file_drop_here);
        })
        .on('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $("#dropBox p").html(l_file_drop_select);
        })
        .on("drop", function(e) {
            e.preventDefault();
            e.stopPropagation();
            $("#dropBox p").html(l_file_drop_select);
        })
    ;

    $(".dropBox")
        .on("mouseenter", function(e) {
            var item = $(this).closest(".file-div").attr("data-item");
            $("#dropBox_"+item+" p").html(l_file_drop_click);
        })
        .on("mouseleave", function(e) {
            var item = $(this).closest(".file-div").attr("data-item");
            $("#dropBox_"+item+" p").html(l_file_drop_select);
        })
        .click(function(){
            var item = $(this).closest(".file-div").attr("data-item");
            $("#dropBox_"+item+" p").html(l_file_drop_select);
            $("#file_input_"+item).click();
        })
        .on("drop", function(e) {
            e.preventDefault();
            e.stopPropagation();
            var item = $(this).closest(".file-div").attr("data-item");
            $("#dropBox_"+item+" p").html(l_file_drop_select);
            var file = e.originalEvent.dataTransfer.files[0]; // Multiple files can be dropped. Lets only deal with the "first" one.
            fd.append('file_input_'+item, file);
            showPreview( file, item );
        })
    ;

    // call a function to handle file selection
    $('input[type=file]').on('change', function(e){
        e.preventDefault();
        e.stopPropagation();
        var item = $(this).closest(".file-div").attr("data-item");
        $("#dropBox_"+item+" p").html(l_file_drop_select);
        var file = $('#file_input_'+item)[0].files[0]; // Multiple files can be dropped. Lets only deal with the "first" one.
        fd.append('file_input_'+item, file);
        showPreview( file, item );
    });

    $(document).on('keyup',function(e) {
        //console.log('Key :'+e.which);
        if(e.which == 13) {
            // Prevent using key Enter
            e.preventDefault();
        }
        if(e.which == 27) {
            // Go to list on escape
            //console.log('Escape 27');
            $("button[name=btn_cancel]").click();
        }
    });

    $("#btn_cancel").click(function(){
        //console.log('forms iframe cancel');
        parent.closeModalPopup();
        //$("#item-form").attr('action', cancel);
        //$("#item-form").submit();
    });

    $("#btn_submit").click(function(){
        // Clean message boxes and errors classes
        cleanMessagesAndErrors();

        // We hide the submit/cancel buttons
        $("#btn_submit").hide();
        $("#btn_cancel").hide();

        // add to formData all the form fields
        $( "form :input" ).each(function()
        {
            // console.log( this.id+' -> '+this.type+' -> '+this.value );
            fd.append( $(this).attr('name'), $(this).val());
        });

        // Changing action to edit because the route to add is edit with id = 0 on route dispatcher
        if ( action === 'add' ) action = 'edit';

        // console.log( '/' + folder + '/' + entity + '/' + action + '/' + reg_id );
        $.ajax({
            url: '/' + folder + '/' + entity + '/' + action + '/' + reg_id,
            // headers:  { 'charset': 'utf-8' },
            type: 'POST',
            data: fd,
            contentType: false,
            cache: false,
            processData:false,
            /*
                error: function(xhr, status, error)
                {
                    // console.log("error : " + xhr.responseText);
                }
                success: function(results, textStatus) {
                debugger;
                // console.log("success : " + results);
            },
            */
        })
            .done(function(response){
                // console.log(response);
                try{
                    var json_resp = $.parseJSON(response);
                }catch(err){
                    //TODO-Carlos: Logging system
                    console.log('Issue: Parsing XMLHttp JSON reponse');
                    console.log('Response', response);
                    console.log('err ->', err);
                    console.log('message ->', message);
                }

                if( json_resp.status == 'OK' )
                {
                    // console.log('------------> OK :'+json_resp.msg);
                    // Avoid user using the form
                    $( "form :input" ).each(function() {
                        $(this).attr('readonly', 'readonly');
                    });
                    $('#box-result').find( "div" ).addClass("alert-success").append($("<p>").addClass("text-center").text(json_resp.msg));

                    showResult();

                    setTimeout(function(){
                        $('#item-form').attr('action', json_resp.action);
                        let iframe_father_form = parent.document.getElementById(iframeFather).contentWindow.document.getElementById('listform');
                        iframe_father_form.submit();
                        parent.closeModalPopup();
                       
                    }, 1000);
                }
                else if( json_resp.status == 'KO' )
                {
                    // console.log('------------> KO');
                    // console.log('Errors '+json_resp.errors);
                    $("#btn_submit").show();
                    $("#btn_cancel").show();
                    $('#box-result').find( "div" ).addClass("alert-danger");
                    $('#box-result').find( "div" ).append($("<p>").text(l_errors_found));
                    $('#box-result').find( "div" ).append($("<ul>").addClass("mt-3"));
                    // Errors
                    $.each(json_resp.errors, function(index, error)
                    {
                        // console.log('Index ('+index+': '+error.dom_object+' -> '+error.msg+')');
                        $('#box-result ul').append($("<li>").html(error.msg));

                        var bad_objects = error.dom_object;
                        // console.log('Bad objects ('+bad_objects+')');
                        $.each(bad_objects, function(index2, bad_object)
                        {
                            // console.log('Index 2 ('+index2+': '+bad_object+')');
                            // Tagging elements with errors with temporary class is_wrong
                            if ( bad_object.indexOf('file-holder-') >= 0 )
                            {
                                //console.log('file '+dom_object);
                                $("#"+bad_object).parent().closest("div").addClass( "is_wrong" );
                            }
                            else
                            {
                                // console.log('Object with errors ('+bad_object+')');
                                if ( bad_object != '')
                                {
                                    // console.log('Objeto no vacio');
                                    $("#"+bad_object).addClass( "is_wrong" );
                                }
                            }
                        });
                    });

                    showResult();
                }
                else
                {
                    // console.log('json_resp.status != OK y KO');

                    $('#box-result').find( "div" ).addClass("alert-danger");
                    $('#box-result').find( "div" ).append($("<p>").text(l_errors_found));
                    $('#box-result').find( "div" ).append($("<ul>").addClass("mt-3"));
                    $('#box-result ul').append($("<li>").html(json_resp.msg));

                    showResult();

                    setTimeout(function(){
                        $('#item-form').attr('action', json_resp.action);
                        $('#item-form').submit();
                    }, 2500);
                }
            })
            .fail(function()
            {
                // console.log('Ajax failed');
                $('#box-result').find( "div" ).addClass("alert-danger");
                $('#box-result').find( "div" ).append($("<p>").text(l_errors_found));
                $('#box-result').find( "div" ).append($("<ul>"));
                $('#box-result ul').append($("<li>").html(l_err_ajax_error));

                showResult();

                setTimeout(function(){
                    $('#item-form').attr('action', json_resp.action);
                    $('#item-form').submit();
                }, 2500);
            })
        ;

    });

    $('#item-form').submit(function(e){
    });

    function showResult()
    {
        // console.log('showErrors function');

        if ( $(".card").length )
        {
            //console.log('Form with tabs');
            var $tab_to_show = '';

            // Restore tabs to normal
            // $(".card-header-tabs").find( "a" ).each( function( index, node )
            // {
            //     $( this ).css("background-color", "#f8f8f8 !important");
            //     $( this ).css("color", "#495057");
            // });

            $(".is_wrong").each( function( index, node )
            {
                // index: is position on the selection's found elements array
                // node: is the DOM element, also is $(this) and this
                // console.log( 'Wrong element ('+index+' '+this.id+')' );
                // Find the tab-pane that this element is inside, and get the id
                var $closest_tab = $(this).closest('.tab-pane');
                var $closest_tab_id = $closest_tab.attr('id');
                var $tab_needle = $closest_tab_id.replace('tab-','');
                //console.log( 'Wrong is in tab ('+$closest_tab_id+')' );
                //console.log( 'Needle of tab ('+$tab_needle+')' );

                // Find the link that corresponds to the pane and mark it with errors
                var $tab_a = $( '#'+$tab_needle+'-tab' );
                // console.log( 'Tab a ('+$tab_a.attr('id')+')' );
                $( $tab_a ).css("background-color", "rgba(255,0,0,0.60)");
                $( $tab_a ).css("color", "rgba(255,255,255,1)");
                if ( $tab_to_show == '' ) $tab_to_show = $tab_needle;
            });

            // Show the first tab with errors
            if ( $tab_to_show != '' )
            {
                // console.log('Tab to show ('+$tab_to_show+'-tab )');
                $( '#'+$tab_to_show+'-tab' ).css('background-color', 'rgba(255,0,0,1)');
                $( '#'+$tab_to_show+'-tab' ).tab('show');

                //console.log('Cards have errors');
            }
        }
        else
        {
            //console.log('Form without tabs');
            if ( $(".is_wrong").length )
            {
                //console.log('Form items with errors');
            }
        }

        $("#box-result").show();

        scrool_to_top_of_page ();
    }

    function showPreview( file, item )
    {
        var fileName = file.name;
        var fileType = file.type;

        var dots = file.name.split(".");
        var file_extension = dots[dots.length-1].toLowerCase();
        dots.pop(); // remove the last element, so remove the extension
        var file_name = dots.join('');
        //console.log('File '+file_name+'.'+file_extension+' Type ('+fileType+')');

        $("#"+files_array[item][files_array_input_id]+"_name").val(file_name);
        $("#"+files_array[item][files_array_input_id]+"_extension").val(file_extension);
        //console.log('File name '+$("#"+files_array[item][files_array_input_id]+"_name").val()+'.'+$("#"+files_array[item][files_array_input_id]+"_extension").val()+')');

        var reader = new FileReader();
        reader.onload = function( file )
        {
            var file_holder = $("#file-holder-"+item);
            file_holder.empty();

            if ( ($.inArray( file_extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) !== -1 )
            {
                if ( fileType.match('image.*') )
                {
                    var size = 200;
                    var image = new Image();
                    image.onload = function(){
                        var canvas = document.createElement("canvas");
                        /*
                        if(image.height > size) {
                            image.width *= size / image.height;
                            image.height = size;
                        }
                        */
                        if(image.width > size) {
                            image.height *= size / image.width;
                            image.width = size;
                        }
                        var ctx = canvas.getContext("2d");
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        canvas.width = image.width;
                        canvas.height = image.height;
                        ctx.drawImage(image, 0, 0, image.width, image.height);
                        canvas.toDataURL("image/png");
                    };
                    image.src = this.result;
                    //alert('Is an image :'+image.src);
                    $("<img />", {
                        "src": image.src,
                        "class": "img-fluid"
                    }).appendTo(file_holder);
                }
                /*
                $("<img />", {
                    "src": file.target.result,
                    "class": "img-fluid"
                }).appendTo(file_holder);
                //console.log('Extension OK '+file.target.result);
                */
            }
            /*
                if ( ($.inArray( file_extension, ['pdf'])) !== -1 )
                {
                    //var pdffile_url = window.URL.createObjectURL(file);
                    $("<iframe />", {
                        "src": reader.result,
                        "id": "viewer",
                        "frameborder": "0",
                        "scrolling": "no",
                        "width": "400",
                        "height": "400",
                        "class": ""
                    }).appendTo(file_holder);
                }
            */
            else
            {
                $("<p />", {
                        "style": "padding: 15px"
                    }
                ).html(l_file_drop_selected + ": " + fileName).appendTo(file_holder);
            }

            $("#dropBox_"+item).hide();
            file_holder.show();
        }
        reader.readAsDataURL( file );
    }
});

function cleanMessagesAndErrors()
{
    // console.log('Cleaning messages and errors function');
    // console.log('Cleaning message box');
    $("#box-result").find( "div" ).removeClass("alert-danger alert-success").empty('');

    // remove the is_wrong class
    $( 'form' ).find('.is_wrong').each(function() {
        // console.log( 'Remove is_wrong from '+this.id );
        $(this).removeClass( "is_wrong" );
    });

    if ( $(".card").length )
    {
        // Restore tabs to normal
        $(".card-header-tabs").find( "a" ).each( function( index, node )
        {
            // console.log('Tab :'+this.id+' - '+$( this ).attr('id'));
            $( this ).css('background-color', 'rgba(255,255,255,1)');
            $( this ).css('color', 'rgba(0,123,255,1)');
        });
    };

}
function scrool_to_top_of_page(){
    //console.log('Scroll to top');
    //$('html,body').animate({scrollTop: 0});
    $("html, body").animate({scrollTop: $("#topofthepage").offset().top}, 1000);
}
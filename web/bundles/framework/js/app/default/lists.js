$(document).ready(function(){

    /* Let's hightlight ordered column title */
    $('.titleItem').css('color', 'rgb(150,150,150)');
    // $('.titleItem').not('.itemOptions').css('cursor', 'pointer');
    $('.titleItem').css('cursor', 'pointer');
    $('.itemOptions, .notSorted').css('cursor', 'default');
    var column_ordered = $('#order').val();
    $("span[data-order-by='"+column_ordered+"']").css('color', 'rgb(37,33,41)');
    $("span[data-order-by='"+column_ordered+"']").css('cursor', 'default');

});

$(function() {

    $('.filter_select').change(function() {

        var numFilterSelects = $('.filter_select').length
        if ( numFilterSelects > '1')
        {

            if ( $(this).attr("data-chainchilds") != '' )
            {
                $('#filter_' + $(this).attr("data-chainchilds") + '_value').val('0');
            }
        }
        $('#num_page').val('1');
        $('#listform').submit();

    });

    // Cleans the input text filter field and submits the filter form
    $('.form-control-clear').bind("mouseup", function(event) {
        var $closest = $(this).closest('.input-group');
        var $input_p = $closest.find( ".form-control" );
        $input_p.val('');
        $('#listform').submit()
    });

    /* Buttons */
    $('#reload_button').click(function()
    {
        //console.log('Reload clicked');
        $('#listform').submit();
    });

    /* List items order functions */
    $( ".titleItem" ).not('.itemOptions').not('.notSorted').click(function() {
        $('#num_page').val('1');
        var order_by = $( this ).attr( "data-order-by" );
        $('#order').val(order_by);
        $('#order_dir').val('ASC');
        $( "#listform" ).submit();
    });
    $( ".order_dir" ).click(function() {
        $('#num_page').val('1');
        if ( $('#order_dir').val() == 'ASC' ){
            $('#order_dir').val('DESC');
        }else{
            $('#order_dir').val('ASC');
        }
        $( "#listform" ).submit();
    });

    /* List items functions */
    $('.item-add').click(function() {
        $('#listform').attr('action', $( this ).attr("data-item"));
        $('#listform').submit();
    });
    $('.item-edit').click(function() {
        $('#listform').attr('action', $( this ).attr("data-item"));
        $( "#listform" ).submit();
    });
    $('.item-delete').click(function() {
        $('#listform').attr('action', $( this ).attr("data-item"));
        $('#listform').submit();
    });
    $('.item-pdf').click(function(){
        window.open($( this ).attr("data-item"), '_blank');
    });
    $('.item-mail').click(function() {
        $('#listform').attr('action', $( this ).attr("data-item"), 'target');
        $('#listform').attr('target', '_blank');
        $('#listform').submit();
    });
});

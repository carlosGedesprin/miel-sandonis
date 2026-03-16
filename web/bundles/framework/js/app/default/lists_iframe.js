$(document).ready(function(){

    /* Let's hightlight ordered column title */
    $('.titleItem').css('color', 'rgb(150,150,150)');
    // $('.titleItem').not('.itemOptions').css('cursor', 'pointer');
    $('.titleItem').css('cursor', 'pointer');
    $('.itemOptions, .notSorted').css('cursor', 'default');
    var column_ordered = $('#order').val();
    $("span[data-order-by='"+column_ordered+"']").css('color', 'rgb(37,33,41)');
    $("span[data-order-by='"+column_ordered+"']").css('cursor', 'default');

    $(window).resize(function () {
 
        var box = $('#boxes .window');

        //Get the screen height and width
        var maskHeight = $(document).height();
        var maskWidth = $(window).width();

        //Set height and width to mask to fill up the whole screen
        $('#mask').css({'width':maskWidth,'height':maskHeight});
            
        //Get the window height and width
        var winH = $(window).height();
        var winW = $(window).width();

        //Set the popup window to center
        box.css('top',  winH/2 - box.height()/2);
        box.css('left', winW/2 - box.width()/2);

    });

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
        //console.log($( this ).attr( "data-item" ));
        if ( ($(this).attr("data-item") != '') )
        {
            parent.openModalPopup( $(this).attr( "data-item" ) );
        }
    });
    $('.item-edit').click(function() {
        //console.log($( this ).attr( "data-item" ));
        if ( ($(this).attr("data-item") != '') )
        {
            parent.openModalPopup( $(this).attr( "data-item" ) );
        }
        //$('#listform').attr('action', $( this ).attr("data-item"));
        //$( "#listform" ).submit();
    });
    $('.item-delete').click(function() {
        //console.log($( this ).attr( "data-item" ));
        if ( ($(this).attr("data-item") != '') )
        {
            parent.openModalPopup( $(this).attr( "data-item" ) );
        }
        //$('#listform').attr('action', $( this ).attr("data-item"));
        //$('#listform').submit();
    });

     //select all the a tag with name equal to modal
     $('a[name=modal]').click(function(e) {
        //console.log('Hemos hecho click');
        //Cancel the link behavior
        e.preventDefault();
        //Get the A tag
        var id = $(this).attr('href');

        //Get the screen height and width
        var maskHeight = $(document).height();
        var maskWidth = $(window).width();

        //Set height and width to mask to fill up the whole screen
        $('#mask').css({'width':maskWidth,'height':maskHeight});
        
        //transition effect             
        $('#mask').fadeIn(1000);        
        $('#mask').fadeTo("slow",0.8);  

        //Get the window height and width
        var winH = $(window).height();
        var winW = $(window).width();
      
        //Set the popup window to center
        $(id).css('top',  winH/2-$(id).height()/2);
        $(id).css('left', winW/2-$(id).width()/2);

        //transition effect
        $(id).fadeIn(1000); 

    });

    //if close button is clicked
    $('.window .close').click(function (e) {
            //Cancel the link behavior
            e.preventDefault();
            $('#mask, .window').hide();
            
            
    });

    //if mask is clicked
    $('#mask').click(function () {
            $(this).hide();
            $('.window').hide();
    });

});

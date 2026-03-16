$(document).ready(function(){

});
(function($) {
    'use strict';

    $('.faq_band_content').click(function() {
        if ( $(this).children('div.faq_band_reply').hasClass('active') )
        {
            $('.faq_band_reply').removeClass('active');
            $('.faq_band_question_icon').html('+');
        }
        else
        {
            $('.faq_band_reply').removeClass('active');
            $(this).children('div.faq_band_reply').addClass('active');
            $('.faq_band_question_icon').html('+');
            $(this).find('.faq_band_question_icon').html('-');
        }
    });

})(jQuery);

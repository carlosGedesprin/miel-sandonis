$(document).ready(function() {

    scrool_to_top_of_page();

});

$(function() {
    'use strict';
});

function scrool_to_top_of_page() {
    // console.log('Going to top');
    $("html, body").animate({scrollTop: $("#topofthepage").offset().top}, 1000);
}

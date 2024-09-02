"use strict"; 

(function($) { 

    /*Add class current in menu*/
    $('ul .menu-item a').on('click',function() {
        $('.menu-item a').removeClass("current");
        $(this).addClass("current");
    });

    /*Header Scroll*/
    /*Fixed Navbar When Scroll*/
    var navbarFix = $("#js-navbar-fixed");
    var headerOffset = navbarFix.offset().top + 100;
    $(window).on('scroll',function () {
        if ($(window).scrollTop() > headerOffset) {
            navbarFix.addClass('fixed animated slideInDown').removeClass("unfixed");
        } else {
            navbarFix.addClass('unfixed').removeClass("fixed animated slideInDown");
        }
    });
    /*End Header Scroll*/

    /*Fixed Navbar When Scroll*/
    var mbnavbarFix = $("#js-navbar-mb-fixed");
    var headerOffsetmb = mbnavbarFix.offset().top + 80;
    $(window).on('scroll',function () {
        if ($(window).scrollTop() > headerOffsetmb) {
            mbnavbarFix.addClass('fixed animated slideInDown').removeClass("unfixed");
        } else {
            mbnavbarFix.addClass('unfixed').removeClass("fixed animated slideInDown");
        }
    });
    /*End Header Scroll*/

    /*Mobile Menu*/
    /*Hamburger Button*/
    $('.hamburger').on("click", function () {
        $(this).toggleClass("is-active");
        $('.au-navbar-mobile').slideToggle(200, 'linear');
    });

    /*Navbar menu dropdown*/
    $('.au-navbar-mobile .au-navbar-menu .drop .drop-link').on('click', function (e) {
        $(this).siblings('.drop-menu').slideToggle(200, 'linear');
        $(this).toggleClass('clicked');
        e.stopPropagation();
    });
    /*End Mobile Menu*/

    /*Back To Top Button*/
    $(window).on('scroll',function () {
        if ($(this).scrollTop() > 300) {
          $('#back-to-top').fadeIn('slow');
        } else {
          $('#back-to-top').fadeOut('slow');
        }
      });
    $('#back-to-top').on( 'click', function() {
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });     
    /*End Back To Top Button*/

})(jQuery);
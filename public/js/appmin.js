$(document).ready(function () {
    $("#div0").show();

    $(document).on("click",function(e) {
        var container = $(".rombo");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $(".div-rombo").hide(); 
            $("#div0").show();
            $('.rombo').removeClass( 'active' );
        }
    });

    $('#formularios a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
      })

/*
 * Replace all SVG images with inline SVG
 */
jQuery('img.svg').each(function () {
    var $img = jQuery(this);
    var imgID = $img.attr('id');
    var imgClass = $img.attr('class');
    var imgURL = $img.attr('src');

    jQuery.get(imgURL, function (data) {
        // Get the SVG tag, ignore the rest
        var $svg = jQuery(data).find('svg');

        // Add replaced image's ID to the new SVG
        if (typeof imgID !== 'undefined') {
            $svg = $svg.attr('id', imgID);
        }
        // Add replaced image's classes to the new SVG
        if (typeof imgClass !== 'undefined') {
            $svg = $svg.attr('class', imgClass + ' replaced-svg');
        }

        // Remove any invalid XML tags as per http://validator.w3.org
        $svg = $svg.removeAttr('xmlns:a');

        // Check if the viewport is set, if the viewport is not set the SVG wont't scale.
        if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
            $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
        }

        // Replace image with new SVG
        $img.replaceWith($svg);

    }, 'xml');
});



});



$(".rombo").click(function() {
    $(".div-rombo").hide().fadeOut(100);
    $("#div"+$(this).attr('target')).show().fadeIn(100);
});


$(function() {
    $( '.rombo' ).on( 'click', function() {
          $( this ).parent().find( '.rombo.active' ).removeClass( 'active' );
          $( this ).addClass( 'active' );
    });
});


$(".botones a[href^='#']").on('click', function(e) {

    // prevent default anchor click behavior
    e.preventDefault();
 
    // store hash
    var hash = this.hash;
    var modo = hash.replace("#", "");

    /* Botones unicamente */
    $('.nav-botones').prop('aria-selected', false);
    $('.nav-botones').removeClass('active');
    $("#btn-"+modo).prop('aria-selected', true);
    $("#btn-"+modo).addClass('active');

    /* Div */


    $(".tab-pane").removeClass('active');
    $("#"+modo).addClass('active');
    
 
    // animate
    $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 700, function(){

        window.location.hash = hash;

      });
 
 });

 $('.carousel').carousel().swipeCarousel({
    
  });

  $(".required").focus(function(){
    $(this).css({"background":"rgb(82, 179, 126)"})
  })
// remap jQuery to $
(function($){})(window.jQuery);

// var $window,
//     $scrollElement,
//     siteStrings,

//     windowWidth,
//     windowHeight;

//////////////
// onScroll
//////////////

// function onScroll() {

//     scrollTop = $window.scrollTop();
// }

/////////////
// Resize
/////////////

// function resizeSite() {

//     windowWidth  = $window.width();
//     windowHeight = $window.height();

// }

////////////
// onLoad
////////////

// function onLoad() {

// }

/////////////
// onReady
/////////////

$(document).ready(function (){

    // $window         = $(window);
    // $scrollElement  = $('html, body').scrollTop(0);
    // $wrapper        = $('#wrapper');

    // // Resize
    // resizeSite();

    // if ( isMobile ) {
    //     window.addEventListener('onorientationchange' in window ? 'orientationchange' : 'resize', resizeSite, false);
    // }
    // else {
    //     $(window).resize(resizeSite);
    // }

    // // Init onScroll handler
    // $(window).scroll(onScroll).trigger("scroll");

    // // Load
    // $(window).load(onLoad);

    // // Strings
    // siteStrings = $.parseJSON(siteVars.siteStrings);
});


////////////
// Utils
////////////

function mapInRange(value, min, max, a, b) {
    return (((b - a)*(value - min) ) / (max - min)) + a;
}

////////////////////////////
// Custom Easing Extends
////////////////////////////

$.extend($.easing,
{
    def: 'easeOutQuad',
    swing: function (x, t, b, c, d) {
        return $.easing[$.easing.def](x, t, b, c, d);
    },

    easeInQuad: function (x, t, b, c, d) {
        return c*(t/=d)*t + b;
    },
    easeOutQuad: function (x, t, b, c, d) {
        return -c *(t/=d)*(t-2) + b;
    },
    easeInOutQuad: function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return c/2*t*t + b;
        return -c/2 * ((--t)*(t-2) - 1) + b;
    },
    easeInCubic: function (x, t, b, c, d) {
        return c*(t/=d)*t*t + b;
    },
    easeOutCubic: function (x, t, b, c, d) {
        return c*((t=t/d-1)*t*t + 1) + b;
    },
    easeInOutCubic: function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return c/2*t*t*t + b;
        return c/2*((t-=2)*t*t + 2) + b;
    }
});
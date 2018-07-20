$(function() {
    $(window).scroll(function() {
        if($(this).scrollTop() != 0) {
            $('#backtotop').fadeIn();
        } else {
            $('#backtotop').fadeOut();
        }
    });

    $('#backtotop').click(function() {
        $('body,html').animate({scrollTop:0},400)
    });
});

$(function() {
    /**
     * Smooth scrolling to page anchor on click
     **/
    $("a[href*='#']:not([href='#'])").click(function() {
        if (
            location.hostname == this.hostname
            && this.pathname.replace(/^\//,"") == location.pathname.replace(/^\//,"")
        ) {
            var anchor = $(this.hash);
            anchor = anchor.length ? anchor : $("[name=" + this.hash.slice(1) +"]");
            if ( anchor.length ) {
                $("html, body").animate( { scrollTop: anchor.offset().top }, 1500);
            }
        }
    });
});

$(function() {
    $('.alert').delay(500).fadeIn('normal', function() {
        $(this).delay(2500).fadeOut();
    });
});
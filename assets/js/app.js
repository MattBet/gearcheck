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

//ATC
$('.ajax_atc').submit(function(e){
    console.log('clicked')
    e.preventDefault()

    var button = $(this).find('button');

    $.ajax({
    type: $(this).attr('method'),
    url: $(this).attr('action'),
    data: $(this).serializeArray,
    beforeSend: function (){
        button.html('LOADING...');
        button.removeClass();
        button.addClass('button--disabled btn--large');
        button.attr('disabled','true');
        button.blur();
    },
    success: function(){
        button.html('HAS BEEN ADDED TO YOUR CART');
        button.removeClass();
        button.addClass('button--disabled btn--large');
        button.attr('disabled','true');
        button.blur();
    },
    }).done(function(response) {
        console.log(response);
    });


});


//CLEAR CART
$('.ajax_atc--clear').submit(function(e){
    console.log('clicked');
    e.preventDefault();

    form = $(this);
    console.log($(this));
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: $(this).serializeArray(),
        success: function () {
            //form.parent().parent().parent().parent().remove();
            $('table#cart-table').remove();
            console.log(form.parent().parent().parent().parent().remove());
            $('.empty_cart').html('Your shopping cart is empty');
        }
    }).done(function (msg) {
        console.log(msg);
    })
});

$('.ajax_delete--product').submit(function(e){
    console.log('clicked');
    e.preventDefault();

    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: $(this).serializeArray(),
        success: function () {
            $('#cart').load(' #cart');
        }
    }).done(function (msg) {
        console.log(msg)
    })
});
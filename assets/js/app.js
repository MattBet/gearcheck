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

/*$(function() {
    $('.alert').delay(500).fadeIn('normal', function() {
        $(this).delay(2500).fadeOut();
    });
});*/


//HOME
$('.navbar-brand').click(function (e) {
    e.preventDefault();

    url = $(this).attr('href');
    $.ajax({
        url: url,
        method: "GET",
        success: function (data) {
            $('body').html(data)
        }
    }).done(function (data) {
        console.log(data)
    })
})
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
        button.blur()
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
            $('body').load('#cart');
        }
    }).done(function (msg) {
        console.log(msg);
    })
});


//DELETE FROM CART
$('.ajax_delete--product').submit(function(e){
    console.log('clicked');
    e.preventDefault();
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: $(this).serializeArray(),
        success: function (data) {
            $('.container').html(data);
        }
    }).done(function (msg) {
        console.log(msg)
    })
});

//CART LINK
$('#cart_link').click(function (e) {
    e.preventDefault();
    console.log("link panier");

    $.ajax({
        url: "/panier",
        type: "GET",
        success:function (data) {
            $('body').html(data)
        }
    }).done(function (msg) {
        console.log(msg);
    })
});

//LOGOUT
$('#logout').click(function (e) {
    e.preventDefault();
    console.log("logout");

    $.ajax({
        url: "/logout",
        type: "GET",
        success:function (data) {
            $('body').html(data);
        }
    }).done(function (msg) {
        console.log(msg);
    })
});

//CLICK ON PRODUCT PAGE
$('.product_link').click(function (e) {
    e.preventDefault();
    var product_url =  $(this).attr('href');
    console.log(product_url);

    $.ajax({
        url: product_url,
        type: "GET",
        success: function (data) {
            $('body').html(data);
            $('html, body').animate({scrollTop:0}, 'slow');
        }
    }).done(function (data) {
        console.log(data);
    })
});

//SIGNUP
$('.ajax_signup').submit(function (e) {
    e.preventDefault();

    $.ajax({
        url: $(this).url,
        type: $(this).method,
        data: $(this).serializeArray(),
        success: function (data) {
            $('#signupModal .modal-body').html("Your are signed up.");
            $('#signupModal').removeClass('show');
            setTimeout(function(){
                $('body').delay(2000).html(data);
            },2000);

        }
    }).done(function (msg) {
        console.log(msg);
    })
});


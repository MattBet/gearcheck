
function notification(html){
    $('#notifications').append("<div class=\"notification\" data-status=''>"+html+"</div>");
    $('.notification').each(function () {
        $(this).css('top',65 * $(this).index() + 'px' );
        $(this).animate({'left':'0px'});
        $(this).delay(5000).fadeOut("low", function(){
            $(this).attr('data-status', "off");
        });
        if($(this).attr('data-status') === "off")
        {
            $(this).remove();
        }
    })
}

$('.notification').click(function () {
    $(this).hide();
    $(this).remove();
})
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

//HOME
$('.navbar-brand').click(function (e) {
    e.preventDefault();

    url = $(this).attr('href');
    $.ajax({
        url: url,
        method: "GET",
    }).done(function (data) {
        afficher(data);
        window.history.pushState("", "", "/");
    })
});

//PROFILE
$('#profile_link').click(function (e) {
    e.preventDefault();

    var page_link  = $(this).attr('href');
    console.log(page_link);
    $.ajax({
        url: page_link,
        method: "GET",
    }).done(function (data) {
        afficher(data);
        window.history.pushState("", "", page_link);
    })
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
        button.blur()
        notification("This item has been added to your shopping cart");
    },
    }).done(function(response) {
        console.log(response);
    });


});


//DELETE FROM CART
$('.ajax_delete--product').submit(function(e){
    console.log('clicked');
    e.preventDefault();
    $('#page_content').append("<div style='margin: 300px; text-align: center;'><img style='height: 50px; width: 50px;' src='../img/loader.gif' alt=''></div>");
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: $(this).serializeArray(),
        beforeSend: function(){
            $('#cart_table').empty();
            $('#cart_table').append("<div style='margin: 300px; text-align: center;'><img style='height: 50px; width: 50px;' src='../img/loader.gif' alt=''></div>");

        },
    }).done(function (data) {
            notification("This item has been removed from your shopping cart.");
            console.log(data)
        afficher(data);
    })
});

//CART LINK
$('#cart_link').click(function (e) {
    e.preventDefault();
    console.log("link panier");
    $('#page_content').append("<div style='margin: 300px; text-align: center;' class='loader'><img style='height: 50px; width: 50px;' src='../img/loader.gif' alt=''></div>");
    $.ajax({
        url: "/panier",
        type: "GET",
    }).done(function (data) {
        afficher(data);
        window.history.pushState("", "", "/panier");
    })
});

//LOGOUT
$('#logout').click(function (e) {
    e.preventDefault();
    console.log("logout");
    $('#page_content').append("<div style='margin: 300px; text-align: center;' class='loader'><img style='height: 50px; width: 50px;' src='../img/loader.gif' alt=''></div>");
    $.ajax({
        url: "/logout",
        type: "GET",
    }).done(function (data) {
        notification("You successfully logged out.");
        afficher(data);
        window.history.pushState("", "", "/"  );
    })
});

//PRODUCT PAGE
$('.product_link').click(function (e) {
    e.preventDefault();
    $('#page_content').append("<div style='margin: 300px; text-align: center;' class='loader'><img style='height: 50px; width: 50px;' src='../img/loader.gif' alt=''></div>");
    var product_url =  $(this).attr('href');
    console.log(product_url);
    $.ajax({
        url: product_url,
        type: "GET",
    }).done(function (data) {
        afficher(data);
        window.history.pushState("", "", product_url);
    })
    return false;
});

/*//SIGNUP
$('.ajax_signup').submit(function (e) {
    e.preventDefault();
    console.log($(this).serializeArray())
    $.ajax({
        url: $(this).url,
        type: $(this).method,
        data: $(this).serializeArray(),
    }).done(function (data) {
        notification("Your are signed up.");
        afficher(data);
    })
});*/

/*
//CHAT
$('#ajax_chat').submit(function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: $(this).serialize()
    }).done(function (data) {
        console.log(data['message']);
        $('.chat_messages').append("<div class='message'>" +
            "<div class=\"message_owner\">\n" + data['user'] + "</div>" +
            "<div class=\"message_content\">\n" + data['message'] + "</div>" +
            "</div>")
        });
    return false;
})*/
function afficher(data){
    $('.loader').remove();
    $("#page_content").fadeOut(500,function(){
        $("#page_content").empty();
        $("#page_content").append(data);
        $("#page_content").fadeIn(1000);
    })
    return false;
}
/*
function charger(){


    setTimeout( function(){

        // on lance une requête AJAX

        $.ajax({

            url : "/chat/allMessages",

            type : "GET",

            success : function(data){

                $('.chat_messages').empty(); // on veut ajouter les nouveaux messages au début du bloc #messages
                $('.chat_messages').append(data); // on veut ajouter les nouveaux messages au début du bloc #messages

            }

        });


        charger(); // on relance la fonction


    }, 5000); // on exécute le chargement toutes les 5 secondes


}*/



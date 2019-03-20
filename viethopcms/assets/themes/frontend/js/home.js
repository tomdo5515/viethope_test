$(document).ready(function(){

    function setCookie(key, value) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    }

    function getCookie(key) {
        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
        return keyValue ? keyValue[2] : null;
    }

    $('.navbar-o-close').on('click', function () {
        if($('header.navbar').hasClass('open-menu')){
            // request close
            setCookie("open_menu", 0);
        }
        else{
            // request open
            setCookie("open_menu", 1);
        }
        $('header.navbar').toggleClass("open-menu");
    })

    $('ul.navbar-nav li.dropdown').hover(function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
    }, function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
    });
    
    // Search
    var searchWrapper = $('.search-wrapper'),
    searchInput = $('.search-input');
    $(document).on('click', function (e) {
        console.log(e);
        if (~e.target.className.indexOf('search')) {
            searchWrapper.addClass('focused');
            searchInput.focus();
        } else {
            searchWrapper.removeClass('focused');
        }
    });

    $('.search-wrapper i.fa-search').on('click', function () {
        if(searchWrapper.hasClass('focused')){
            let keyword = searchInput.val();
            if(keyword.trim() != ''){
                let url = window.location.host;
                window.location.href = '/search/' + keyword.trim();
            }
            else{
                return false;
            }
        }
    });

    $('.search-input').keypress(function( event ) {
        
        if ( event.which == 13 && event.keyCode == 13 ) {
            let keyword = $(this).val();
            if(keyword.trim() != ''){
                let url = window.location.host;
                window.location.href = '/search/' + keyword.trim();
            }
            else{
                return false;
            }
        }
    });

    $('.searchsubmit i.fa-search').on('click', function () {
        let keyword = $('.searchkey').val();
        if(keyword.trim() != ''){
            let url = window.location.host;
            window.location.href = '/search/' + keyword.trim();
        }
        else{
            return false;
        }
    
    });
    // for every slide in carousel, copy the next slide's item in the slide.
    // Do the same for the next, next item.
    // new WOW().init();
});


// scroll down
$('.scroll-down').click(function(event) {
    event.preventDefault();
    var height = $('.our-program').height();
    $('html, body').animate({ scrollTop: height }, 'slow');
});


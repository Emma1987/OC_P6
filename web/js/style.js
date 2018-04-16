$('.my-selector-images').collection({
    init_with_n_elements: 1
});
$('.my-selector-images').collection({
    add: '<a href="#" class="btn btn-default"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a>',
    remove: '<a href="#" class="btn btn-default"><i class="fa fa-trash-o" aria-hidden="true"></i></a>',
    allow_up: false,
    allow_down: false,
    prefix: 'images'
});
$('.my-selector-videos').collection({
     init_with_n_elements: 1
});
$('.my-selector-videos').collection({
    add: '<a href="#" class="btn btn-default"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a>',
    remove: '<a href="#" class="btn btn-default"><i class="fa fa-trash-o" aria-hidden="true"></i></a>',
    allow_up: false,
    allow_down: false,
    prefix: 'videos'
});

$(".allTricks").click(function(e) {
    e.preventDefault();
    $("#tricks .homeTrick").slice(0,15).show();
    $("#loadMore").show();
    $(".allTricks").fadeOut();
    $('html,body').animate({
        scrollTop: $(this).offset().top
    }, 1500);
});

var tshow = 30;
var tricks = $(".nbTricks").data("nbtricks");
console.log(tricks);
$("#loadMore").click(function(e) {
    e.preventDefault();
    $(".scrollTopButton").show();
    $("#tricks .homeTrick").slice(0,tshow).show('slow');
    tshow = tshow + 15;
    if (tshow > tricks) {
        $("#loadMore").hide();
    }
});

$('a.scrollTopButton').click(function () {
    $('body,html').animate({
        scrollTop: 0
    }, 1500);
    return false;
});

$(window).scroll(function () {
    if ($(this).scrollTop() > 900) {
        $('a.scrollTopButton').fadeIn();
    } else {
        $('a.scrollTopButton').fadeOut();
    }
});

$("a.updateMediasAction").click(function(e) {
    e.preventDefault();
    $(".updateMediasButton").fadeOut();
    $(".updateMediasBlock").fadeIn();
});
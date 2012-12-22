$(document).ready(function(){
    $("#seuravalikko ul li").hoverIntent({
        sensitivity: 3,
        interval: 200,
        over: function(){
            $(this).children("#alavalikko").slideDown(1000);
        },
        timeout: 100,
        out: function (){
            $(this).children("#alavalikko").fadeOut('slow');
        }
    });
});
$(document).ready(function() {
    inKlikattava($("#ilmoittaudu .in"));
    outKlikattava($("#ilmoittaudu .out"));
    kommentoiKlikattavaksi();
});
function inKlikattava(elementti) {
    $(elementti).click(function(e) {
        $(e.target).parent().children("div").unbind("click");
        var id = $(this).data("id");
        ilmoittaudu(id, 1, function(data) {
            if (data.onnistui) {
                $(e.target).removeClass("in").addClass("in_ilmoittautunut");
                if ($(e.target).parent().children(".out_ilmoittautunut")) {
                    $(e.target).parent().children(".out_ilmoittautunut").removeClass("out_ilmoittautunut").addClass("out");
                    outKlikattava($(e.target).parent().children(".out"));
                }
                if ($("#kommentti").hasClass("hidden")) {
                    $("#kommentti").removeClass("hidden");
                }
                var login = $("#tapahtuma #" + data.kayttaja).html();
                $("#tapahtuma #" + data.kayttaja).remove();
                $("#tapahtuma #in #pelaajat").append("<div class=\"pelaaja\" id=\"" + data.kayttaja + "\">" + login + "</div>");
            } else {
                inKlikattava($("#ilmoittaudu .in"));
                $("#ilmoittautumis-error").html(data.error);
                $("#ilmoittautumis-error").show();
            }
        });
    });
}
function outKlikattava(elementti) {
    $(elementti).click(function(e) {
        var id = $(this).data("id");
        ilmoittaudu(id, 0, function(data) {
            if (data.onnistui) {
                $(e.target).removeClass("out").addClass("out_ilmoittautunut");
                $(elementti).unbind("click");
                if ($(e.target).parent().children(".in_ilmoittautunut")) {
                    $(e.target).parent().children(".in_ilmoittautunut").removeClass("in_ilmoittautunut").addClass("in");
                    inKlikattava($(e.target).parent().children(".in"));
                }
                if ($("#kommentti").hasClass("hidden")) {
                    $("#kommentti").removeClass("hidden");
                }
                var login = $("#tapahtuma #" + data.kayttaja).html();
                $("#tapahtuma #" + data.kayttaja).remove();
                $("#tapahtuma #out #pelaajat").append("<div class=\"pelaaja\" id=\"" + data.kayttaja + "\">" + login + "</div>");
            } else {
                outKlikattava($("#ilmoittaudu .out"));
                $("#ilmoittautumis-error").html(data.error);
                $("#ilmoittautumis-error").show();
            }
        });
    });
}
function kommentoiKlikattavaksi() {
    $("#kommentoi").click(function() {
        var kommentti = $(this).parent().children(".kommenttikentta").val();
        lisaaKommentti($(this).data("id"), kommentti, function(data) {
            if (data.onnistui) {
                $("#kommentointi-ok").slideDown(500);
                setTimeout(function(){
                    $("#kommentointi-ok").slideUp(1000);
                }, 2000);
            } else {
                $("#kommentointi-error").html(data.error);
                $("#kommentointi-error").show();
                $("#kommentointi-error").hide(1000);
            }
        });
    });
}
function ilmoittaudu(id, lasna, success) {
    var data = {
        tapahtuma: id,
        lasna: lasna
    };
    $.ajax({
        url: "http://fbcremix.com/Remix/foorumi/ajax/ilmoittaudutapahtumaan.php",
        dataType: 'json',
        type: 'post',
        data: data,
        success: success
    });
}
function lisaaKommentti(id, kommentti, success) {
    var data = {
        tapahtuma: id,
        kommentti: kommentti
    };
    $.ajax({
        url: "http://fbcremix.com/Remix/foorumi/ajax/lisaakommentti.php",
        dataType: 'json',
        type: 'post',
        data: data,
        success: success
    });
}
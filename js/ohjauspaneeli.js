"use strict";
(function() {

    var nyk_haku = "undefined";
    var nyk_hakusana = "";

    function vaihda(url, nimet, idt, default_get) {
        var get = (default_get !== "" ? "?" + default_get : "");
        $.each(idt, function(index, value) {
            var arvo = $(value).val();
            if (arvo != "" && arvo != undefined)
                get += (get !== "" ? "&" : "?") + nimet[index] + "=" + $(value).val();
        });
        siirry("/Remix" + url + get);
    }

    function kayttajienHaku(hakuKentta, jarjestysKentat, mihin) {

        var hakusana = $("#" + hakuKentta).val();

        var jarjesta = 0;
        jarjesta = $("#" + jarjestysKentat + ":checked").val();

        if (hakusana != "") {
            setTimeout(function() {
                if (hakusana !== $("#" + hakuKentta).val() || jarjesta !== $("#" + jarjestysKentat + ":checked").val() || (nyk_haku !== "undefined" && hakusana === nyk_hakusana)) {
                    return false;
                }
                if (nyk_haku !== "undefined") {
                    nyk_haku.abort();
                }
                nyk_hakusana = hakusana;
                nyk_haku = $.ajax({
                    type: "GET",
                    url: "/Remix/json/haekayttajat.php",
                    data: {
                        hakusana: hakusana,
                        tiedot: "id|login|email|etunimi|sukunimi|syntymavuosi",
                        jarjesta: jarjesta
                    },
                    dataType: "json",
                    success: function(data, textStatus, jqXHR) {
                        nyk_haku = "undefined";
                        nyk_hakusana = "";
                        naytaKayttajanTiedot(data, mihin);
                    }
                });
            }, 500);

        } else {
            $("#" + mihin).empty();
        }
    }

    function naytaKayttajanTiedot(data, mihin) {
        var sivuid = $("#sivuid").val();
        $("#" + mihin).empty();
        var i = 0;
        while (typeof data[i] !== "undefined") {

            var div = $("<div/>", {
                class: "kayttaja",
            });
            var div2 = $("<div/>", {
                class: "tarkattiedot",
                css: {
                    display: "none"
                },
            });

            div2.append("<div class=\"tieto\"><div class=\"vasen\">Tunnus</div><div class=\"oikea\">" + data[i].login + "</div></div>" +
                    "<div class=\"tieto\"><div class=\"vasen\">Sähköposti</div><div class=\"oikea\">" + data[i].email + "</div></div>" +
                    "<div class=\"tieto\"><div class=\"vasen\">Syntymävuosi</div><div class=\"oikea\">" + data[i].syntymavuosi) + "</div></div>";
            $(div).append("<div class=\"perustiedot\"><div class=\"nimi\">" + data[i].etunimi + " " + data[i].sukunimi + "</div><div class=\"oikealle\"></div></div>");
            $(div).children(".perustiedot").children(".nimi").click(function() {
                naytaKayttajanTarkatTiedot(this);
            });
            var input = $("<input/>", {
                type: "button",
                value: "Muokkaa"
            }).appendTo($(div).children(".perustiedot").children(".oikealle"));
            $(input).click({i: i}, function(event) {
                siirry("/Remix/ohjauspaneeli.php?sivuid=" + sivuid + "&mode=muokkaa&kayttajatid=" + data[event.data.i].id);
            });
            div2.appendTo(div);
            $(div).append("<div id=\"clear\"></div>");
            div.appendTo("#" + mihin);
            i++;
        }
    }

    var naytaKayttajanTarkatTiedot = function(element) {
        var parent = $(element).parent().parent();
        parent.children(".tarkattiedot").show(400);
        $(element).unbind("click");
        $(element).bind("click", function() {
            piilotaKayttajanTarkatTiedot(element);
        });
    };

    var piilotaKayttajanTarkatTiedot = function(element) {
        var parent = $(element).parent().parent();
        parent.children(".tarkattiedot").hide(400);
        $(element).unbind("click");
        $(element).bind("click", function() {
            naytaKayttajanTarkatTiedot(element);
        });
    };

    function haekeskustelualueet(kyselyid, valikko) {
        var ryhmatid = $("#ohjauspaneeli #kayttajienhallinta #keskustelualueoikeudethallinta #ryhmanvalintavalikko #keskustelualueryhmat").val();
        var tunnusid = $("#ohjauspaneeli #kayttajienhallinta #keskustelualueoikeudethallinta #ryhmanvalintavalikko #tunnusid").val();
        console.log(tunnusid);
        $.ajax({
            type: "POST",
            url: "/Remix/json/haekeskustelualueet.php",
            data: {
                ryhmatid: ryhmatid,
                tunnusid: tunnusid,
                kyselyid: kyselyid
            },
            dataType: "json",
            success: function(data, textStatus, jqXHR) {
                paivitakeskustelualueet(data, valikko);
            }
        });
    }
    
    function paivitakeskustelualueet(data, valikko){
        $("#"+valikko+" option").remove();
        $(data).each(function(id, keskustelualue){
            $("#"+valikko).append("<option value=\"" + keskustelualue.id + "\">" + keskustelualue.nimi + "</option>");
        });
    }
    
    $(document).ready(function() {
        $("#ohjauspaneeli #ohjauspaneeli_valikko").change(function() {
            vaihda("/ohjauspaneeli.php", ["sivuid"], ["#ohjauspaneeli_valikko"], "");
        });
        $("#ohjauspaneeli #keskustelualuehallinta #ryhmavalikko").change(function() {
            vaihda("/ohjauspaneeli.php", ["sivuid", "mode", "keskustelualueryhmatid"], ["#sivuid", "#mode", "#ryhmavalikko"], "");
        });
        $("#ohjauspaneeli #keskustelualuehallinta #muokkaaform #muokkaa").click(function() {
            laheta("muokkaaform", ['ohjaatiedot'], ['9'], ['keskustelualue']);
        });
        $("#ohjauspaneeli #keskustelualuehallinta #muokkaaform #poista").click(function() {
            laheta("muokkaaform", ['ohjaatiedot'], ['10'], ['keskustelualue']);
        });
        $("#ohjauspaneeli #keskustelualuehallinta #siirraform #siirra").click(function() {
            laheta("siirraform", [], [], ['keskustelualue']);
        });
        $("#ohjauspaneeli #kayttajienhallinta #kayttajanvalinta").change(function() {
            $(this).submit();
        });
        $("#ohjauspaneeli #kayttajienhallinta  #haku").keyup(function() {
            kayttajienHaku("haku", "jarjesta", "tulokset");
        });
        $("#ohjauspaneeli #kayttajienhallinta  .jarjesta").change(function() {
            kayttajienHaku("haku", "jarjesta", "tulokset");
        });
        $("#ohjauspaneeli #kayttajienhallinta #muokkaaform #muokkaa").click(function() {
            vahvista_ja_laheta("muokkaaform", [], [], [], "Haluatko varmasti muokata käyttäjän tietoja?");
        });
        $("#ohjauspaneeli #kayttajienhallinta #estaform #esta").click(function() {
            vahvista_ja_laheta("estaform", [], [], [], "Haluatko varmasti estää/poistaa eston?");
        });
        $("#ohjauspaneeli #kayttajienhallinta #keskustelualueoikeudethallinta #ryhmanvalintavalikko #keskustelualueryhmat").change(function(){
            haekeskustelualueet(1, "kahlisays");
            haekeskustelualueet(2, "kahpoisto");
            haekeskustelualueet(3, "kanlisays");
            haekeskustelualueet(4, "kanpoisto");
        });
        $("#ohjauspaneeli #kayttajienhallinta #keskustelualueoikeudethallinta .kahlisaa").click(function(){
            var oikeudet = {
                oikeudet: $("#kahlisays").val()
            }
            $("#ohjauspaneeli #kayttajienhallinta #oikeudet").val(JSON.stringify(oikeudet));
            laheta('keskustelualueoikeudetform', ['keskustelualueoikeudet_ohjaa'], ['14'], []);
        });
        $("#ohjauspaneeli #kayttajienhallinta #keskustelualueoikeudethallinta .kahpoista").click(function(){
            var oikeudet = {
                oikeudet: $("#kahpoisto").val()
            }
            $("#ohjauspaneeli #kayttajienhallinta #oikeudet").val(JSON.stringify(oikeudet));
            laheta('keskustelualueoikeudetform', ['keskustelualueoikeudet_ohjaa'], ['31'], []);
        });
        $("#ohjauspaneeli #kayttajienhallinta #keskustelualueoikeudethallinta .kanlisaa").click(function(){
            var oikeudet = {
                oikeudet: $("#kanlisays").val()
            }
            $("#ohjauspaneeli #kayttajienhallinta #oikeudet").val(JSON.stringify(oikeudet));
            laheta('keskustelualueoikeudetform', ['keskustelualueoikeudet_ohjaa'], ['13'], []);
        });
        $("#ohjauspaneeli #kayttajienhallinta #keskustelualueoikeudethallinta .kanpoista").click(function(){
            var oikeudet = {
                oikeudet: $("#kanpoisto").val()
            }
            $("#ohjauspaneeli #kayttajienhallinta #oikeudet").val(JSON.stringify(oikeudet));
            laheta('keskustelualueoikeudetform', ['keskustelualueoikeudet_ohjaa'], ['30'], []);
        });
    });
})();
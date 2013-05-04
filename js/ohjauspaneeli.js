//Hakee käyttäjät
function haeKayttajat(sivuid, hakuKentta, jarjestysKentat, mihin) {
    //Hakusana
    var hakusana = $("#" + hakuKentta).val();
    var jarjesta = 0;
    //Haetaan arvo millä tavalla järjestetään tulokset
    jarjesta = $("#" + jarjestysKentat + ":checked").val();
    //Jos hakusana ei ollut tyhjä
    if (hakusana != "") {
        $.ajax({
            type: "GET",
            url: "/Remix/haekayttajat.php",
            data: {
                hakusana: hakusana,
                tiedot: "id|login|email|etunimi|sukunimi|syntymavuosi",
                jarjesta: jarjesta
            },
            dataType: "json",
            success: function(data, textStatus, jqXHR) {
                naytaKayttajanTiedot(data, sivuid, mihin);
            }
        });
    } else {
        $("#" + mihin).empty();
    }
}

function naytaKayttajanTiedot(data, sivuid, mihin) {
    $("#" + mihin).empty();
    var i = 0;
    while (typeof data[i] != "undefined") {
        $("#" + mihin).append("<div id=\"kayttaja\" onclick=\"siirry('/Remix/ohjauspaneeli.php?sivuid=" + sivuid + "&mode=muokkaa&kayttajatid=" + data[i].id + "')\">" +
                "Tunnus: " + data[i].login +
                " Sähköposti: " + data[i].email +
                " Etunimi: " + data[i].etunimi +
                " Sukunimi: " + data[i].sukunimi +
                " Syntymävuosi: " + data[i].syntymavuosi +
                "</div>");
        i++;
    }
}
function paivitaKeskustelualueet(kayttaja, ryhmatid, mihinid, kysely) {
    haeKeskustelualueet(ryhmatid, kysely, kayttaja, function(data) {
        $("#" + mihinid + " option").remove();
        $(data).each(function(id, keskustelualue) {
            $("#" + mihinid).append("<option value=\"" + keskustelualue.id + "\">" + keskustelualue.nimi + "</option>");
        })
    });
}

//Hakee keskustelualueen tiedot
function haeKeskustelualueenTiedot(keskustelualueid, mihinNimi, mihinKuvaus, julkinen) {
    var keskustelualue = document.getElementById(keskustelualueid);
    //Jos valittu vähinttän yksi
    if (keskustelualue.options.length > 0) {
        alustaPyynto();
        Pyynto.onreadystatechange = function() {
            if (Pyynto.readyState == 4 && Pyynto.status == 200) {
                var xmlDoc = Pyynto.responseXML;
                //Jos saatiin tietoja
                if (xmlDoc.getElementsByTagName("keskustelualue")[0].childNodes.length != 0) {
                    keskustelualue = xmlDoc.getElementsByTagName("keskustelualue")[0];
                    //Asetetaan ne oikeisiin kenttiin
                    $("#" + mihinNimi).val(keskustelualue.getElementsByTagName("nimi")[0].childNodes[0].nodeValue);
                    $("#" + mihinKuvaus).val(keskustelualue.getElementsByTagName("kuvaus")[0].childNodes[0].nodeValue);
                    $("#" + julkinen).attr('checked', (keskustelualue.getElementsByTagName("julkinen")[0].childNodes[0].nodeValue == 1 ? true : false));
                }

            }
        }
        Pyynto.open("POST", "/Foorumit/haekeskustelualueentiedot.php", true);
        Pyynto.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        Pyynto.send("keskustelualue=" + keskustelualue.options[keskustelualue.selectedIndex].value);
    }
    else {
        $("#" + mihinNimi).val("");
        $("#" + mihinKuvaus).val("");
    }
}



//Hakee Käyttäjät joukkueeseen
function haeKayttajatJoukkueeseen(hakuKentta, jarjestysKentat, mihin) {
    var hakusana = $("#" + hakuKentta).val();
    var jarjesta = 0;
    //Haetaan arvo millä tavalla järjestetään tulokset
    jarjesta = $("#" + jarjestysKentat + ":checked").val();
    //Jos hakusana ei ollut tyhjä
    if (hakusana != "") {
        $.ajax({
            type: "GET",
            url: "/Remix/haekayttajat.php",
            data: {
                hakusana: hakusana,
                tiedot: "id|etunimi|sukunimi|syntymavuosi|email",
                jarjesta: jarjesta
            },
            dataType: "json",
            success: function(data, textStatus, jqXHR) {
                naytaKayttajanTiedotJoukkueeseen(data, mihin);
            }
        });
    } else {
        $("#" + mihin).empty();
    }
}

function naytaKayttajanTiedotJoukkueeseen(data, mihin) {
    $("#" + mihin).empty();
    var i = 0;
    while (typeof data[i] != "undefined") {
        $("#" + mihin).append("<div onclick=\"lisaaLisattaviin('" + data[i].id + "', '" + data[i].etunimi + "', '" + data[i].sukunimi + "', '" + data[i].syntymavuosi + "', '" + data[i].email + "')\">" +
                "Etunimi: " + data[i].etunimi +
                " Sukunimi: " + data[i].sukunimi +
                " Syntymävuosi: " + data[i].syntymavuosi +
                " Sähköposti: " + data[i].email +
                "</div>");
        i++;
    }
}

//Lisää tunnuksen lisättävien listaan
function lisaaLisattaviin(id, etunimi, sukunimi, syntymavuosi, email) {
    if (!tarkistaOnkoJoLisatty(id)) {
        lisattavat[lisattavat.length] = new Array(id, etunimi, sukunimi, syntymavuosi, email);
        paivitaLisattavat();
    }
}

function tarkistaOnkoJoLisatty(id) {
    for (var i = 0; i < lisattavat.length; i++) {
        if (lisattavat[i][0] == id) {
            return true;
        }
    }
    return false;
}
//Poistaa tunnuksen lisättävien listasta
function poistaLisattavista(id) {
    for (var i = 0; i < lisattavat.length; i++) {
        if (lisattavat[i][0] == id)
            lisattavat.splice(i, 1);
    }
    paivitaLisattavat();
}

//Päivittää lisättävien listan
function paivitaLisattavat() {

    $("#lisattavat").empty();
    for (var i = 0; i < lisattavat.length; i++) {
        $("#lisattavat").append("<div onclick=\"poistaLisattavista('" + lisattavat[i][0] + "')\">Etunimi: " + lisattavat[i][1] +
                " Sukunimi: " + lisattavat[i][2] +
                " Syntymävuosi: " + lisattavat[i][3] +
                " Sähköposti: " + lisattavat[i][4] + "</div>");
    }
}

//Hakee valitut roolit lisättäville ja lähettää formin
function lahetaPelaajat(form) {
    var tiedot = "";
    for (var i = 0; i < lisattavat.length; i++) {
        tiedot += (i == 0 ? "" : "|") + lisattavat[i][0];
    }
    laheta(form, ['pelaajat'], [tiedot]);
}

//Vaihtaa hallintapaneelia
function vaihdaHallinta() {
    var valikko = document.getElementById("sivuid");
    var sivuid = valikko.options[valikko.selectedIndex].value;
    if (sivuid != "")
        siirry("/Remix/ohjauspaneeli.php?sivuid=" + sivuid);
}

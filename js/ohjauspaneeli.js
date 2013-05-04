//Hakee k�ytt�j�t
function haeKayttajat(sivuid, hakuKentta, jarjestysKentat, mihin) {
    //Hakusana
    var hakusana = $("#" + hakuKentta).val();
    var jarjesta = 0;
    //Haetaan arvo mill� tavalla j�rjestet��n tulokset
    jarjesta = $("#" + jarjestysKentat + ":checked").val();
    //Jos hakusana ei ollut tyhj�
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
                " S�hk�posti: " + data[i].email +
                " Etunimi: " + data[i].etunimi +
                " Sukunimi: " + data[i].sukunimi +
                " Syntym�vuosi: " + data[i].syntymavuosi +
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
    //Jos valittu v�hintt�n yksi
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



//Hakee K�ytt�j�t joukkueeseen
function haeKayttajatJoukkueeseen(hakuKentta, jarjestysKentat, mihin) {
    var hakusana = $("#" + hakuKentta).val();
    var jarjesta = 0;
    //Haetaan arvo mill� tavalla j�rjestet��n tulokset
    jarjesta = $("#" + jarjestysKentat + ":checked").val();
    //Jos hakusana ei ollut tyhj�
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
                " Syntym�vuosi: " + data[i].syntymavuosi +
                " S�hk�posti: " + data[i].email +
                "</div>");
        i++;
    }
}

//Lis�� tunnuksen lis�tt�vien listaan
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
//Poistaa tunnuksen lis�tt�vien listasta
function poistaLisattavista(id) {
    for (var i = 0; i < lisattavat.length; i++) {
        if (lisattavat[i][0] == id)
            lisattavat.splice(i, 1);
    }
    paivitaLisattavat();
}

//P�ivitt�� lis�tt�vien listan
function paivitaLisattavat() {

    $("#lisattavat").empty();
    for (var i = 0; i < lisattavat.length; i++) {
        $("#lisattavat").append("<div onclick=\"poistaLisattavista('" + lisattavat[i][0] + "')\">Etunimi: " + lisattavat[i][1] +
                " Sukunimi: " + lisattavat[i][2] +
                " Syntym�vuosi: " + lisattavat[i][3] +
                " S�hk�posti: " + lisattavat[i][4] + "</div>");
    }
}

//Hakee valitut roolit lis�tt�ville ja l�hett�� formin
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

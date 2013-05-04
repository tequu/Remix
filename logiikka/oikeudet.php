<?php

//Tarkistaa onko käyttäjällä näkyvyys oikeudet keskustelualueelle
function tarkistaNakyvyysOikeudetKeskustelualueelle($yhteys, $keskustelualue) {
    $kysely = kysely($yhteys, "SELECT julkinen FROM keskustelualueet WHERE id='" . $keskustelualue . "'");
    $tulos = mysql_fetch_array($kysely);
    if ($tulos['julkinen']) {
        return true;
    }
    if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        return true;
    }
    $kayttaja = mysql_real_escape_string($_SESSION['id']);
    //Tarkistetaan hallinta oikeudet
    if (tarkistaHallintaOikeudet($yhteys, "keskustelualueetID", $keskustelualue)) {
        return true;
    }
    //Tarkistetaan näkyvyys oikdeudet
    $kysely = kysely($yhteys, "SELECT k.id FROM keskustelualueoikeudet RIGHT OUTER JOIN keskustelualueet k ON keskustelualueetID=k.id " .
            "WHERE julkinen='1' OR (k.id='" . $keskustelualue . "' AND tunnuksetID='" . $kayttaja . "')");
    while ($tulos = mysql_fetch_array($kysely)) {
        if ($tulos['id'] == $keskustelualue) {
            return true;
        }
    }
    $kysely = kysely($yhteys, "SELECT id FROM joukkueet WHERE keskustelualueetID='" . $keskustelualue . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        if (tarkistaNakyvyysOikeudetJoukkueeseen($yhteys, $tulos['id'])) {
            return true;
        }
    }
    return false;
}

//Tarkistetaan onko hallinta oikeudet keskustelualueelle
function tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue) {
    global $siirry;
    if (tarkistaHallintaOikeudet($yhteys, "keskustelualueetID", $keskustelualue)) {
        return true;
    }
    $joukkue = tarkistaOnkoJoukkueenKeskustelualue($yhteys, $keskustelualue);
    if ($joukkue) {
        if (tarkistaHallintaOikeudet($yhteys, "joukkueetID", $joukkue)) {
            return true;
        }
    }
    if ($siirry)
        siirry("eioikeuksia.php");
    return false;
}

function tarkistaNakyvyysOikeudetJoukkueeseen($yhteys, $joukkue) {
    if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        return true;
    }
    $kayttaja = mysql_real_escape_string($_SESSION['id']);
    //Tarkistetaan ensin onko hallinta oikeudet
    if (tarkistaHallintaoikeudet($yhteys, "joukkueetID", $joukkue)) {
        return true;
    }
    //Jos ei nii tarkistetaan vielä näkyvyys
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM pelaajat WHERE tunnuksetID='" . $kayttaja . "'");
    while ($tulos = mysql_fetch_array($kysely)) {
        if ($tulos['joukkueetID'] == $joukkue) {
            return true;
        }
    }
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM yhteyshenkilot WHERE tunnuksetID='" . $kayttaja . "'");
    while ($tulos = mysql_fetch_array($kysely)) {
        if ($tulos['joukkueetID'] == $joukkue) {
            return true;
        }
    }
}

//Tarkistaa onko käyttäjällä hallinta oikeudet joukkueeseen
function tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue) {
    if (tarkistaHallintaOikeudet($yhteys, "joukkueetID", $joukkue)) {
        return true;
    }
    return false;
}

/**
 * Tarkistaa onko käyttäjällä halutut admin oikeudet
 * 
 * @param type $admin Haluttu admin oikeus
 * @return boolean True jos on, muute false
 */
function tarkistaAdminOikeudet($yhteys, $admin) {
    //Tarkistetaan tunnus
    tarkistaKirjautuneenTunnus($yhteys);
    //Päivitetään oikeudet
    paivitaOikeudet($yhteys);
    if ($_SESSION['oikeudet'] == "Masteradmin") {
        return true;
    }
    if ($_SESSION['oikeudet'] == $admin) {
        return true;
    }
    return false;
}

//Tarkistaa hallinta oikudet
function tarkistaHallintaOikeudet($yhteys, $kumpi, $vaadittava) {
    //Tarkistetaan, jos Masteradmin
    if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        return true;
    }
    //Tarkistetaan tunnus
    tarkistaKirjautuneenTunnus($yhteys);
    $kayttaja = mysql_real_escape_string($_SESSION['id']);
    //Jos vaadittava ei ole nolla
    if ($vaadittava != 0) {
        //Tarkistetaan onko hallinta oikeudet kyseiseen paikkaan
        $kysely = kysely($yhteys, "SELECT " . $kumpi . " id FROM oikeudet WHERE tunnuksetID='" . $kayttaja . "'");
        while ($tulos = mysql_fetch_array($kysely)) {
            if ($tulos['id'] == $vaadittava) {
                return true;
            }
        }
    } else {
        //Tarkistetaan onko oikeudet johonkin joukkueeseen tai keskustelualueeseen
        $kysely = kysely($yhteys, "SELECT tunnuksetID FROM oikeudet WHERE tunnuksetID='" . $kayttaja . "' AND " . $kumpi . "!='null'");
        if ($tulos = mysql_fetch_array($kysely)) {
            return true;
        }
    }
    return false;
}

?>

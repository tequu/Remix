<?php

/*
 * Tarkistaa että kirjautuneen tunnus on olemassa ja tiedot on oikein sessionissa.
 */

function tarkistaKirjautuneenTunnus($yhteys) {
    global $siirry;
    //Tarkistetaan että on olemassa sessioni
    if (!isset($_SESSION['id'])) {
        if ($siirry) {
            //Otetaan talteen nykyinen sivu, että voidaan palata sinne kun on kirjauduttu sisään
            $sivu = explode("/", $_SERVER['PHP_SELF']);
            $sivu = $sivu[count($sivu) - 1];
            if (!empty($_GET)) {
                $sivu .= "?";
                foreach ($_GET as $a => $b) {
                    $sivu .= $a . "=" . $b . "&";
                }
                $sivu = substr($sivu, 0, -1);
            }
            $_SESSION['kirjaudu'] = $sivu;
            //Jos ei ole niin ohjataan kirjautumis sivulle
            siirry("kirjaudu.php");
        }
        return false;
    }
    $id = mysql_real_escape_string($_SESSION['id']);
    $kysely = kysely($yhteys, "SELECT login FROM tunnukset WHERE id='" . $id . "'");
    //Tarkistetaan että login vastaa tietokannasta saatua tulosta
    if ($tulos = mysql_fetch_array($kysely)) {
        if ($tulos['login'] != "" && $tulos['login'] == $_SESSION['login']) {
            return true;
        }
    }
    if ($siirry) {
        siirry("virhe.php");
    }
    return false;
}

/**
 * Päivittää käyttäjän admin oikeudet
 */
function paivitaOikeudet($yhteys) {
    global $siirry;
    $tunnusid = mysql_real_escape_string($_SESSION['id']);
    //Haetaan käyttäjän oikeudet
    $kysely = kysely($yhteys, "SELECT isadmin FROM tunnukset WHERE id='" . $tunnusid . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        $_SESSION['oikeudet'] = $tulos['isadmin'];
        return;
    }
    $_SESSION['oikeudet'] = "";
    if ($siirry)
        siirry("virhe.php");
    return;
}

//Tarkistetaan että tietokannasta löytyy id:tä vastaava tunnus
function tarkistaTunnuksenOlemassaOlo($yhteys, $id) {
    $id = mysql_real_escape_string($id);
    $kysely = kysely($yhteys, "SELECT id FROM tunnukset WHERE id='" . $id . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        return true;
    }
    return false;
}

//Poistaa kaikki tunnukset, jotka eivät ole käytössä
function poistaTunnuksetJotkaEivatKaytossa($yhteys) {
    kysely($yhteys, "DELETE FROM nakyvattiedot WHERE " .
            "id IN (SELECT nakyvattiedotID FROM tunnukset WHERE enabled='0' AND id NOT IN (SELECT tunnuksetID FROM pelaajat) AND id NOT IN (SELECT tunnuksetID FROM yhteyshenkilot) AND id NOT IN (SELECT tunnuksetID FROM tilastot))");
    kysely($yhteys, "DELETE FROM tokenit WHERE tunnuksetID IN " .
            "(SELECT id FROM tunnukset WHERE enabled='0' AND id NOT IN (SELECT tunnuksetID FROM pelaajat) AND id NOT IN (SELECT tunnuksetID FROM yhteyshenkilot) AND id NOT IN (SELECT tunnuksetID FROM tilastot))");
    kysely($yhteys, "DELETE FROM tunnukset WHERE enabled='0' AND id NOT IN (SELECT tunnuksetID FROM pelaajat) AND id NOT IN (SELECT tunnuksetID FROM yhteyshenkilot) AND id NOT IN (SELECT tunnuksetID FROM tilastot)");
}

function haeKayttajanNimi($yhteys, $tunnusid){
    $tunnusid = mysql_real_escape_string($tunnusid);
    $kysely = kysely($yhteys, "SELECT etunimi, sukunimi FROM tunnukset WHERE id='".$tunnusid."'");
    $tulos = mysql_fetch_array($kysely);
    return $tulos['etunimi']." ".$tulos['sukunimi'];
}
?>

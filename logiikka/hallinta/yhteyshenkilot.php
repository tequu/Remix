<?php

//Liss� uusi yhteyshenkilo joukkueeseen
function lisaaUusiYhteyshenkilo($yhteys) {
    global $oyhteyshenkilot, $error;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue) || !tarkistaOnkoJoukkueNykysellaKaudella($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lis�t� pelaajia " . haeJoukkueenNimi($yhteys, $joukkue) . " joukkueeseen.";
        siirry("eioikeuksia.php");
    }
    $tunnusid = luoTunnus($yhteys);
    $tiedot = mysql_real_escape_string(trim($_POST['tiedot']));
    $rooli = mysql_real_escape_string($_POST['rooli'] == "Muu, mik�?" ? $_POST['muu'] : $_POST['rooli']);
    if (!$tunnusid) {
        return false;
    }
    lisaaYhteyshenkiloJoukkueeseen($yhteys, $joukkue, array($tunnusid), array($tiedot), array($rooli));
    if (empty($error)) {
        ohjaaOhajuspaneeliin($oyhteyshenkilot, "&joukkue=" . $joukkue);
    }
}

//Lis�� yhteyshenkil�n joukkueeseen
function lisaaYhteyshenkilo($yhteys) {
    global $siirry, $oyhteyshenkilot;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    $yhteyshenkilot = explode("|", mysql_real_escape_string($_POST['yhteyshenkilot']));
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue) || !tarkistaOnkoJoukkueNykysellaKaudella($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lis�t� yhteyshenkil�it� " . haeJoukkueenNimi($yhteys, $joukkue) . " joukkueeseen.";
        if ($siirry)
            siirry("eioikeuksia.php");
        $error = $_SESSION['eioikeuksia'];
        return false;
    }
    if(!lisaaYhteyshenkiloJoukkueeseen($yhteys, $joukkue, $yhteyshenkilot, array(), array())){
        return false;
    }
    if ($siirry) {
        ohjaaOhajuspaneeliin($oyhteyshenkilot, "&joukkue=" . $joukkue);
    }
    return true;
}

//Muokkaa yhteyshenkilon rooli
function muokkaaYhteyshenkiloa($yhteys) {
    global $oyhteyshenkilot;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue) || !tarkistaOnkoJoukkueNykysellaKaudella($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lis�t� pelaajia " . haeJoukkueenNimi($yhteys, $joukkue) . " joukkueeseen.";
        siirry("eioikeuksia.php");
    }
    $yhteyshenkilotid = mysql_real_escape_string($_GET['yhteyshenkilotid']);
    if (!tarkistaEttaYhteyshenkiloKuuluuJoukkueeseen($yhteys, $joukkue, $yhteyshenkilotid)) {
        $_SESSION['virhe'] = "Yhteyshenkil�� ei l�ytynyt joukkueesta.";
        siirry("virhe.php");
    }
    $tiedot = mysql_real_escape_string(trim($_POST['tiedot']));
    $rooli = mysql_real_escape_string($_POST['rooli'] == "Muu, mik�?" ? $_POST['muu'] : $_POST['rooli']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM yhteyshenkilot WHERE joukkueetID='" . $joukkue . "' AND tunnuksetID='" . $yhteyshenkilotid . "'");
     if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Joukkueesta " . haeJoukkueenNimi($yhteys, $joukkue) . " ei l�ytynyt muokkaamaasi yhteyshenkil��.";
        siirry("virhe.php");
    }
    muokkaaTunnuksenTietoja($yhteys, $yhteyshenkilotid);
    kysely($yhteys, "UPDATE yhteyshenkilot SET tiedot='".$tiedot."', rooli='".$rooli."' WHERE joukkueetID='".$joukkue."' AND tunnuksetID='".$yhteyshenkilotid."'");
    ohjaaOhajuspaneeliin($oyhteyshenkilot, "&joukkue=" . $joukkue . "&mode=muokkaa&yhteyshenkilotid=" . $yhteyshenkilotid);
}

//Poistaa joukkueen yhteyshenkilon
function poistaYhteyshenkilo($yhteys) {
    global $oyhteyshenkilot;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    $yhteyshenkilotid = mysql_real_escape_string($_GET['yhteyshenkilotid']);
    if (!tarkistaEttaYhteyshenkiloKuuluuJoukkueeseen($yhteys, $joukkue, $yhteyshenkilotid)) {
        $_SESSION['virhe'] = "Pelaajaa ei l�ytynyt joukkueesta.";
        siirry("virhe.php");
    }
    poistaJoukkueenJasen($yhteys, "yhteyshenkilot", $yhteyshenkilotid, $joukkue);
    ohjaaOhajuspaneeliin($oyhteyshenkilot, "&joukkue=" . $joukkue);
}

function tarkistaEttaYhteyshenkiloKuuluuJoukkueeseen($yhteys, $joukkue, $yhteyshenkilo) {
    global $error, $siirry;
    if (!tarkistaTunnuksenOlemassaOlo($yhteys, $yhteyshenkilo)) {
        $_SESSION['virhe'] = "Tunnusta ei l�ytynyt.";
        if ($siirry) {
            siirry("virhe.php");
        }
        $error = $_SESSION['virhe'];
        return false;
    }
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM yhteyshenkilot WHERE joukkueetID='" . $joukkue . "' AND tunnuksetID='" . $yhteyshenkilo . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        return true;
    }
    return false;
}
?>

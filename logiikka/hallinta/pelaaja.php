<?php

//Lisää uusi pelaaja joukkueeseen
function lisaaUusiPelaajaJoukkueeseen($yhteys) {
    global $opelaajat;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue) || !tarkistaOnkoJoukkueNykysellaKaudella($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lisätä pelaajia " . haeJoukkueenNimi($yhteys, $joukkue) . " joukkueeseen.";
        siirry("eioikeuksia.php");
    }
    $tunnusid = luoTunnus($yhteys);
    $rooli = mysql_real_escape_string($_POST['rooli'] == "Muu, mikä?" ? $_POST['muu'] : $_POST['rooli']);
    $kapteeni = mysql_real_escape_string($_POST['kapteeni']);
    $pelinumero = mysql_real_escape_string($_POST['pelinumero']);
    if (!$tunnusid) {
        return false;
    }
    //Lisätään pelaaja joukkueeseen
    lisaaPelaajatJoukkueeseen($yhteys, $joukkue, array($tunnusid), array($rooli), array($pelinumero));
    if ($kapteeni) {
        kysely($yhteys, "UPDATE joukkueet SET kapteeni='" . $tunnusid . "' WHERE id='" . $joukkue . "'");
    }
    if (empty($error)) {
        ohjaaOhajuspaneeliin($opelaajat, "&joukkue=" . $joukkue);
    }
    return false;
}

//Lisää pelaajan joukkueeseen
function lisaaPelaajat($yhteys) {
    global $error, $siirry, $opelaajat;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    $pelaajat = explode("|", mysql_real_escape_string($_POST['pelaajat']));
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue) || !tarkistaOnkoJoukkueNykysellaKaudella($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lisätä pelaajia " . haeJoukkueenNimi($yhteys, $joukkue) . " joukkueeseen.";
        if ($siirry)
            siirry("eioikeuksia.php");
        $error = $_SESSION['eioikeuksia'];
        return false;
    }
    $joukkueeseen = lisaaPelaajatJoukkueeseen($yhteys, $joukkue, $pelaajat, array(), array());
    $tilastot = array();
    $kysely = kysely($yhteys, "SELECT id FROM tilastoryhmat WHERE joukkueetID='" . $joukkue . "'");
    while ($tulos = mysql_fetch_array($kysely)) {
        if ($_POST['tilastoon_' . $tulos['id']]) {
            $tilastot[] = $tulos['id'];
        }
    }
    if (!empty($tilastot)) {
        $tilastoon = lisaaTilastoon($yhteys, $tilastot, $joukkue, $pelaajat);
    }
    if (!empty($error) || !$joukkueeseen || !$tilastoon) {
        return false;
    }
    if ($siirry) {
        ohjaaOhajuspaneeliin($opelaajat, "&joukkue=" . $joukkue);
    }
    return true;
}

function muokkaaPelaaja($yhteys) {
    global $error, $opelaajat;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata roolia.";
        siirry("eioikeuksia.php");
    }
    $pelaajatid = mysql_real_escape_string($_GET['pelaajatid']);
    if (!tarkistaTunnuksenOlemassaOlo($yhteys, $pelaajatid)) {
        $_SESSION['virhe'] = "Tunnusta ei löytynyt.";
        siirry("virhe.php");
    }
    $rooli = mysql_real_escape_string($_POST['rooli'] == "Muu, mikä?" ? $_POST['muu'] : $_POST['rooli']);
    $pelinumero = mysql_real_escape_string($_POST['pelinumero']);
    $kapteeni = mysql_real_escape_string($_POST['kapteeni']);
    $kuvannimi = muutaTekstiHyvaksyttavaanMuotoon(basename($_FILES['kuva']['name']));
    $kuvanosoite = "/home/fbcremix/public_html/Remix/kuvat/pelaajakortit/" . $joukkue . "/pelaajat/" . $kuvannimi;
    $minikuvanosoite = "/home/fbcremix/public_html/Remix/kuvat/pelaajakortit/" . $joukkue . "/mini_pelaajat/" . $kuvannimi;
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM pelaajat WHERE joukkueetID='" . $joukkue . "' AND tunnuksetID='" . $pelaajatid . "'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Joukkueesta " . haeJoukkueenNimi($yhteys, $joukkue) . " ei löytynyt muokkaamaasi pelaajaa.";
        siirry("virhe.php");
    }
    muokkaaTunnuksenTietoja($yhteys, $pelaajatid);
    if (!empty($kuvannimi)) {
        siirraKuva($kuvanosoite, 380, 570, 380, 570, $minikuvanosoite, 120, 180);
    }
    kysely($yhteys, "UPDATE pelaajat SET rooli='" . $rooli . "', pelinumero='" . $pelinumero . "'" . (!empty($kuvannimi) && empty($error) ? ", kuva='" . $kuvannimi . "'" : "") .
            " WHERE joukkueetID='" . $joukkue . "' AND tunnuksetID='" . $pelaajatid . "'");
    paivitaPelaajanLisatiedot($yhteys, $joukkue, $pelaajatid);
    if ($kapteeni) {
        kysely($yhteys, "UPDATE joukkueet SET kapteeni='" . $pelaajatid . "' WHERE id='" . $joukkue . "'");
    } else {
        $kysely = kysely($yhteys, "SELECT kapteeni FROM joukkueet WHERE id='" . $joukkue . "'");
        $tulos = mysql_fetch_array($kysely);
        if ($tulos['kapteeni'] == $pelaajatid) {
            kysely($yhteys, "UPDATE joukkueet SET kapteeni='0' WHERE id='" . $joukkue . "'");
        }
    }
    if (empty($error)) {
        ohjaaOhajuspaneeliin($opelaajat, "&joukkue=" . $joukkue . "&mode=muokkaa&pelaajatid=" . $pelaajatid);
    }
    return false;
}

function paivitaPelaajanLisatiedot($yhteys, $joukkue, $pelaajatid) {
    poistaPelaajanLisatiedot($yhteys, $joukkue, $pelaajatid);
    for ($i = 0; $i < 8; $i++) {
        if (!isset($_POST['pelaajakortti_kentta_' . $i]) || !isset($_POST['pelaajakortti_kentta_arvo_' . $i]))
            break;
        if (empty($_POST['pelaajakortti_kentta_' . $i]) || empty($_POST['pelaajakortti_kentta_arvo_' . $i]))
            break;
        $lisatieto_nimi = mysql_real_escape_string(trim($_POST['pelaajakortti_kentta_' . $i]));
        $lisatieto_sisalto = mysql_real_escape_string(trim($_POST['pelaajakortti_kentta_arvo_' . $i]));
        kysely($yhteys, "INSERT INTO pelaajakorttilisatieto(nimi, lisatieto, jarjestysnumero, joukkueetID, tunnuksetID) " .
                "VALUES ('" . $lisatieto_nimi . "', '" . $lisatieto_sisalto . "', '" . $i . "', '" . $joukkue . "', '" . $pelaajatid . "')");
    }
}

function poistaPelaajanLisatiedot($yhteys, $joukkue, $pelaajatid) {
    $joukkue = mysql_real_escape_string($joukkue);
    $tunnus = mysql_real_escape_string($pelaajatid);

    kysely($yhteys, "DELETE FROM pelaajakorttilisatieto WHERE joukkueetID='" . $joukkue . "' AND tunnuksetID='" . $pelaajatid . "'");
}

function poistaPelaajakorttiKuva($yhteys) {
    global $error, $opelaajat;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    $pelaaja = mysql_real_escape_string($_GET['pelaaja']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeukia lisätä pelaajalle lisätietoja.";
        siirry("eioikeuksia.php");
    }
    if (!tarkistaEttaPelaajaKuuluuJoukkueeseen($yhteys, $joukkue, $pelaaja)) {
        $_SESSION['virhe'] = "Pelaajaa ei löytynyt joukkueesta.";
        siirry("virhe.php");
    }
    poistaPelaajanKuva($yhteys, $joukkue, $pelaaja);
    ohjaaOhajuspaneeliin($opelaajat, "&joukkue=" . $joukkue . "&pelaaja=" . $pelaaja);
}

function poistaPelaajanKuva($yhteys, $joukkue, $pelaaja) {
    global $error;
    $mista = "/home/fbcremix/public_html/Remix/kuvat/pelaajakortit/" . $joukkue . "/pelaajat/";
    $minimista = "/home/fbcremix/public_html/Remix/kuvat/pelaajakortit/" . $joukkue . "/mini_pelaajat/";
    $kysely = kysely($yhteys, "SELECT kuva FROM pelaajat WHERE joukkueetID='" . $joukkue . "' AND tunnuksetID='" . $pelaaja . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        if (!unlink($mista . $tulos['kuva']) && !unlink($minimista . $tulos['kuva'])) {
            $error = "Kuvan poistaminen epäonnistui.";
            return false;
        }
        kysely($yhteys, "UPDATE pelaajat SET kuva='null' WHERE joukkueetID='" . $joukkue . "' AND tunnuksetID='" . $pelaaja . "'");
    }
}

//Poistaa pelaajan
function poistaPelaaja($yhteys) {
    global $error, $opelaajat;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    $pelaajatid = mysql_real_escape_string($_GET['pelaajatid']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeukia lisätä pelaajalle lisätietoja.";
        siirry("eioikeuksia.php");
    }
    if (!tarkistaEttaPelaajaKuuluuJoukkueeseen($yhteys, $joukkue, $pelaajatid)) {
        $_SESSION['virhe'] = "Pelaajaa ei löytynyt joukkueesta.";
        siirry("virhe.php");
    }
    $kysely = kysely($yhteys, "SELECT kuva FROM pelaajat WHERE joukkueetID='" . $joukkue . "' AND tunnuksetID='" . $pelaajatid . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        if ($tulos['kuva'] != null) {
            //Poista pelaajan pelaajakortti kuva
            poistaPelaajanKuva($yhteys, $joukkue, $pelaajatid);
        }
    }
    poistaJoukkueenJasen($yhteys, "pelaajat", $pelaajatid, $joukkue);
    kysely($yhteys, "DELETE FROM pelaajakorttilisatieto WHERE joukkueetID='" . $joukkue . "' AND tunnuksetID='" . $pelaajatid . "'");
    if (empty($error))
        ohjaaOhajuspaneeliin($opelaajat, "&joukkue=" . $joukkue);
}

?>
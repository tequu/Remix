<?php

//lisää uutinen
function lisaaUutinen($yhteys) {
    global $error, $ouutiset;
    if (!tarkistaAdminOikeudet($yhteys, "Admin") && !tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0)) {
        $_SESSION['eioikeukia'] = "Sinulla ei ole oikeuksia kirjoittaa uutisia.";
        siirry("eioikeukia.php");
    }
    $kirjoittaja = $_SESSION['id'];
    $otsikko = mysql_real_escape_string(trim($_POST['otsikko']));
    $kuvanotsikko = muutaTekstiHyvaksyttavaanMuotoon($otsikko);
    $kuvaus = trim($_POST['kuvaus']);
    $uutinen = trim($_POST['uutinen']);
    $asettele = "ei";
    $aikaleima = time();
    if (empty($otsikko))
        $error = "*Otsikko kenttä on tyhjä<br />";
    if (empty($uutinen))
        $error .= "*Uutinen kenttä on tyhjä<br />";
    if (empty($kuvaus))
        $error .= "*Kuvaus kenttä on tyhjä<br />";
    if (!empty($error)) {
        return false;
    }
    if (!empty($_FILES['kuva']['name'])) {
        $minne = "/home/fbcremix/public_html/Remix/kuvat/uutiset/uutinen_" . date("dmY", $aikaleima) . "/" . $kuvanotsikko . "/";
        $kuvannimi = muutaTekstiHyvaksyttavaanMuotoon(basename($_FILES['kuva']['name']));
        $koko = getimagesize($_FILES['kuva']['tmp_name']);
        if (siirraKuva($minne . $kuvannimi, 420, 230, 0, 0, $minne . "mini_" . $kuvannimi, 100, 100)) {
            if ($koko[0] <= 200) {
                $asettele = "v";
            } else {
                $asettele = "k";
            }
        } else {
            return false;
        }
    } else {
        $kuvannimi = "ei";
        $asettele = "ei";
    }
    $uutinen = str_replace("\r", "", $uutinen);
    $uutinen = str_replace("\n", "<br />", $uutinen);
    $uutinen = stripslashes($uutinen);
    $uutinen = mysql_real_escape_string($uutinen);

    $kuvaus = str_replace("\r", "", $kuvaus);
    $kuvaus = str_replace("\n", "", $kuvaus);
    $kuvaus = stripslashes($kuvaus);
    $kuvaus = mysql_real_escape_string($kuvaus);
    $kirjoitus_aika = date("Y-m-d H:i", $aikaleima);
    $kysely = kysely($yhteys, "SELECT etunimi, sukunimi FROM tunnukset WHERE id='" . $kirjoittaja . "'");
    $tulos = mysql_fetch_array($kysely);
    $nimi = $tulos['sukunimi'] . " " . $tulos['etunimi'];
    kysely($yhteys, "INSERT INTO uutiset (uutinen, kirjoitusaika,kirjoittaja, otsikko, kuvaus, kuva, asettele, tunnuksetID) " .
            "VALUES ('" . $uutinen . "', '" . $kirjoitus_aika . "', '" . $nimi . "', '" . $otsikko . "', '" . $kuvaus . "', '" . $kuvannimi . "', '" . $asettele . "', '" . $kirjoittaja . "')");
    ohjaaOhajuspaneeliin($ouutiset, "");
}

function muokkaaUutinen($yhteys) {
    global $error;
    $uutisetid = mysql_real_escape_string($_GET['uutisetid']);

    $kysely = kysely($yhteys, "SELECT otsikko, UNIX_TIMESTAMP(kirjoitusaika) kirjoitusaika, kirjoittaja, kuva, asettele, tunnuksetID FROM uutiset WHERE id='" . $uutisetid . "'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Uutista ei löytynyt.";
        siirry("virhe.php");
    }
    $kirjoittaja = $_SESSION['id'];
    if (!tarkistaAdminOikeudet($yhteys, "Masteradmin") && ($kirjoittaja == 0 || $kirjoittaja != $tulos['tunnuksetID'])) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata uutista.";
        siirry("eioikeuksia.php");
    }
    $otsikko = mysql_real_escape_string(trim($_POST['otsikko']));
    $vanhankuvanotsikko = muutaTekstiHyvaksyttavaanMuotoon($tulos['otsikko']);
    $kuvanotsikko = muutaTekstiHyvaksyttavaanMuotoon($otsikko);
    $kuvaus = trim($_POST['kuvaus']);
    $uutinen = trim($_POST['uutinen']);
    $asettele = "ei";
    $minne = "/home/fbcremix/public_html/Remix/kuvat/uutiset/uutinen_" . date("dmY", $tulos['kirjoitusaika']) . "/" . $kuvanotsikko;
    if (empty($otsikko))
        $error = "*Otsikko kenttä on tyhjä<br />";
    if (empty($kuvaus))
        $error .= "*Kuvaus kenttä on tyhjä<br />";
    if (empty($uutinen))
        $error .= "*Uutinen kenttä on tyhjä<br />";
    if (!empty($error)) {
        return false;
    }
    if (!empty($_FILES['kuva']['name'])) {

        $kuvannimi = muutaTekstiHyvaksyttavaanMuotoon(basename($_FILES['kuva']['name']));
        $koko = getimagesize($_FILES['kuva']['tmp_name']);
        if ($kuvannimi == $tulos['kuva']) {
            $error = "Kuvan nimi ei saa olla sama kuin vanhan kuvan.";
            return false;
        }
        if (siirraKuva($minne . "/" . $kuvannimi, 420, 230, 0, 0, $minne . "/mini_" . $kuvannimi, 100, 100)) {
            poistaUutisenKuva($yhteys, false);
            if ($koko[0] <= 200) {
                $asettele = "oikealle";
            } else {
                $asettele = "keskelle";
            }
        } else {
            return false;
        }
    } else {
        $kuvannimi = $tulos['kuva'];
        $asettele = $tulos['asettele'];
        if ($tulos['kuva'] != "ei") {
            rename("/home/fbcremix/public_html/Remix/kuvat/uutiset/uutinen_" . date("dmY", $tulos['kirjoitusaika']) . "/" . $vanhankuvanotsikko, $minne);
        }
    }
    $uutinen = str_replace("\r", "", $uutinen);
    $uutinen = str_replace("\n", "<br />", $uutinen);
    $uutinen = stripslashes($uutinen);
    $uutinen = mysql_real_escape_string($uutinen);

    $kuvaus = str_replace("\r", "", $kuvaus);
    $kuvaus = str_replace("\n", "", $kuvaus);
    $kuvaus = stripslashes($kuvaus);
    $kuvaus = mysql_real_escape_string($kuvaus);

    kysely($yhteys, "UPDATE uutiset SET uutinen='" . $uutinen . "', otsikko='" . $otsikko . "', kuvaus='" . $kuvaus . "', kuva='" . $kuvannimi . "', asettele='" . $asettele . "' WHERE id='" . $uutisetid . "'");

    siirry("uutiset.php?uutisid=" . $uutisetid);
}

function poistaUutisenKuva($yhteys, $siirry) {
    $uutisetid = mysql_real_escape_string($_GET['uutisetid']);
    $kysely = kysely($yhteys, "SELECT UNIX_TIMESTAMP(kirjoitusaika) kirjoitusaika, otsikko, kuva, kirjoittaja FROM uutiset WHERE id='" . $uutisetid . "'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Uutista ei löytynyt.";
        siirry("virhe.php");
    }
    $kirjoittaja = $_SESSION['id'];
    if (!tarkistaAdminOikeudet($yhteys, "Masteradmin") && ($kirjoittaja == 0 || $kirjoittaja != $tulos['kirjoittaja'])) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata uutista.";
        siirry("eioikeuksia.php");
    }
    $kuvanotsikko = muutaTekstiHyvaksyttavaanMuotoon($tulos['otsikko']);
    if ($tulos['kuva'] != "ei" && !empty($tulos['kuva'])) {
        $mista = "/home/fbcremix/public_html/Remix/kuvat/uutiset/uutinen_" . date("dmY", $tulos['kirjoitusaika']) . "/" . $kuvanotsikko . "/";
        unlink($mista . "mini_" . $tulos['kuva']);
        unlink($mista . $tulos['kuva']);
        if (onkoKansioTyhja($mista, array("index.html"))) {
            unlink($mista . "index.html");
            rmdir($mista);
        }
        kysely($yhteys, "UPDATE uutiset SET kuva='ei', asettele='ei' WHERE id='" . $uutisetid . "'");
    }
    if ($siirry) {
        siirry("uutiset.php?uutisetid=" . $uutisetid);
    }
    return true;
}

?>

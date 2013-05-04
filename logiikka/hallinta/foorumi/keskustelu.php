<?php

function uusiKeskustelu($yhteys) {
    tarkistaKirjautuneenTunnus($yhteys);
    global $error;
    $otsikko = mysql_real_escape_string(trim($_POST['otsikko']));
    $viesti = mysql_real_escape_string(trim($_POST['viesti']));
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $kayttaja = mysql_real_escape_string($_SESSION['id']);

    if (tarkistaKeskustelualueetOlemassaOlo($yhteys, $keskustelualue)) {
        if (!tarkistaNakyvyysOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
            return false;
        }

        /* Tarkista käyttäjän oikeudet kirjottaa keskustelualueelle */
        if (empty($otsikko)) {
            $error .= "Et antanut otsikkoa<br />";
        }
        if (empty($viesti)) {
            $error .= "Et kirjoittanut viestiä<br />";
        }
        if (!empty($error)) {
            return;
        }
        //Lisätään uusi keskustelu
        $keskustelu = luoUusiKeskustelu($yhteys, $keskustelualue, $otsikko, $viesti, $kayttaja);
        siirry("foorumi/keskustelu.php?keskustelualue=" . $keskustelualue . "&keskustelu=" . $keskustelu);
    } else {
        //Tänne ei pitäisi päätyä
        siirry("foorumi/index.php");
    }
}

function luoUusiKeskustelu($yhteys, $keskustelualue, $otsikko, $viesti, $kayttaja, $tapahtuma = 0) {
    $aikaleima = time();
    $aika = date("Y-m-d H:i", $aikaleima);
    kysely($yhteys, "INSERT INTO keskustelut (otsikko, aloitusaika, aloittaja, nakyypelaajille" . ($tapahtuma == 0 ? "" : ", tapahtumatID" ) . ") VALUES ('" . $otsikko . "', '" . $aika . "', '" . $kayttaja . "', '1'" . ($tapahtuma == 0 ? "" : ",'" . $tapahtuma . "'") . ")");
    $keskustelu = mysql_insert_id();
    kysely($yhteys, "INSERT INTO keskustelualuekeskustelut (keskustelutID, keskustelualueetID) VALUES ('" . $keskustelu . "', '" . $keskustelualue . "')");
    if ($tapahtuma == 0) {
        kysely($yhteys, "INSERT INTO viestit (otsikko, viesti, lahetysaika, keskustelutID, tunnuksetID) VALUES ('" . $otsikko . "', '" . $viesti . "', '" . $aika . "', '" . $keskustelu . "', '" . $kayttaja . "')");
    }
    return $keskustelu;
}

function muokkaaKeskustelua($yhteys) {
    global $error;
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $keskustelu = mysql_real_escape_string($_POST['keskustelu']);
    $otsikko = mysql_real_escape_string(trim($_POST['otsikko']));
    if (!tarkistaKeskustelunOlemassaOlo($yhteys, $keskustelu, $keskustelualue)) {
        $error = "Keskustelua ei löytynyt.";
        return false;
    }
    if (!tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
        $error = "Sinulla ei ole oikeuksia muokata keskustelualuetta.";
        return false;
    }
    kysely($yhteys, "UPDATE keskustelut SET otsikko='" . $otsikko . "' WHERE id='" . $keskustelu . "'");
    siirry("foorumi/keskustelualue.php?keskustelualue=" . $keskustelualue);
}

function poistaKeskustelu($yhteys) {
    global $error;
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $keskustelu = mysql_real_escape_string($_POST['keskustelu']);
    $sivu = $_GET['sivu'];
    if (empty($sivu))
        $sivu = 0;
    if (!tarkistaKeskustelunOlemassaOlo($yhteys, $keskustelu, $keskustelualue)) {
        $error = "Keskustelua ei löytynyt.";
        return false;
    }
    if (!tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
        $error = "Sinulla ei ole oikeuksia muokata keskustelualuetta.";
        return false;
    }
    $kysely = kysely($yhteys, "SELECT count(keskustelualueetID) maara FROM keskustelualuekeskustelut WHERE keskustelutID='" . $keskustelu . "'");
    $tulos = mysql_fetch_array($kysely);
    if ($tulos['maara'] == 1) {
        kysely($yhteys, "DELETE FROM viestit WHERE keskustelutID='" . $keskustelu . "'");
        kysely($yhteys, "DELETE FROM paikallaolo WHERE tapahtumatID IN (SELECT tapahtumatID FROM keskustelut WHERE id='" . $keskustelu . "')");
        kysely($yhteys, "DELETE FROM tapahtumat WHERE id IN (SELECT tapahtumatID id FROM keskustelut WHERE id='" . $keskustelu . "')");
        kysely($yhteys, "DELETE FROM keskustelut WHERE id='" . $keskustelu . "'");
    }
    kysely($yhteys, "DELETE FROM keskustelualuekeskustelut WHERE keskustelualueetID='" . $keskustelualue . "' AND keskustelutID='" . $keskustelu . "'");

    siirry("foorumi/keskustelualue.php?keskustelualue=" . $keskustelualue . "&sivu=" . $sivu);
}

function siirraKeskustelu($yhteys) {
    global $error;
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $keskustelu = mysql_real_escape_string($_POST['keskustelu']);
    $mkeskustelualue = mysql_real_escape_string($_POST['mkeskustelualue']);
    if (!tarkistaKeskustelunOlemassaOlo($yhteys, $keskustelu, $keskustelualue)) {
        $error = "Keskustelua ei löytynyt.";
        return false;
    }
    if (!tarkistaKeskustelualueetOlemassaOlo($yhteys, $keskustelualue)) {
        $error = "Keskustelualuetta ei löytynyt.";
        return false;
    }
    if (!tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue) || !tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $mkeskustelualue)) {
        $error = "Sinulla ei ole oikeuksia siirtää keskustelualuetta.";
        return false;
    }
    kysely($yhteys, "UPDATE keskustelualuekeskustelut SET keskustelualueetID='" . $mkeskustelualue . "' WHERE keskustelualueetID='" . $keskustelualue . "' AND keskustelutID='" . $keskustelu . "'");
    siirry("foorumi/keskustelualue.php?keskustelualue=" . $mkeskustelualue);
}

?>

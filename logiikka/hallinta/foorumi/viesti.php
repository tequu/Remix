<?php

function uusiViesti($yhteys) {
    tarkistaKirjautuneenTunnus($yhteys);
    global $error;
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $keskustelu = mysql_real_escape_string($_POST['keskustelu']);
    $otsikko = mysql_real_escape_string(htmlspecialchars(trim($_POST['otsikko'])));
    $viesti = mysql_real_escape_string(htmlspecialchars(trim($_POST['viesti'])));
    $kayttaja = $_SESSION['id'];
    $aikaleima = time();
    $aika = date("Y-m-d H:i", $aikaleima);

    $kysely = kysely($yhteys, "SELECT keskustelualueetID FROM keskustelualuekeskustelut WHERE keskustelutID='" . $keskustelu . "' AND keskustelualueetID='" . $keskustelualue . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        if (!tarkistaNakyvyysOikeudetKeskustelualueelle($yhteys, $tulos['keskustelualueetID'])) {
            $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia kirjoittaa keskustelualueelle.";
            siirry("eioikeuksia.php");
        }
        if (empty($viesti)) {
            $error = "Viesti kenttä oli tyhjä<br />";
            return;
        }
        //Tavara tietokantaan
        kysely($yhteys, "INSERT INTO viestit(" . (empty($otsikko) ? "" : "otsikko, ") . "viesti, lahetysaika, keskustelutID, tunnuksetID) VALUES (" . (empty($otsikko) ? "" : "'" . $otsikko . "', ") . "'" . $viesti . "', '" . $aika . "', '" . $keskustelu . "', '" . $kayttaja . "')");
        $kysely = kysely($yhteys, "SELECT count(id) maara FROM viestit WHERE keskustelutID='" . $keskustelu . "'");
        $tulos = mysql_fetch_array($kysely);
        siirry("foorumi/keskustelu.php?keskustelualue=" . $keskustelualue . "&keskustelu=" . $keskustelu . "&sivu=" . (ceil($tulos['maara'] / 10)-1));
    }
    siirry("foorumi/index.php");
}

function muokkaaViestia($yhteys) {
    global $error;
    $kayttaja = $_SESSION['id'];
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $keskustelu = mysql_real_escape_string($_POST['keskustelu']);
    $viestiid = mysql_real_escape_string($_POST['viesti']);
    $kysely = kysely($yhteys, "SELECT tunnuksetID FROM viestit v, keskustelut k, keskustelualuekeskustelut kk " .
            "WHERE v.keskustelutID=k.id AND k.id=kk.keskustelutID AND v.id='" . $viestiid . "' AND k.id='" . $keskustelu . "' AND kk.keskustelualueetID='" . $keskustelualue . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        if ($kayttaja == $tulos['tunnuksetID'] || tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
            $otsikko = mysql_real_escape_string(htmlspecialchars(trim($_POST['otsikko'])));
            $viesti = mysql_real_escape_string(htmlspecialchars(trim($_POST['teksti'])));
            //Tarkistetaan että viesti kenttä ei ollut tyhjä
            if (empty($viesti)) {
                $error = "Viesti kenttä oli tyhjä<br />";
                return;
            }
            kysely($yhteys, "UPDATE viestit SET otsikko='" . $otsikko . "', viesti='" . $viesti . "' WHERE id='" . $viestiid . "'");
            siirry("foorumi/keskustelu.php?keskustelualue=" . $keskustelualue . "&keskustelu=" . $keskustelu);
        }
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata keskustelualueen viestiä.";
        siirry("eioikeuksia.php");
    }
    siirry("foorumi/index.php");
}

function poistaViesti($yhteys) {
    $kayttaja = $_SESSION['id'];
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $keskustelu = mysql_real_escape_string($_POST['keskustelu']);
    $viesti = mysql_real_escape_string(trim($_GET['viesti']));
    $sivu = mysql_real_escape_string(trim($_GET['sivu']));
    if (empty($sivu))
        $sivu = 0;
    $kysely = kysely($yhteys, "SELECT tunnuksetID FROM keskustelualuekeskustelut kk, viestit v WHERE kk.keskustelutID=v.keskustelutID AND kk.keskustelualueetID='" . $keskustelualue . "' AND v.keskustelutID='" . $keskustelu . "' AND v.id='".$viesti."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Viestiä ei löytynyt.";
        siirry("virhe.php");
    }
    if ($tulos['tunnuksetID'] == $kayttaja || tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
        kysely($yhteys, "DELETE FROM viestit WHERE id='" . $viesti . "'");
        $kysely = kysely($yhteys, "SELECT maara, tapahtumatID FROM keskustelut k, ".
                "(SELECT count(id) maara, keskustelutID FROM viestit GROUP BY keskustelutID) m WHERE m.keskustelutID=k.id AND k.id='".$keskustelu."'");
        $tulos = mysql_fetch_array($kysely);
        if($tulos['maara'] == 0 && !$tulos['tapahtumatID']){
            kysely($yhteys, "DELETE FROM keskustelut WHERE id='" . $keskustelu . "'");
            kysely($yhteys, "DELETE FROM keskustelualuekeskustelut WHERE keskustelualueetID='" . $keskustelualue . "' AND keskustelutID='" . $keskustelu . "'");
            siirry("foorumi/keskustelualue.php?keskustelualue=" . $keskustelualue);
        }
        siirry("foorumi/keskustelu.php?keskustelualue=" . $keskustelualue . "&keskustelu=" . $keskustelu . "&sivu=" . $sivu);
    }
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa keskustelualueen viestiä.";
    siirry("eioikeuksia.php");
}

?>

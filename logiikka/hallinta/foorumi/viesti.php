<?php

function uusiViesti($yhteys) {
//    tarkistaKirjautuneenTunnus($yhteys);
    global $error;
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $keskustelu = mysql_real_escape_string($_POST['keskustelu']);
    $otsikko = mysql_real_escape_string(htmlspecialchars(trim($_POST['otsikko'])));
    $viesti = mysql_real_escape_string(htmlspecialchars(trim($_POST['viesti'])));
//    $kayttaja = $_SESSION['id'];
    $kayttaja = 1;
    $aikaleima = time();
    $aika = date("Y-m-d H:i", $aikaleima);

    $kysely = kysely($yhteys, "SELECT keskustelualueetID FROM keskustelualuekeskustelut WHERE keskustelutID='" . $keskustelu . "' AND keskustelualueetID='".$keskustelualue."'");
    if ($tulos = mysql_fetch_array($kysely)) {
//        tarkistaOikeudetKeskustelualueelle($yhteys, $tulos['keskustelualueetID']);
        if (empty($viesti)) {
            $error = "Viesti kenttä oli tyhjä<br />";
            return;
        }
        //Tavara tietokantaan
        kysely($yhteys, "INSERT INTO viestit(" . (empty($otsikko) ? "" : "otsikko, ") . "viesti, lahetysaika, keskustelutID, tunnuksetID) VALUES (" . (empty($otsikko) ? "" : "'" . $otsikko . "', ") . "'" . $viesti . "', '" . $aika . "', '" . $keskustelu . "', '" . $kayttaja . "')");
        $kysely = kysely($yhteys, "SELECT count(id) maara FROM viestit WHERE keskustelutID='" . $keskustelu . "'");
        $tulos = mysql_fetch_array($kysely);
        siirry("foorumi/keskustelu.php?keskustelualue=".$keskustelualue."&keskustelu=" . $keskustelu . "&sivu=" . (ceil($tulos['maara'] / 10)));
    }
    siirry("foorumi/index.php");
}

function muokkaaViestia($yhteys) {
    global $error;
//    tarkistaKirjautuneenTunnus($yhteys);
    $kayttaja = $_SESSION['id'];
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $keskustelu = mysql_real_escape_string($_POST['keskustelu']);
    $viestiid = mysql_real_escape_string($_POST['viesti']);
    $kysely = kysely($yhteys, "SELECT tunnuksetID FROM viestit v, keskustelut k, keskustelualuekeskustelut kk " .
            "WHERE v.keskustelutID=k.id AND k.id=kk.keskustelutID AND v.id='" . $viestiid . "' AND k.id='".$keskustelu."' AND kk.keskustelualueetID='".$keskustelualue."'");
    if ($tulos = mysql_fetch_array($kysely)) {
//        if ($kayttaja == $tulos['tunnuksetID'] || tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
            $otsikko = mysql_real_escape_string(htmlspecialchars(trim($_POST['otsikko'])));
            $viesti = mysql_real_escape_string(htmlspecialchars(trim($_POST['teksti'])));
            //Tarkistetaan että viesti kenttä ei ollut tyhjä
            if (empty($viesti)) {
                $error = "Viesti kenttä oli tyhjä<br />";
                return;
            }
            kysely($yhteys, "UPDATE viestit SET otsikko='" . $otsikko . "', viesti='" . $viesti . "' WHERE id='" . $viestiid . "'");
            siirry("foorumi/keskustelu.php?keskustelualue=".$keskustelualue."&keskustelu=".$keskustelu);
//        }
        siirry("foorumi/eioikeuksia.php");
    }
    siirry("foorumi/index.php");
}
?>

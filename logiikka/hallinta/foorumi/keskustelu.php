<?php

function uusiKeskustelu($yhteys) {
//    tarkistaKirjautuneenTunnus($yhteys);
    global $error;
    $otsikko = mysql_real_escape_string(trim($_POST['otsikko']));
    $viesti = mysql_real_escape_string(trim($_POST['viesti']));
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
//    $kayttaja = mysql_real_escape_string($_SESSION['id']);
    $kayttaja = 1;

    if (tarkistaKeskustelualueetOlemassaOlo($yhteys, $keskustelualue)) {
//        tarkistaOikeudetKeskustelualueelle($yhteys, $keskustelualue);
        //viestin lähetys aika

        /* Tarkista käyttäjän oikeudet kirjottaa keskustelualueelle */
//        tarkistaNOikeudet($yhteys, $joukkue, $keskustelualue);
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
    kysely($yhteys, "INSERT INTO keskustelut (otsikko, aloitusaika, aloittaja, nakyypelaajille" . ($tapahtuma == 0 ? "" : ", tapahtumatID" ). ") VALUES ('" . $otsikko . "', '" . $aika . "', '" . $kayttaja . "', '1'" .($tapahtuma == 0 ? "" : ",'".$tapahtuma."'"). ")");
    $keskustelu = mysql_insert_id();
    kysely($yhteys, "INSERT INTO keskustelualuekeskustelut (keskustelutID, keskustelualueetID) VALUES ('" . $keskustelu . "', '" . $keskustelualue . "')");
    if ($tapahtuma == 0) {
        kysely($yhteys, "INSERT INTO viestit (otsikko, viesti, lahetysaika, keskustelutID, tunnuksetID) VALUES ('" . $otsikko . "', '" . $viesti . "', '" . $aika . "', '" . $keskustelu . "', '" . $kayttaja . "')");
    }
    return $keskustelu;
}

?>

<?php

//lisää johtokunnan jäseniä
function lisaaJohtokuntaanJasen($yhteys) {
    global $ojohtokunta;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lisätä johtokuntaan jäseniä.";
        siirry("eioikeuksia.php");
    }
    //haetaan tiedot
    $etunimi = mysql_real_escape_string(trim($_POST['etunimi']));
    $sukunimi = mysql_real_escape_string(trim($_POST['sukunimi']));
    if ($_POST['arvovalinta'] == 'luettelo')
        $arvo = mysql_real_escape_string($_POST['arvonimil']);
    elseif ($_POST['arvovalinta'] == 'muu')
        $arvo = mysql_real_escape_string($_POST['arvonimit']);
    else
        $arvo = 'ei';
    $sahkoposti = mysql_real_escape_string($_POST['sahkoposti']);
    $puhelin = mysql_real_escape_string($_POST['puhelin']);
    $jarjestysnumero = 1;
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM johtokunta ORDER BY jarjestysnumero DESC LIMIT 0,1");
    if ($tulos = mysql_fetch_array($kysely))
        $jarjestysnumero = $tulos['jarjestysnumero'] + 1;
    kysely($yhteys, "INSERT INTO johtokunta (etunimi, sukunimi, arvo, sahkoposti, puhelinnumero, jarjestysnumero) " .
            "VALUES ('" . $etunimi . "', '" . $sukunimi . "', '" . $arvo . "', '" . $sahkoposti . "', '" . $puhelin . "', '" . $jarjestysnumero . "')");
    ohjaaOhajuspaneeliin($ojohtokunta, "");
}

//muokkaa johtokuntaa
function muokkaaJohtokunnanJasenta($yhteys) {
    global $ojohtokunta;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata johtokunnan jäseniä.";
        siirry("eioikeuksia.php");
    }
    $jasenetid = mysql_real_escape_string($_GET['jasenetid']);
    $etunimi = mysql_real_escape_string(trim($_POST['etunimi']));
    $sukunimi = mysql_real_escape_string(trim($_POST['sukunimi']));
    $sahkoposti = mysql_real_escape_string($_POST['sahkoposti']);
    $puhelin = mysql_real_escape_string($_POST['puhelin']);
    if ($_POST['arvovalinta'] == 'luettelo')
        $arvo = mysql_real_escape_string($_POST['arvonimil']);
    elseif ($_POST['arvovalinta'] == 'muu')
        $arvo = mysql_real_escape_string($_POST['arvonimit']);
    else
        $arvo = 'ei';
    kysely($yhteys, "UPDATE johtokunta SET etunimi='" . $etunimi . "', sukunimi='" . $sukunimi . "', arvo='" . $arvo . "', " .
            "sahkoposti='" . $sahkoposti . "', puhelinnumero='" . $puhelin . "' WHERE id='" . $jasenetid . "'");
    ohjaaOhajuspaneeliin($ojohtokunta, "&mode=muokkaa&jasenetid=" . $jasenetid);
}

//poista jäseniä johtokunnasta
function poistaJasenJohtokunnasta($yhteys) {
    global $ojohtokunta;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksa poistaa johtokunnasta jäseniä.";
        siirry("eioikeuksia.php");
    }
    $jasenetid = mysql_real_escape_string($_GET['jasenetid']);
    kysely($yhteys, "DELETE FROM johtokunta WHERE id='" . $jasenetid . "'");
    ohjaaOhajuspaneeliin($ojohtokunta, "");
}

//Vaihda johtokunnan jäsenten järjestystä
function siirraJasentaJohtokunnassa($yhteys) {
    global $error, $siirry, $ojohtokunta;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata johtokuntaa.";
        if ($siirry)
            siirry("eioikeuksia.php");
        $error = $_SESSION['eioikeuksia'];
        return false;
    }
    $siirrettavaid = mysql_real_escape_string($_POST['siirrettavaid']);
    $suunta = mysql_real_escape_string($_POST['suunta']);
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM johtokunta ORDER BY jarjestysnumero DESC LIMIT 0,1");
    $tulos = mysql_fetch_array($kysely);
    $suurin = $tulos['jarjestysnumero'];
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM sarjataulukot WHERE id='" . $siirrettavaid . "'");
    $tulos = mysql_fetch_array($kysely);
    $i = $tulos['jarjestysnumero'];
    if (($suunta == "ylos" && $i <= 1) || ($suunta == "alas" && $i == $suurin)) {
        $error = "Ei voida siirtää!<br />";
        return false;
    }
    if ($suunta == "ylos") {
        kysely($yhteys, "UPDATE johtokunta SET jarjestysnumero=jarjestysnumero+1 WHERE jarjestysnumero='" . ($i - 1) . "'");
        kysely($yhteys, "UPDATE johtokunta SET jarjestysnumero=jarjestysnumero-1 WHERE id='" . $siirrettavaid . "'");
    } else {
        kysely($yhteys, "UPDATE johtokunta SET jarjestysnumero=jarjestysnumero-1 WHERE jarjestysnumero='" . ($i + 1) . "'");
        kysely($yhteys, "UPDATE johtokunta SET jarjestysnumero=jarjestysnumero+1 WHERE id='" . $siirrettavaid . "'");
    }
    if ($siirry)
        ohjaaOhajuspaneeliin($ojohtokunta, "");
    return true;
}

//lisää pöytäkirja
function lisaaPoytakirja($yhteys) {
    global $error, $ojohtokunta;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lisätä pöytäkirjoja.";
        siirry("eioikeuksia.php");
    }
    $uppikansio = "/home/fbcremix/public_html/Remix/poytakirjat";
    $hyvaksytyt = array("application/vnd.oasis.opendocument.text", "application/pdf");
    if (!in_array($_FILES['poytakirja']['type'], $hyvaksytyt)) {
        $error = "Pöytäkirjan tiedostomuoto on väärä.";
        return false;
    }
    if ($_FILES['poytakirja']['size'] > 1000000) {
        $error = "Pöytäkirjan koko on liian suuri. Maksimi koko on 1Mt<br />";
        return false;
    }
    if (move_uploaded_file($_FILES['poytakirja']['tmp_name'], $uppikansio . "/" . basename($_FILES['poytakirja']['name'])))
        ohjaaOhajuspaneeliin ($ojohtokunta, "");
    else {
        $error = "Pöytäkirjan lähetys ei onnistu<br />";
        return false;
    }
}

?>

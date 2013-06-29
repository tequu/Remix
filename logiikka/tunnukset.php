<?php

function lahetaRekisterointiPosti($token, $sahkoposti) {
    $otsikko = "Rekister�innin vahvistus";
    $viesti = "Kiitos rekister�itymisest� FBC Remixin foorumeille. \n\nOle hyv� ja varmista rekister�itymisesi klikkaamalla seuraavaa linkki�.\n\n" .
            "fbcremix.com/aktivoi.php?email=" . $sahkoposti . "&token=" . $token . "\n\n" .
            "Jos linkki ei aukea klikkaamalla, voit kopioida sen selaimen osoite kentt��n.\n\n" .
            "T�m� on automaattinen viesti. �l� vastaa t�h�n.";
    mail($sahkoposti, $otsikko, $viesti);
}

function tulostatunnukset($etunimi = null, $sukunimi = null, $sahkoposti = null, $parametri = null) {
    //haetaan kaikki tunnukset
    $kysely = mysql_query("SELECT id, login FROM tunnukset ORDER BY login") or die("tulosta tunnukset1: " . mysql_error());
    //tulostetaan lomake
    echo "<form action='" . $_SERVER['PHP_SELF'];
    $tulostettu = false;
    if (isset($_GET) && !empty($_GET)) {
        echo"?";
        foreach ($_GET as $avain => $arvo) {
            echo $avain . "=" . $arvo;
            $tulostettu = true;
        }
    }
    if (!empty($parametri)) {
        if ($tulostettu)
            echo"&";
        else
            echo"?";
        echo $parametri;
    }
    echo"' method='post'>
    Valitse tunnus:<br />
    <select name='tunnusid'>";
    while ($tulos = mysql_fetch_array($kysely))
        echo"<option value=" . $tulos['id'] . ">" . $tulos['login'] . "</option>";
    echo"</select>
    <br />
    <br />
    <input type='submit' value='Hae tiedot' />
    </form>";
}

//vaihda salasana


function muokkaaTunnuksenTietoja($yhteys, $tunnuksetid) {
    global $error;
    $kysely = kysely($yhteys, "SELECT etunimi, sukunimi, syntymavuosi, email FROM tunnukset WHERE id='" . $tunnuksetid . "' AND enabled='0'");
    if ($tulos = mysql_fetch_array($kysely)) {
        $etunimi = mysql_real_escape_string($_POST['etunimi']);
        $sukunimi = mysql_real_escape_string($_POST['sukunimi']);
        $syntymavuosi = mysql_real_escape_string($_POST['syntymavuosi']);
        $email = mysql_real_escape_string($_POST['email']);
        if (tarkistaPerusTiedot($etunimi, $sukunimi, $syntymavuosi)) {
            $sql = "UPDATE tunnukset SET ";
            $muokattu = false;
            if ($etunimi != $tulos['etunimi']) {
                $sql .= "etunimi='" . $etunimi . "', ";
                $muokattu = true;
            }
            if ($sukunimi != $tulos['sukunimi']) {
                $sql .= "sukunimi='" . $sukunimi . "', ";
                $muokattu = true;
            }
            if ($syntymavuosi != $tulos['syntymavuosi']) {
                $sql .= "syntymavuosi='" . $syntymavuosi . "', ";
                $muokattu = true;
            }
            if ($email != $tulos['email']) {
                $kysely = kysely($yhteys, "SELECT id FROM tunnukset WHERE email='" . $email . "'");
                if ($tulos = mysql_fetch_array($kysely)) {
                    $error .= "T�ll� s�hk�postilla on jo toinen tunnus.";
                } else {
                    $sql .= "email='" . $email . "', ";
                    $muokattu = true;
                    if (!empty($email)) {

                        $kysely = kysely($yhteys, "SELECT token FROM tokenit WHERE tunnuksetID='" . $tunnuksetid . "'");
                        if ($tulos = mysql_fetch_array($kysely)) {
                            $token = $tulos['token'];
                        } else {
                            $token = md5(microtime());
                            kysely($yhteys, "INSERT INTO tokenit (token, tunnuksetID) VALUES ('" . $token . "', '" . $tunnuksetid . "')");
                        }
                        $otsikko = "Sinulle on luotu tunnus FBC Remixin sivustolle";
                        $viesti = "FBC Remixin joukkue " . haeJoukkueenNimi($yhteys, $joukkue) . " on lis�nnyt sinut joukkueeseensa ja luonut samalla sinulle tunnuksen FBC Remixin sivustolle.\n" .
                                "Ole hyv� ja mene www.fbcremix.com/Remix/aktivoi.php?email=" . $email . "&token=" . $token . "&id=" . $tunnuksetid . " varmistamaan s�hk�posti ja ottamaan se k�ytt��n." .
                                "Sivulla sinua pyydet��n sy�tt�m��n k�ytt�j�tunnuksesi ja salasanasi.\nJos yll� annettu linkki ei toimi klikkaamalla, voit kopioida sen selaimesi osoite kentt��n.\n\n" .
                                "Huom. T�m� on automaattisesti l�hetetty viesti. �l� vastaa t�h�n.";
                        mail($email, $otsikko, $viesti);
                    }
                }
            }
            if ($muokattu) {
                $sql = substr($sql, 0, -2);
                $sql .= " WHERE id='" . $tunnuksetid . "'";
                kysely($yhteys, $sql);
            }
        }
    }
}

function tarkistaLoginJaEmail($yhteys, $login, $email, $mihin, $tunnusid) {
    global $error;
    $login = mysql_real_escape_string(trim($login));
    $email = mysql_real_escape_string(trim($email));
    if (strlen($login) < 4) {
        $error[$mihin]['login'] = "Tunnus on liian lyhyt, v�himm�is pituus on 4 merkki�";
    } elseif (strlen($login) > 20) {
        $error[$mihin]['login'] = "Tunnus on liian pitk�, enemm�is pituus on 20 merkki�";
    } elseif (tarkistaEiSallitutMerkit($login)) {
        $error[$mihin]['login'] = "Tunnus ei saa sis�lt�� �, �, �, �, �, � merkkej�";
    }
    if (empty($email)) {
        $error[$mihin]['email'] = "S�hk�posti ei saa olla tyhj�";
        return;
    }

    if (count($error[$mihin]) > 0) {
        return;
    }
    $kysely = kysely($yhteys, "SELECT id, login, email FROM tunnukset WHERE login='" . $login . "' OR email='" . $email . "'");
    while ($tulos = mysql_fetch_array($kysely)) {
        if ($login == $tulos['login'] && $tunnusid != $tulos['id']) {
            $error[$mihin]['login'] = "*Nimimerkki on jo k�yt�ss�<br />";
        }
        if ($email == $tulos['email'] && $tunnusid != $tulos['id']) {
            $error[$mihin]['email'] = "*S�hk�postiosoite on jo k�yt�ss�<br />";
        }
    }
}

function lahetaUusiPelaajaViesti($yhteys, $token, $email, $tunnusid, $joukkue) {
    $otsikko = "Sinulle on luotu tunnus FBC Remixin sivustolle";
    $viesti = "FBC Remixin joukkue " . haeJoukkueenNimi($yhteys, $joukkue) . " on lis�nnyt sinut joukkueeseensa ja luonut samalla sinulle tunnuksen FBC Remixin sivustolle.\n" .
            "Ole hyv� ja vahvista s�hk�postisi osoitteessa www.fbcremix.com/Remix/aktivoi.php?email=" . $email . "&token=" . $token . "&id=" . $tunnusid . " otta se k�ytt��n." .
            "Sivulla sinua pyydet��n sy�tt�m��n k�ytt�j�tunnuksesi ja salasanasi.\nJos yll� annettu linkki ei toimi klikkaamalla, voit kopioida sen selaimesi osoite kentt��n.\n\n" .
            "Huom. T�m� on automaattisesti l�hetetty viesti. �l� vastaa t�h�n.";
    mail($email, $otsikko, $viesti);
}

function lahetaUusiVahvistus($yhteys) {
    tarkistaOikeudet($yhteys, "admin", "MasterAdmin", "Sinulla ei ole oikeuksa l�hett�� vahvistus viestej�");
    $tunnusid = mysql_real_escape_string($_POST['tunnusid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID, login, email, enabled, token FROM tokenit tk, tunnukset t LEFT OUTER JOIN pelaajat p ON t.id=p.tunnuksetID ".
            "WHERE tk.tunnuksetID=t.id AND t.id='".$tunnusid."'");
    if($tulos = mysql_fetch_array($kysely)){
        if(!$tulos['enabled'] && !empty($tulos['email'])){
            if(empty($tulos['login']) && $tulos['token']){
                lahetaUusiPelaajaViesti($yhteys, $tulos['token'], $tulos['email'], $tunnusid, $tulos['joukkueetID']);
            } elseif($tulos['token']) {
                lahetaRekisterointiPosti($tulos['token'], $tulos['email']);
            }
        }
    }
}

?>

<?php

function lahetaRekisterointiPosti($token, $sahkoposti) {
    $otsikko = "Rekisteröinnin vahvistus";
    $viesti = "Kiitos rekisteröitymisestä FBC Remixin foorumeille. \n\nOle hyvä ja varmista rekisteröitymisesi klikkaamalla seuraavaa linkkiä.\n\n" .
            "fbcremix.com/aktivoi.php?email=" . $sahkoposti . "&token=" . $token . "\n\n" .
            "Jos linkki ei aukea klikkaamalla, voit kopioida sen selaimen osoite kenttään.\n\n" .
            "Tämä on automaattinen viesti. Älä vastaa tähän.";
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
                    $error .= "Tällä sähköpostilla on jo toinen tunnus.";
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
                        $viesti = "FBC Remixin joukkue " . haeJoukkueenNimi($yhteys, $joukkue) . " on lisännyt sinut joukkueeseensa ja luonut samalla sinulle tunnuksen FBC Remixin sivustolle.\n" .
                                "Ole hyvä ja mene www.fbcremix.com/Remix/aktivoi.php?email=" . $email . "&token=" . $token . "&id=" . $tunnuksetid . " varmistamaan sähköposti ja ottamaan se käyttöön." .
                                "Sivulla sinua pyydetään syöttämään käyttäjätunnuksesi ja salasanasi.\nJos yllä annettu linkki ei toimi klikkaamalla, voit kopioida sen selaimesi osoite kenttään.\n\n" .
                                "Huom. Tämä on automaattisesti lähetetty viesti. Älä vastaa tähän.";
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
        $error[$mihin]['login'] = "Tunnus on liian lyhyt, vähimmäis pituus on 4 merkkiä";
    } elseif (strlen($login) > 20) {
        $error[$mihin]['login'] = "Tunnus on liian pitkä, enemmäis pituus on 20 merkkiä";
    } elseif (tarkistaEiSallitutMerkit($login)) {
        $error[$mihin]['login'] = "Tunnus ei saa sisältää Å, Ä, Ö, å, ä, ö merkkejä";
    }
    if (empty($email)) {
        $error[$mihin]['email'] = "Sähköposti ei saa olla tyhjä";
        return;
    }

    if (count($error[$mihin]) > 0) {
        return;
    }
    $kysely = kysely($yhteys, "SELECT id, login, email FROM tunnukset WHERE login='" . $login . "' OR email='" . $email . "'");
    while ($tulos = mysql_fetch_array($kysely)) {
        if ($login == $tulos['login'] && $tunnusid != $tulos['id']) {
            $error[$mihin]['login'] = "*Nimimerkki on jo käytössä<br />";
        }
        if ($email == $tulos['email'] && $tunnusid != $tulos['id']) {
            $error[$mihin]['email'] = "*Sähköpostiosoite on jo käytössä<br />";
        }
    }
}

function lahetaUusiPelaajaViesti($yhteys, $token, $email, $tunnusid, $joukkue) {
    $otsikko = "Sinulle on luotu tunnus FBC Remixin sivustolle";
    $viesti = "FBC Remixin joukkue " . haeJoukkueenNimi($yhteys, $joukkue) . " on lisännyt sinut joukkueeseensa ja luonut samalla sinulle tunnuksen FBC Remixin sivustolle.\n" .
            "Ole hyvä ja vahvista sähköpostisi osoitteessa www.fbcremix.com/Remix/aktivoi.php?email=" . $email . "&token=" . $token . "&id=" . $tunnusid . " otta se käyttöön." .
            "Sivulla sinua pyydetään syöttämään käyttäjätunnuksesi ja salasanasi.\nJos yllä annettu linkki ei toimi klikkaamalla, voit kopioida sen selaimesi osoite kenttään.\n\n" .
            "Huom. Tämä on automaattisesti lähetetty viesti. Älä vastaa tähän.";
    mail($email, $otsikko, $viesti);
}

function lahetaUusiVahvistus($yhteys) {
    tarkistaOikeudet($yhteys, "admin", "MasterAdmin", "Sinulla ei ole oikeuksa lähettää vahvistus viestejä");
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

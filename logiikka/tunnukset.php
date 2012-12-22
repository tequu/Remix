<?php

//Rekisteroi
function rekisteroi($yhteys) {
    global $error;
//L�hetetyt tiedot talteen
    $aikaleima = time();
    $login = mysql_real_escape_string(trim($_POST['login']));
    $salasana = mysql_real_escape_string(trim($_POST['salasana']));
    $vahvistasalasana = mysql_real_escape_string(trim($_POST['vahvistasalasana']));
    $sahkoposti = mysql_real_escape_string(trim($_POST['sahkoposti']));
    $vahvistasahkoposti = mysql_real_escape_string(trim($_POST['vahvistasahkoposti']));
    $nakyysahkoposti = mysql_real_escape_string($_POST['nakyysahkoposti']);
    $etunimi = mysql_real_escape_string(trim($_POST['etunimi']));
    $nakyyetunimi = mysql_real_escape_string($_POST['nakyyetunimi']);
    $sukunimi = mysql_real_escape_string(trim($_POST['sukunimi']));
    $nakyysukunimi = mysql_real_escape_string($_POST['nakyysukunimi']);
    $syntymavuosi = mysql_real_escape_string($_POST['syntymavuosi']);
    $nakyysyntymavuosi = mysql_real_escape_string($_POST['nakyysyntymavuosi']);
    $ip = $_SERVER['REMOTE_ADDR'];
    $aika = date("Y-m-d H:i", $aikaleima);
//Tarkistetaan ett� kaikissa kentiss� on jotain ja oikeat arvot
    if (empty($login))
        $error .= "*Nimimerkki kentt� on tyhj�<br />";
    if (empty($salasana))
        $error .= "*Salasana kentt� on tyhj�<br />";
    if (empty($vahvistasalasana))
        $error .= "*Varmista salasana kentt� on tyhj�<br />";
    if (empty($sahkoposti))
        $error .= "*S�hk�posti kentt� on tyhj�<br />";
    if (empty($vahvistasahkoposti))
        $error .= "*Varmista s�hk�posti kentt� on tyhj�<br />";
    if (!tarkistaPerusTiedot($etunimi, $sukunimi, $syntymavuosi))
        return;
//tarkistetaan salasanan pituus
    if (strlen($salasana) < 6)
        $error = "*Salasana on liian lyhyt, v�himm�is pituus on 6 merkki�<br />";
    if (strlen($salasana) > 20)
        $error = "*Salasana on liian pitk�, enimm�is pituus on 20 merkki�<br />";

//tarkistetaan nimimerkin pituus
    if (strlen($login) < 4)
        $error = "*Nimimerkki on liian lyhyt, v�himm�is pituus on 4 merkki�<br />";
    if (strlen($login) > 20)
        $error = "*Nimimerkki on liian pitk�, enemm�is pituus on 20 merkki�<br />";
//tarkistetaan l�ytyyk� salasanasta tai nimimerkist� v��ri�  merkkej�
    if (tarkistaEiSallitutMerkit($login) || tarkistaEiSallitutMerkit($salasana)) {
        $error = "*Nimimerkki ja salasana ei saa sis�lt�� �, �, �, �, �, � merkkej�<br />";
    }
    if (!empty($error)) {
        return;
    }
    $kysely = kysely($yhteys, "SELECT login, email FROM tunnukset WHERE login='" . $login . "' OR email='" . $sahkoposti . "'");
//tarkistetaan onko nimimerkki jo k�yt�ss�
    while ($tulos = mysql_fetch_array($kysely)) {
        if ($login == $tulos['login']) {
            $error = "*Nimimerkki on jo k�yt�ss�<br />";
        }
        if ($sahkoposti == $tulos['email']) {
            $error = "*S�hk�postiosoite on jo k�yt�ss�<br />";
        }
    }
    if (!empty($error))
        return;

//tarkistetaan t�sm��k� salasanat ja s�hk�posti osoitteet
    if ($salasana != $vahvistasalasana) {
        $error = "*Salasanat eiv�t ole samat<br />";
        return;
    }
//tarkistetaan t�sm��k� s�k�postiosoitteet
    if ($sahkoposti != $vahvistasahkoposti) {
        $error = "*S�hk�postiosoitteet eiv�t ole samat<br />";
        return;
    }
//cryptataan salasana
    $salasana = crypt($salasana);
//luodaan tokeni
    $token = md5(microtime());
//lis�t��n n�kyv�t tiedot tietokantaan
    kysely($yhteys, "INSERT INTO nakyvattiedot (email, etunimi, sukunimi, syntymavuosi) VALUES ('" . $nakyysahkoposti . "', '" . $nakyyetunimi . "', '" . $nakyysukunimi . "', '" . $nakyysyntymavuosi . "')");
//nakyvien tietojen id
    $nakyvattiedotid = mysql_insert_id();
//lis�t��n tiedot tietokantaan
    kysely($yhteys, "INSERT INTO tunnukset (login, salasana, email, etunimi, sukunimi, syntymavuosi, isadmin, enabled, estetty, nakyvattiedotID) VALUES ('" . $login . "', '" . $salasana . "', '" . $sahkoposti . "', '" . $etunimi . "', '" . $sukunimi . "', '" . $syntymavuosi . "', 'Perus', '0', '0', '" . $nakyvattiedotid . "')");
//otetaan talteen tunnusid
    $kayttajaid = mysql_insert_id();
//asetetaan tokeni tietokantaan
    kysely($yhteys, "INSERT INTO tokenit (token, tunnuksetID) VALUES ('" . $token . "', '" . $kayttajaid . "')");
//l�hetet��n s�hk�posti
    lahetaRekisterointiPosti($token, $sahkoposti);
    siirry("index.php");
}

function lahetaRekisterointiPosti($token, $sahkoposti) {
//luodaan otsikko
    $otsikko = "Rekister�innin vahvistus";
//luodaan viesti
    $viesti = "Kiitos rekister�itymisest� FBC Remixin foorumeille. \n\nOle hyv� ja varmista rekister�itymisesi klikkaamalla seuraavaa linkki�.\n\n" .
            "fbcremix.com/aktivoi.php?email=" . $sahkoposti . "&token=" . $token . "\n\n" .
            "Jos linkki ei aukea klikkaamalla, voit kopioida sen selaimen osoite kentt��n.\n\n" .
            "T�m� on automaattinen viesti. �l� vastaa t�h�n.";
    mail($sahkoposti, $otsikko, $viesti);
}

//Kirjaudu
function kirjaudu($yhteys) {
    global $error;
    if (isset($_SESSION['id'])) {
        $error = "Olet jo kirjautunut sis��n.<br />";
        return;
    }
//tarkistetaan ett� saatiin login ja salasana
    if (isset($_POST['login']) && isset($_POST['salasana'])) {
//login ja salasana talteen
        $login = mysql_real_escape_string(trim($_POST['login']));
        $salasana = mysql_real_escape_string(trim($_POST['salasana']));
//tarkistetaan ettei ne ollu tyhji�
        if (empty($login) || empty($salasana)) {
            $error = "K�ytt�j�tunnus tai salasana kentt� on tyhj�.";
            return;
        }
//haetaan tunnuksen tietoja
        $kysely = kysely($yhteys, "SELECT id, login, salasana, isadmin, enabled, estetty FROM tunnukset WHERE login='" . $login . "'");
//tarkistetaan ett� l�ytyi tunnus ja salasana t�sm�� tunnukseen
        if ($tulos = mysql_fetch_array($kysely)) {
            if (crypt($salasana, $tulos['salasana']) == $tulos['salasana']) {
//tarkistetaan ettei tunnusta ole estetty
                if (!$tulos['estetty']) {
//ja ett� tunnus on aktivoitu
                    if ($tulos['enabled']) {
//id, nimimerkki ja oikeudet talteen
                        $_SESSION['id'] = $tulos['id'];
                        $_SESSION['login'] = $tulos['login'];
                        $_SESSION['oikeudet'] = $tulos['isadmin'];
//kirjautumis aika
                        $aikaleima = time();
                        $kirjautumis_aika = date("Y-m-d H:i", $aikaleima);
//tietokantaan t�m�n kertaisen kirjautumisen tiedot
                        kysely($yhteys, "INSERT INTO kirjautumistiedot (ip, aika, tunnuksetID) VALUES ('" . $_SERVER['REMOTE_ADDR'] . "', '" . $kirjautumis_aika . "', '" . $tulos['id'] . "')");
                    } else {
                        $error = "Tunnusta ei ole aktivoitu";
                    }
                } else {
                    $error = "Tunnus on estetty";
                }
            } else {
                $error = "K�ytt�j�tunnus tai salasana on v��r�";
            }
        } else {
            $error = "K�ytt�j�tunnus tai salasana on v��r�";
        }
    } else {
        $error = "Virhe kirjautuessa";
    }
//jos jokun oli v��rin, hyp�t��n funktiosta pihalle
    if (!empty($error))
        return;
    if (isset($_SESSION['kirjaudu'])) {
        siirry($_SESSION['kirjaudu']);
    }
//ohjataan k�ytt�j� etusivulle onnistuttaessa
    siirry("index.php");
}

//Est�� tai poistaa eston k�ytt�j�lt�
function vaihdaEsto($yhteys) {
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        siirry("eioikeuksia.php");
    }
    $kayttaja = mysql_real_escape_string($_GET['kayttaja']);
    $kysely = kysely($yhteys, "SELECT estetty FROM Tunnukset WHERE id='" . $kayttaja . "'");
    //Muutetaan esto jos l�ytyi k�ytt�j�
    if ($tulos = mysql_fetch_array($kysely)) {
        kysely($yhteys, "UPDATE Tunnukset SET estetty='" . !$tulos['estetty'] . "' WHERE id='" . $kayttaja . "'");
    } else {
        $error = "Tunnusta ei l�ytynyt<br />";
        return;
    }
    ohjaaOhajuspaneeliin(2, "&kayttaja=" . $kayttaja);
}

//Muokkaa k�ytt�j�n omat tiedot
function muokkaaOmatTiedot($yhteys) {
    global $error, $oomattiedot;
    tarkistaKirjautuneenTunnus($yhteys);
    $tunnusid = mysql_real_escape_string($_SESSION['id']);
    $etunimi = mysql_real_escape_string($_POST['etunimi']);
    $sukunimi = mysql_real_escape_string($_POST['sukunimi']);
    $syntymavuosi = mysql_real_escape_string($_POST['syntymavuosi']);
    $netunimi = mysql_real_escape_string($_POST['nakyyetunimi']);
    $nsukunimi = mysql_real_escape_string($_POST['nakyysukunimi']);
    $nsyntymavuosi = mysql_real_escape_string($_POST['nakyysyntymavuosi']);
    $nemail = mysql_real_escape_string($_POST['nakyysahkoposti']);
    $vanhasalasana = mysql_real_escape_string($_POST['salasana']);
    $uusisalasana = mysql_real_escape_string($_POST['uusisalasana']);
    $vahvistasalasana = mysql_real_escape_string($_POST['vahvistauusisalasana']);
    //Tarkistetaan perustiedot
    if (!tarkistaPerusTiedot($etunimi, $sukunimi, $syntymavuosi)) {
        return;
    }
    //P�ivitet��n tiedot
    $sql = "UPDATE tunnukset SET etunimi='" . $etunimi . "', sukunimi='" . $sukunimi . "', syntymavuosi='" . $syntymavuosi . "'";
    if (!empty($uusisalasana)) {
        if ($uusisalasana == $vahvistasalasana) {
            //P�ivitet��n salasana jos se vanha oli oikein ja uudet vastaavat toisiaan
            $kysely = kysely($yhteys, "SELECT salasana FROM tunnukset WHERE id='" . $tunnusid . "'");
            $tulos = mysql_fetch_array($kysely);
            if (crypt($vanhasalasana, $tulos['salasana']) == $tulos['salasana']) {
                $uusisalasana = crypt($uusisalasana);
                $sql .= ", salasana='" . $uusisalasana . "'";
            } else {
                $error .= "Salasanan muokkaus ep�onnistui. V��r� salasana<br />";
            }
        } else {
            $error .= "Salasanan muokkaus ep�innostui. Salasanat eiv�t t�sm��<br />";
        }
    }
    $sql .= " WHERE id='" . $tunnusid . "'";
    kysely($yhteys, "UPDATE nakyvattiedot SET email='" . $nemail . "', etunimi='" . $netunimi . "', sukunimi='" . $nsukunimi . "', syntymavuosi='" . $nsyntymavuosi . "' WHERE id IN (SELECT nakyvattiedotID id FROM tunnukset WHERE id='" . $tunnusid . "')");
    kysely($yhteys, $sql);
    if (!empty($error))
        return;
    ohjaaOhajuspaneeliin($oomattiedot, "");
}

//Luo tunnuksen perus tiedoilla
function luoTunnus($yhteys) {
    global $error;
    $etunimi = mysql_real_escape_string(trim($_POST['etunimi']));
    $sukunimi = mysql_real_escape_string(trim($_POST['sukunimi']));
    $syntymavuosi = mysql_real_escape_string($_POST['syntymavuosi']);
    $email = mysql_real_escape_string(trim($_POST['email']));
    //Tarkistetaan ett� perustiedot on oikein
    tarkistaPerusTiedot($etunimi, $sukunimi, $syntymavuosi);
    if (!empty($error)) {
        return false;
    }
    $kysely = kysely($yhteys, "SELECT id FROM tunnukset WHERE LOWER(etunimi)='" . strtolower($etunimi) . "' AND LOWER(sukunimi)='" . strtolower($sukunimi) . "' AND syntymavuosi='" . $syntymavuosi . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        $error = "T�ll� nimell� ja syntym�vuodella on jo tunnus. K�yt� hakua.";
        return false;
    }
    if (!empty($email)) {
        $kysely = kysely($yhteys, "SELECT id FROM tunnukset WHERE LOWER(email)='" . strtolower($email) . "'");
        if ($tulos = mysql_fetch_array($kysely)) {
            $error = "S�hk�postiosoite on jo k�yt�ss�. K�yt� hakua.";
            return false;
        }
    }
    //Luodaan tunnus
    kysely($yhteys, "INSERT INTO tunnukset (" . (!empty($email) ? "email, " : "") . "etunimi, sukunimi, syntymavuosi, isadmin, enabled, estetty) " .
            "VALUES (" . (!empty($email) ? "'" . $email . "', " : "") . "'" . $etunimi . "', '" . $sukunimi . "', '" . $syntymavuosi . "', 'Perus', '0', '0')");
    $tunnusid = mysql_insert_id();
    if (!empty($email)) {
        $token = md5(microtime());
        kysely($yhteys, "INSERT INTO tokenit (token, tunnuksetID) VALUES ('" . $token . "', '" . $tunnusid . "')");
        $otsikko = "Sinulle on luotu tunnus FBC Remixin sivustolle";
        $viesti = "FBC Remixin joukkue " . haeJoukkueenNimi($yhteys, $joukkue) . " on lis�nnyt sinut joukkueeseensa ja luonut samalla sinulle tunnuksen FBC Remixin sivustolle.\n" .
                "Ole hyv� ja mene www.fbcremix.com/Remix/aktivoi.php?email=" . $email . "&token=" . $token . "&id=" . $tunnusid . " varmistamaan s�hk�posti ja ottamaan se k�ytt��n." .
                "Sivulla sinua pyydet��n sy�tt�m��n k�ytt�j�tunnuksesi ja salasanasi.\nJos yll� annettu linkki ei toimi klikkaamalla, voit kopioida sen selaimesi osoite kentt��n.\n\n" .
                "Huom. T�m� on automaattisesti l�hetetty viesti. �l� vastaa t�h�n.";
        mail($email, $otsikko, $viesti);
    }
    return $tunnusid;
}

//tulostaa kaikki tietokannassa olevat tunnukset
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
function vaihdasalasana($yhteys) {
    global $error, $oomattiedot;
    $id = mysql_real_escape_string($_SESSION['id']);
    tarkistaKirjautuneenTunnus($yhteys);
    $uusisalasana = mysql_real_escape_string(trim($_POST['uusisalasana']));
    $vuusisalasana = trim($_POST['vahvistauusisalasana']);
    $vanhasalasana = mysql_real_escape_string(trim($_POST['salasana']));
    if ($uusisalasana != $vuusisalasana) {
        $error = "*Salasanat eiv�t t�sm��";
        return false;
    }
    if (strlen($uusisalasana) < 6) {
        $error = "*Salasanan v�himm�is pituus on 6 merkki�";
        return false;
    }
    if (strlen($uusisalasana) > 20) {
        $error = "*Salasanan enim�is pituus on 20 merkki�";
        return false;
    }
    if (preg_match("/[������]/", $uusisalasana)) {
        $error = "*Salasana ei saa sis�lt�� �, �, � merkkej�";
        return false;
    }
    $kysely = kysely($yhteys, "SELECT salasana, email FROM tunnukset WHERE id='" . $id . "'");
    $tulos = mysql_fetch_array($kysely);
    $email = $tulos['email'];
    if (crypt($vanhasalasana, $tulos['salasana']) == $tulos['salasana']) {
        $uusisalasana = crypt($uusisalasana);
        kysely($yhteys, "UPDATE tunnukset SET salasana='" . $uusisalasana . "' WHERE id='" . $id . "'");
        ohjaaOhajuspaneeliin($oomattiedot, "");
    } else {
        $error = "*Vanha salasana oli v��rin";
        return false;
    }
}

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

function aktivoi($yhteys) {
    global $error;
    $email = mysql_real_escape_string($_GET['email']);
    $token = mysql_real_escape_string($_GET['token']);
    $tunnusid = mysql_real_escape_string($_GET['id']);
    $kysely = kysely($yhteys, "SELECT id FROM tunnukset, tokenit WHERE id=tunnuksetID AND id='" . $tunnusid . "' AND email='" . $email . "' AND token='" . $token . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        $login = mysql_real_escape_string(trim($_POST['login']));
        $salasana = mysql_real_escape_string(trim($_POST['salasana']));
        $vahvistasalasana = mysql_real_escape_string(trim($_POST['vahvistasalasana']));
        $nakyysahkoposti = mysql_real_escape_string($_POST['nakyysahkoposti']);
        $nakyyetunimi = mysql_real_escape_string($_POST['nakyyetunimi']);
        $nakyysukunimi = mysql_real_escape_string($_POST['nakyysukunimi']);
        $nakyysyntymavuosi = mysql_real_escape_string($_POST['nakyysyntymavuosi']);
        if (empty($login))
            $error .= "*Nimimerkki kentt� on tyhj�<br />";
        if (empty($salasana))
            $error .= "*Salasana kentt� on tyhj�<br />";
        if (empty($vahvistasalasana))
            $error .= "*Varmista salasana kentt� on tyhj�<br />";
        if (!empty($error)) {
            return false;
        }
        if (strlen($salasana) < 6)
            $error = "*Salasana on liian lyhyt, v�himm�is pituus on 6 merkki�<br />";
        if (strlen($salasana) > 20)
            $error = "*Salasana on liian pitk�, enimm�is pituus on 20 merkki�<br />";
        if (strlen($login) < 4)
            $error = "*Nimimerkki on liian lyhyt, v�himm�is pituus on 4 merkki�<br />";
        if (strlen($login) > 20)
            $error = "*Nimimerkki on liian pitk�, enemm�is pituus on 20 merkki�<br />";
        if (tarkistaEiSallitutMerkit($login) || tarkistaEiSallitutMerkit($salasana)) {
            $error = "*Nimimerkki ja salasana ei saa sis�lt�� �, �, �, �, �, � merkkej�<br />";
        }
        if (!empty($error)) {
            return false;
        }
        $kysely = kysely($yhteys, "SELECT id FROM tunnukset WHERE login='" . $login . "'");
        if ($tulos = mysql_fetch_array($kysely)) {
            $error = "*Nimimerkki on jo k�yt�ss�<br />";
            return false;
        }
        if ($salasana != $vahvistasalasana) {
            $error = "*Salasanat eiv�t ole samat<br />";
            return false;
        }
        $salasana = crypt($salasana);
        kysely($yhteys, "INSERT INTO nakyvattiedot (email, etunimi, sukunimi, syntymavuosi) VALUES " .
                "('" . $nakyysahkoposti . "', '" . $nakyyetunimi . "', '" . $nakyysukunimi . "', '" . $nakyysyntymavuosi . "')");
        $nakyvattiedotid = mysql_insert_id();
        $aika = time();
        $aika = date("Y-m-d H:i", $aika);
        kysely($yhteys, "UPDATE tunnukset SET login='" . $login . "', salasana='" . $salasana . "', enabled='1', rpaivamaara='" . $aika . "', nakyvattiedotID='" . $nakyvattiedotid . "' " .
                "WHERE id='" . $tunnusid . "'");
        kysely($yhteys, "DELETE FROM tokenit WHERE token='" . $token . "' AND tunnuksetID='" . $tunnusid . "'");
        siirry("index.php");
    }
}

?>

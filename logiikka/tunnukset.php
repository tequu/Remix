<?php

//Rekisteroi
function rekisteroi($yhteys) {
    global $error;
//Lähetetyt tiedot talteen
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
//Tarkistetaan että kaikissa kentissä on jotain ja oikeat arvot
    if (empty($login))
        $error .= "*Nimimerkki kenttä on tyhjä<br />";
    if (empty($salasana))
        $error .= "*Salasana kenttä on tyhjä<br />";
    if (empty($vahvistasalasana))
        $error .= "*Varmista salasana kenttä on tyhjä<br />";
    if (empty($sahkoposti))
        $error .= "*Sähköposti kenttä on tyhjä<br />";
    if (empty($vahvistasahkoposti))
        $error .= "*Varmista sähköposti kenttä on tyhjä<br />";
    if (!tarkistaPerusTiedot($etunimi, $sukunimi, $syntymavuosi))
        return;
//tarkistetaan salasanan pituus
    if (strlen($salasana) < 6)
        $error = "*Salasana on liian lyhyt, vähimmäis pituus on 6 merkkiä<br />";
    if (strlen($salasana) > 20)
        $error = "*Salasana on liian pitkä, enimmäis pituus on 20 merkkiä<br />";

//tarkistetaan nimimerkin pituus
    if (strlen($login) < 4)
        $error = "*Nimimerkki on liian lyhyt, vähimmäis pituus on 4 merkkiä<br />";
    if (strlen($login) > 20)
        $error = "*Nimimerkki on liian pitkä, enemmäis pituus on 20 merkkiä<br />";
//tarkistetaan löytyykö salasanasta tai nimimerkistä vääriä  merkkejä
    if (tarkistaEiSallitutMerkit($login) || tarkistaEiSallitutMerkit($salasana)) {
        $error = "*Nimimerkki ja salasana ei saa sisältää Å, Ä, Ö, å, ä, ö merkkejä<br />";
    }
    if (!empty($error)) {
        return;
    }
    $kysely = kysely($yhteys, "SELECT login, email FROM tunnukset WHERE login='" . $login . "' OR email='" . $sahkoposti . "'");
//tarkistetaan onko nimimerkki jo käytössä
    while ($tulos = mysql_fetch_array($kysely)) {
        if ($login == $tulos['login']) {
            $error = "*Nimimerkki on jo käytössä<br />";
        }
        if ($sahkoposti == $tulos['email']) {
            $error = "*Sähköpostiosoite on jo käytössä<br />";
        }
    }
    if (!empty($error))
        return;

//tarkistetaan täsmääkö salasanat ja sähköposti osoitteet
    if ($salasana != $vahvistasalasana) {
        $error = "*Salasanat eivät ole samat<br />";
        return;
    }
//tarkistetaan täsmääkö säköpostiosoitteet
    if ($sahkoposti != $vahvistasahkoposti) {
        $error = "*Sähköpostiosoitteet eivät ole samat<br />";
        return;
    }
//cryptataan salasana
    $salasana = crypt($salasana);
//luodaan tokeni
    $token = md5(microtime());
//lisätään näkyvät tiedot tietokantaan
    kysely($yhteys, "INSERT INTO nakyvattiedot (email, etunimi, sukunimi, syntymavuosi) VALUES ('" . $nakyysahkoposti . "', '" . $nakyyetunimi . "', '" . $nakyysukunimi . "', '" . $nakyysyntymavuosi . "')");
//nakyvien tietojen id
    $nakyvattiedotid = mysql_insert_id();
//lisätään tiedot tietokantaan
    kysely($yhteys, "INSERT INTO tunnukset (login, salasana, email, etunimi, sukunimi, syntymavuosi, isadmin, enabled, estetty, nakyvattiedotID) VALUES ('" . $login . "', '" . $salasana . "', '" . $sahkoposti . "', '" . $etunimi . "', '" . $sukunimi . "', '" . $syntymavuosi . "', 'Perus', '0', '0', '" . $nakyvattiedotid . "')");
//otetaan talteen tunnusid
    $kayttajaid = mysql_insert_id();
//asetetaan tokeni tietokantaan
    kysely($yhteys, "INSERT INTO tokenit (token, tunnuksetID) VALUES ('" . $token . "', '" . $kayttajaid . "')");
//lähetetään sähköposti
    lahetaRekisterointiPosti($token, $sahkoposti);
    siirry("index.php");
}

function lahetaRekisterointiPosti($token, $sahkoposti) {
//luodaan otsikko
    $otsikko = "Rekisteröinnin vahvistus";
//luodaan viesti
    $viesti = "Kiitos rekisteröitymisestä FBC Remixin foorumeille. \n\nOle hyvä ja varmista rekisteröitymisesi klikkaamalla seuraavaa linkkiä.\n\n" .
            "fbcremix.com/aktivoi.php?email=" . $sahkoposti . "&token=" . $token . "\n\n" .
            "Jos linkki ei aukea klikkaamalla, voit kopioida sen selaimen osoite kenttään.\n\n" .
            "Tämä on automaattinen viesti. Älä vastaa tähän.";
    mail($sahkoposti, $otsikko, $viesti);
}

//Kirjaudu
function kirjaudu($yhteys) {
    global $error;
    if (isset($_SESSION['id'])) {
        $error = "Olet jo kirjautunut sisään.<br />";
        return;
    }
//tarkistetaan että saatiin login ja salasana
    if (isset($_POST['login']) && isset($_POST['salasana'])) {
//login ja salasana talteen
        $login = mysql_real_escape_string(trim($_POST['login']));
        $salasana = mysql_real_escape_string(trim($_POST['salasana']));
//tarkistetaan ettei ne ollu tyhjiä
        if (empty($login) || empty($salasana)) {
            $error = "Käyttäjätunnus tai salasana kenttä on tyhjä.";
            return;
        }
//haetaan tunnuksen tietoja
        $kysely = kysely($yhteys, "SELECT id, login, salasana, isadmin, enabled, estetty FROM tunnukset WHERE login='" . $login . "'");
//tarkistetaan että löytyi tunnus ja salasana täsmää tunnukseen
        if ($tulos = mysql_fetch_array($kysely)) {
            if (crypt($salasana, $tulos['salasana']) == $tulos['salasana']) {
//tarkistetaan ettei tunnusta ole estetty
                if (!$tulos['estetty']) {
//ja että tunnus on aktivoitu
                    if ($tulos['enabled']) {
//id, nimimerkki ja oikeudet talteen
                        $_SESSION['id'] = $tulos['id'];
                        $_SESSION['login'] = $tulos['login'];
                        $_SESSION['oikeudet'] = $tulos['isadmin'];
//kirjautumis aika
                        $aikaleima = time();
                        $kirjautumis_aika = date("Y-m-d H:i", $aikaleima);
//tietokantaan tämän kertaisen kirjautumisen tiedot
                        kysely($yhteys, "INSERT INTO kirjautumistiedot (ip, aika, tunnuksetID) VALUES ('" . $_SERVER['REMOTE_ADDR'] . "', '" . $kirjautumis_aika . "', '" . $tulos['id'] . "')");
                    } else {
                        $error = "Tunnusta ei ole aktivoitu";
                    }
                } else {
                    $error = "Tunnus on estetty";
                }
            } else {
                $error = "Käyttäjätunnus tai salasana on väärä";
            }
        } else {
            $error = "Käyttäjätunnus tai salasana on väärä";
        }
    } else {
        $error = "Virhe kirjautuessa";
    }
//jos jokun oli väärin, hypätään funktiosta pihalle
    if (!empty($error))
        return;
    if (isset($_SESSION['kirjaudu'])) {
        siirry($_SESSION['kirjaudu']);
    }
//ohjataan käyttäjä etusivulle onnistuttaessa
    siirry("index.php");
}

//Estää tai poistaa eston käyttäjältä
function vaihdaEsto($yhteys) {
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        siirry("eioikeuksia.php");
    }
    $kayttaja = mysql_real_escape_string($_GET['kayttaja']);
    $kysely = kysely($yhteys, "SELECT estetty FROM Tunnukset WHERE id='" . $kayttaja . "'");
    //Muutetaan esto jos löytyi käyttäjä
    if ($tulos = mysql_fetch_array($kysely)) {
        kysely($yhteys, "UPDATE Tunnukset SET estetty='" . !$tulos['estetty'] . "' WHERE id='" . $kayttaja . "'");
    } else {
        $error = "Tunnusta ei löytynyt<br />";
        return;
    }
    ohjaaOhajuspaneeliin(2, "&kayttaja=" . $kayttaja);
}

//Muokkaa käyttäjän omat tiedot
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
    //Päivitetään tiedot
    $sql = "UPDATE tunnukset SET etunimi='" . $etunimi . "', sukunimi='" . $sukunimi . "', syntymavuosi='" . $syntymavuosi . "'";
    if (!empty($uusisalasana)) {
        if ($uusisalasana == $vahvistasalasana) {
            //Päivitetään salasana jos se vanha oli oikein ja uudet vastaavat toisiaan
            $kysely = kysely($yhteys, "SELECT salasana FROM tunnukset WHERE id='" . $tunnusid . "'");
            $tulos = mysql_fetch_array($kysely);
            if (crypt($vanhasalasana, $tulos['salasana']) == $tulos['salasana']) {
                $uusisalasana = crypt($uusisalasana);
                $sql .= ", salasana='" . $uusisalasana . "'";
            } else {
                $error .= "Salasanan muokkaus epäonnistui. Väärä salasana<br />";
            }
        } else {
            $error .= "Salasanan muokkaus epäinnostui. Salasanat eivät täsmää<br />";
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
    //Tarkistetaan että perustiedot on oikein
    tarkistaPerusTiedot($etunimi, $sukunimi, $syntymavuosi);
    if (!empty($error)) {
        return false;
    }
    $kysely = kysely($yhteys, "SELECT id FROM tunnukset WHERE LOWER(etunimi)='" . strtolower($etunimi) . "' AND LOWER(sukunimi)='" . strtolower($sukunimi) . "' AND syntymavuosi='" . $syntymavuosi . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        $error = "Tällä nimellä ja syntymävuodella on jo tunnus. Käytä hakua.";
        return false;
    }
    if (!empty($email)) {
        $kysely = kysely($yhteys, "SELECT id FROM tunnukset WHERE LOWER(email)='" . strtolower($email) . "'");
        if ($tulos = mysql_fetch_array($kysely)) {
            $error = "Sähköpostiosoite on jo käytössä. Käytä hakua.";
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
        $viesti = "FBC Remixin joukkue " . haeJoukkueenNimi($yhteys, $joukkue) . " on lisännyt sinut joukkueeseensa ja luonut samalla sinulle tunnuksen FBC Remixin sivustolle.\n" .
                "Ole hyvä ja mene www.fbcremix.com/Remix/aktivoi.php?email=" . $email . "&token=" . $token . "&id=" . $tunnusid . " varmistamaan sähköposti ja ottamaan se käyttöön." .
                "Sivulla sinua pyydetään syöttämään käyttäjätunnuksesi ja salasanasi.\nJos yllä annettu linkki ei toimi klikkaamalla, voit kopioida sen selaimesi osoite kenttään.\n\n" .
                "Huom. Tämä on automaattisesti lähetetty viesti. Älä vastaa tähän.";
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
        $error = "*Salasanat eivät täsmää";
        return false;
    }
    if (strlen($uusisalasana) < 6) {
        $error = "*Salasanan vähimmäis pituus on 6 merkkiä";
        return false;
    }
    if (strlen($uusisalasana) > 20) {
        $error = "*Salasanan enimäis pituus on 20 merkkiä";
        return false;
    }
    if (preg_match("/[ÅÄÖåäö]/", $uusisalasana)) {
        $error = "*Salasana ei saa sisältää å, ä, ö merkkejä";
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
        $error = "*Vanha salasana oli väärin";
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
            $error .= "*Nimimerkki kenttä on tyhjä<br />";
        if (empty($salasana))
            $error .= "*Salasana kenttä on tyhjä<br />";
        if (empty($vahvistasalasana))
            $error .= "*Varmista salasana kenttä on tyhjä<br />";
        if (!empty($error)) {
            return false;
        }
        if (strlen($salasana) < 6)
            $error = "*Salasana on liian lyhyt, vähimmäis pituus on 6 merkkiä<br />";
        if (strlen($salasana) > 20)
            $error = "*Salasana on liian pitkä, enimmäis pituus on 20 merkkiä<br />";
        if (strlen($login) < 4)
            $error = "*Nimimerkki on liian lyhyt, vähimmäis pituus on 4 merkkiä<br />";
        if (strlen($login) > 20)
            $error = "*Nimimerkki on liian pitkä, enemmäis pituus on 20 merkkiä<br />";
        if (tarkistaEiSallitutMerkit($login) || tarkistaEiSallitutMerkit($salasana)) {
            $error = "*Nimimerkki ja salasana ei saa sisältää Å, Ä, Ö, å, ä, ö merkkejä<br />";
        }
        if (!empty($error)) {
            return false;
        }
        $kysely = kysely($yhteys, "SELECT id FROM tunnukset WHERE login='" . $login . "'");
        if ($tulos = mysql_fetch_array($kysely)) {
            $error = "*Nimimerkki on jo käytössä<br />";
            return false;
        }
        if ($salasana != $vahvistasalasana) {
            $error = "*Salasanat eivät ole samat<br />";
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

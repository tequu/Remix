<?php

//Lisää uusi joukkue
function lisaaJoukkue($yhteys) {
    global $error, $kausi, $ojoukkue;
    if (!tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lisätä joukkueita.";
        siirry("eioikeuksia.php");
    }
    $nimi = mysql_real_escape_string(trim($_POST['nimi']));
    $kerho = mysql_real_escape_string($_POST['kerho']);
    //Tarkistetaan että saatiin nimi
    if (empty($nimi)) {
        $error = "Et antanut nimeä";
        return false;
    }
    //Tarkistetaan ettei ole jo olemassa saman nimistä
    $kysely = kysely($yhteys, "SELECT id FROM joukkueet WHERE nimi='" . $nimi . "' AND kausi='" . $kausi . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        $error = "Nimi on jo käytössä";
        return false;
    }
    //Luodaan joukkueelle salasana
    $salasana = luoSatunnainenMerkkijono(10);
    kysely($yhteys, "INSERT INTO keskustelualueet(nimi,kuvaus,keskustelualueryhmatID, julkinen) VALUES('" . $nimi . "', 'Joukkueen " . $nimi . " keskustelualue', (SELECT id FROM keskustelualueryhmat WHERE otsikko='Joukkueet'), '0')");
    $keskustelualueetid = mysql_insert_id();
    $jarjestysnumero = 1;
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM joukkueet WHERE kausi='" . $kausi . "' ORDER BY jarjestysnumero DESC LIMIT 0, 1");
    if ($tulos = mysql_fetch_array($kysely)) {
        $jarjestysnumero = $tulos['jarjestysnumero'] + 1;
    }
    kysely($yhteys, "INSERT INTO joukkueet (nimi, kapteeni, kerho, salasana, keskustelualueetID, jarjestysnumero, kausi) " .
            "VALUES('" . $nimi . "', '0', '" . $kerho . "', '" . $salasana . "', '" . $keskustelualueetid . "', '" . $jarjestysnumero . "', '" . $kausi . "')");
    ohjaaOhajuspaneeliin($ojoukkue, "");
}

//Muokkaa joukkueen tietoja
function muokkaaJoukkueenTietoja($yhteys) {
    global $error, $ojoukkue;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata joukkueen " . haeJoukkueenNimi($joukkue) . " tietoja.";
        siirry("eioikeuksia.php");
    }
    $nimi = mysql_real_escape_string(trim($_POST['nimi']));
    $kuvaus = mysql_real_escape_string(trim($_POST['kuvaus']));
    $salasana = mysql_real_escape_string(trim($_POST['salasana']));
    $sql = "UPDATE joukkueet SET ";
    $kyselyok = false;
    //Jos muokataan nimeä vaaditaan masteradminin oikeudet
    if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        if (strlen($nimi) > 0) {
            $sql .= "nimi='" . $nimi . "', ";
            $kyselyok = true;
        } else {
            $error .= "Nimi kenttä oli tyhjä<br />";
        }
    }
    //Tarkistetaan että annettiin salasana
    if (strlen($salasana) > 0) {
        //Salasanan on oltava myös vähinttään kolme ja korkeintaa 20 merkkiä
        if (strlen($salasana) < 3 && strlen($salasana) > 20) {
            $error .= "Salasanan pituuden tulee olla välillä 3-20";
        } else {
            $sql .= "salasana='" . $salasana . "', ";
            $kyselyok = true;
        }
    } else {
        $error .= "Salasana kenttä oli tyhjä<br />";
    }
    $sql = substr($sql, 0, -2);
    $sql .= " WHERE id='" . $joukkue . "'";
    //Jos jokin muokkaus onnistui muokataan se
    if ($kyselyok) {
        kysely($yhteys, $sql);
    }
    if (strlen($kuvaus) > 0) {
        kysely($yhteys, "UPDATE keskustelualueet SET kuvaus='" . $kuvaus . "' WHERE id=(SELECT keskustelualueetID FROM joukkueet WHERE id='" . $joukkue . "')");
    } else {
        $error .= "Et antanut kuvausta";
    }
    if (!empty($error))
        return false;
    ohjaaOhajuspaneeliin($ojoukkue, "&joukkue=" . $joukkue);
}

//Poista joukkue
function poistaJoukkue($yhteys) {
    global $ojoukkue;
    if (!tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa joukkueita.";
        siirry("eioikeuksia.php");
    }
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    $kysely = kysely($yhteys, "SELECT keskustelualueetID FROM joukkueet WHERE id='" . $joukkue . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        //Poistetaan kaikki tarpeelliset tiedot
//        kysely($yhteys, "DELETE FROM paikallaolo WHERE tapahtumatID IN (SELECT tapahtumatID FROM keskustelut k, keskustelualuekeskustelut kk " .
//                "WHERE k.id=kk.keskustelutID AND kk.keskustelualueetID='" . $tulos['keskustelualueetID'] . "')");
//        kysely($yhteys, "DELETE FROM tapahtumat WHERE id IN (SELECT tapahtumatID id FROM keskustelut k, keskustelualuekeskustelut kk " .
//                "WHERE k.id=kk.keskustelutID AND kk.keskustelualueetID='" . $tulos['keskustelualueetID'] . "')");
//        kysely($yhteys, "DELETE FROM viestit WHERE keskustelutID IN (SELECT keskustelutID FROM keskustelualuekeskustelut WHERE keskustelualueetID='" . $tulos['keskustelualueetID'] . "')");
//        kysely($yhteys, "DELETE FROM keskustelut WHERE id IN (SELECT keskustelutID id FROM keskustelualuekeskustelut WHERE keskustelualueetID='" . $tulos['keskustelualueetID'] . "')");
//        kysely($yhteys, "DELETE FROM keskustelualuekeskustelut WHERE keskustelualueetID='" . $tulos['keskustelualueetID'] . "'");
        kysely($yhteys, "DELETE FROM pelaajakorttilisatieto WHERE joukkueetID='" . $joukkue . "'");
        $kysely = kysely($yhteys, "SELECT kuva FROM pelaajat WHERE joukkueetID='" . $joukkue . "'");
        while ($tulos = mysql_fetch_array($kysely)) {
            //Poista fyysiset kuvat
        }
        kysely($yhteys, "DELETE FROM pelaajat WHERE joukkueetID='" . $joukkue . "'");
        kysely($yhteys, "DELETE FROM yhteyshenkilot WHERE joukkueetID='" . $joukkue . "'");
        poistaTunnuksetJotkaEivatKaytossa($yhteys);
        kysely($yhteys, "DELETE FROM keskustelualueet WHERE id='" . $tulos['keskustelualueetID'] . "'");
        kysely($yhteys, "DELETE FROM oikeudet WHERE keskustelualueetID IN (SELECT keskustelualueetID FROM joukkueet WHERE id='" . $joukkue . "') || joukkueetID='" . $joukkue . "'");
//        kysely($yhteys, "DELETE FROM keskustelualueoikeudet WHERE keskustelualueetID='" . $tulos['keskustelualueetID'] . "'");
        kysely($yhteys, "DELETE FROM tiedotukset WHERE joukkueetID='" . $joukkue . "'");
//        $kysely = kysely($yhteys, "SELECT kuva FROM kuvat, kuvakategoriat kk WHERE kk.id=kuvakategoriatID AND joukkueetID='" . $joukkue . "'");
//        while ($tulos = mysql_fetch_array($kysely)) {
//            //Poista fyysiset kuvat
//        }
//        kysely($yhteys, "DELETE FROM kuvat WHERE kuvakategoriatID IN (SELECT id FROM kuvakategoriat WHERE joukkueetID='" . $joukkue . "')");
//        kysely($yhteys, "DELETE FROM kuvakategoriat WHERE joukkueetID='" . $joukkue . "'");
        kysely($yhteys, "DELETE FROM pelit WHERE peliryhmatID IN (SELECT id FROM peliryhmat WHERE joukkueetID='" . $joukkue . "')");
        kysely($yhteys, "DELETE FROM peliryhmat WHERE joukkueetID='" . $joukkue . "'");
        kysely($yhteys, "DELETE FROM sarjataulukot WHERE sarjataulukkoryhmatID IN (SELECT id FROM sarjataulukkoryhmat WHERE joukkueetID='" . $joukkue . "')");
        kysely($yhteys, "DELETE FROM sarjataulukkoryhmat WHERE joukkueetID='" . $joukkue . "'");
        kysely($yhteys, "DELETE FROM tilastot WHERE tilastoryhmatID IN (SELECT id FROM tilastoryhmat WHERE joukkueetID='" . $joukkue . "')");
        kysely($yhteys, "DELETE FROM tilastoryhmat WHERE joukkueetID='" . $joukkue . "'");
//        $kysely = kysely($yhteys, "SELECT kuva FROM sponsorit WHERE joukkueetID='" . $joukkue . "'");
//        while ($tulos = mysql_fetch_array($kysely)) {
//            //Poista fyysiset sponsorien kuvat
//        }
//        kysely($yhteys, "DELETE FROM sponsorit WHERE joukkueetID='" . $joukkue . "'");
        kysely($yhteys, "DELETE FROM joukkueet WHERE id='" . $joukkue . "'");
    }
    ohjaaOhajuspaneeliin($ojoukkue, "");
}

//Lisää jäsen joukkueeseen, älä käytä!
function lisaaPelaajatJoukkueeseen($yhteys, $joukkue, $tunnukset, $roolit, $pelinumerot) {
    global $error, $siirry;
    if (!empty($tunnukset)) {
        $lisattava = "";
        for ($i = 0; $i < count($tunnukset); $i++) {
            if (!tarkistaTunnuksenOlemassaOlo($yhteys, $tunnukset[$i])) {
                $_SESSION['virhe'] = "Pelaaja ei onnistuttu lisäämään joukkueeseen.";
                if ($siirry)
                    siirry("virhe.php");
                $error = $_SESSION['virhe'];
                return false;
            }
            if (tarkistaOnkoHenkiloJoJoukkeenPelaaja($yhteys, $joukkue, $tunnukset[$i])) {
                $error .= "Henkilö " . haeKayttajanNimi($yhteys, $tunnukset[$i]) . " on jo joukkueen jäsenenä.";
                continue;
            }
            $lisattava .= "('" . $roolit[$i] . "', '" . $joukkue . "', '" . $tunnukset[$i] . "', '" . $pelinumerot[$i] . "'), ";
        }
        $lisattava = substr($lisattava, 0, -2);
        if (!empty($lisattava)) {
            kysely($yhteys, "INSERT INTO pelaajat (rooli, joukkueetID, tunnuksetID, pelinumero) VALUES " . $lisattava);
        }
        return true;
    }
}

//Lisää jäsen joukkueeseen, älä käytä!
function lisaaYhteyshenkiloJoukkueeseen($yhteys, $joukkue, $tunnukset, $tiedot, $roolit) {
    global $error, $siirry;
    if (!empty($tunnukset)) {
        $lisattava = "";
        for ($i = 0; $i < count($tunnukset); $i++) {
            if (!tarkistaTunnuksenOlemassaOlo($yhteys, $tunnukset[$i])) {
                $_SESSION['virhe'] = "Yhteyshenkilöä ei onnistuttu lisäämään joukkueeseen.";
                if ($siirry)
                    siirry("virhe.php");
                $error = $_SESSION['virhe'];
                return false;
            }
            if (tarkistaOnkoHenkiloJoJoukkueenYhteyshenkilo($yhteys, $joukkue, $tunnukset[$i])) {
                $error .= "Henkilö " . haeKayttajanNimi($yhteys, $tunnukset[$i]) . " on jo joukkueen jäsenenä.";
                continue;
            }
            $lisattava .= "('" . $tiedot[$i] . "', '" . $roolit[$i] . "', '" . $joukkue . "', '" . $tunnukset[$i] . "'), ";
        }
        $lisattava = substr($lisattava, 0, -2);
        if (!empty($lisattava)) {
            kysely($yhteys, "INSERT INTO yhteyshenkilot (tiedot, rooli, joukkueetID, tunnuksetID) VALUES " . $lisattava);
        }
        return true;
    }
}

function tarkistaOnkoHenkiloJoJoukkueessa($yhteys, $joukkue, $tunnus) {
    if (tarkistaOnkoHenkiloJoJoukkeenPelaaja($yhteys, $joukkue, $tunnus)) {
        return true;
    }

    return tarkistaOnkoHenkiloJoJoukkueenYhteyshenkilo($yhteys, $joukkue, $tunnus);
}

function tarkistaOnkoHenkiloJoJoukkeenPelaaja($yhteys, $joukkue, $tunnus) {
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM pelaajat p " .
            "WHERE tunnuksetID='" . $tunnus . "' AND joukkueetID='" . $joukkue . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        return true;
    }
    return false;
}

function tarkistaOnkoHenkiloJoJoukkueenYhteyshenkilo($yhteys, $joukkue, $tunnus) {
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM yhteyshenkilot WHERE tunnuksetID='" . $tunnus . "' AND joukkueetID='" . $joukkue . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        return true;
    }
    return false;
}

//Poista joukkueen jäsen
function poistaJoukkueenJasen($yhteys, $taulu, $tunnus, $joukkue) {
    global $error;
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        siirry("eioikeuksia.php");
    }
    if (!tarkistaTunnuksenOlemassaOlo($yhteys, $tunnus)) {
        $_SESSION['virhe'] = "Tunnusta ei löytynyt.";
        siirry("virhe.php");
    }
    //Poistetaan pelaaja joukkueesta
    kysely($yhteys, "DELETE FROM " . $taulu . " WHERE joukkueetID='" . $joukkue . "' AND tunnuksetID='" . $tunnus . "'");
    poistaTunnuksetJotkaEivatKaytossa($yhteys);
}

//Liitä henkilö joukkueen jäseneksi
function liitaHenkiloJoukkueeseen($yhteys) {
    global $oomattiedot, $error;
    tarkistaKirjautuneenTunnus($yhteys);
    $joukkue = mysql_real_escape_string($_POST['joukkue']);
    $tunnus = mysql_real_escape_string($_SESSION['id']);
    $salasana = mysql_real_escape_string($_POST['salasana']);
    $rooli = $_POST['rooli'];
    $kysely = kysely($yhteys, "SELECT salasana FROM joukkueet WHERE id='" . $joukkue . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        //Tarkistetaan että salasana oli oikein
        if ($salasana == $tulos['salasana']) {
            //Lisätään pelaaja joukkueeseen
            if ($rooli == "pelaaja") {
                if (!tarkistaOnkoHenkiloJoJoukkeenPelaaja($yhteys, $joukkue, $tunnus)) {
                    kysely($yhteys, "INSERT INTO pelaajat (joukkueetID, tunnuksetID) VALUES ('" . $joukkue . "', '" . $tunnus . "')");
                }
            } else if ($rooli == "yhteyshenkilo") {
                if (!tarkistaOnkoHenkiloJoJoukkueenYhteyshenkilo($yhteys, $joukkue, $tunnus)) {
                    kysely($yhteys, "INSERT INTO yhteyshenkilot (rooli, joukkueetID, tunnuksetID) VALUES ('Yhteyshenkilo', '" . $joukkue . "', '" . $tunnus . "')");
                }
            }
        } else {
            $error = "Salasana oli väärin<br />";
        }
    } else {
        $error = "Joukkuetta ei löytynyt<br />";
    }
    ohjaaOhajuspaneeliin($oomattiedot, "");
}

?>

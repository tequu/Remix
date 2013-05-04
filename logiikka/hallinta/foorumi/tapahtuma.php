<?php

function uusiTapahtuma($yhteys) {
    
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    if (tarkistaKeskustelualueetOlemassaOlo($yhteys, $keskustelualue)) {
        if (!tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
            return false;
        }
        global $error;
        $kayttaja = $_SESSION['id'];
        $nimi = mysql_real_escape_string(trim($_POST['nimi']));
        $tapahtuma = mysql_real_escape_string($_POST['tapahtuma']);
        $milloina = array(mysql_real_escape_string($_POST['paiva_a']), mysql_real_escape_string($_POST['kuukausi_a']), mysql_real_escape_string($_POST['vuosi_a']), mysql_real_escape_string($_POST['tunti_a']), mysql_real_escape_string($_POST['minuutti_a']));
        $loppuu = mysql_real_escape_string($_POST['loppuu']);
        $milloinl = array(mysql_real_escape_string($_POST['paiva_l']), mysql_real_escape_string($_POST['kuukausi_l']), mysql_real_escape_string($_POST['vuosi_l']), mysql_real_escape_string($_POST['tunti_l']), mysql_real_escape_string($_POST['minuutti_l']));
        $takailmoo = mysql_real_escape_string($_POST['takailmoo']);
        $takailmo = array(mysql_real_escape_string($_POST['paiva_t']), mysql_real_escape_string($_POST['kuukausi_t']), mysql_real_escape_string($_POST['vuosi_t']), mysql_real_escape_string($_POST['tunti_t']), mysql_real_escape_string($_POST['minuutti_t']));
        $maxilmoo = mysql_real_escape_string($_POST['maxilmoo']);
        $maxilmo = mysql_real_escape_string(trim($_POST['maxilmo']));
        $kuvaus = mysql_real_escape_string(trim($_POST['kuvaus']));
        $lisatieto = mysql_real_escape_string(trim($_POST['lisatieto']));
        $paikka = mysql_real_escape_string(trim($_POST['paikka']));
        //Tarkistetaan ettei saatu tyhji‰ kentti‰ ja syˆtetyt tiedot ovat oikeassa muodossa
        if (empty($nimi)) {
            $error .= "Et antanut nime‰<br />";
        }
        if (empty($tapahtuma)) {
            $error .= "Valitse tapahtuma<br />";
        }
        if (!vertaaAjat($milloina, time())) {
            $error .= "Alkamis aika on menneisyydess‰<br />";
        }
        if ($loppuu && !vertaaAjat($milloinl, time())) {
            $error .= "Loppumis aika on menneisyydess‰<br />";
        }
        if ($loppuu && !vertaaAjat($milloinl, mktime(0, 0, 0, $milloina[1], $milloina[0], $milloina[2]))) {
            $error .= "Alkamis aika on loppumis ajan j‰lkeen<br />";
        }
        if ($takailmoo && !vertaaAjat($takailmo, time())) {
            $error .= "Ilmoittautumisen tajaraja on menneisyydess‰<br />";
        }
        if ($maxilmoo && empty($maxilmo)) {
            $error .= "Et antanut ilmoittautujien maksimi m‰‰r‰‰<br />";
        } elseif ($maxilmoo && !is_numeric($maxilmo)) {
            $error .= "Ilmoittautujien maksimi m‰‰r‰ ei ollut numeerinen arvo<br />";
        }
        if (!empty($error)) {
            return;
        }
        //Asetetaan saadut tiedto tietokantaan
        $sqlalku = "INSERT tapahtumat (tapahtuma, alkamisaika";
        $sqlloppu = "VALUES ('" . $tapahtuma . "', '" . paivamaaraTietokantaMuotoon($milloina) . "'";
        if ($loppuu) {
            $sqlalku .= ", loppumisaika";
            $sqlloppu .= ", '" . paivamaaraTietokantaMuotoon($milloinl) . "'";
        }
        if (!empty($kuvaus)) {
            $sqlalku .= ", kuvaus";
            $sqlloppu .= ", '" . $kuvaus . "'";
        }
        if ($takailmoo) {
            $sqlalku .= ", ilmotakaraja";
            $sqlloppu .= ", '" . paivamaaraTietokantaMuotoon($takailmo) . "'";
        }
        if ($maxilmoo) {
            $sqlalku .= ", ilmomaxmaara";
            $sqlloppu .= ", '" . $maxilmo . "'";
        }
        if (!empty($lisatieto)) {
            $sqlalku .= ", lisatieto";
            $sqlloppu .= ", '" . $lisatieto . "'";
        }
        if (!empty($paikka)) {
            $sqlalku .= ", paikka";
            $sqlloppu .= ", '" . $paikka . "'";
        }
        $sql = $sqlalku . ") " . $sqlloppu . ")";
        kysely($yhteys, $sql);
        $id = mysql_insert_id();
        //Luodaan uusi keskustelu, johon tapahtuma liitet‰‰n
        $keskustelu = luoUusiKeskustelu($yhteys, $keskustelualue, $nimi, "", $kayttaja, $id);
        siirry("foorumi/keskustelu.php?keskustelualue=" . $keskustelualue . "&keskustelu=" . $keskustelu);
    } else {
        siirry("foorumi/index.php");
    }
}

function muokkaaTapahtumaa($yhteys) {
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $keskustelu = mysql_real_escape_string($_POST['keskustelu']);
    if (tarkistaKeskustelualueetOlemassaOlo($yhteys, $keskustelualue)) {
        if (!tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
            $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksa muokata keskustelualuetta.";
            siirry("eioikeuksia.php");
        }
        global $error;
        $defaultaika = "0000-0-0 00:00:00";
        $nimi = mysql_real_escape_string(trim($_POST['nimi']));
        $tapahtuma = mysql_real_escape_string($_POST['tapahtuma']);
        $milloina = array(mysql_real_escape_string($_POST['paiva_a']), mysql_real_escape_string($_POST['kuukausi_a']), mysql_real_escape_string($_POST['vuosi_a']), mysql_real_escape_string($_POST['tunti_a']), mysql_real_escape_string($_POST['minuutti_a']));
        $loppuu = mysql_real_escape_string($_POST['loppuu']);
        $milloinl = array(mysql_real_escape_string($_POST['paiva_l']), mysql_real_escape_string($_POST['kuukausi_l']), mysql_real_escape_string($_POST['vuosi_l']), mysql_real_escape_string($_POST['tunti_l']), mysql_real_escape_string($_POST['minuutti_l']));
        $takailmoo = mysql_real_escape_string($_POST['takailmoo']);
        $takailmo = array(mysql_real_escape_string($_POST['paiva_t']), mysql_real_escape_string($_POST['kuukausi_t']), mysql_real_escape_string($_POST['vuosi_t']), mysql_real_escape_string($_POST['tunti_t']), mysql_real_escape_string($_POST['minuutti_t']));
        $maxilmoo = mysql_real_escape_string($_POST['maxilmoo']);
        $maxilmo = mysql_real_escape_string(trim($_POST['maxilmo']));
        $kuvaus = mysql_real_escape_string(trim($_POST['kuvaus']));
        $lisatieto = mysql_real_escape_string(trim($_POST['lisatieto']));
        $paikka = mysql_real_escape_string(trim($_POST['paikka']));
        //Tarkistetaan ettei saatu tyhji‰ kentti‰ ja syˆtetyt tiedot ovat oikeassa muodossa
        if (empty($nimi)) {
            $error .= "Et antanut nime‰<br />";
        }
        if (empty($tapahtuma)) {
            $error .= "Valitse tapahtuma<br />";
        }
        if (!vertaaAjat($milloina, time())) {
            $error .= "Alkamis aika on menneisyydess‰<br />";
        }
        if ($loppuu && !vertaaAjat($milloinl, time())) {
            $error .= "Loppumis aika on menneisyydess‰<br />";
        }
        if ($loppuu && !vertaaAjat($milloinl, mktime(0, 0, 0, $milloina[1], $milloina[0], $milloina[2]))) {
            $error .= "Alkamis aika on loppumis ajan j‰lkeen<br />";
        }
        if ($takailmoo && !vertaaAjat($takailmo, time())) {
            $error .= "Ilmoittautumisen tajaraja on menneisyydess‰<br />";
        }
        if ($maxilmoo && empty($maxilmo)) {
            $error .= "Et antanut ilmoittautujien maksimi m‰‰r‰‰<br />";
        } elseif ($maxilmoo && !is_numeric($maxilmo)) {
            $error .= "Ilmoittautujien maksimi m‰‰r‰ ei ollut numeerinen arvo<br />";
        }
        if (!empty($error)) {
            return;
        }
        //Asetetaan saadut tiedto tietokantaan
        $sql = "UPDATE tapahtumat SET tapahtuma='" . $tapahtuma . "', alkamisaika='" . paivamaaraTietokantaMuotoon($milloina) . "', kuvaus='" . $kuvaus . "', " .
                "lisatieto='" . $lisatieto . "', paikka='" . $paikka . "', " .
                "loppumisaika='" . ($loppuu ? paivamaaraTietokantaMuotoon($milloinl) : $defaultaika) . "', " .
                "ilmotakaraja='" . ($takailmoo ? paivamaaraTietokantaMuotoon($takailmo) : $defaultaika) . "', " .
                "ilmomaxmaara='" . ($maxilmoo ? $maxilmo : null) . "' " .
                "WHERE id=(SELECT tapahtumatID FROM keskustelut k, keskustelualuekeskustelut kk WHERE k.id=kk.keskustelutID AND kk.keskustelualueetID='" . $keskustelualue . "' AND " .
                "k.id='" . $keskustelu . "')";
        kysely($yhteys, $sql);
        kysely($yhteys, "UPDATE keskustelut SET otsikko='".$nimi."' WHERE id='".$keskustelu."'");
        //Luodaan uusi keskustelu, johon tapahtuma liitet‰‰n
        siirry("foorumi/keskustelu.php?keskustelualue=" . $keskustelualue . "&keskustelu=" . $keskustelu);
    } else {
        $_SESSION['error'] = "Keskustelualuetta ei lˆytynyt.";
        siirry("virhe.php");
    }
}

function poistaTapahtuma($yhteys) {
    global $error;
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $keskustelu = mysql_real_escape_string($_POST['keskustelu']);
    $kysely = kysely($yhteys, "SELECT tapahtumatID FROM keskustelut, keskustelualuekeskustelut WHERE id=keskustelutID AND id='" . $keskustelu . "' AND keskustelualueetID='" . $keskustelualue . "'");
    $tulos = mysql_fetch_array($kysely);
    if (!$tulos['tapahtumatID']) {
        $_SESSION['error'] = "Tapahtumaa ei lˆytynyt.";
        siirry("virhe.php");
    }
    if (!tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole hallinta oikeuksia keskustelualueelle.";
        siirry("eioikeuksia.php");
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

    siirry("foorumi/tapahtumat.php");
}

?>

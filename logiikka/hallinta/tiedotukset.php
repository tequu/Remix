<?php
function lisaaTiedotus($yhteys) {
    global $error, $otiedotus;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue) || !tarkistaOnkoJoukkueNykysellaKaudella($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia listätä joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " tiedotuksia.";
        siirry("eioikeuksia.php");
    }
    $eivanhanemisaikaa = mysql_real_escape_string($_POST['eivanhenemisaikaa']);
    $paiva = mysql_real_escape_string($_POST['paiva']);
    $kuukausi = mysql_real_escape_string($_POST['kuukausi']);
    $vuosi = mysql_real_escape_string($_POST['vuosi']);
    $aikaleima = mktime(0, 0, 0, $kuukausi, $paiva, $vuosi);
    $tiedotus = trim($_POST['tiedotus']);
    $vanhenemisaika = $vuosi . "-" . $kuukausi . "-" . $paiva . " 0:0:0";
    if ($aikaleima < (time() - 24 * 60 * 60) && !$eivanhanemisaikaa) {
        $error = "Vanhenemis aika on menneisyydessä.<br />";
    }
    if (empty($tiedotus)) {
        $error .= "Tiedotus kenttä on tyhjä.";
    }
    if (!empty($error)) {
        return false;
    }
    $tiedotus = str_replace("\r", "", $tiedotus);
    $tiedotus = str_replace("\n", "<br />", $tiedotus);
    $tiedotus = stripslashes($tiedotus);
    $tiedotus = mysql_real_escape_string($tiedotus);
    $aika = time();
    $kirjoitusaika = date("Y-m-d H:i", $aika);
    kysely($yhteys, "INSERT INTO tiedotukset (tiedotus, kirjoitusaika, vanhenemisaika, eivanhenemisaikaa, joukkueetID) " .
            "VALUES ('" . $tiedotus . "', '" . $kirjoitusaika . "', '" . $vanhenemisaika . "', '" . $eivanhanemisaikaa . "', '" . $joukkue . "')");
    ohjaaOhajuspaneeliin($otiedotus, "&joukkue=" . $joukkue);
}

//muokkaa tiedotusta
function muokkaaTiedotusta($yhteys) {
    global $error, $otiedotus, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia muokata joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " tiedotusta.";
        siirry("eioikeuksia.php");
    }

    $tiedotuksetid = mysql_real_escape_string($_GET['tiedotuksetid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM tiedotukset t, joukkueet j WHERE joukkueetID=j.id AND t.id='" . $tiedotuksetid . "' AND j.id='" . $joukkue . "' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSSION['virhe'] = "Virhe muokattaessa joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " tiedotusta.";
        siirry("virhe.php");
    }

    $eivanhanemisaikaa = mysql_real_escape_string($_POST['eivanhenemisaikaa']);
    $paiva = mysql_real_escape_string($_POST['paiva']);
    $kuukausi = mysql_real_escape_string($_POST['kuukausi']);
    $vuosi = mysql_real_escape_string($_POST['vuosi']);
    $aikaleima = mktime(0, 0, 0, $kuukausi, $paiva, $vuosi);
    $tiedotus = trim($_POST['tiedotus']);
    $vanhenemisaika = $vuosi . "-" . $kuukausi . "-" . $paiva . " 0:0:0";
    if ($aikaleima < (time() - 24 * 60 * 60) && !$eivanhanemisaikaa) {
        $error = "Vanhenemis aika on menneisyydessä<br />";
    }
    if ($tiedotus == "") {
        $error .= "Tiedotus kenttä on tyhjä.";
    }
    if (!empty($error)) {
        return false;
    }
    $tiedotus = str_replace("\r", "", $tiedotus);
    $tiedotus = str_replace("\n", "<br />", $tiedotus);
    $tiedotus = stripslashes($tiedotus);
    $tiedotus = mysql_real_escape_string($tiedotus);
    $aika = time();
    $kirjoitusaika = date("Y-m-d H:i", $aika);
    kysely($yhteys, "UPDATE tiedotukset SET tiedotus='" . $tiedotus . "', kirjoitusaika='" . $kirjoitusaika . "', ".
            "vanhenemisaika='" . $vanhenemisaika . "', eivanhenemisaikaa='" . $eivanhanemisaikaa . "' WHERE id=" . $tiedotuksetid);
    ohjaaOhajuspaneeliin($otiedotus, "&joukkue=".$joukkue);
}

//poista tiedotus
function poistaTiedotus($yhteys) {
    global $otiedotus, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia poistaa joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " tiedotusta.";
        siirry("eioikeuksia.php");
    }
    $tiedotuksetid = mysql_real_escape_string($_GET['tiedotuksetid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM tiedotukset t, joukkueet j WHERE joukkueetID=j.id AND t.id='" . $tiedotuksetid . "' AND j.id='".$joukkue."' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe tiedotusta poistettaessa";
        siirry("virhe.php");
    }

    kysely($yhteys, "DELETE FROM tiedotukset WHERE id='" . $tiedotuksetid . "'");
    ohjaaOhajuspaneeliin($otiedotus, "&joukkue=".$joukkue);
}

?>

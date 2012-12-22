<?php
//luo uusi sarjataulukko joukkueelle
function luoSarjataulukko($yhteys) {
    global $error, $osarjataulukko;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue) || !tarkistaOnkoJoukkueNykysellaKaudella($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia luoda uutta sarjataulukko joukkueelle " . haeJoukkueenNimi($yhteys, $joukkue) . ".";
        siirry("eioikeuksia.php");
    }
    $nimi = mysql_real_escape_string(trim($_POST['nimi']));
    $oletus = mysql_real_escape_string($_POST['oletus']);
    if (empty($nimi)) {
        $error = "Et antanut sarjataulukolle nimeä";
        return false;
    }
    if ($oletus)
        kysely($yhteys, "UPDATE sarjataulukkoryhmat SET oletus='0' WHERE joukkueetID='" . $joukkue . "' AND oletus='1'");
    kysely($yhteys, "INSERT INTO sarjataulukkoryhmat (nimi, joukkueetID, oletus) VALUES ('" . $nimi . "', '" . $joukkue . "', '" . $oletus . "')");
    ohjaaOhajuspaneeliin($osarjataulukko, "&joukkue=".$joukkue);
}
//lisää joukkue sarjataulukkoon
function lisaaJoukkueSarjataulukkoon($yhteys) {
    global $error, $osarjataulukko, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia lisätä joukkueita joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " sarjataulukkoon.";
        siirry("eioikeuksia.php");
    }
    $sarjataulukkoryhmatid = mysql_real_escape_string($_GET['sarjataulukkoryhmatid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM sarjataulukkoryhmat sr, joukkueet j ".
            "WHERE joukkueetID=j.id AND j.id='".$joukkue."' AND sr.id='" . $sarjataulukkoryhmatid . "' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe joukkuetta lisätessä joukkueen ".haeJoukkueenNimi($yhteys, $joukkue)." sarjataulukkoon.";
        siirry("virhe.php");
    }
    $nimi = mysql_real_escape_string(trim($_POST['nimi']));
    if (empty($nimi)) {
        $error = "Et antanut nimeä joukkueelle";
        return false;
    }
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM sarjataulukot WHERE sarjataulukkoryhmatID='" . $sarjataulukkoryhmatid . "' ORDER BY jarjestysnumero DESC LIMIT 0,1");
    $tulos = mysql_fetch_array($kysely);
    $jarjestysnumero = $tulos['jarjestysnumero'] + 1;

    kysely($yhteys, "INSERT INTO sarjataulukot (joukkue, O, V, T, H, TM, PM, P, jarjestysnumero, sarjataulukkoryhmatID) " .
            "VALUES ('" . $nimi . "', '0', '0', '0', '0', '0', '0', '0', '".$jarjestysnumero."', '" . $sarjataulukkoryhmatid . "')");
    ohjaaOhajuspaneeliin($osarjataulukko, "&joukkue=" . $joukkue . "&mode=muokkaa&sarjataulukkoryhmatid=" . $sarjataulukkoryhmatid);
}

//poista joukkue sarjataulukosta
function poistaJoukkueSarjataulukosta($yhteys) {
    global $osarjataulukko, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia poistaa joukkueita joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " sarjataulukkosta.";
        siirry("eioikeuksia.php");
    }
    $sarjataulukkoryhmatid = mysql_real_escape_string($_GET['sarjataulukkoryhmatid']);
    $poistettavaid = mysql_real_escape_string($_POST['poistettavaid']);
    $kysely = kysely($yhteys, "SELECT s.jarjestysnumero jarjestysnumero FROM sarjataulukkoryhmat sr, sarjataulukot s, joukkueet j ".
            "WHERE sr.id=sarjataulukkoryhmatID AND joukkueetID=j.id AND j.id='".$joukkue."' AND s.id='" . $poistettavaid . "' AND sr.id='".$sarjataulukkoryhmatid."' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe joukkuetta poistettaessa joukkueen ".haeJoukkueenNimi($yhteys, $joukkue). "sarjataulukosta";
        siirry("virhe.php");
    }
    $i = $tulos['jarjestysnumero'];

    kysely($yhteys, "DELETE FROM sarjataulukot WHERE id='" . $poistettavaid . "'");
    kysely($yhteys, "UPDATE sarjataulukot SET jarjestysnumero=jarjestysnumero-1 WHERE jarjestysnumero>'" . $i . "' AND sarjataulukkoryhmatID='" . $sarjataulukkoryhmatid . "'");
    ohjaaOhajuspaneeliin($osarjataulukko, "&joukkue=" . $joukkue . "&mode=muokkaa&sarjataulukkoryhmatid=" . $sarjataulukkoryhmatid);
}

//poista sarjataulukko
function poistaSarjataulukko($yhteys) {
    global $osarjataulukko, $kausi;
    $joukkue = $_GET['joukkue'];
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia poistaa joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " sarjataulukkoa.";
        siirry("eioikeuksia.php");
    }
    $sarjataulukkoryhmatid = mysql_real_escape_string($_GET['sarjataulukkoryhmatid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM sarjataulukkoryhmat sr, joukkueet j ".
            "WHERE joukkueetID=j.id AND j.id='".$joukkue."' AND sr.id='" . $sarjataulukkoryhmatid . "' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe joukkueen ".haeJoukkueenNimi($yhteys, $joukkue)." sarjataulukkoa poistettaessa.";
        siirry("virhe.php");
    }
    kysely($yhteys, "DELETE FROM sarjataulukot WHERE sarjataulukkoryhmatID='" . $sarjataulukkoryhmatid . "'");
    kysely($yhteys, "DELETE FROM sarjataulukkoryhmat WHERE id='" . $sarjataulukkoryhmatid . "'");
    ohjaaOhajuspaneeliin($osarjataulukko, "&joukkue=" . $joukkue);
}

//lisää pisteitä joukkueelle
function muokkaaSarjataulukko($yhteys) {
    global $error, $osarjataulukko, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia lisätä pisteitä joukkueille joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " sarjataulukossa.";
        siirry("eioikeuksia.php");
    }
    $sarjataulukkoryhmatid = mysql_real_escape_string($_GET['sarjataulukkoryhmatid']);
    $nimi = mysql_real_escape_string($_POST['nimi']);
    $oletus = mysql_real_escape_string($_POST['oletus']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM sarjataulukkoryhmat sr, joukkueet j ".
            "WHERE joukkueetID=j.id AND j.id='".$joukkue."' AND sr.id='" . $sarjataulukkoryhmatid . "' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe lisätessä pisteitä joukkueen ".haeJoukkueenNimi($yhteys, $joukkue)." sarjataulukkoon.";
        siirry("virhe.php");
    }
    muokkaaSarjataulukonNimiJaOletus($yhteys, $joukkue, $sarjataulukkoryhmatid, $nimi, $oletus);
    $kysely = kysely($yhteys, "SELECT sarjataulukot.id AS id FROM sarjataulukkoryhmat, sarjataulukot WHERE sarjataulukkoryhmat.id=sarjataulukkoryhmatID AND ".
            "sarjataulukkoryhmat.id='" . $sarjataulukkoryhmatid . "' AND joukkueetID='" . $joukkue . "'");

    while ($tulos = mysql_fetch_array($kysely)) {
        $joukkuenimi = $_POST['joukkue_' . $tulos['id']];
        $ottelut = $_POST['ottelut_' . $tulos['id']];
        $voitot = $_POST['voitot_' . $tulos['id']];
        $tasapelit = $_POST['tasapelit_' . $tulos['id']];
        $haviot = $_POST['haviot_' . $tulos['id']];
        $tm = $_POST['tm_' . $tulos['id']];
        $pm = $_POST['pm_' . $tulos['id']];
        $pisteet = $_POST['pisteet_' . $tulos['id']];
        kysely($yhteys, "UPDATE sarjataulukot SET joukkue='" . $joukkuenimi . "', O='" . $ottelut . "', V='" . $voitot . "', T='" . $tasapelit . "', ".
                "H='" . $haviot . "', TM='" . $tm . "', PM='" . $pm . "', P='" . $pisteet . "' WHERE id='" . $tulos['id'] . "'");
    }
    if(empty($error)){
        ohjaaOhajuspaneeliin($osarjataulukko, "&joukkue=".$joukkue."&mode=muokkaa&sarjataulukkoryhmatid=".$sarjataulukkoryhmatid);
    }
    return false;
}



//muokkaa sarjataulukon nimeä, case 45
function muokkaaSarjataulukonNimiJaOletus($yhteys, $joukkue, $sarjataulukkoryhmatid, $nimi, $oletus) {
    global $error;
   
    if (empty($nimi)) {
        $error = "Nimi kenttä on tyhjä";
        return false;
    }
    if ($oletus)
        kysely($yhteys, "UPDATE sarjataulukkoryhmat SET oletus='0' WHERE joukkueetID='" . $joukkue . "' AND oletus='1'");
    kysely($yhteys, "UPDATE sarjataulukkoryhmat SET nimi='" . $nimi . "', oletus='" . $oletus . "' WHERE id='" . $sarjataulukkoryhmatid . "'");
    return true;
}
/**
 * Siirtää post:lla lähetetyn siirrettavaid vastaavan joukkueen yhden ylös tai alaspäin riippuen valitusta suunnasta
 * 
 */
function siirraJoukkuettaSarjataulukossa($yhteys) {
    global $kausi, $error, $siirry, $osarjataulukko;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia muokata joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . "sarjataulukkoa.";
        if($siirry)
            siirry("eioikeuksia.php");
        $error = $_SESSION['eioikeuksia'];
        return false;
    }
    $sarjataulukkoryhmatid = mysql_real_escape_string($_GET['sarjataulukkoryhmatid']);
    $siirrettavaid = mysql_real_escape_string($_POST['siirrettavaid']);
    $suunta = mysql_real_escape_string($_POST['suunta']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM sarjataulukkoryhmat sr, joukkueet j ".
            "WHERE joukkueetID=j.id AND j.id='".$joukkue."' AND sr.id='" . $sarjataulukkoryhmatid . "' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe siirrettäessä joukkuetta joukkueen ".haeJoukkueenNimi($yhteys, $joukkue)." sarjataulukossa.";
        if($siirry)
            siirry("virhe.php");
        $error = $_SESSIOn['virhe'];
        return false;
    }
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM sarjataulukot WHERE sarjataulukkoryhmatID='" . $sarjataulukkoryhmatid . "' ORDER BY jarjestysnumero DESC LIMIT 0,1");
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
        kysely($yhteys, "UPDATE sarjataulukot SET jarjestysnumero=jarjestysnumero+1 WHERE jarjestysnumero='" . ($i - 1) . "' AND sarjataulukkoryhmatID='" . $sarjataulukkoryhmatid . "'");
        kysely($yhteys, "UPDATE sarjataulukot SET jarjestysnumero=jarjestysnumero-1 WHERE id='" . $siirrettavaid . "' AND sarjataulukkoryhmatID='" . $sarjataulukkoryhmatid . "'");
    } else {
        kysely($yhteys, "UPDATE sarjataulukot SET jarjestysnumero=jarjestysnumero-1 WHERE jarjestysnumero='" . ($i + 1) . "' AND sarjataulukkoryhmatID='" . $sarjataulukkoryhmatid . "'");
        kysely($yhteys, "UPDATE sarjataulukot SET jarjestysnumero=jarjestysnumero+1 WHERE id='" . $siirrettavaid . "' AND sarjataulukkoryhmatID='" . $sarjataulukkoryhmatid . "'");
    }
    if($siirry)
        ohjaaOhajuspaneeliin($osarjataulukko, "&joukkue=".$joukkue."&mode=muokkaa&sarjataulukkoryhmatid=".$sarjataulukkoryhmatid);
    return true;
}

?>

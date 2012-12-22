<?php
function luoPeliryhma($yhteys) {
    global $error, $opelit;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue) || !tarkistaOnkoJoukkueNykysellaKaudella($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lis�t� peliryhm�� " . haeJoukkueenNimi($joukkue) . " joukkueeseen";
        siirry("eioikeuksia.php");
    }
    $nimi = mysql_real_escape_string(trim($_POST['nimi']));
    $oletus = mysql_real_escape_string($_POST['oletus']);
    if (empty($nimi)) {
        $error = "Et antanut pelilistalle nime�.";
        return false;
    }
    if ($oletus)
        kysely($yhteys, "UPDATE peliryhmat SET oletus='0' WHERE joukkueetID='" . $joukkue . "' AND oletus='1'");
    kysely($yhteys, "INSERT INTO peliryhmat (nimi,joukkueetID,oletus) VALUES ('" . $nimi . "', '" . $joukkue . "', '" . $oletus . "')");
    ohjaaOhajuspaneeliin($opelit, "&joukkue=" . $joukkue);
}

function poistaPeliryhma($yhteys) {
    global $opelit, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa joukkueen " . haeJoukkueenNimi($joukkue) . " peliryhm��.";
        siirry("eioikeuksia.php");
    }
    $peliryhmatid = mysql_real_escape_string($_GET['peliryhmatid']);
    
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM peliryhmat pr, joukkueet j WHERE joukkueetID=j.id AND pr.id='" . $peliryhmatid . "' AND j.id='".$joukkue."' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe poistettaessa joukkueen ".haeJoukkueenNimi($yhteys, $joukkue)." peliryhm��.";
        siirry("virhe.php");
    }

    kysely($yhteys, "DELETE FROM pelit WHERE peliryhmatID='" . $peliryhmatid . "'");
    kysely($yhteys, "DELETE FROM peliryhmat WHERE id='" . $peliryhmatid . "'");
    ohjaaOhajuspaneeliin($opelit, "&joukkue=" . $joukkue);
}

function lisaaPeli($yhteys) {
    global $error, $opelit;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lis�t� peli� joukkueen " . haeJoukkueenNimi($joukkue) . " peliryhm��n.";
        siirry("eioikeuksia.php");
    }
    $peliryhmatid = mysql_real_escape_string($_GET['peliryhmatid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM peliryhmat WHERE joukkueetID='" . $joukkue . "' AND id='" . $peliryhmatid . "'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe peli� lis�tt�ess� joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " peliryhm��n.";
        siirry("virhe.php");
    }
    $vastustaja = mysql_real_escape_string(trim($_POST['vastustaja']));
    $kv = mysql_real_escape_string(trim($_POST['kv']));
    $kotiturnaus = mysql_real_escape_string($_POST['kotiturnaus']);
    $paikka = mysql_real_escape_string(trim($_POST['paikka']));
    $paiva = mysql_real_escape_string($_POST['paiva']);
    $kuukausi = mysql_real_escape_string($_POST['kuukausi']);
    $vuosi = mysql_real_escape_string($_POST['vuosi']);
    $tunti = mysql_real_escape_string($_POST['tunti']);
    $minuutti = mysql_real_escape_string($_POST['minuutti']);
    $paivamaara = $vuosi . "-" . $kuukausi . "-" . $paiva . " " . $tunti . ":" . $minuutti . ":00";
    if (empty($vastustaja))
        $error .= "Vastustaja kentt� on tyhj�.<br />";
    if (empty($paikka))
        $error .= "Pelipaikka kentt� on tyhj�.<br />";
    if($kv != "koti" && $kv != "vieras"){
        $error .= "Et valinnut onko Remix koti vai vieras joukkue.<br />";
    }
    if (!empty($error))
        return false;
    $kv = ($kv == "koti"?1:0);
    $kysely = kysely($yhteys, "INSERT INTO pelit (vastustaja, aika, koti, pelattu, pelipaikka, kotiturnaus, peliryhmatID) " .
            "VALUES ('" . $vastustaja . "', '" . $paivamaara . "', '" . $kv . "','0', '" . $paikka . "', '" . $kotiturnaus . "', '" . $peliryhmatid . "')");
    ohjaaOhajuspaneeliin($opelit, "&joukkue=" . $joukkue . "&mode=muokkaa&peliryhmatid=" . $peliryhmatid);
}

function muokkaaPelia($yhteys) {
    global $error, $opelit, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata pelin tietoja joukkueen " . haeJoukkueenNimi($joukkue) . " peliryhm�ss�.";
        siirry("eioikeuksia.php");
    }
    $peliryhmatid = mysql_real_escape_string($_GET['peliryhmatid']);
    $pelitid = mysql_real_escape_string($_GET['pelitid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM peliryhmat pr, pelit p, joukkueet j " .
            "WHERE peliryhmatID=pr.id AND joukkueetID=j.id AND j.id='" . $joukkue . "' AND pr.id='" . $peliryhmatid . "' AND p.id='" . $pelitid . "' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe muokatessa pelin tietoja joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " peliryhm�ss�.";
        siirry("virhe.php");
    }
    $kotiturnaus = mysql_real_escape_string($_POST['kotiturnaus']);
    $vastustaja = mysql_real_escape_string(trim($_POST['vastustaja']));
    $kv = mysql_real_escape_string(trim($_POST['kv']));
    $paikka = mysql_real_escape_string(trim($_POST['paikka']));
    $paiva = mysql_real_escape_string($_POST['paiva']);
    $kuukausi = mysql_real_escape_string($_POST['kuukausi']);
    $vuosi = mysql_real_escape_string($_POST['vuosi']);
    $tunti = mysql_real_escape_string($_POST['tunti']);
    $minuutti = mysql_real_escape_string($_POST['minuutti']);
    $paivamaara = $vuosi . "-" . $kuukausi . "-" . $paiva . " " . $tunti . ":" . $minuutti . ":00";

    if (empty($vastustaja))
        $error .= 'Vastustaja kentt� on tyhj�<br />';
    if (empty($paikka))
        $error .= 'Pelipaikka kentt� on tyhj�<br />';
    if($kv != "koti" && $kv != "vieras"){
        $error .= "Et valinnut onko Remix koti vai vieras joukkue.<br />";
    }
    if (!empty($error)) {
        return false;
    }
    $kv = ($kv == "koti"?1:0);
    kysely($yhteys, "UPDATE pelit SET vastustaja='" . $vastustaja . "', aika='" . $paivamaara . "', koti='" . $kv . "', kotiturnaus='" . $kotiturnaus . "', pelipaikka='" . $paikka . "' ".
            "WHERE id='" . $pelitid . "'");
    ohjaaOhajuspaneeliin($opelit, "&joukkue=" . $joukkue . "&mode=muokkaapelia&peliryhmatid=" . $peliryhmatid."&pelitid=".$pelitid);
}

function poistaPeli($yhteys) {
    global $opelit;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata pelin tietoja joukkueen " . haeJoukkueenNimi($joukkue) . " peliryhmass�.";
        siirry("eioikeuksia.php");
    }
    $peliryhmatid = mysql_real_escape_string($_GET['peliryhmatid']);
    $pelitid = mysql_real_escape_string($_GET['pelitid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM peliryhmat pr,pelit " .
            "WHERE peliryhmatID=pr.id AND joukkueetID='" . $joukkue . "' AND pr.id='" . $peliryhmatid . "' AND pelit.id='" . $pelitid . "'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe poistettaessa peli� joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " peliryhm�st�.";
        siirry("virhe.php");
    }
    kysely($yhteys, "DELETE FROM pelit WHERE id='" . $pelitid . "'");
    ohjaaOhajuspaneeliin($opelit, "&joukkue=" . $joukkue."&mode=muokkaa&peliryhmatid=".$peliryhmatid);
}

function lisaaTulos($yhteys) {
    global $opelit, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lis�t� tulosta joukkueen " . haeJoukkueenNimi($joukkue) . " peliin.";
        siirry("eioikeuksia.php");
    }
    $peliryhmatid = mysql_real_escape_string($_GET['peliryhmatid']);
    $pelitid = mysql_real_escape_string($_GET['pelitid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM peliryhmat pr,pelit p, joukkueet j " .
            "WHERE peliryhmatID=pr.id AND joukkueetID=j.id AND j.id='" . $joukkue . "' AND pr.id='" . $peliryhmatid . "' AND p.id='" . $pelitid . "' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe tulosta lis�tess� joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " peliin.";
        siirry("virhe.php");
    }
    $kotimaalit = mysql_real_escape_string(trim($_POST['kotimaalit']));
    $vierasmaalit = mysql_real_escape_string(trim($_POST['vierasmaalit']));

    kysely($yhteys, "UPDATE pelit SET kotimaalit='" . $kotimaalit . "', vierasmaalit='" . $vierasmaalit . "', pelattu='1' WHERE id='" . $pelitid . "'");
    ohjaaOhajuspaneeliin($opelit, "&joukkue=" . $joukkue . "&mode=muokkaapelia&peliryhmatid=" . $peliryhmatid."&pelitid=".$pelitid);
}

//poista tulos, case 24
function poistaTulos($yhteys) {
    global $opelit, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa tulosta joukkueen " . haeJoukkueenNimi($joukkue) . " pelist�.";
        siirry("eioikeuksia.php");
    }
    $peliryhmatid = mysql_real_escape_string($_GET['peliryhmatid']);
    $pelitid = mysql_real_escape_string($_GET['pelitid']);

    $kysely = kysely($yhteys, "SELECT joukkueetID FROM peliryhmat pr,pelit p, joukkueet j " .
            "WHERE peliryhmatID=pr.id AND joukkueetID=j.id AND j.id='" . $joukkue . "' AND pr.id='" . $peliryhmatid . "' AND p.id='" . $pelitid . "' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe tulosta poistettaessa joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " pelist�.";
        siirry("virhe.php");
    }

    kysely($yhteys, "UPDATE pelit SET kotimaalit='', vierasmaalit='', pelattu='0' WHERE id='" . $pelitid . "'");
    ohjaaOhajuspaneeliin($opelit, "&joukkue=" . $joukkue . "&mode=muokkaapelia&peliryhmatid=" . $peliryhmatid."&pelitid=".$pelitid);
}

function muokkaaPeliryhmanNimea($yhteys) {
    global $error, $opelit, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata joukkueen " . haeJoukkueenNimi($joukkue) . " peliryhm��.";
        siirry("eioikeuksia.php");
    }
    
    $peliryhmatid = mysql_real_escape_string($_GET['peliryhmatid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM peliryhmat pr, joukkueet j WHERE joukkueetID=j.id AND pr.id='" . $peliryhmatid . "' AND j.id='".$joukkue."' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe muokatessa joukkueen ".haeJoukkueenNimi($yhteys, $joukkue)." peliryhm��.";
        siirry("virhe.php");
    }
    
    $nimi = mysql_real_escape_string(trim($_POST['nimi']));
    $oletus = mysql_real_escape_string($_POST['oletus']);
    if (empty($nimi)) {
        $error = "Nimi kentt� on tyhj�.";
        return false;
    }
    if ($oletus)
        kysely($yhteys, "UPDATE peliryhmat SET oletus='0' WHERE joukkueetID='" . $joukkue . "' AND oletus='1'");
    kysely($yhteys, "UPDATE peliryhmat SET nimi='" . $nimi . "', oletus='" . $oletus . "' WHERE id='" . $peliryhmatid . "'");
    ohjaaOhajuspaneeliin($opelit, "&joukkue=" . $joukkue . "&mode=muokkaa&peliryhmatid=" . $peliryhmatid);
}

?>

<?php

function lisaaKuvaKategoria($yhteys) {
    global $error, $okuvat;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!(($joukkue != 0 && tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) || ($joukkue == 0 && tarkistaAdminOikeudet($yhteys, "Admin")))) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia luoda uutta kuvakategoriaa joukkueelle " . haeJoukkueenNimi($yhteys, $joukkue) . ".";
        siirry("eioikeuksia.php");
    }
    $nimi = mysql_real_escape_string(trim($_POST['nimi']));
    if (empty($nimi)) {
        $error = "*Et antanut kategorialla nimeä";
        return false;
    }
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM kuvakategoriat WHERE joukkueetID='" . $joukkue . "' ORDER BY jarjestysnumero DESC LIMIT 0,1");
    $tulos = mysql_fetch_array($kysely);
    $jarjestysnumero = $tulos['jarjsetysnumero'] + 1;

    kysely($yhteys, "INSERT INTO kuvakategoriat(nimi, joukkueetID, jarjestysnumero) VALUES('" . $nimi . "', '" . $joukkue . "', '" . $jarjestysnumero . "')");
    ohjaaOhajuspaneeliin($okuvat, "&joukkue=" . $joukkue);
}

function muokkaaKuvaKategoriaa($yhteys) {
    global $error, $kausi, $okuvat;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    $kuvakategoriatid = mysql_real_escape_string($_GET['kuvakategoriatid']);
    tarkistaOikeudetJaOlemassaOloKuvaKategorialle($yhteys, $joukkue, $kuvakategoria);
    $nimi = mysql_real_escape_string(trim($_POST['nimi']));
    if (empty($nimi)) {
        $error = "*Nimi kenttä oli tyhjä";
        return false;
    }
    kysely($yhteys, "UPDATE kuvakategoriat SET nimi='" . $nimi . "' WHERE id='" . $kuvakategoriatid . "'");
    ohjaaOhajuspaneeliin($okuvat, "&joukkue=" . $joukkue . "&mode=muokkaa&kuvakategoriatid=" . $kuvakategoriatid);
}

function poistaKuvaKategoria($yhteys) {
    global $okuvat, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    $kuvakategoriatid = mysql_real_escape_string($_GET['kuvakategoriatid']);
    tarkistaOikeudetJaOlemassaOloKuvaKategorialle($yhteys, $joukkue, $kuvakategoria);

    $kysely = kysely($yhteys, "SELECT kuva FROM kuvat WHERE kuvakategoriatID='" . $kuvakategoriatid . "'");
    while ($tulos = mysql_fetch_array($kysely)) {
        if (!unlink("/home/fbcremix/public_html/Remix/kuvat/kuvakategoriat/" . $kuvakategoriatid . "/" . $tulos['kuva'])) {
            $error = "Joitakin kuvia ei onnistuttu poistamaan.";
            return false;
        }
    }
    $kysely = kysely($yhteys, "DELETE FROM kuvat WHERE kuvakategoriatID='" . $kuvakategoriatid . "'");
    $kysely = kysely($yhteys, "DELETE FROM kuvakategoriat WHERE id='" . $kuvakategoriatid . "'");
    ohjaaOhajuspaneeliin($okuvat, "&joukkue=" . $joukkue);
}

function lisaaKuvaKuvakategoriaan($yhteys) {
    global $okuvat, $error;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    $kuvakategoriatid = mysql_real_escape_string($_GET['kuvakategoriatid']);
    tarkistaOikeudetJaOlemassaOloKuvaKategorialle($yhteys, $joukkue, $kuvakategoriatid);
    $kuvannimi = muutaTekstiHyvaksyttavaanMuotoon(basename($_FILES['kuva']['name']));
    $kuvanosoite = "/home/fbcremix/public_html/Remix/kuvat/kuvakategoriat/" . $kuvakategoriatid . "/" . $kuvannimi;
    if (file_exists($kuvanosoite)) {
        $error = "Kuva on jo olemassa.";
        return false;
    }
    $kuvateksti = mysql_real_escape_string(trim($_POST['kuvateksti']));
    if (siirraKuva($kuvanosoite, 600, 450, 0, 0, "", 0, 0)) {
        kysely($yhteys, "INSERT INTO kuvat (kuva, kuvateksti, kuvakategoriatID) VALUES('" . $kuvannimi . "', '" . $kuvateksti . "', '" . $kuvakategoriatid . "')");
        ohjaaOhajuspaneeliin($okuvat, "&joukkue=" . $joukkue . "&mode=muokkaa&kuvakategoriatid=" . $kuvakategoriatid);
    }
    return false;
}

function muokkaaKuvaKategorianKuvaa($yhteys) {
    global $okuvat, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!(($joukkue != 0 && tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) || ($joukkue == 0 && tarkistaAdminOikeudet($yhteys, "Admin"))) || !tarkistaOnkoJoukkueNykysellaKaudella($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia muokata joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " kuvakategoriaa.";
        siirry("eioikeuksia.php");
    }
    $kuvakategoriatid = mysql_real_escape_string($_GET['kuvakategoriatid']);
    $kuvatid = mysql_real_escape_string($_GET['kuvatid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM kuvat k, kuvakategoriat kk, joukkueet j " .
            "WHERE kuvakategoriatID=kk.id AND joukkueetID=j.id AND k.id='" . $kuvatid . "' AND j.id='" . $joukkue . "' AND kk.id='" . $kuvakategoriatid . "' AND kausi='" . $kausi . "'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Kuvakategoriaa ei löydy.";
        siirry("virhe.php");
    }
    $kuvateksti = mysql_real_escape_string(trim($_POST['kuvateksti']));
    kysely($yhteys, "UPDATE kuvat SET kuvateksti='" . $kuvateksti . "' WHERE kuvakategoriatID='" . $kuvakategoriatid . "' AND id='" . $kuvatid . "'");
    ohjaaOhajuspaneeliin($okuvat, "&joukkue=" . $joukkue . "&mode=muokkaakuvaa&kuvakategoriatid=" . $kuvakategoriatid . "&kuvatid=" . $kuvatid);
}

function poistaKuvaKategorianKuva($yhteys) {
    global $okuvat, $error, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!(($joukkue != 0 && tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) || ($joukkue == 0 && tarkistaAdminOikeudet($yhteys, "Admin"))) || !tarkistaOnkoJoukkueNykysellaKaudella($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia muokata joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " kuvakategoriaa.";
        siirry("eioikeuksia.php");
    }
    $kuvakategoriatid = mysql_real_escape_string($_GET['kuvakategoriatid']);
    $kuvatid = mysql_real_escape_string($_GET['kuvatid']);
    $kysely = kysely($yhteys, "SELECT kuva FROM kuvat k, kuvakategoriat kk, joukkueet j " .
            "WHERE kuvakategoriatID=kk.id AND joukkueetID=j.id AND k.id='" . $kuvatid . "' AND j.id='" . $joukkue . "' AND kk.id='" . $kuvakategoriatid . "' AND kausi='" . $kausi . "'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Kuvakategoriaa ei löydy.";
        siirry("virhe.php");
    }
    if (!unlink("/home/fbcremix/public_html/Remix/kuvat/kuvakategoriat/" . $kuvakategoriatid . "/" . $tulos['kuva'])) {
        $error = "Kuvaa ei onnistuttu poistamaan.<br />";
        return false;
    }
    kysely($yhteys, "DELETE FROM kuvat WHERE id='" . $kuvatid . "'");
    ohjaaOhajuspaneeliin($okuvat, "&joukkue=" . $joukkue . "&mode=muokkaa&kuvakategoriatid=" . $kuvakategoriatid);
}

function tarkistaOikeudetJaOlemassaOloKuvaKategorialle($yhteys, $joukkue, $kuvakategoriatid) {
    global $kausi;
    if (!(($joukkue != 0 && tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) || ($joukkue == 0 && tarkistaAdminOikeudet($yhteys, "Admin")))) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia muokata joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " kuvakategoriaa.";
        siirry("eioikeuksia.php");
    }
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM kuvakategoriat kk WHERE  joukkueetID='" . $joukkue . "' AND kk.id='" . $kuvakategoriatid . "'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Kuvakategoriaa ei löydy.";
        siirry("virhe.php");
    }
}

?>

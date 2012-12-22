<?php

//Hae tilasttoon: ei, jos ei haeta mist��n, 0 jos joukkueesta tai id, mist� tilastosta
function luoTilasto($yhteys) {
    global $error, $otilastot;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue) || !tarkistaOnkoJoukkueNykysellaKaudella($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia lis�t� joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " tilastoryhmi�.";
        siirry("eioikeuksia.php");
    }
    $nimi = mysql_real_escape_string(trim($_POST['nimi']));
    $oletus = mysql_real_escape_string($_POST['oletus']);
    $kokonaistilastoon = mysql_real_escape_string($_POST['kokonaistilastoon']);
    $haetilastoon = mysql_real_escape_string($_POST['haetilastoon']);
    if (empty($nimi)) {
        $error = "Et antanut tilastolle nime�";
        return false;
    }
    if ($oletus) {
        kysely($yhteys, "UPDATE tilastoryhmat SET oletus='0' WHERE joukkueetID='" . $joukkue . "' AND oletus='1'");
    }
    kysely($yhteys, "INSERT INTO tilastoryhmat (nimi, joukkueetID, oletus, kokonaistilastoon) " .
            "VALUES ('" . $nimi . "', '" . $joukkue . "', '" . $oletus . "', '" . $kokonaistilastoon . "')");
    if ($haetilastoon != "ei") {
        $tilastotryhmatid = mysql_insert_id();
        if (!$haetilastoon)
            kysely($yhteys, "INSERT INTO tilastot (O, RLM, RLY, RM, S, M, plusmiinus, tunnuksetID, tilastoryhmatID) " .
                    "SELECT 0, 0, 0, 0, 0, 0, 0, tunnuksetID, " . $tilastotryhmatid . " FROM pelaajat WHERE joukkueetID='" . $joukkue."'");
        else
            kysely($yhteys, "INSERT INTO tilastot (O, RLM, RLY, RM, S, M, plusmiinus, tunnuksetID, tilastoryhmatID) " .
                    "SELECT 0, 0, 0, 0, 0, 0, 0, tunnuksetID, " . $tilastotryhmatid . " FROM tilastot, tilastoryhmat tr ".
                    "WHERE tilastoryhmatID=tr.id AND joukkueetID='" . $joukkue . "' AND tr.id='" . $haetilastoon . "'");
    }
    ohjaaOhajuspaneeliin($otilastot, "&joukkue=" . $joukkue);
}

//poista tilasto, case 42
function poistaTilasto($yhteys) {
    global $otilastot, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia poistaa joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " tilastoryhm��.";
        siirry("eioikeuksia.php");
    }

    $tilastoryhmatid = mysql_real_escape_string($_GET['tilastoryhmatid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM joukkueet j, tilastoryhmat tr WHERE j.id=joukkueetID AND tr.id='" . $tilastoryhmatid . "' AND j.id='" . $joukkue . "' AND kausi='".$kausi."'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe poistettaessa joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " tilastoryhm��";
        siirry("virhe.php");
    }
    $kysely = kysely($yhteys, "DELETE FROM tilastot WHERE tilastoryhmatID='" . $tilastoryhmatid . "'");
    $kysely = kysely($yhteys, "DELETE FROM tilastoryhmat WHERE id='" . $tilastoryhmatid . "'");
    poistaTunnuksetJotkaEivatKaytossa($yhteys);
    ohjaaOhajuspaneeliin($otilastot, "&joukkue=" . $joukkue);
}

//muokkaa tilaston nime�, case 43
function muokkaaTilastoa($yhteys) {
    global $error, $otilastot, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ollut oikeuksia muokata joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " tilastoryhm��.";
        siirry("eioikeuksia.php");
    }
    $tilastoryhmatid = mysql_real_escape_string($_GET['tilastoryhmatid']);
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM joukkueet j, tilastoryhmat tr WHERE j.id=joukkueetID AND tr.id='" . $tilastoryhmatid . "' AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Virhe muokatessa joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " tilastoryhm��";
        siirry("virhe.php");
    }
    $nimi = mysql_real_escape_string(trim($_POST['nimi']));
    $oletus = mysql_real_escape_string($_POST['oletus']);
    $kokonaistilastoon = mysql_real_escape_string($_POST['kokonaistilastoon']);

    if (empty($nimi)) {
        $error = "Nimi kentt� on tyhj�.";
        return false;
    }
    if ($oletus)
        $kysely = kysely($yhteys, "UPDATE tilastoryhmat SET oletus='0' WHERE joukkueetID='" . $joukkue . "' AND oletus='1'");
    $kysely = kysely($yhteys, "UPDATE tilastoryhmat SET nimi='" . $nimi . "', oletus='" . $oletus . "', kokonaistilastoon='" . $kokonaistilastoon . "' WHERE id='" . $tilastoryhmatid . "'");
    ohjaaOhajuspaneeliin($otilastot, "&joukkue=" . $joukkue . "&mode=muokkaa&tilastoryhmatid=" . $tilastoryhmatid);
}

function lisaaPelaajaTilastoon($yhteys) {
    global $siirry, $otilastot;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    $tilastoryhmatid = mysql_real_escape_string($_GET['tilastoryhmatid']);
    $pelaajat = explode("|", mysql_real_escape_string($_POST['pelaajat']));
    if (!lisaaTilastoon($yhteys, array($tilastoryhmatid), $joukkue, $pelaajat)) {
        return false;
    }
    if ($siirry) {
        ohjaaOhajuspaneeliin($otilastot, "&joukkue=" . $joukkue . "&mode=muokkaa&tilastoryhmatid=" . $tilastoryhmatid);
    }
    return true;
}

function lisaaUusiPelaajaTilastoon($yhteys) {
    global $otilastot;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    $tilastoryhmatid = mysql_real_escape_string($_GET['tilastoryhmatid']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lis�t� pelaajia joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " tilastoon.";
        siirry("eioikeuksa.php");
    }
    $pelaaja = luoTunnus($yhteys);
    if (!$pelaaja) {
        return false;
    }
    lisaaTilastoon($yhteys, array($tilastoryhmatid), $joukkue, array($pelaaja));
    ohjaaOhajuspaneeliin($otilastot, "&joukkue=" . $joukkue . "&mode=muokkaa&tilastoryhmatid=" . $tilastoryhmatid);
}

function lisaaTilastoon($yhteys, $tilastoryhmatidt, $joukkue, $pelaajat) {
    global $error, $siirry;
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lis�t� henkiloita joukkueen" . haeJoukkueenNimi($yhteys, $joukkue) . " tilastoryhmaan.";
        if ($siirry)
            siirry("eioikeuksia.php");
        $error = $_SESSION['eioikeuksia'];
        return false;
    }
    $lisatty = false;
    $sql = "INSERT INTO tilastot (O, RLM, RLY, RM, S, M, plusmiinus, tunnuksetID, tilastoryhmatID) VALUES ";
    for ($u = 0; $u < count($pelaajat); $u++) {
        $pelaaja = $pelaajat[$u];
        if (!tarkistaTunnuksenOlemassaOlo($yhteys, $pelaaja)) {
            $_SESSION['virhe'] = "Pelaaja ei onnistuttu lis��m��n joukkueeseen.";
            if (siirry)
                siirry("virhe.php");
            $error = $_SESSION['virhe'];
            return false;
        }
        for ($i = 0; $i < count($tilastoryhmatidt); $i++) {
            $tilastoryhmatid = mysql_real_escape_string($tilastoryhmatidt[$i]);
            if (tarkistaOnkoPelaajaJoTilastossa($yhteys, $pelaaja, $tilastoryhmatid)) {
                $error .= "Pelaaja " . haeKayttajanNimi($yhteys, $pelaaja) . " on jo " . haeTilastonNimi($yhteys, $tilastoryhmatid) . " tilastossa.<br />";
                continue;
            }
            $sql .= "('0', '0', '0', '0', '0', '0', '0', '" . $pelaaja . "', '" . $tilastoryhmatid . "'), ";
            $lisatty = true;
        }
        if (!$lisatty) {
            $_SESSION['virhe'] = "Tilastoja ei l�ytynyt.";
            if ($siirry) {
                siirry("virhe.php");
            }
            $error .= $_SESSION['virhe'];
            return false;
        }
    }
    $sql = substr($sql, 0, -2);
    if ($lisatty) {
        kysely($yhteys, $sql);
    }
    return true;
}

function poistaPelaajaTilastosta($yhteys) {
    global $otilastot, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    $pelaajatid = mysql_real_escape_string($_GET['pelaajatid']);
    $tilastoryhmatid = mysql_real_escape_string($_GET['tilastoryhmatid']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa pelaajia joukkueen" . haeJoukkueenNimi($yhteys, $joukkue) . " tilastoryhm�st�.";
        siirry("eioikeuksia.php");
    }
    if (!tarkistaTunnuksenOlemassaOlo($yhteys, $pelaajatid)) {
        $_SESSION['virhe'] = "Pelaajan tunnusta ei l�ytynyt.";
        siirry("virhe.php");
    }
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM joukkueet j, tilastoryhmat tr, tilastot " .
            "WHERE j.id=joukkueetID AND tr.id=tilastoryhmatID AND tr.id='" . $tilastoryhmatid . "' AND j.id='" . $joukkue . "' AND tunnuksetID='" . $pelaajatid . "' AND kausi='" . $kausi . "'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Pelaajaa ei l�ytynyt joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " " . haeTilastonNimi($yhteys, $tilastoryhmatid) . " tilastosta.";
        siirry("virhe.php");
    }
    kysely($yhteys, "DELETE FROM tilastot WHERE tilastoryhmatID='" . $tilastoryhmatid . "' AND tunnuksetID='" . $pelaajatid . "'");
    poistaTunnuksetJotkaEivatKaytossa($yhteys);
    ohjaaOhajuspaneeliin($otilastot, "&joukkue=" . $joukkue . "&mode=muokkaa&tilastoryhmatid=" . $tilastoryhmatid);
}

function muokkaaTilastonPelaaja($yhteys) {
    global $otilastot, $kausi;
    $joukkue = mysql_real_escape_string($_GET['joukkue']);
    if (!tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lis�t� pisteit� joukkueen " . haeJoukkueenNimi($yhteys, $joukkue) . " tilastoryhm��n.";
        siirry("eioikeuksia.php");
    }
    $pelaajatid = mysql_real_escape_string($_GET['pelaajatid']);
    if (!tarkistaTunnuksenOlemassaOlo($yhteys, $pelaajatid)) {
        $_SESSION['virhe'] = "Pelaajaa ei l�ytynyt.";
        siirry("virhe.php");
    }
    $tilastoryhmatid = mysql_real_escape_string($_GET['tilastoryhmatid']);
    if (!tarkistaTilastonOlemassaOlo($yhteys, $tilastoryhmatid)) {
        $_SESSION['virhe'] = "Tilastoa ei l�ytynyt.";
        siirry("virhe.php");
    }
    $kysely = kysely($yhteys, "SELECT O, RLM, RLY, RM, S, M, plusmiinus FROM joukkueet j, tilastoryhmat tr, tilastot " .
            "WHERE j.id=joukkueetID AND tr.id=tilastoryhmatID AND tr.id='" . $tilastoryhmatid . "' AND j.id='" . $joukkue . "' AND tunnuksetID='" . $pelaajatid . "' AND kausi='" . $kausi . "'");
    if (!$tulos = mysql_fetch_array($kysely)) {
        $_SESSION['virhe'] = "Pelaajaa ei l�ytynyt joukkueen" . haeJoukkueenNimi($yhteys, $joukkue) . " " . haeTilastonNimi($yhteys, $tilastoryhmatid) . " tilastosta.";
        siirry("virhe.php");
    }
    muokkaaTunnuksenTietoja($yhteys, $pelaajatid);
    //haetaan k�ytt�j�n l�hett�m�t tiedot ja lis�t��n niihin vanhat
    $ottelut = mysql_real_escape_string($_POST['o']) + $tulos['O'];
    $rangaistuslaukausm = mysql_real_escape_string($_POST['rlm']) + $tulos['RLM'];
    $rangaistuslaukausy = mysql_real_escape_string($_POST['rly']) + $tulos['RLY'];
    $rangaistusminuutit = mysql_real_escape_string($_POST['rm']) + $tulos['RM'];
    $syotot = mysql_real_escape_string($_POST['s']) + $tulos['S'];
    $maalit = mysql_real_escape_string($_POST['m']) + $tulos['M'];
    $plusmiinus = mysql_real_escape_string($_POST['plusmiinus']) + $tulos['plusmiinus'];
    kysely($yhteys, "UPDATE tilastot SET O='" . $ottelut . "', RLM='" . $rangaistuslaukausm . "', RLY='" . $rangaistuslaukausy . "', RM='" . $rangaistusminuutit . "', " .
            "S='" . $syotot . "', M='" . $maalit . "', plusmiinus='" . $plusmiinus . "' WHERE tilastoryhmatID='" . $tilastoryhmatid . "' AND tunnuksetID='" . $pelaajatid . "'");
    ohjaaOhajuspaneeliin($otilastot, "&joukkue=" . $joukkue . "&mode=muokkaapelaajaa&tilastoryhmatid=" . $tilastoryhmatid . "&pelaajatid=" . $pelaajatid);
}

?>

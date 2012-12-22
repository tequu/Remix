<?php



//Uusi keskustelualue ryhm‰
function uusiKeskustelualueRyhma($yhteys) {
    global $okeskustelualueryhma;
    if (!tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        $_SESSION['eioikeuksa'] = "Sinulla ei ole oikeuksia luoda keskustelualue ryhmi‰.";
        siirry("eioikeuksia.php");
    }
    global $error;
    $otsikko = mysql_real_escape_string(trim($_POST['otsikko']));
    //Tarkistetaan ett‰ saatiin nimi
    if (empty($otsikko)) {
        $error = "Et antanut nime‰.";
        return;
    }
    //Luodaan uusi ryhm‰
    kysely($yhteys, "INSERT INTO keskustelualueryhmat(otsikko) VALUES ('" . $otsikko . "')");
    ohjaaOhajuspaneeliin($okeskustelualueryhma, "");
}

//Uusi keskustelualue
function uusiKeskustelualue($yhteys) {
    global $error, $okeskustelualue;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia luoda keskustelualueita.";
        siirry("eioikeuksia.php");
    }
    $keskustelualueryhmatid = mysql_real_escape_string($_GET['keskustelualueryhmatid']);
    $nimi = mysql_real_escape_string(trim($_POST['nimi']));
    $kuvaus = mysql_real_escape_string(trim($_POST['kuvaus']));
    $julkinen = mysql_real_escape_string($_POST['julkinen']);
    //Tarkistetaan ettei mik‰‰n ollut tyhj‰
    if (empty($nimi)) {
        $error = "Et antanut nime‰<br />";
    }
    if (empty($kuvaus)) {
        $error .= "Et antanut kuvausta<br />";
    }
    if (!empty($error)) {
        return false;
    }
    //Tarkistetaan ettei yritetty luoda Joukkueet otsikon alle
    $kysely = kysely($yhteys, "SELECT otsikko FROM keskustelualueryhmat WHERE id='" . $keskustelualueryhmatid . "'");
    while ($tulos = mysql_fetch_array($kysely)) {
        if ($tulos['otsikko'] == "Joukkueet") {
            $error = "Luodaksesi uuden keskustelualueen t‰h‰n ryhm‰‰n sinun t‰ytyy luoda uusi joukkue.";
            return;
        }
    }

    kysely($yhteys, "INSERT INTO keskustelualueet (nimi, kuvaus, keskustelualueryhmatID, julkinen) ".
            "VALUES ('" . $nimi . "', '" . $kuvaus . "', '" . $keskustelualueryhmatid . "', '" . $julkinen . "')");
    ohjaaOhajuspaneeliin($okeskustelualue, "&mode=muokkaa&keskustelualueryhmatid=".$keskustelualueryhmatid);
}

//Muokkaa keskustelualueen ryhm‰n nime‰
function muokkaaKeskustelualueenRyhmanNimea($yhteys) {
    global $error, $okeskustelualueryhma;
    if (!tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata ryhm‰‰.";
        siirry("eioikeuksia.php");
    }
    $ryhma = mysql_real_escape_string($_POST['ryhma']);
    $otsikko = mysql_real_escape_string(trim($_POST['otsikko']));
    $kysely = kysely($yhteys, "SELECT otsikko FROM keskustelualueryhmat WHERE id='" . $ryhma . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        //Tarkistetaan ettei yritet‰ muokata kiellettyj‰ keskustelualueita
        if ($tulos['otsikko'] == "Joukkueet") {
            $error = "Yleist‰ ja Joukkueet rymi‰ ei voida muokata.";
            return false;
        }
    }
    //Tarkistetaan ett‰ saatiin nimi
    if (empty($otsikko)) {
        $error = "Et antanut nime‰<br />";
        return;
    }
    kysely($yhteys, "UPDATE keskustelualueryhmat SET otsikko='" . $otsikko . "' WHERE id='" . $ryhma . "'");
    ohjaaOhajuspaneeliin($okeskustelualueryhma, "");
}

//Poista keskustelualue ryhm‰
function poistaKeskustelualueRyhma($yhteys) {
    global $error, $okeskustelualueryhma;
    if (!tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        $_SESSION['eioikeuksa'] = "Sinulla ei ole oikeuksia poistaa keskustelualue ryhmi‰.";
        siirry("eioikeuksia.php");
    }
    $ryhma = mysql_real_escape_string($_POST['ryhma']);
    $kysely = kysely($yhteys, "SELECT otsikko FROM keskustelualueryhmat WHERE id='" . $ryhma . "'");
    $tulos = mysql_fetch_array($kysely);
    if ($tulos['otsikko'] == "Joukkueet") {
        $error = "Joukkueet ryhm‰‰ ei voida poistaa.";
        return;
    }
    //Tarkistetaan ett‰ kaikki keskustelualueet on poistettu ryhm‰n alta ennen poistoa
    $kysely = kysely($yhteys, "SELECT id FROM keskustelualueet WHERE keskustelualueryhmatID='" . $ryhma . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        $error = "Kaikki ryhm‰ keskustelualueet on poistettava ennen kuin voit poistaa ryhm‰n.";
        return;
    }
    //Poistetaan
    kysely($yhteys, "DELETE FROM keskustelualueryhmat WHERE id='" . $ryhma . "'");
    ohjaaOhajuspaneeliin($okeskustelualueryhma, "");
}

//Muokkaa keskustelualueen tietoja
function muokkaaKeskustelualuetta($yhteys) {
    global $error, $okeskustelualue;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksa'] = "Sinulla ei ole oikeuksia muokata keskustelualueita.";
        siirry("eioikeuksia.php");
    }
    $keskustelualueryhmatid = mysql_real_escape_string($_GET['keskustelualueryhmatid']);
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $nimi = mysql_real_escape_string($_POST['nimi']);
    $kuvaus = mysql_real_escape_string($_POST['kuvaus']);
    $julkinen = mysql_real_escape_string($_POST['julkinen']);
    
    if (tarkistaOnkoJoukkueenKeskustelualue($yhteys, $keskustelualue)) {
        $error = "T‰t‰ keskustelualuetta ei voida muokata. Kuvausta voidaan muokata joukkueiden hallinnan kautta.<br />";
        return false;
    }
    //Tarkistetaan ettei ollut tyhji‰
    if (empty($keskustelualue)) {
        $error .= "Et valinnut keskustelualuetta.<br />";
    }
    if (empty($nimi)) {
        $error .= "Et antanut nime‰.<br />";
    }
    if (empty($kuvaus)) {
        $error .= "Et antanut kuvausta.<br />";
    }
    if (!empty($error)) {
        return false;
    }
    kysely($yhteys, "UPDATE keskustelualueet SET nimi='" . $nimi . "', kuvaus='" . $kuvaus . "', julkinen='" . $julkinen . "' WHERE id='" . $keskustelualue . "' AND keskustelualueryhmatID='" . $keskustelualueryhmatid . "'");
    ohjaaOhajuspaneeliin($okeskustelualue, "&mode=muokkaa&keskustelualueryhmatid=".$keskustelualueryhmatid);
}

//Poiste keskustelualue
function poistaKeskustelualue($yhteys) {
    global $error, $okeskustelualue;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa keskustelualueita.";
        siirry("eioikeuksia.php");
    }
    $keskustelualueryhmatid = mysql_real_escape_string($_GET['keskustelualueryhmatid']);
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $kysely = kysely($yhteys, "SELECT k.id FROM keskustelualueet k, keskustelualueryhmat kr WHERE keskustelualueryhmatID=kr.id AND k.id='".$keskustelualue."' AND kr.id='".$keskustelualueryhmatid."'");
    if(!$tulos = mysql_fetch_array($kysely)){
        $_SESSION['virhe'] = "Keskustelualuetta ei lˆytynyt.";
        siirry("virhe.php");
    }
    //Tarkistetaan ettei ollut joukkueen keskustelualue
    if (tarkistaOnkoJoukkueenKeskustelualue($yhteys, $keskustelualue)) {
        $error = "Poistaaksesi t‰m‰n keskustelualueen sinun tulee poistaa kyseinen joukkue joukkueiden hallinnasta.";
        return false;
    }
    //Poistetaan keskustelualueelta viestit, paikallaolot, tapahtumat, keskustelut ja keskustelualue
    kysely($yhteys, "DELETE FROM viestit WHERE keskustelutID IN (SELECT id keskustelutID FROM keskustelut, keskustelualuekeskustelut WHERE keskustelutID=id AND keskustelualueetID='" . $keskustelualue . "')");
    kysely($yhteys, "DELETE FROM paikallaolo WHERE tapahtumatID IN (SELECT tapahtumatID FROM keskustelut k, keskustelualuekeskustelut kk WHERE k.id=keskustelutID AND keskustelualueetID='" . $keskustelualue . "')");
    kysely($yhteys, "DELETE FROM tapahtumat WHERE id IN (SELECT tapahtumatID FROM keskustelut k, keskustelualuekeskustelut kk WHERE k.id=keskustelutID AND keskustelualueetID='" . $keskustelualue . "')");
    kysely($yhteys, "DELETE FROM keskustelut WHERE id IN (SELECT keskustelutID id FROM keskustelualuekeskustelut WHERE keskustelualueetID='" . $keskustelualue . "')");
    kysely($yhteys, "DELETE FROM keskustelualueet WHERE id='" . $keskustelualue . "' AND keskustelualueryhmatID='" . $keskustelualueryhmatid . "'");
    ohjaaOhajuspaneeliin($okeskustelualue, "&mode=muokkaa&keskustelualueryhmatid=".$keskustelualueryhmatid);
}

//Siirr‰ keskustelualue toiseen ryhm‰‰n
function siirraKeskustelualue($yhteys) {
    global $error, $okeskustelualue;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksa siirt‰‰ keskustelualueita.";
        siirry("eioikeuksia.php");
    }
    $keskustelualueryhmatid = mysql_real_escape_string($_GET['keskustelualueryhmatid']);
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $mryhma = mysql_real_escape_string($_POST['mryhma']);
    //Tarkistetaan ettei yritetty siirt‰‰ joukkueen keskustelualuetta
    if (tarkistaOnkoJoukkueenKeskustelualue($yhteys, $keskustelualue)) {
        $error = "Et voi siirt‰‰ joukkueiden keskustelualueita.";
        return false;
    }
    //Siirret‰‰n
    kysely($yhteys, "UPDATE keskustelualueet SET keskustelualueryhmatID='" . $mryhma . "' WHERE keskustelualueryhmatID='" . $keskustelualueryhmatid . "' AND id='" . $keskustelualue . "'");
    ohjaaOhajuspaneeliin($okeskustelualue, "&mode=muokkaa&keskustelualueryhmatid=".$keskustelualueryhmatid);
}

function poistaViesti($yhteys) {
    tarkistaKirjautuneenTunnus($yhteys);
    $viesti = mysql_real_escape_string(trim($_GET['viesti']));
    $sivu = mysql_real_escape_string(trim($_GET['sivu']));
    if (empty($sivu))
        $sivu = 1;
    $kysely = kysely($yhteys, "SELECT k.id kid, keskustelualueetID FROM Viestit v, Keskustelut k, Keskustelualueet_keskustelut kk WHERE v.keskustelutID=k.id AND k.id=kk.keskustelutID AND v.id='" . $viesti . "'");
    //Tarkistetaan viestin olemassa olo
    if ($tulos = mysql_fetch_array($kysely)) {
        //Tarkistetaan oikeudet poistaa viesti
        tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $tulos['keskustelualueetID']);
        kysely($yhteys, "DELETE FROM Viestit WHERE id='" . $viesti . "'");
        siirry("keskustelu.php?keskustelu=" . $tulos['kid'] . "&sivu=" . $sivu);
    }
    siirry("index.php");
}

function poistaKeskustelu($yhteys) {
    tarkistaKirjautuneenTunnus($yhteys);
    $keskustelu = mysql_real_escape_string(trim($_GET['keskustelu']));
    $sivu = $_GET['sivu'];
    if (empty($sivu))
        $sivu = 1;
    $kysely = kysely($yhteys, "SELECT keskustelualueetID FROM Keskustelualueet_keskustelut WHERE keskustelutID='" . $keskustelu . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $tulos['keskustelualueetID']);
        //Poistetaan kaikki keskusteluun liittyv‰t asiat
        kysely($yhteys, "DELETE FROM Viestit WHERE keskustelutID='" . $keskustelu . "'");
        kysely($yhteys, "DELETE FROM Paikallaolo WHERE tapahtumatID IN (SELECT tapahtumatID FROM Keskustelut WHERE id='" . $keskustelu . "')");
        kysely($yhteys, "DELETE FROM Tapahtumat WHERE id IN (SELECT tapahtumatID id FROM Keskustelut WHERE id='" . $keskustelu . "')");
        kysely($yhteys, "DELETE FROM Keskustelualueet_keskustelut WHERE keskustelutID='" . $keskustelu . "'");
        kysely($yhteys, "DELETE FROM Keskustelut WHERE id='" . $keskustelu . "'");
        siirry("keskustelualue.php?keskustelualue=" . $tulos['keskustelualueetID'] . "&sivu=" . $sivu);
    } else {
        siirry("index.php");
    }
}

?>

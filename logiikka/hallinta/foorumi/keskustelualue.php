<?php

//Lisää näkyvyys oikeudet käyttäjälle
function lisaaNakyvyysOikeudet($yhteys) {
    global $error, $okayttajat;
    if (!tarkistaAdminOikeudet($yhteys, "MasterAdmin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia lisätä oikeuksia käyttäjille.";
        siirry("eioikeuksia.php");
    }
    $kayttajatid = mysql_real_escape_string(trim($_POST['kayttajatid']));
    $keskustelualueet = $_POST['lisattavatoikeudet'];
    //Tarkistetaan tunnuksen olemassaolo
    if (!tarkistaTunnuksenOlemassaOlo($yhteys, $kayttajatid)) {
        $_SESSION['virhe'] = "Käyttäjää ei löytynyt.";
        siirry("virhe.php");
    }
    //Jos saatiin oikeuksia mitä halutaan antaa
    if (!empty($keskustelualueet)) {
        //Luodaan kaikista yksi sql-lause
        $sql = "INSERT INTO keskustelualueoikeudet (keskustelualueetID, tunnuksetID) VALUES ";
        foreach ($keskustelualueet as $keskustelualue) {
            $keskustelualue = mysql_real_escape_string($keskustelualue);
            $sql .= "('" . $keskustelualue . "', '" . $kayttajatid . "'), ";
        }
        $sql = substr($sql, 0, -2);
        kysely($yhteys, $sql);
    }
    ohjaaOhajuspaneeliin($okayttajat, "&mode=muokkaa&kayttajatid=" . $kayttajatid);
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
    //Tarkistetaan ettei mikään ollut tyhjä
    if (empty($nimi)) {
        $error = "Et antanut nimeä<br />";
    }
    if (empty($kuvaus)) {
        $error .= "Et antanut kuvausta<br />";
    }
    if (!empty($error)) {
        return false;
    }
    //Tarkistetaan ettei yritetty luoda Joukkueet otsikon alle
    $kysely = kysely($yhteys, "SELECT otsikko FROM keskustelualueryhmat WHERE id='" . $keskustelualueryhmatid . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        if ($tulos['otsikko'] == "Joukkueet") {
            $error = "Luodaksesi uuden keskustelualueen tähän ryhmään sinun täytyy luoda uusi joukkue.";
            return;
        }
    }

    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM keskustelualueet WHERE keskustelualueryhmatID='" . $keskustelualueryhmatid . "' ORDER BY jarjestysnumero DESC LIMIT 0,1");
    $tulos = mysql_fetch_array($kysely);
    kysely($yhteys, "INSERT INTO keskustelualueet (nimi, kuvaus, keskustelualueryhmatID, julkinen, jarjestysnumero) " .
            "VALUES ('" . $nimi . "', '" . $kuvaus . "', '" . $keskustelualueryhmatid . "', '" . $julkinen . "', '" . ($tulos['jarjestysnumero'] + 1) . "')");
    ohjaaOhajuspaneeliin($okeskustelualue, "&mode=muokkaa&keskustelualueryhmatid=" . $keskustelualueryhmatid);
}

//Muokkaa keskustelualueen tietoja
function muokkaaKeskustelualuetta($yhteys) {
    global $error, $okeskustelualue;

    $keskustelualueryhmatid = mysql_real_escape_string($_GET['keskustelualueryhmatid']);
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $nimi = mysql_real_escape_string($_POST['nimi']);
    $kuvaus = mysql_real_escape_string($_POST['kuvaus']);
    $julkinen = mysql_real_escape_string($_POST['julkinen']);

    if (!tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
        $_SESSION['eioikeuksa'] = "Sinulla ei ole oikeuksia muokata keskustelualueita.";
        siirry("eioikeuksia.php");
    }
    if (tarkistaOnkoJoukkueenKeskustelualue($yhteys, $keskustelualue)) {
        $error = "Tätä keskustelualuetta ei voida muokata. Kuvausta voidaan muokata joukkueiden hallinnan kautta.<br />";
        return false;
    }
    //Tarkistetaan ettei ollut tyhjiä
    if (empty($keskustelualue)) {
        $error .= "Et valinnut keskustelualuetta.<br />";
    }
    if (empty($nimi)) {
        $error .= "Et antanut nimeä.<br />";
    }
    if (empty($kuvaus)) {
        $error .= "Et antanut kuvausta.<br />";
    }
    if (!empty($error)) {
        return false;
    }
    kysely($yhteys, "UPDATE keskustelualueet SET nimi='" . $nimi . "', kuvaus='" . $kuvaus . "', julkinen='" . $julkinen . "' WHERE id='" . $keskustelualue . "' AND keskustelualueryhmatID='" . $keskustelualueryhmatid . "'");
    ohjaaOhajuspaneeliin($okeskustelualue, "&mode=muokkaa&keskustelualueryhmatid=" . $keskustelualueryhmatid);
}

//Poiste keskustelualue
function poistaKeskustelualue($yhteys) {
    global $error, $okeskustelualue;
    if (!tarkistaAdminOikeudet($yhteys, "MasterAdmin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa keskustelualueita.";
        siirry("eioikeuksia.php");
    }
    $keskustelualueryhmatid = mysql_real_escape_string($_POST['keskustelualueryhmatid']);
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    if (tarkistaKeskustelualueetOlemassaOlo($yhteys, $keskustelualue)) {
        $_SESSION['virhe'] = "Keskustelualuetta ei löytynyt.";
        siirry("virhe.php");
    }
    //Tarkistetaan ettei ollut joukkueen keskustelualue
    if (tarkistaOnkoJoukkueenKeskustelualue($yhteys, $keskustelualue)) {
        $error = "Poistaaksesi tämän keskustelualueen sinun tulee poistaa kyseinen joukkue joukkueiden hallinnasta.";
        return false;
    }
    //Poistetaan keskustelualueelta viestit, paikallaolot, tapahtumat, keskustelut ja keskustelualue
    kysely($yhteys, "DELETE FROM viestit WHERE keskustelutID IN (SELECT keskustelutID FROM keskustelualuekeskustelut WHERE keskustelualueetID='" . $keskustelualue . "')");
    kysely($yhteys, "DELETE FROM paikallaolo WHERE tapahtumatID IN (SELECT tapahtumatID FROM keskustelut k, keskustelualuekeskustelut kk WHERE k.id=keskustelutID AND keskustelualueetID='" . $keskustelualue . "')");
    kysely($yhteys, "DELETE FROM tapahtumat WHERE id IN (SELECT tapahtumatID FROM keskustelut k, keskustelualuekeskustelut kk WHERE k.id=keskustelutID AND keskustelualueetID='" . $keskustelualue . "')");
    kysely($yhteys, "DELETE FROM keskustelut WHERE id IN (SELECT keskustelutID id FROM keskustelualuekeskustelut WHERE keskustelualueetID='" . $keskustelualue . "')");
    kysely($yhteys, "DELETE FROM keskustelualueoikeudet WHERE keskustelualueetID='".$keskustelualue."'");
    kysely($yhteys, "DELETE FROM oikeudet WHERE keskustelualueetID='".$keskustelualue."'");
    kysely($yhteys, "DELETE FROM keskustelualueet WHERE id='" . $keskustelualue . "' AND keskustelualueryhmatID='" . $keskustelualueryhmatid . "'");
    ohjaaOhajuspaneeliin($okeskustelualue, "&mode=muokkaa&keskustelualueryhmatid=" . $keskustelualueryhmatid);
}

//Siirrä keskustelualue toiseen ryhmään
function siirraKeskustelualue($yhteys) {
    global $error, $okeskustelualue;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksa siirtää keskustelualueita.";
        siirry("eioikeuksia.php");
    }
    $keskustelualueryhmatid = mysql_real_escape_string($_POST['keskustelualueryhmatid']);
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $mryhma = mysql_real_escape_string($_POST['mryhma']);
    $kysely = kysely($yhteys, "SELECT id FROM keskustelualueryhmat WHERE id='".$mryhma."'");
    if(!$tulos = mysql_fetch_array($kysely)){
        $error = "Ryhmää ei löytyny.";
        return false;
    }
    //Tarkistetaan ettei yritetty siirtää joukkueen keskustelualuetta
    if (tarkistaOnkoJoukkueenKeskustelualue($yhteys, $keskustelualue)) {
        $error = "Et voi siirtää joukkueiden keskustelualueita.";
        return false;
    }
    //Siirretään
    kysely($yhteys, "UPDATE keskustelualueet SET keskustelualueryhmatID='" . $mryhma . "' WHERE keskustelualueryhmatID='" . $keskustelualueryhmatid . "' AND id='" . $keskustelualue . "'");
    ohjaaOhajuspaneeliin($okeskustelualue, "&mode=muokkaa&keskustelualueryhmatid=" . $keskustelualueryhmatid);
}

function siirraKeskustelualuettaRyhmassa($yhteys){
    global $siirry, $error, $okeskustelualue;
    $keskustelualueryhma = mysql_real_escape_string($_POST['keskustelualueryhma']);
    $keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
    $suunta = $_POST['suunta'];
    if(tarkistaAdminOikeudet($yhteys, "Admin")){
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata keskustelualueiden järjestystä.";
        if($siirry){
            siirry("eioikeuksia.php");
            return false;
        }
        $error = $_SESSION['eioikeuksia'];
        return false;
    }
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM keskustelualueet WHERE keskustelualueryhmatID='".$keskustelualueryhma."' ORDER BY jarjestysnumero DESC LIMIT 0,1");
    if(!$tulos = mysql_fetch_array($kysely)){
        $error = "Ryhmää ei löydy tai ryhmässä ei ole keskustelualueita.";
        return false;
    }
    $suurin = $tulos['jarjestysnumero'];
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM keskustelualueet WHERE id='".$keskustelualue."' AND keskustelualueryhmatID='".$keskustelualueryhma."'");
    if(!$tulos = mysql_fetch_array($kysely)){
        $error = "Keskustelualuetta ei löydy.";
        return false;
    }
    if(($suunta == "ylos" && $tulos['jarjestysnumero'] <= 1) || ($suunta == "alas" && $tulos['jarjestysnumero'] >= $suurin)){
        $error = "Ei voida siirtää.";
        return false;
    }
    if($suunta == "ylos"){
        kysely($yhteys, "UPDATE keskustelualueet SET jarjestysnumero=jarjestysnumero+1 WHERE jarjestysnumero='".($tulos['jarjestysnumero']-1)."' AND keskustelualueryhmatID='".$keskustelualueryhma."'");
        kysely($yhteys, "UPDATE keskustelualueet SET jarjestysnumero=jarjestysnumero-1 WHERE id='".$keskustelualue."' AND keskustelualueryhmatID='".$keskustelualueryhma."'");
    } elseif($suunta == "alas"){
        kysely($yhteys, "UPDATE keskustelualueet SET jarjestysnumero=jarjestysnumero-1 WHERE jarjestysnumero='".($tulos['jarjestysnumero']+1)."' AND keskustelualueryhmatID='".$keskustelualueryhma."'");
        kysely($yhteys, "UPDATE keskustelualueet SET jarjestysnumero=jarjestysnumero+1 WHERE id='".$keskustelualue."' AND keskustelualueryhmatID='".$keskustelualueryhma."'");
    }
    if($siirry){
        ohjaaOhajuspaneeliin($okeskustelualue, "&mode=siirra&keskustelualueryhmatid=" . $keskustelualueryhmatid);
    }
}
?>

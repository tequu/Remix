<?php

//Uusi keskustelualue ryhmä
function uusiKeskustelualueRyhma($yhteys) {
    global $okeskustelualueryhma, $error;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksa'] = "Sinulla ei ole oikeuksia luoda keskustelualue ryhmiä.";
        siirry("eioikeuksia.php");
    }
    echo $okeskustelualueryhma;
    die("k");
    $otsikko = mysql_real_escape_string(trim($_POST['otsikko']));
    //Tarkistetaan että saatiin nimi
    if (empty($otsikko)) {
        $error['lisaa']['otsikko'] = "Et antanut nimeä.";
        return;
    }
    //Luodaan uusi ryhmä
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM keskustelualueryhmat ORDER BY jarjestysnumero DESC LIMIT 0,1");
    $tulos = mysql_fetch_array($kysely);
    kysely($yhteys, "INSERT INTO keskustelualueryhmat(otsikko, jarjestysnumero) VALUES ('" . $otsikko . "', '" . ($tulos['jarjestysnumero'] + 1) . "')");
    ohjaaOhajuspaneeliin($okeskustelualueryhma, "");
}
//Muokkaa keskustelualueen ryhmän nimeä
function muokkaaKeskustelualueenRyhmanNimea($yhteys) {
    global $error, $okeskustelualueryhma;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata ryhmää.";
        siirry("eioikeuksia.php");
    }
    $ryhma = mysql_real_escape_string($_POST['ryhma']);
    $otsikko = mysql_real_escape_string(trim($_POST['otsikko']));
    $kysely = kysely($yhteys, "SELECT otsikko FROM keskustelualueryhmat WHERE id='" . $ryhma . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        //Tarkistetaan ettei yritetä muokata kiellettyjä keskustelualueita
        if ($tulos['otsikko'] == "Joukkueet") {
            return false;
        }
    }
    //Tarkistetaan että saatiin nimi
    if (empty($otsikko)) {
        $error['muokkaa']['otsikko'] = "Et antanut nimeä<br />";
        return;
    }
    kysely($yhteys, "UPDATE keskustelualueryhmat SET otsikko='" . $otsikko . "' WHERE id='" . $ryhma . "'");
    ohjaaOhajuspaneeliin($okeskustelualueryhma, "");
}

//Poista keskustelualue ryhmä
function poistaKeskustelualueRyhma($yhteys) {
    global $error, $okeskustelualueryhma;
    if (!tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        $_SESSION['eioikeuksa'] = "Sinulla ei ole oikeuksia poistaa keskustelualue ryhmiä.";
        siirry("eioikeuksia.php");
    }
    $ryhma = mysql_real_escape_string($_POST['ryhma']);
    $kysely = kysely($yhteys, "SELECT otsikko FROM keskustelualueryhmat WHERE id='" . $ryhma . "'");
    $tulos = mysql_fetch_array($kysely);
    if ($tulos['otsikko'] == "Joukkueet") {
        return false;
    }
    //Tarkistetaan että kaikki keskustelualueet on poistettu ryhmän alta ennen poistoa
    $kysely = kysely($yhteys, "SELECT id FROM keskustelualueet WHERE keskustelualueryhmatID='" . $ryhma . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        $error = "Kaikki ryhmä keskustelualueet on poistettava ennen kuin voit poistaa ryhmän.";
        return false;
    }
    //Poistetaan
    kysely($yhteys, "DELETE FROM keskustelualueryhmat WHERE id='" . $ryhma . "'");
    ohjaaOhajuspaneeliin($okeskustelualueryhma, "");
}

function siirraKeskustelualueryhmaaListassa($yhteys){
    global $error, $siirry, $okeskustelualueryhma;
    $ryhma = mysql_real_escape_string($_POST['ryhma']);
    $suunta = $_POST['suunta'];
    if(tarkistaAdminOikeudet($yhteys, "Admin")){
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia siirtää keskustelualuetta.";
        if($siirry){
            siirry("eioikeuksia.php");
            return false;
        }
        $error = $_SESSION['error'];
        return false;
    }
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM keskustelualueryhmat ORDER BY jarjestysnumero DESC LIMIT 0,1");
    $tulos = mysql_fetch_array($kysely);
    $suurin = $tulos['jarjestysnumero'];
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM keskustelualueryhmat WHERE id='".$ryhma."'");
    if(!$tulos = mysql_fetch_array($kysely)){
        $error = "Ryhmää ei löytynyt.";
        return false;
    }
    if(($suunta == "ylos" && $tulos['jarjestysnumero'] <= 1) || ($suunta == "alas" && $tulos['jarjestysnumero'] >= $suurin)){
        $error = "Ei voida siirtää.";
        return false;
    }
    if($suunta == "ylos"){
        kysely($yhteys, "UPDATE keskustelualueryhmat SET jarjestysnumero=jarjestysnumero+1 WHERE jarjestysnumero='".($tulos['jarjestysnumero']-1)."'");
        kysely($yhteys, "UPDATE keskustelualueryhmat SET jarjestysnumero=jarjestysnumero-1 WHERE id='".$ryhma."'");
    } elseif($suunta == "alas"){
        kysely($yhteys, "UPDATE keskustelualueryhmat SET jarjestysnumero=jarjestysnumero-1 WHERE jarjestysnumero='".($tulos['jarjestysnumero']+1)."'");
        kysely($yhteys, "UPDATE keskustelualueryhmat SET jarjestysnumero=jarjestysnumero+1 WHERE id='".$ryhma."'");
    }
    if($siirry){
        ohjaaOhajuspaneeliin($okeskustelualueryhma, "");
    }
    return true;
}
?>

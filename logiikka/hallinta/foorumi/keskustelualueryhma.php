<?php

//Uusi keskustelualue ryhm‰
function uusiKeskustelualueRyhma($yhteys) {
    global $okeskustelualueryhma;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
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
    $kysely = kysely($yhteys, "SELECT jarjestysnumero FROM keskustelualueryhmat ORDER BY jarjestysnumero DESC LIMIT 0,1");
    $tulos = mysql_fetch_array($kysely);
    kysely($yhteys, "INSERT INTO keskustelualueryhmat(otsikko, jarjestysnumero) VALUES ('" . $otsikko . "', '" . ($tulos['jarjestysnumero'] + 1) . "')");
    ohjaaOhajuspaneeliin($okeskustelualueryhma, "");
}
//Muokkaa keskustelualueen ryhm‰n nime‰
function muokkaaKeskustelualueenRyhmanNimea($yhteys) {
    global $error, $okeskustelualueryhma;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata ryhm‰‰.";
        siirry("eioikeuksia.php");
    }
    $ryhma = mysql_real_escape_string($_POST['ryhma']);
    $otsikko = mysql_real_escape_string(trim($_POST['otsikko']));
    $kysely = kysely($yhteys, "SELECT otsikko FROM keskustelualueryhmat WHERE id='" . $ryhma . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        //Tarkistetaan ettei yritet‰ muokata kiellettyj‰ keskustelualueita
        if ($tulos['otsikko'] == "Joukkueet") {
            $error = "Joukkueet ryhm‰‰ ei voida muokata.";
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
        return false;
    }
    //Tarkistetaan ett‰ kaikki keskustelualueet on poistettu ryhm‰n alta ennen poistoa
    $kysely = kysely($yhteys, "SELECT id FROM keskustelualueet WHERE keskustelualueryhmatID='" . $ryhma . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        $error = "Kaikki ryhm‰ keskustelualueet on poistettava ennen kuin voit poistaa ryhm‰n.";
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
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia siirt‰‰ keskustelualuetta.";
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
        $error = "Ryhm‰‰ ei lˆytynyt.";
        return false;
    }
    if(($suunta == "ylos" && $tulos['jarjestysnumero'] <= 1) || ($suunta == "alas" && $tulos['jarjestysnumero'] >= $suurin)){
        $error = "Ei voida siirt‰‰.";
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

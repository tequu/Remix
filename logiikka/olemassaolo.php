<?php
function tarkistaKeskustelunOlemassaOlo($yhteys, $keskustelu, $keskustelualue){
    $kysely = kysely($yhteys, "SELECT keskustelutID FROM keskustelualuekeskustelut WHERE keskustelualueetID='".$keskustelualue."' AND keskustelutID='".$keskustelu."' LIMIT 0,1");
    if($tulos = mysql_fetch_array($kysely)){
        return true;
    }
    return false;
}
//Tarkistetaan onko joukkueen keskustelualue
function tarkistaOnkoJoukkueenKeskustelualue($yhteys, $id) {
    $id = mysql_real_escape_string($id);
    $kysely = kysely($yhteys, "SELECT id FROM joukkueet WHERE keskustelualueetID='" . $id . "'");
    if($tulos = mysql_fetch_array($kysely)) {
        return $tulos['id'];
    }
    return false;
}

//Tarkistetaan onko keskustelualue olemassa
function tarkistaKeskustelualueetOlemassaOlo($yhteys, $keskustelualue) {
    $keskustelualue = mysql_real_escape_string($keskustelualue);
    $kysely = kysely($yhteys, "SELECT nimi FROM keskustelualueet WHERE id='" . $keskustelualue . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        return true;
    }
    return false;
}

//Tarkistetaan joukkueen olemassa olo
function tarkistaJoukkueenOlemassaOlo($yhteys, $joukkue) {
    $joukkue = mysql_real_escape_string($joukkue);
    $kysely = kysely($yhteys, "SELECT id FROM joukkueet WHERE id='" . $joukkue . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        return true;
    }
    return false;
}

function tarkistaTilastonOlemassaOlo($yhteys, $tilastoryhmatid){
    $tilastoryhmatid = mysql_real_escape_string($tilastoryhmatid);
    $kysely = kysely($yhteys, "SELECT id FROM tilastoryhmat WHERE id='".$tilastoryhmatid."'");
    if($tulos = mysql_fetch_array($kysely)){
        return true;
    }
    return false;
}
?>

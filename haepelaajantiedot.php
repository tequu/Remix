<?php
header("Content-Type: text/plain; charset=UTF-8");
include("/home/fbcremix/public_html/Remix/mysql/yhteys.php");
include("/home/fbcremix/public_html/Remix/logiikka/asetetutarvot.php");
$id = mysql_real_escape_string(trim($_GET['id']));
$joukkueid = mysql_real_escape_string(trim($_GET['joukkueid']));
$kysely = mysql_query("SELECT etunimi, sukunimi, syntymavuosi, rooli, pelinumero, kuva FROM tunnukset t, pelaajat p " .
        "WHERE t.id=tunnuksetID AND joukkueetID='" . $joukkueid . "' AND t.id='" . $id . "'") or die("Pelaajan tietojen hakeminen epäonnistui: " . mysql_error());
$tulos = mysql_fetch_array($kysely);
$tiedot = array("etunimi" => utf8_encode($tulos['etunimi']), "sukunimi" => utf8_encode($tulos['sukunimi']), "syntymavuosi" => utf8_encode($tulos['syntymavuosi']), 
        "maalivahti" => ($tulos['rooli'] == $pelaajaroolit[1] ? "1" : "0"), "numero" => utf8_encode($tulos['pelinumero']), "kuva" => utf8_encode($tulos['kuva']));

$kysely = mysql_query("SELECT kapteeni FROM joukkueet WHERE id='" . $joukkueid . "'") or die("Kapteenin haku epäonnistui: " . mysql_error());
$tulos = mysql_fetch_array($kysely);
$tiedot['kapteeni'] = ($tulos['kapteeni'] == $id ? "1" : "0");

$kysely = mysql_query("SELECT nimi, lisatieto FROM pelaajakorttilisatieto " .
        "WHERE joukkueetID='" . $joukkueid . "' AND tunnuksetID='" . $id . "' ORDER BY jarjestysnumero") or die("Pelaajakortti tietojen hakeminen epäonnistui: " . mysql_error());
$i = 0;
$tiedot['lisatieto'] = array();
while ($tulos = mysql_fetch_array($kysely)) {
    $tiedot['lisatieto'][] = array("nimi" => utf8_encode($tulos['nimi']), "tieto" => utf8_encode($tulos['lisatieto']));
    $i++;
}
$tiedot = json_encode($tiedot);
echo $tiedot;
mysql_close($yhteys);
?>

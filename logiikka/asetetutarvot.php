<?php
$kausi = "2012-2013";
$siirry = true;
$headers = false;
$seura = false;
//ohjauspaneelin sivut
$oomattiedot = 0;
$okeskustelualueryhma = 1;
$okeskustelualue = 2;
$okayttajat = 3;
$ojoukkue = 4;
$otiedotus = 5;
$opelaajat = 6;
$oyhteyshenkilot = 13;
$osarjataulukko = 7;
$opelit = 8;
$otilastot = 9;
$okuvat = 10;
$ouutiset = 11;
$ojohtokunta = 12;

$joukkue = mysql_real_escape_string($_GET['joukkue']);
$pelaajaroolit = array("Pelaaja", "Maalivahti", "Muu, mikä?");
$yhteyshenkiloroolit = array("Valmentaja", "Yhteyshenkilö", "Muu, mikä?");
if (isset($_SESSION['id'])) {
    $kayttaja = mysql_real_escape_string($_SESSION['id']);
}
?>

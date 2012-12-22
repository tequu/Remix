<?php
header("Content-Type: text/plain; charset=UTF-8");
include("/home/fbcremix/public_html/Remix/mysql/yhteys.php");
$id = mysql_real_escape_string($_GET['id']);
$kysely = mysql_query("SELECT uutinen, UNIX_TIMESTAMP(kirjoitusaika) as kirjoitusaika, kirjoittaja, otsikko, kuvaus, kuva, asettele FROM uutiset u, tunnukset t ".
        "WHERE u.id='" . $id . "'") or die("Uutisen hakeminen ei onnistu: " . mysql_error());
$tulos = mysql_fetch_array($kysely);
$vastaus = array("otsikko" => utf8_encode($tulos['otsikko']), "kuvaus" => utf8_encode($tulos['kuvaus']),  "uutinen" => utf8_encode($tulos['uutinen']), "kirjoitusaika" => $tulos['kirjoitusaika'], 
        "kirjoittaja" => utf8_encode($tulos['kirjoittaja']), "kuva" => $tulos['kuva'], "asettele" => $tulos['asettele']);
$vastaus = json_encode($vastaus);
echo $vastaus;
mysql_close($yhteys);
?>
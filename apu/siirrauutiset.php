<?php
function kysely($yhteys, $kysely) {
    $tulos = mysql_query($kysely);
    return $tulos;
}
//mysql asetukset
$username = "fbcremix_fbc";
$password = "irwQ/es29E";
$servername = "localhost";
$yhteys = mysql_connect($servername, $username, $password)
        or die("Kantaan ei saatu yhteyttä: " . mysql_error());
mysql_select_db("fbcremix_remix", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
$kysely = kysely($yhteys, "SELECT id, uutinen, kirjoitusaika, kirjoittaja, otsikko, kuvaus, kuvanosoite, asettele FROM uutiset");
mysql_select_db("fbcremix_seura", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
while($tulos = mysql_fetch_array($kysely)){
    kysely($yhteys, "INSERT INTO uutiset (id, uutinen, kirjoitusaika, kirjoittaja, otsikko, kuvaus, kuva, asettele, tunnuksetID) VALUES ".
            "('".$tulos['id']."', '".$tulos['uutinen']."', '".$tulos['kirjoitusaika']."', '".$tulos['kirjoittaja']."', '".$tulos['otsikko']."', '".$tulos['kuvaus']."', '".$tulos['kuvanosoite']."', '".$tulos['asettele']."', '0')");
}
mysql_close($yhteys);
?>
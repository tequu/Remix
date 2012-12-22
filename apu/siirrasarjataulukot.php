<?php

function kysely($yhteys, $kysely) {
    $tulos = mysql_query($kysely) or print($kysely . ":" . mysql_error());
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
$kysely = kysely($yhteys, "SELECT id, nimi, joukkueid, oletus FROM sarjataulukkonimet");
mysql_select_db("fbcremix_seura", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
while ($tulos = mysql_fetch_array($kysely)) {
    kysely($yhteys, "INSERT INTO sarjataulukkoryhmat (id, nimi, oletus, joukkueetID) VALUES ('" . $tulos['id'] . "', '" . $tulos['nimi'] . "', '" . $tulos['oletus'] . "', '" . $tulos['joukkueid'] . "')");
}
mysql_select_db("fbcremix_remix", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
$kysely = kysely($yhteys, "SELECT id, joukkue, O, V, T, H, TM, PM, P, jarjestysnumero, sarjataulukkonimetid FROM sarjataulukot");
mysql_select_db("fbcremix_seura", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
while ($tulos = mysql_fetch_array($kysely)) {
    kysely($yhteys, "INSERT INTO sarjataulukot (id, joukkue, O, V, T, H, TM, PM, P, jarjestysnumero, sarjataulukkoryhmatID) VALUES ".
            "('".$tulos['id']."', '".$tulos['joukkue']."', '".$tulos['O']."', '".$tulos['V']."', '".$tulos['T']."', '".$tulos['H']."', '".$tulos['TM']."', '".$tulos['PM']."', '".$tulos['P']."', '".$tulos['jarjestysnumero']."', '".$tulos['sarjataulukkonimetid']."')");
}
mysql_close($yhteys);
?>

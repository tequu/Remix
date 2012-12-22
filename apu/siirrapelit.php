<?php

function kysely($yhteys, $kysely) {
    $tulos = mysql_query($kysely) or print($kysely.":".mysql_error());
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
$kysely = kysely($yhteys, "SELECT id, nimi, joukkueid, oletus FROM pelitnimet");
mysql_select_db("fbcremix_seura", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
while ($tulos = mysql_fetch_array($kysely)) {
    kysely($yhteys, "INSERT INTO peliryhmat (id, nimi, oletus, joukkueetID) VALUES ('".$tulos['id']."', '".$tulos['nimi']."', '".$tulos['oletus']."', '".$tulos['joukkueid']."')");
}
mysql_select_db("fbcremix_remix", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
$kysely = kysely($yhteys, "SELECT id, vastustaja, aika, koti, kotimaalit, vierasmaalit, pelipaikka, pelattu, kotiturnaus, pelitnimetid FROM pelit");
mysql_select_db("fbcremix_seura", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
while($tulos = mysql_fetch_array($kysely)){
    kysely($yhteys, "INSERT INTO pelit (id, vastustaja, aika, koti, kotimaalit, vierasmaalit, pelattu, pelipaikka, kotiturnaus, peliryhmatID) VALUES ".
            "('".$tulos['id']."', '".$tulos['vastustaja']."', '".$tulos['aika']."', '".$tulos['koti']."', '".$tulos['kotimaalit']."', '".$tulos['vierasmaalit']."', '".$tulos['pelattu']."', '".$tulos['pelipaikka']."', '".$tulos['kotiturnaus']."', '".$tulos['pelitnimetid']."')");
}
mysql_close($yhteys);
?>

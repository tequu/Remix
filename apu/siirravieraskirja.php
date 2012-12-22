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
$kysely = kysely($yhteys, "SELECT id, username, email, aika, seura, joukkue, viesti, ip, poistettu, tunnuksetID, enabled FROM vieraskirja");
mysql_select_db("fbcremix_seura", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
while($tulos = mysql_fetch_array($kysely)){
    kysely($yhteys, "INSERT INTO vieraskirja (id, kirjoittaja, email, aika, seura, joukkue, viesti, ip, poistettu, enabled, tunnuksetID) VALUES ".
            "('".$tulos['id']."', '".$tulos['username']."', '".$tulos['email']."', '".$tulos['aika']."', '".$tulos['seura']."', '".$tulos['joukkue']."', '".$tulos['viesti']."', '".$tulos['ip']."', '".$tulos['poistettu']."', '".$tulos['enabled']."', '".$tulos['tunnuksetID']."')");
}
mysql_close($yhteys);
?>

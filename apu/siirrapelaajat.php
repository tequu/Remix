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
$kysely = kysely($yhteys, "SELECT id, etunimi, sukunimi, syntymavuosi FROM pelaajat");
while($tulos = mysql_fetch_array($kysely)){
    mysql_select_db("fbcremix_seura", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
    $id = 0;
    $kysely2 = kysely($yhteys, "SELECT id FROM tunnukset WHERE lower(etunimi)='".strtolower($tulos['etunimi'])."' AND lower(sukunimi)='".strtolower($tulos['sukunimi'])."'");
    if($tulos2 = mysql_fetch_array($kysely2)){
        $id = $tulos2['id'];
    } else {
        kysely($yhteys, "INSERT INTO tunnukset (etunimi, sukunimi, syntymavuosi, isadmin, enabled, estetty) VALUES ".
                "('".$tulos['etunimi']."', '".$tulos['sukunimi']."', '".$tulos['syntymavuosi']."', 'Perus', '0', '0')");
        $id = mysql_insert_id();
    }
    mysql_select_db("fbcremix_remix", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
    $kysely2 = kysely($yhteys, "SELECT joukkueid, pelinumero, kuva FROM joukkueenpelaajat WHERE pelaajatid='".$tulos['id']."'");
    mysql_select_db("fbcremix_seura", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
    while($tulos2 = mysql_fetch_array($kysely2)){
        kysely($yhteys, "INSERT INTO pelaajat (joukkueetID, tunnuksetID, pelinumero, kuva) VALUES ('".$tulos2['joukkueid']."', '".$id."', '".$tulos['pelinumero']."', '".$tulos['kuva']."')");
    }
}
mysql_close($yhteys);
?>

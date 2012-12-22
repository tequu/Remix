<?php

function kysely($yhteys, $kysely) {
    $tulos = mysql_query($kysely) or die("Kysely epäonnistui: " . $kysely . ": " . mysql_error());
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
$kysely = kysely($yhteys, "SELECT id, login, password, email, etunimi, sukunimi, is_admin, rpaivamaara, enabled FROM tunnukset");
mysql_select_db("fbcremix_seura", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
while ($tulos = mysql_fetch_array($kysely)) {
    kysely($yhteys, "INSERT INTO nakyvattiedot(email, etunimi, sukunimi, syntymavuosi) VALUES ('0', '0', '0', '0')");
    $id = mysql_insert_id();
    kysely($yhteys, "INSERT INTO tunnukset (id, login, salasana, email, etunimi, sukunimi, syntymavuosi, isadmin, rpaivamaara, enabled, estetty, nakyvattiedotID) ".
            "VALUES ('".$tulos['id']."', '".$tulos['login']."', '".$tulos['password']."', '".$tulos['email']."', '".$tulos['etunimi']."', '".$tulos['sukunimi']."', '0000', '".$tulos['is_admin']."', ".
            "'".$tulos['rpaivamaara']."', '".$tulos['enabled']."', '0', '".$id."')");
}
mysql_close($yhteys);
?>

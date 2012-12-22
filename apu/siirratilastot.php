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
$kysely = kysely($yhteys, "SELECT id, nimi, joukkueid, oletus, kokonaistilastoon FROM tilastotnimet");
mysql_select_db("fbcremix_seura", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
while ($tulos = mysql_fetch_array($kysely)) {
    kysely($yhteys, "INSERT INTO tilastoryhmat (id, nimi, oletus, kokonaistilastoon, joukkueetID) VALUES ('" . $tulos['id'] . "', '" . $tulos['nimi'] . "', '" . $tulos['oletus'] . "', '" . $tulos['kokonaistilastoon'] . "', '" . $tulos['joukkueid'] . "')");
}
mysql_select_db("fbcremix_remix", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
$kysely = kysely($yhteys, "SELECT etunimi, sukunimi, O, RLM, RLY, RM, S, M, plusmiinus, tilastotnimetid FROM tilastot, pelaajat WHERE pelaajatid=pelaajat.id");
mysql_select_db("fbcremix_seura", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
while ($tulos = mysql_fetch_array($kysely)) {
    $id = 0;
    $kysely2 = kysely($yhteys, "SELECT id FROM tunnukset WHERE lower(etunimi)='" . strtolower($tulos['etunimi']) . "' AND lower(sukunimi)='" . strtolower($tulos['sukunimi']) . "'");
    if ($tulos2 = mysql_fetch_array($kysely2)) {
        $id = $tulos2['id'];
    } else {
        kysely($yhteys, "INSERT INTO tunnukset (etunimi, sukunimi, syntymavuosi, isadmin, enabled, estetty) VALUES " .
                "('" . $tulos['etunimi'] . "', '" . $tulos['sukunimi'] . "', '" . $tulos['syntymavuosi'] . "', 'Perus', '0', '0')");
        $id = mysql_insert_id();
    }
    kysely($yhteys, "INSERT INTO tilastot (O, RLM, RLY, RM, S, M, plusmiinus, tunnuksetID, tilastoryhmatID) VALUES ".
            "('".$tulos['O']."', '".$tulos['RLM']."', '".$tulos['RLY']."', '".$tulos['RM']."', '".$tulos['S']."', '".$tulos['M']."', '".$tulos['plusmiinus']."', '".$id."', '".$tulos['tilastotnimetid']."')");
}
mysql_close($yhteys);
?>

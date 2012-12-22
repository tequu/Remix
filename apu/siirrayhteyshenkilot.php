<?php

function kysely($yhteys, $kysely) {
    $tulos = mysql_query($kysely) or print($kysely . ": " . mysql_error());
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
$kysely = kysely($yhteys, "SELECT joukkueID, etunimi, sukunimi, sahkoposti, puhelin, valmentaja FROM yhteystiedot");
while ($tulos = mysql_fetch_array($kysely)) {
    mysql_select_db("fbcremix_seura", $yhteys)
            or die("Kantaa ei saatu valittua: " . mysql_error());
    $id = 0;
    $kysely2 = kysely($yhteys, "SELECT id FROM tunnukset WHERE lower(etunimi)='" . strtolower($tulos['etunimi']) . "' AND lower(sukunimi)='" . strtolower($tulos['sukunimi']) . "'");
    if ($tulos2 = mysql_fetch_array($kysely2)) {
        $id = $tulos2['id'];
    } else {
        kysely($yhteys, "INSERT INTO tunnukset (etunimi, sukunimi, syntymavuosi, isadmin, enabled, estetty) VALUES " .
                "('" . $tulos['etunimi'] . "', '" . $tulos['sukunimi'] . "', '" . $tulos['syntymavuosi'] . "', 'Perus', '0', '0')");
        $id = mysql_insert_id();
    }
    $rooli = "";
    if ($tulos['valmentaja']) {
        $rooli = "Valmentaja";
    } else {
        $rooli = "Yhteyshenkilo";
    }
    $tiedot = $tulos['sahkoposti'] . ", " . $tulos['puhelin'];
    kysely($yhteys, "INSERT INTO yhteyshenkilot (tiedot, rooli, joukkueetID, tunnuksetID) VALUES " .
            "('" . $tiedot . "', '" . $rooli . "', '" . $tulos['joukkueID'] . "', '" . $id . "')");
}
mysql_close($yhteys);
?>

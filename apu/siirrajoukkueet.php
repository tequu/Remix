<?php
function kysely($yhteys, $kysely) {
    $tulos = mysql_query($kysely);
    return $tulos;
}
function luoSatunnainenMerkkijono($pituus) {
    $merkit = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!#%&?";
    $merkkijono = "";
    for ($i = 0; $i < $pituus; $i++) {
        $merkkijono .= $merkit[rand(0, strlen($merkit) - 1)];
    }
    return $merkkijono;
}
//mysql asetukset
$username = "fbcremix_fbc";
$password = "irwQ/es29E";
$servername = "localhost";
$yhteys = mysql_connect($servername, $username, $password)
        or die("Kantaan ei saatu yhteyttä: " . mysql_error());
mysql_select_db("fbcremix_remix", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
$kysely = kysely($yhteys, "SELECT id, joukkue, kerho, jarjestysnumero, kausi FROM joukkueet");
mysql_select_db("fbcremix_seura", $yhteys)
        or die("Kantaa ei saatu valittua: " . mysql_error());
while($tulos = mysql_fetch_array($kysely)){
    $salasana = luoSatunnainenMerkkijono(8);
    kysely($yhteys, "INSERT INTO joukkueet (id, nimi, kapteeni, kerho, salasana, jarjestysnumero, kausi) VALUES ".
            "('".$tulos['id']."', '".$tulos['joukkue']."', '0', '".$tulos['kerho']."', '".$salasana."', '".$tulos['jarjestysnumero']."', '".$tulos['kausi']."')");
}
mysql_close($yhteys);
?>

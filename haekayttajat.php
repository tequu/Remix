<?php
header("Content-Type: text/plain; charset=UTF-8");
session_start();
include("/home/fbcremix/public_html/Remix/mysql/yhteys.php");
include("/home/fbcremix/public_html/Remix/logiikka/funktiot.php");
include("/home/fbcremix/public_html/Remix/logiikka/kayttaja.php");
include("/home/fbcremix/public_html/Remix/logiikka/oikeudet.php");
$siirry = false;
$jarjestys = array("etunimi", "sukunimi", "login", "email", "syntymavuosi");
$hakusana = mysql_real_escape_string(trim($_GET['hakusana']));
$tiedot = explode("|", mysql_real_escape_string($_GET['tiedot']));
$vastaus = array();
if (isset($_GET['jarjesta']))
    $jarjesta = mysql_real_escape_string($_GET['jarjesta']);
else
    $jarjesta = 0;
$sallitut = array("id", "login", "email", "etunimi", "sukunimi", "syntymavuosi");
foreach($tiedot as $a){
    if(!in_array($a, $sallitut)){
        $vastaus['virhe'] = $a;
        $vastaus = json_encode($vastaus);
        echo $vastaus;
        die(0);
    }
}
if(in_array("etunimi", $tiedot) || in_array("sukunimi", $tiedot) ||  in_array("email", $tiedot) || in_array("syntymavuosi", $tiedot)){
    if(!tarkistaKirjautuneenTunnus($yhteys)){
        $vastaus['eikirjautunut'] = 1;
        $vastaus = json_encode($vastaus);
        echo $vastaus;
        die(0);
    }
    if(!tarkistaAdminOikeudet($yhteys, "Admin") && !tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0)){
        $vastaus['eioikeuksa'] = 1;
        $vastaus = json_encode($vastaus);
        echo $vastaus;
        die(0);
    }
}
$sql = "SELECT ";
foreach ($tiedot as $tieto) {
    $sql .= $tieto . ", ";
}
$sql = substr($sql, 0, -2);
$sql .= " FROM tunnukset WHERE";
$hakusana = explode(" ", $hakusana);
foreach ($hakusana as $sana) {
    $sql .= " (login LIKE '" . $sana . "%' OR email LIKE '" . $sana . "%' OR etunimi LIKE '" . $sana . "%' OR sukunimi LIKE '" . $sana . "%' OR syntymavuosi LIKE '" . $sana . "%') AND";
}
$sql = substr($sql, 0, -4);
$sql .= " ORDER BY " . $jarjestys[$jarjesta];
$kysely = kysely($yhteys, $sql);
while ($tulos = mysql_fetch_array($kysely)) {
    $apu = array();
    foreach($tiedot as $tieto){
        $apu[$tieto] = utf8_encode($tulos[$tieto]);
    }
    $vastaus[] = $apu;  
}
$vastaus = json_encode($vastaus);
echo $vastaus;
mysql_close($yhteys);
?>

<?php
include("/home/fbcremix/public_html/ohjelmat/mysql/yhteys.php");
$kysely = mysql_query("SELECT id, etunimi, sukunimi FROM pelaajat") or die("EI onnistu: ".mysql_error());
while($tulos = mysql_fetch_array($kysely)){
    $id = $tulos['id'];
    $etunimi = ucfirst($tulos['etunimi']);
    $sukunimi = ucfirst($tulos['sukunimi']);
    $kysely2 = mysql_query("UPDATE pelaajat SET etunimi='".$etunimi."', sukunimi='".$sukunimi."' WHERE id='".$id."'") or die("ei onnistu ".$etunimi." ".$sukunimi.": ".mysql_error());
}
?>

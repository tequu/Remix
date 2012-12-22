<?php
header("Content-type: text/xml");
include("/home/fbcremix/public_html/Remix/ohjelmat/mysql/yhteys.php");
$keskustelualue = mysql_real_escape_string($_POST['keskustelualue']);
$kysely = mysql_query("SELECT nimi, kuvaus, julkinen FROM Keskustelualueet WHERE id='".$keskustelualue."'");
$vastaus = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?><keskustelualue>";
while ($tulos = mysql_fetch_array($kysely)) {
    $vastaus .= "<nimi>" . $tulos['nimi'] . "</nimi>".
            "<kuvaus>".$tulos['kuvaus']."</kuvaus>".
            "<julkinen>".$tulos['julkinen']."</julkinen>";
}
$vastaus .= "</keskustelualue>";
mysql_close($yhteys);
echo $vastaus;
?>

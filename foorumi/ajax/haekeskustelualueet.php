<?php

header("Content-type: text/xml");
include("/home/fbcremix/public_html/Remix/ohjelmat/mysql/yhteys.php");
$ryhma = mysql_real_escape_string($_POST['ryhma']);
$kysely = mysql_query("SELECT id, nimi FROM Keskustelualueet WHERE keskustelualueet_otsikkoID='" . $ryhma . "'");
$vastaus = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?><keskustelualueet>";
while ($tulos = mysql_fetch_array($kysely)) {
    $vastaus .= "<keskustelualue>".
            "<id>" . $tulos['id'] . "</id>" .
            "<nimi>" . $tulos['nimi'] . "</nimi>".
            "</keskustelualue>";
}
$vastaus .= "</keskustelualueet>";
mysql_close($yhteys);
echo $vastaus;
?>

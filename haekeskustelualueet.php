<?php

header("Content-type: text/xml");
include("/home/fbcremix/public_html/Remix/mysql/yhteys.php");
$ryhmatid = mysql_real_escape_string($_POST['ryhmatid']);
$kyselyid = $_POST['kyselyid'];
$tunnuksetid = mysql_real_escape_string($_POST['tunnuksetid']);
if ($kyselyid == 1) {
    $kysely = mysql_query("SELECT id, nimi FROM keskustelualueet WHERE keskustelualueryhmatID='" . $ryhmatid . "' AND id NOT IN (SELECT keskustelualueetID FROM oikeudet " .
            "WHERE keskustelualueetID!='null' AND tunnuksetID='" . $tunnuksetid . "')")
            or die("Kysely epäonnistui:" . mysql_error());
} elseif ($kyselyid == 2) {
    $kysely = mysql_query("SELECT id, nimi FROM keskustelualueet, oikeudet WHERE id=keskustelualueetID AND keskustelualueryhmatID='" . $ryhmatid . "' AND tunnuksetID='" . $tunnuksetid . "'")
            or die("Kysely epäonnistui:" . mysql_error());
} else {
    $kysely = mysql_query("SELECT id, nimi FROM keskustelualueet WHERE keskustelualueryhmatID='" . $ryhmatid . "'") or die("Kysely epäonnistui:" . mysql_error());
}
$vastaus = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?><keskustelualueet>";
while ($tulos = mysql_fetch_array($kysely)) {
    $vastaus .= "<keskustelualue>" .
            "<id>" . $tulos['id'] . "</id>" .
            "<nimi>" . $tulos['nimi'] . "</nimi>" .
            "</keskustelualue>";
}
$vastaus .= "</keskustelualueet>";
mysql_close($yhteys);
echo $vastaus;
?>

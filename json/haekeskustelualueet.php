<?php
/*
 * kyselyid = 1: Ryhm�n keskustelualueet joihin k�ytt�j�ll� ei ole hallinta oikeuksia.
 * kyselyid = 2: Ryhm�n keskustelualueet joihin k�ytt�j�ll� on hallinta oikeudet.
 * kyselyid = 3: Ryhm�n keskustelualueet joihin k�ytt�j�ll� ei ole n�kyvyys oikeuksia.
 * kyselyid = 4: Ryhm�n keskustelualueet joihin k�ytt�j�ll� on n�kyvyys oikeudet.
 * Muuten: Kaikki ryhm�n keskustelualueet.
 */
header("Content-Type: text/plain; charset=UTF-8");
include("/home/fbcremix/public_html/Remix/mysql/yhteys.php");
$ryhmatid = mysql_real_escape_string($_POST['ryhmatid']);
$kyselyid = $_POST['kyselyid'];
$tunnusid = mysql_real_escape_string($_POST['tunnusid']);
$vastaus = array();
if ($kyselyid == 1) {
    $kysely = mysql_query("SELECT id, nimi FROM keskustelualueet WHERE keskustelualueryhmatID='" . $ryhmatid . "' AND id NOT IN (SELECT keskustelualueetID FROM oikeudet " .
            "WHERE keskustelualueetID!='null' AND tunnuksetID='" . $tunnusid . "')") or die("Kysely ep�onnistui:" . mysql_error());
} elseif ($kyselyid == 2) {
    $kysely = mysql_query("SELECT id, nimi FROM keskustelualueet, oikeudet WHERE id=keskustelualueetID AND keskustelualueryhmatID='" . $ryhmatid . "' AND tunnuksetID='" . $tunnusid . "'") or die("Kysely ep�onnistui:" . mysql_error());
} elseif ($kyselyid == 3) {
    $kysely = mysql_query("SELECT id, nimi FROM keskustelualueet WHERE keskustelualueryhmatID='" . $ryhmatid . "' AND id NOT IN (SELECT keskustelualueetID FROM keskustelualueoikeudet " .
            "WHERE tunnuksetID='" . $tunnusid . "')") or die("Kysely ep�onnistui: " . mysql_error());
} elseif($kyselyid == 4){
    $kysely = mysql_query("SELECT id, nimi FROM keskustelualueet ka, keskustelualueoikeudet ko WHERE ka.id=ko.keskustelualueetID AND ka.keskustelualueryhmatID='".$ryhmatid."' ".
            "AND ko.tunnuksetID='".$tunnusid."'");
}else {
    $kysely = mysql_query("SELECT id, nimi FROM keskustelualueet WHERE keskustelualueryhmatID='" . $ryhmatid . "'") or die("Kysely ep�onnistui:" . mysql_error());
}
while ($tulos = mysql_fetch_array($kysely)) {
    $vastaus[] = array("id" => $tulos['id'], "nimi" => utf8_encode($tulos['nimi']));
}
mysql_close($yhteys);
echo json_encode($vastaus);
?>

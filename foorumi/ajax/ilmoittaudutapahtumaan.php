<?php
header("Content-type: text/html; charset='windows-1252'");
session_start();
include("/home/fbcremix/public_html/Remix/ohjelmat/mysql/yhteys.php");
include("/home/fbcremix/public_html/Remix/logiikka/funktiot.php");
$kayttaja = mysql_real_escape_string($_SESSION['id']);
$tapahtuma = mysql_real_escape_string($_POST['tapahtuma']);
$lasna = mysql_real_escape_string($_POST['lasna']);
$error = "";
$siirry = false;
if (tarkistaTapahtumanOlemassaOlo($yhteys, $tapahtuma)) {
    $kysely = kysely($yhteys, "SELECT keskustelualueetID FROM Keskustelut k, Keskustelualueet_keskustelut kk WHERE k.id=keskustelutID AND tapahtumatID='" . $tapahtuma . "'");
    $tulos = mysql_fetch_array($kysely);
    
    if (tarkistaKirjautuneenTunnus($yhteys) && tarkistaOikeudetKeskustelualueelle($yhteys, $tulos['keskustelualueetID'])) {
        if ($lasna == 1 || $lasna == 0) {
            $kysely = kysely($yhteys, "SELECT lasna FROM Paikallaolo WHERE tunnuksetID='" . $kayttaja . "' AND tapahtumatID='" . $tapahtuma . "'");
            if ($tulos = mysql_fetch_array($kysely)) {
                if ($tulos['lasna'] != $lasna) {
                    kysely($yhteys, "UPDATE Paikallaolo SET lasna='" . $lasna . "' WHERE tunnuksetID='" . $kayttaja . "' AND tapahtumatID='" . $tapahtuma . "'");
                }
            } else {
                kysely($yhteys, "INSERT INTO Paikallaolo (lasna, viesti, tunnuksetID, tapahtumatID) VALUES('" . $lasna . "', '', '" . $kayttaja . "', '" . $tapahtuma . "')");
            }
        } else {
            $error = "Virheellinen lasnaolo merkintä";
        }
    } else {
        $error = "Virhe ilmoittautumisessa";
    }
} else {
    $error = "Tapahtumaa ei löytynyt";
}
if (empty($error)) {
    echo "Ilmoittatuminen hyväksytty";
} else {
    echo $error;
}
?>

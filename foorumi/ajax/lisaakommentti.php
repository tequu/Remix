<?php

header("Content-type: text/html; charset='windows-1252'");
session_start();
include("/home/fbcremix/public_html/Remix/mysql/yhteys.php");
include("/home/fbcremix/public_html/Remix/logiikka/funktiot.php");
include("/home/fbcremix/public_html/Remix/logiikka/kayttaja.php");
include("/home/fbcremix/public_html/Remix/logiikka/oikeudet.php");
$kayttaja = mysql_real_escape_string($_SESSION['id']);
$tapahtuma = mysql_real_escape_string($_POST['tapahtuma']);
$kommentti = mysql_real_escape_string($_POST['kommentti']);
$error = "";
$siirry = false;
$palautettava = array();
if (tarkistaTapahtumanOlemassaOlo($yhteys, $tapahtuma)) {
    if (tarkistaKirjautuneenTunnus($yhteys)) {
        $kysely = kysely($yhteys, "SELECT keskustelualueetID FROM keskustelut k, keskustelualuekeskustelut kk WHERE k.id=keskustelutID AND tapahtumatID='" . $tapahtuma . "'");
        while ($tulos = mysql_fetch_array($kysely)) {
            if (tarkistaNakyvyysOikeudetKeskustelualueelle($yhteys, $tulos['keskustelualueetID'])) {
                $kysely = kysely($yhteys, "SELECT lasna FROM paikallaolo WHERE tunnuksetID='" . $kayttaja . "' AND tapahtumatID='" . $tapahtuma . "'");
                if ($tulos = mysql_fetch_array($kysely)) {
                    kysely($yhteys, "UPDATE paikallaolo SET kommentti='" . $kommentti . "' WHERE tunnuksetID='" . $kayttaja . "' AND tapahtumatID='" . $tapahtuma . "'");
                    $palautettava['onnistui'] = 1;
                    $palautettava['kayttaja'] = $kayttaja;
                } else {
                    $palautettava['onnistui'] = 0;
                    $palautettava['error'] = "Ilmoittaudu tapahtumaan ensin.";
                }
                break;
            } else {
                $palautettava['onnistui'] = 0;
                $palautettava['error'] = "Virhe ilmoittautumisessa";
            }
        }
    } else {
        $palautettava['onnistui'] = 0;
        $palautettava['error'] = "Virhe tunnuksessa";
    }
} else {
    $palautettava['onnistui'] = 0;
    $palautettava['error'] = utf8_encode("Tapahtumaa ei löytynyt");
}
if (!empty($error)) {
    $palautettava['error'] = $error;
}
$palautettava['error'] = utf8_encode($palautettava['error']);
echo json_encode($palautettava);
mysql_close($yhteys);
?>

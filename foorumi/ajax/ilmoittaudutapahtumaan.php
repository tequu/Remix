<?php

header("Content-type: text/html; charset='windows-1252'");
session_start();
include("/home/fbcremix/public_html/Remix/mysql/yhteys.php");
include("/home/fbcremix/public_html/Remix/logiikka/funktiot.php");
include("/home/fbcremix/public_html/Remix/logiikka/kayttaja.php");
include("/home/fbcremix/public_html/Remix/logiikka/oikeudet.php");
$kayttaja = mysql_real_escape_string($_SESSION['id']);
$tapahtuma = mysql_real_escape_string($_POST['tapahtuma']);
$lasna = mysql_real_escape_string($_POST['lasna']);
$aikaleima = time();
$aika = date("Y-m-d H:i", $aikaleima);
$siirry = false;
$palautettava = array();
if (tarkistaTapahtumanOlemassaOlo($yhteys, $tapahtuma)) {
    if (tarkistaKirjautuneenTunnus($yhteys)) {
        $kysely = kysely($yhteys, "SELECT UNIX_TIMESTAMP(ilmotakaraja) takaraja, ilmomaxmaara, inmaara, keskustelualueetID FROM tapahtumat t, keskustelut k, keskustelualuekeskustelut kk " .
                "WHERE t.id=k.tapahtumatID AND k.id=keskustelutID AND t.id='" . $tapahtuma . "'");
        if ($tulos = mysql_fetch_array($kysely)) {
            if (tarkistaNakyvyysOikeudetKeskustelualueelle($yhteys, $tulos['keskustelualueetID'])) {
                if ($lasna == 1 || $lasna == 0) {
                    if ($tulos['takaraja'] == 0 || $aikaleima < $tulos['takaraja']) {
                        if ($lasna == 0 || $tulos['ilmomaxmaara'] == 0 || $tulos['inmaara'] < $tulos['ilmomaxmaara']) {
                            $palautettava['error'] = "";
                            $kysely = kysely($yhteys, "SELECT lasna FROM paikallaolo WHERE tunnuksetID='" . $kayttaja . "' AND tapahtumatID='" . $tapahtuma . "'");
                            if ($tulos = mysql_fetch_array($kysely)) {
                                if ($tulos['lasna'] != $lasna) {
                                    kysely($yhteys, "UPDATE paikallaolo SET lasna='" . $lasna . "', ilmoittautumisaika='" . $aika . "' WHERE tunnuksetID='" . $kayttaja . "' AND tapahtumatID='" . $tapahtuma . "'");
                                    if ($tulos['lasna'] == 1) {
                                        kysely($yhteys, "UPDATE tapahtumat SET inmaara=inmaara-1, outmaara=outmaara+1 WHERE id='" . $tapahtuma . "'");
                                    } else {
                                        kysely($yhteys, "UPDATE tapahtumat SET inmaara=inmaara+1, outmaara=outmaara-1 WHERE id='" . $tapahtuma . "'");
                                    }
                                }
                            } else {
                                kysely($yhteys, "INSERT INTO paikallaolo (lasna, kommentti, tunnuksetID, tapahtumatID) VALUES('" . $lasna . "', '', '" . $kayttaja . "', '" . $tapahtuma . "')");
                                if ($lasna == 1) {
                                    kysely($yhteys, "UPDATE tapahtumat SET inmaara=inmaara+1 WHERE id='" . $tapahtuma . "'");
                                } else {
                                    kysely($yhteys, "UPDATE tapahtumat SET outmaara=outmaara+1 WHERE id='" . $tapahtuma . "'");
                                }
                            }
                            $palautettava['onnistui'] = 1;
                            $palautettava['kayttaja'] = $kayttaja;
                        } else {
                            $palautettava['onnistui'] = 0;
                            $palautettava['error'] = "Ilmoittautuminen on täynnä";
                        }
                    } else {
                        $palautettava['onnistui'] = 0;
                        $palautettava['error'] = "Ilmoittautumisen aikaraja on mennyt";
                    }
                } else {
                    $palautettava['onnistui'] = 0;
                    $palautettava['error'] = "Virheellinen läsnaolo merkintä";
                }
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
    $palautettava['error'] = "Tapahtumaa ei löytynyt";
}
$palautettava['error'] = utf8_encode($palautettava['error']);
echo json_encode($palautettava);
mysql_close($yhteys);
?>

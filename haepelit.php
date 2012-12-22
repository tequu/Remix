<?php

header("Content-Type: text/plain; charset=UTF-8");
include("/home/fbcremix/public_html/Remix/mysql/yhteys.php");
$alku = mysql_real_escape_string($_GET['alku']);
$loppu = mysql_real_escape_string($_GET['loppu']);
$joukkueid = mysql_real_escape_string($_GET['joukkueid']);
if ($joukkueid == 0)
    $kysely = mysql_query("SELECT j.nimi nimi, UNIX_TIMESTAMP(aika) AS aika, kotiturnaus FROM pelit p, peliryhmat pr, joukkueet j " .
            "WHERE joukkueetID=j.id AND pr.id=peliryhmatID AND UNIX_TIMESTAMP(aika)>='" . $alku . "' AND UNIX_TIMESTAMP(aika)<'" . $loppu . "'")
            or die("Pelien hakeminen epäonnistui: " . mysql_error());
else {
    $sql = "SELECT UNIX_TIMESTAMP(aika) AS aika, kotiturnaus FROM pelit p, peliryhmat pr " .
            "WHERE peliryhmatID=pr.id AND UNIX_TIMESTAMP(aika)>='" . $alku . "' AND UNIX_TIMESTAMP(aika)<'" . $loppu . "' AND joukkueetID='" . $joukkueid . "'";
    $kysely = mysql_query($sql)
            or die("Joukkueen pelien hakeminen epäonnistui: " . $sql . " " . mysql_error());
}
$taulukko = array();
$vastaus = array();
while ($tulos = mysql_fetch_array($kysely)) {
    $aika = getdate($tulos['aika']);
    $paiva = $aika['mday'];
    $kuukausi = $aika['mon'];
    $vuosi = $aika['year'];
    $aika = mktime(0, 0, 0, $kuukausi, $paiva, $vuosi) * 1000;
    $taulukko['paivat'][] = $aika;
    if ($taulukko[$aika]['kotiturnaus'] != 1) {
        $taulukko[$aika]['kotiturnaus'] = $tulos['kotiturnaus'];
    }
    if ($joukkueid == 0) {
        $taulukko[$aika]['joukkueet'][] = $tulos['nimi'];
    }
}
if (!empty($taulukko)) {
    $taulukko['paivat'] = array_unique($taulukko['paivat']);
    foreach ($taulukko['paivat'] as $aika) {
        $joukkueet = array();
        if ($joukkueid == 0) {
            foreach ($taulukko[$aika]['joukkueet'] as $joukkue) {
                $joukkue = utf8_encode($joukkue);
                if (!in_array($joukkue, $joukkueet)) {
                    $joukkueet[] = $joukkue;
                }
            }
        }
        $vastaus[] = array("aika" => $aika, "kotiturnaus" => $taulukko[$aika]['kotiturnaus'], "joukkueet" => $joukkueet);
    }
}
$vastaus = json_encode($vastaus);
echo $vastaus;
mysql_close($yhteys);
?>
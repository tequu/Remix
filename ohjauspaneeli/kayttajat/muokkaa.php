<?php
$ryhmat = array();
$kayttajatid = mysql_real_escape_string($_GET['kayttajatid']);
$kysely = kysely($yhteys, "SELECT login, email, etunimi, sukunimi, syntymavuosi, isadmin, enabled, estetty FROM tunnukset WHERE id='" . $kayttajatid . "'");
if ($tulos = mysql_fetch_array($kysely)) {

    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajat/kayttajantiedot.php");
    if ($tulos['enabled']) {
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajat/joukkueoikeudet.php");
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajat/keskustelualueoikeudet.php");
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajat/adminoikeudet.php");
    }
}
?>

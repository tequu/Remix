<?php
$ryhmat = array();
$kayttajatid = mysql_real_escape_string($_GET['kayttajatid']);
$kysely = kysely($yhteys, "SELECT login, email, etunimi, sukunimi, syntymavuosi, isadmin, enabled, estetty FROM tunnukset WHERE id='" . $kayttajatid . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    $kysely2 = kysely($yhteys, "SELECT * FROM keskustelualueryhmat ");
    while ($tulos2 = mysql_fetch_array($kysely2)) {
        $ryhmat[$tulos2['id']] = $tulos2['otsikko'];
    }
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajat/muokkaakayttajaa.php");
    ?>  
    <div class="ala_otsikko">Oikeuksien hallinta</div>
    <?php
//    if (!tarkistaOnkoAdmin($yhteys, $kayttajatid, "Masteradmin")) {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajat/joukkueoikeudet.php");
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajat/keskustelualueoikeudet.php");
//    }
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajat/adminoikeudet.php");
}
?>

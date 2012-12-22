<div class="otsikko">Keskustelualueiden hallinta</div>
<?php
if (tarkistaAdminOikeudet($yhteys, "Admin")) {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/keskustelualue/valikko.php");
    if ($_GET['mode'] == "muokkaa") {
        $keskustelualueryhmatid = mysql_real_escape_string($_GET['keskustelualueryhmatid']);
        $kysely = kysely($yhteys, "SELECT id FROM keskustelualueryhmat WHERE id='" . $keskustelualueryhmatid . "'");
        if ($tulos = mysql_fetch_array($kysely)) {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/keskustelualue/lisaa.php");
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/keskustelualue/muokkaa.php");
        } else {
            $_SESSION['virhe'] = "Keskustelualue ryhmää ei löydy.";
            siirry("virhe.php");
        }
    }
} else {
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata keskustelualueita.";
    siirry("eioikeuksia.php");
}
?>
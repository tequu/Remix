<div id="keskustelualuehallinta">
    <h3 class="otsikko">Keskustelualueiden hallinta</h3>
    <?php
    if (tarkistaAdminOikeudet($yhteys, "MasterAdmin")) {
        $kysely = kysely($yhteys, "SELECT id, otsikko FROM keskustelualueryhmat WHERE otsikko!='Joukkueet'");
        if ($tulos = mysql_fetch_array($kysely)) {
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
            echo "Et voi luoda uusia keskustelualueita ennen kuin on luotu vähintään yksi keskustelualue ryhmä.";
        }
    } else {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata keskustelualueita.";
        siirry("eioikeuksia.php");
    }
    ?>
</div>
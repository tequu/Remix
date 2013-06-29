<div id="keskustelualueryhmathallinta">
    <h3 class="otsikko">Keskustelualueiden ryhmien hallinta</h3>
    <?php
    if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/keskustelualueryhmat/lisaa.php");
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/keskustelualueryhmat/muokkaa.php");
    } else {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata keskustelualueiden ryhmiä.";
        siirry("eioikeuksia.php");
    }
    ?>
</div>
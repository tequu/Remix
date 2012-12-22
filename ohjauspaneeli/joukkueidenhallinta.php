<?php
if (tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0)) {
    ?>
    <div class="otsikko">Joukkueiden hallinta</div>
    <?php
    include("/home/fbcremix/public_html/Remix/ohjelmat/joukkueenvalintavalikko.php");
    if ($joukkue != 0 && tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/joukkue/muokkaa.php");
    }
    elseif(tarkistaAdminOikeudet($yhteys, "Masteradmin")){
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/joukkue/lisaa.php");
    }
} else {
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata joukkueita.";
    siirry("eioikeuksia.php");
}
?>
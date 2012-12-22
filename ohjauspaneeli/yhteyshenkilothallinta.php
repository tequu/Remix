<?php
if (tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0)) {
    ?>
    <div class="otsikko">Yhteyshenkilot hallinta</div>
    <?php
    include("/home/fbcremix/public_html/Remix/ohjelmat/joukkueenvalintavalikko.php");
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/yhteyshenkilot/valikko.php");
    if ($joukkue != 0 && tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        if ($_GET['mode'] == 'poista') {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/yhteyshenkilot/poista.php");
        } elseif ($_GET['mode'] == 'muokkaa') {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/yhteyshenkilot/muokkaa.php");
        } else {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/yhteyshenkilot/lisaa.php");
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/yhteyshenkilot/lisaauusi.php");
        }
    }
} else {
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata joukkueiden yhteyshenkilöitä.";
    siirry("eioikeuksia.php");
}
?>
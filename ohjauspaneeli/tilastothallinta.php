<?php
if (tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0)) {
    ?>
    <div class="otsikko">Tilastot hallinta</div>
    <?php
    include("/home/fbcremix/public_html/Remix/ohjelmat/joukkueenvalintavalikko.php");
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tilastot/valikko.php");
    if ($joukkue != 0 && tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        if ($_GET['mode'] == "muokkaa" || $_GET['mode'] == "muokkaapelaajaa" || $_GET['mode'] == "poistapelaaja" || $_GET['mode'] == "poista") {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tilastot/muokkaa.php");
        } else {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tilastot/uusiryhma.php");
        }
    }
} else {
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata joukkueiden tilastoja.";
    siirry("eioikeuksia.php");
}
?>
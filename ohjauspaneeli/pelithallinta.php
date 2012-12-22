<?php
if (tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0)) {
    ?>
    <div class='otsikko'>Pelit hallinta</div>
    <?php
    include("/home/fbcremix/public_html/Remix/ohjelmat/joukkueenvalintavalikko.php");
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelit/valikko.php");
    if ($joukkue != 0 && tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        if ($_GET['mode'] == "muokkaa" || $_GET['mode'] == "muokkaapelia" || $_GET['mode'] == "poistapeli" || $_GET['mode'] == "poista" || $_GET['mode'] == "poistatulos") {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelit/muokkaa.php");
        } else {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelit/uusiryhma.php");
        }
    }
} else {
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata joukkueiden pelilistaa.";
    siirry("eioikeuksia.php");
}
?>

<?php
if (tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0)) {
    ?>
    <div class="otsikko">Pelaajat hallinta</div>
    <?php
    include("/home/fbcremix/public_html/Remix/ohjelmat/joukkueenvalintavalikko.php");
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelaajat/valikko.php");
    if ($joukkue != 0 && tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        if ($_GET['mode'] == "poista") {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelaajat/poista.php");
        } elseif ($_GET['mode'] == "muokkaa") {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelaajat/muokkaa.php");
        } else {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelaajat/lisaa.php");
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelaajat/lisaauusi.php");
        }
    }
} else {
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata joukkueiden pelaajia.";
    siirry("eioikeuksia.php");
}
?>
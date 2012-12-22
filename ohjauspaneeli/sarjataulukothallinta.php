<?php
if (tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0)) {
    ?>
    <div class="otsikko">Sarjataulukkot hallinta</div>
    <?php
    include("/home/fbcremix/public_html/Remix/ohjelmat/joukkueenvalintavalikko.php");
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/sarjataulukko/valikko.php");
    if ($joukkue != 0 && tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        if ($_GET['mode'] == 'poista') {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/sarjataulukko/poista.php");
        } elseif ($_GET['mode'] == "poistajoukkue") {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/sarjataulukko/poistajoukkue.php");
        } elseif ($_GET['mode'] == "muokkaa") {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/sarjataulukko/muokkaa.php");
            
        } else {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/sarjataulukko/uusiryhma.php");
        }
    }
} else {
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata joukkueiden sarjataulukoita.";
    siirry("eioikeuksia.php");
}
?>
<?php
if (tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0) || tarkistaAdminOikeudet($yhteys, "Admin")) {
    ?>
    <div class="otsikko">Kuvakategoriat hallinta</div>
    <?php
    $seura = true;
    include("/home/fbcremix/public_html/Remix/ohjelmat/joukkueenvalintavalikko.php");
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kuvat/valikko.php");
    if (isset($_GET['joukkue']) && (($joukkue != 0 && tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) || ($joukkue == 0 && tarkistaAdminOikeudet($yhteys, "Admin")))) {
        if ($_GET['mode'] == "muokkaa" || $_GET['mode'] == "muokkaakuvaa" || $_GET['mode'] == "poistakuva" || $_GET['mode'] == "poista") {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kuvat/muokkaa.php");
        } else {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kuvat/uusikategoria.php");
        }
    }
} else {
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata joukkueiden sarjataulukoita.";
    siirry("eioikeuksia.php");
}
?>
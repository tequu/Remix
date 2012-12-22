<?php
if (tarkistaAdminOikeudet($yhteys, "Admin")) {
    ?>
    <div class="otsikko">Johtokunta ohjauspaneeli</div>
    <?php
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/johtokunta/valikko.php");
    if ($_GET['mode'] == "muokkaa") {
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/johtokunta/muokkaa.php");
    } elseif ($_GET['mode'] == "poista") {
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/johtokunta/poista.php");
    } else {
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/johtokunta/lisaa.php");
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/johtokunta/lisaapoytakirja.php");
    }
} else {
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata johtokuntaa.";
    siirry("eioikeuksia.php");
}
?>
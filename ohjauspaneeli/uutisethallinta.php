<?php

if (tarkistaAdminOikeudet($yhteys, "Admin") || tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0)) {
    if ($_GET['mode'] == "muokkaa") {
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/uutiset/muokkaa.php");
    } elseif ($_GET['mode'] == "poistakuva") {
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/uutiset/poistakuva.php");
    } else {
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/uutiset/lisaa.php");
    }
} else {
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata uutisia.";
    siirry("eioikeuksia.php");
}
?>

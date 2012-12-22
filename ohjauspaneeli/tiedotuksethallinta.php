<?php
if (tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0)) {
    echo"<div class='otsikko'>Tiedotukset hallinta</div>";
    include("/home/fbcremix/public_html/Remix/ohjelmat/joukkueenvalintavalikko.php");
    if ($joukkue != 0 && tarkistaHallintaOikeudetJoukkueeseen($yhteys, $joukkue)) {
        if ($_GET['mode'] == "muokkaa") {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tiedotus/muokkaa.php");
        } elseif ($_GET['mode'] == "poista") {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tiedotus/poista.php");
        } else {
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tiedotus/lisaa.php");
        }
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tiedotus/lista.php");
    }
} else {
    $_SESSION['eioikeuksa'] = "Sinulla ei ole oikeuksia muokata seuran tai joukkueen tiedotuksia.";
    siirry("eioikeuksia.php");
}
?>

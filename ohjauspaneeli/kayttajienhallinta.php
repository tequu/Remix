<?php
if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
    ?>
    <div class="otsikko">Käyttäjien hallinta</div>
    <?php
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajat/valikko.php");
    if ($_GET['mode'] == "muokkaa") {
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajat/muokkaa.php");
    } elseif ($_GET['mode'] == "poista") {
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajat/poista.php");
    } else {
        include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajat/haku.php");
    }
} else {
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata käyttäjiä.";
    siirry("eioikeuksia.php");
}
?>
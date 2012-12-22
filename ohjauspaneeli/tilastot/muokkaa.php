<?php
$tilastoryhmatid = mysql_real_escape_string($_GET['tilastoryhmatid']);
include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tilastot/pelaajavalikko.php");
if ($_GET['mode'] == "poista") {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tilastot/poista.php");
} elseif ($_GET['mode'] == "muokkaapelaajaa") {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tilastot/muokkaapelaajaa.php");
} elseif ($_GET['mode'] == "poistapelaaja") {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tilastot/poistapelaaja.php");
} else {
    ?>
    <hr />
    <div id="takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otilastot . "&joukkue=" . $joukkue; ?>')">Takaisin</div>
    <div id="error"><?php echo $error; ?></div>
    <?php
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tilastot/muokkaaryhmaa.php");
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tilastot/lisaa.php");
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tilastot/lisaauusi.php");
}
//lisää tulos
?>
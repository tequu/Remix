<?php
$kuvakategoriatid = mysql_real_escape_string($_GET['kuvakategoriatid']);
include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kuvat/kuvavalikko.php");
if ($_GET['mode'] == 'poista') {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kuvat/poista.php");
} elseif ($_GET['mode'] == "poistakuva") {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kuvat/poistakuva.php");
} elseif ($_GET['mode'] == "muokkaakuvaa") {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kuvat/muokkaakuvaa.php");
} else {
    ?>
    <hr />
    <div id="takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okuvat . "&joukkue=" . $joukkue; ?>')">Takaisin</div>
    <div id="error"><?php echo $error; ?></div>
    <?php
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kuvat/muokkaakategoriaa.php");
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kuvat/lisaakuva.php");
}
?>

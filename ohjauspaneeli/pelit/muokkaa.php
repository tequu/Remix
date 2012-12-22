<?php
$peliryhmatid = mysql_real_escape_string($_GET['peliryhmatid']);
include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelit/pelivalikko.php");
if ($_GET['mode'] == "poista") {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelit/poista.php");
} elseif ($_GET['mode'] == "muokkaapelia") {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelit/muokkaapelia.php");
} elseif ($_GET['mode'] == "poistapeli") {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelit/poistapeli.php");
} elseif ($_GET['mode'] == "poistatulos") {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelit/poistatulos.php");
} else {
    ?>
    <hr />
    <div id="takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelit . "&joukkue=" . $joukkue; ?>')">Takaisin</div>
    <div id="error"><?php echo $error; ?></div>
    <?php
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelit/muokkaaryhmaa.php");
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelit/lisaapeli.php");
}
//lisää tulos
?>

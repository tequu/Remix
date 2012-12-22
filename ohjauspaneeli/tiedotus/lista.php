<?php
$kysely = kysely($yhteys, "SELECT count(id) AS maara FROM tiedotukset WHERE joukkueetID='" . $joukkue . "'");
$tulos = mysql_fetch_array($kysely);
$maara = $tulos['maara'];
if ($maara != 0) {
    ?>
    <hr />
    <div class='ala_otsikko'>Muokkaa ja Poista tiedotuksia</div>
    <div id="sivunumerot">
        <?php
        $ysm = 10;
        $sivu = $_GET['sivu'];
        $linkki = "/Remix/ohjauspaneeli.php?sivuid=" . $otiedotus . "&joukkue=" . $joukku . "&";
        $viimeinen = false;
        $nuolet = true;
        include("/home/fbcremix/public_html/Remix/ohjelmat/sivunumerot.php");
        ?>
    </div>
    <div id="clear"></div>
    <div id="bold">Tiedotukset</div>
    <?php
    $kysely = kysely($yhteys, "SELECT id, tiedotus, UNIX_TIMESTAMP(kirjoitusaika) kirjoitusaika FROM tiedotukset WHERE joukkueetID='" . $joukkue . "' ORDER BY kirjoitusaika DESC LIMIT " . $sivu * $ysm . ", " . $ysm);
    $laskuri = 0;
    while ($tulos = mysql_fetch_array($kysely)) {
        ?>
        <div id="tiedotus">
            <fieldset>
                <legend><?php echo date("d.m.Y H:i", $tulos['kirjoitusaika']); ?></legend>
                <?php echo $tulos['tiedotus']; ?><br />
                <input type="button" value="Muokkaa" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otiedotus . "&joukkue=" . $joukkue . "&mode=muokkaa&tiedotuksetid=" . $tulos['id']; ?>')" />
                <input type="button" value="Poista" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otiedotus . "&joukkue=" . $joukkue . "&mode=poista&tiedotuksetid=" . $tulos['id']; ?>')" />
                <div style="clear:both;"></div>
            </fieldset>
        </div>
        <hr />
        <?php
    }
}
?>
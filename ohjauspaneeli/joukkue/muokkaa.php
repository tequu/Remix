<?php
$kysely = kysely($yhteys, "SELECT j.nimi, salasana, kuvaus FROM joukkueet j, keskustelualueet k WHERE keskustelualueetID=k.id AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr/>
    <div id="takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $ojoukkue;; ?>')">Takaisin</div>
    <form name="muokkaajoukkuetta" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $ojoukkue . "&joukkue=" . $joukkue; ?>" method="post">
        <input type="hidden" name="ohjaa" id="ohjaa" />
        <?php
        if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
            ?>
            Joukkueet nimi: <input type="text" name="nimi" value="<?php echo $tulos['nimi']; ?>" /><br />
            <?php
        }
        ?>
        Joukkueen salasana: <input type="text" name="salasana" value="<?php echo $tulos['salasana']; ?>" /><br />
        Keskustelualueen kuvaus: <input type="text" name="kuvaus" value="<?php echo $tulos['kuvaus']; ?>" /><br />
        <br />
        <input type="button" value="Muokkaa" onclick="laheta('muokkaajoukkuetta', ['ohjaa'], ['18'])" /><input type="button" value="Poista" onclick="laheta('muokkaajoukkuetta', ['ohjaa'], ['19'])" />
    </form>
    <?php
}
?>

<?php
$kysely = kysely($yhteys, "SELECT tr.id id, tr.nimi nimi FROM tilastoryhmat tr, joukkueet j WHERE joukkueetID=j.id AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr />
    <form id="muokkaatilastoa" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="sivuid" value="<?php echo $otilastot; ?>" />
        <input type="hidden" name="joukkue" value="<?php echo $joukkue; ?>" />
        <input type="hidden" name="mode" value="muokkaa" />
        Valitse tilasto:
        <select name="tilastoryhmatid"  onchange="laheta('muokkaatilastoa', [], [])">
            <?php
            echo!isset($_GET['peliryhmatid']) ? "<option></option>" : "";
            do {
                echo "<option value=\"" . $tulos['id'] . "\"" . ($_GET['tilastoryhmatid'] == $tulos['id'] ? " SELECTED" : "") . ">" . $tulos['nimi'] . "</option>";
            } while ($tulos = mysql_fetch_array($kysely));
            ?>
        </select><br />
    </form>
    <?php
}
?>
<?php
$kysely = kysely($yhteys, "SELECT sr.id id, sr.nimi nimi FROM sarjataulukkoryhmat sr, joukkueet j WHERE joukkueetID=j.id AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr />
    <form id="muokkaasarjataulukkoa" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="sivuid" value="<?php echo $osarjataulukko; ?>" />
        <input type="hidden" name="joukkue" value="<?php echo $joukkue; ?>" />
        <input type="hidden" name="mode" value="muokkaa" />
        Valitse sarjataulukko:
        <select name="sarjataulukkoryhmatid" onchange="laheta('muokkaasarjataulukkoa', [], [])">
            <?php
            echo!isset($_GET['sarjataulukkoryhmatid']) ? "<option></option>" : "";
            do {
                echo "<option value=\"" . $tulos['id'] . "\"" . ($_GET['sarjataulukkoryhmatid'] == $tulos['id'] ? " SELECTED" : "") . ">" . $tulos['nimi'] . "</option>";
            } while ($tulos = mysql_fetch_array($kysely));
            ?>
        </select><br />
    </form>
    <?php
}
?>
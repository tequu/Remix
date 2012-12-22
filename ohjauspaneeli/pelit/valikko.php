<?php
$kysely = kysely($yhteys, "SELECT pr.id id, pr.nimi nimi FROM peliryhmat pr, joukkueet j WHERE joukkueetID=j.id AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr />
    <form id="muokkaapelitlistaa" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="sivuid" value="<?php echo $opelit; ?>" />
        <input type="hidden" name="joukkue" value="<?php echo $joukkue; ?>" />
        <input type="hidden" name="mode" value="muokkaa" />
        Valitse pelilista:
        <select name="peliryhmatid"  onchange="laheta('muokkaapelitlistaa', [], [])">
            <?php
            echo!isset($_GET['peliryhmatid']) ? "<option></option>" : "";
            do {
                echo "<option value=\"" . $tulos['id'] . "\"" . ($_GET['peliryhmatid'] == $tulos['id'] ? " SELECTED" : "") . ">" . $tulos['nimi'] . "</option>";
            } while ($tulos = mysql_fetch_array($kysely));
            ?>
        </select><br />
    </form>
    <?php
}
?>
<?php
$kysely = kysely($yhteys, "SELECT id, etunimi, sukunimi FROM johtokunta");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <form id="muokkaajohtokunnanjasenia" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="sivuid" value="<?php echo $ojohtokunta; ?>" />
        <input type="hidden" name="mode" value="muokkaa"/>
        Valitse nimi:
        <select name="jasenetid" onchange="laheta('muokkaajohtokunnanjasenia',[],[])">
            <?php
            echo!isset($_GET['jasenetid']) ? "<option></option>" : "";
            do {
                echo "<option value=\"" . $tulos['id'] . "\"".($_GET['jasenetid'] == $tulos['id']?" SELECTED":"").">" . $tulos['etunimi'] . " " . $tulos['sukunimi'] . "</option>";
            } while ($tulos = mysql_fetch_array($kysely));
            ?>
        </select><br />
    </form>
    <hr />
    <?php
}
?>

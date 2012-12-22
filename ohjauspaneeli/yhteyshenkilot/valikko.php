<?php
$kysely = kysely($yhteys, "SELECT t.id id, etunimi, sukunimi FROM joukkueet j, yhteyshenkilot, tunnukset t ".
        "WHERE j.id=joukkueetID AND tunnuksetID=t.id AND j.id='" . $joukkue . "' AND kausi='".$kausi."' ORDER BY etunimi, sukunimi");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr />
    <form name="muokkaayhteyshenkiloa" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="sivuid" value="<?php echo $oyhteyshenkilot; ?>" />
        <input type="hidden" name="joukkue" value="<?php echo $joukkue; ?>" />
        <input type="hidden" name="mode" value="muokkaa" />
        Valitse yhteyshenkiö:<select name="yhteyshenkilotid" onchange="laheta('muokkaayhteyshenkiloa',[],[])">
            <?php
            echo!isset($_GET['yhteyshenkilotid']) ? "<option></option>" : "";
            do {
                echo"<option value=\"" . $tulos['id'] . "\"" . ($_GET['yhteyshenkilotid'] == $tulos['id'] ? " SELECTED" : "") . ">" . $tulos['etunimi'] . " " . $tulos['sukunimi'] . "</option>";
            } while ($tulos = mysql_fetch_array($kysely))
            ?>
        </select>
    </form>
    <?php
}
?>
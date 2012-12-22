<?php
$kysely = kysely($yhteys, "SELECT t.id id, etunimi, sukunimi FROM joukkueet j, tilastoryhmat tr, tilastot, tunnukset t ".
        "WHERE j.id=joukkueetID AND tr.id=tilastoryhmatID AND tunnuksetID=t.id AND j.id='" . $joukkue . "' AND tr.id='".$tilastoryhmatid."' AND kausi='".$kausi."' ORDER BY etunimi, sukunimi");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr />
    <form name="muokkaapelaajaa" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="sivuid" value="<?php echo $otilastot; ?>" />
        <input type="hidden" name="joukkue" value="<?php echo $joukkue; ?>" />
        <input type="hidden" name="mode" value="muokkaapelaajaa" />
        <input type="hidden" name="tilastoryhmatid" value="<?php echo $tilastoryhmatid; ?>" />
        Valitse pelaaja:<select name="pelaajatid" onchange="laheta('muokkaapelaajaa',[],[])">
            <?php
            echo!isset($_GET['pelaajatid']) ? "<option></option>" : "";
            do {
                echo"<option value=\"" . $tulos['id'] . "\"" . ($_GET['pelaajatid'] == $tulos['id'] ? " SELECTED" : "") . ">" . $tulos['etunimi'] . " " . $tulos['sukunimi'] . "</option>";
            } while ($tulos = mysql_fetch_array($kysely))
            ?>
        </select>
    </form>
    <?php
}
?>
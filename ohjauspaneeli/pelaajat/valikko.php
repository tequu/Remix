<?php
$kysely = kysely($yhteys, "SELECT t.id id, etunimi, sukunimi FROM joukkueet j, pelaajat, tunnukset t ".
        "WHERE j.id=joukkueetID AND tunnuksetID=t.id AND joukkueetID='" . $joukkue . "' AND kausi='".$kausi."' ORDER BY etunimi, sukunimi");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr />
    <form name="muokkaapelaajaa" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="sivuid" value="<?php echo $opelaajat; ?>" />
        <input type="hidden" name="joukkue" value="<?php echo $joukkue; ?>" />
        <input type="hidden" name="mode" value="muokkaa" />
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
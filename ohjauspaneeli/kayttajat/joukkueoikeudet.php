<hr />
<div id="bold">Joukkueiden oikeudet</div>
<?php
$kysely = kysely($yhteys, "SELECT id, nimi FROM joukkueet WHERE id NOT IN (SELECT joukkueetID FROM oikeudet WHERE joukkueetID!='null' AND tunnuksetID='" . $kayttajatid . "')");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <div id="floatleft">
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okayttajat . "&mode=muokkaa&kayttajatid=" . $kayttajatid; ?>" method="post">
            <input type="hidden" name="ohjaa" value="15" />
            Lisättävät<br />
            <select name="oikeudet[]" multiple="multiple" size="5">
                <?php
                do {
                    echo"<option value='" . $tulos['id'] . "'>" . $tulos['nimi'] . "</option>";
                } while ($tulos = mysql_fetch_array($kysely));
                ?>
            </select><br />
            <input type="submit" value="Lisää oikeudet" />
        </form>
    </div>
    <?php
}
$kysely = kysely($yhteys, "SELECT id, nimi FROM joukkueet j, oikeudet WHERE j.id=joukkueetID AND tunnuksetID='" . $kayttajatid . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <div id="floatleft" style="margin-left:20px;">
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okayttajat . "&mode=muokkaa&kayttajatid=" . $kayttajatid; ?>" method="post">
            <input type="hidden" name="ohjaa" value="32" />
            Poistettavat<br />
            <select name="oikeudet[]" multiple="multiple" size="5">
                <?php
                do {
                    echo"<option value='" . $tulos['id'] . "'>" . $tulos['nimi'] . "</option>";
                } while ($tulos = mysql_fetch_array($kysely));
                ?>
            </select><br />
            <input type="submit" value="Poista oikeudet" />
        </form>
    </div>
    <?php
}
?>
<div id="clear"></div>
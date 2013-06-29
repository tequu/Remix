<hr />
<h3 class="ala_otsikko">Joukkueiden oikeuksien hallinta</h3>
<?php
$kysely2 = kysely($yhteys, "SELECT id, nimi FROM joukkueet WHERE id NOT IN (SELECT joukkueetID FROM oikeudet WHERE joukkueetID!='null' AND tunnuksetID='" . $kayttajatid . "')");
if ($tulos2 = mysql_fetch_array($kysely2)) {
    ?>
    <div class="floatleft">
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?" . get_to_string(); ?>" method="post">
            <input type="hidden" name="ohjaa" value="15" />
            <input type="hidden" name="tunnusid" value="<?php echo $kayttajatid; ?>" />
            <select name="oikeudet[]" multiple="multiple" size="5">
                <?php
                do {
                    echo"<option value='" . $tulos2['id'] . "'>" . $tulos2['nimi'] . "</option>";
                } while ($tulos2 = mysql_fetch_array($kysely2));
                ?>
            </select><br />
            <input type="submit" value="Lisää oikeudet" />
        </form>
    </div>
    <?php
}
$kysely2 = kysely($yhteys, "SELECT id, nimi FROM joukkueet j, oikeudet WHERE j.id=joukkueetID AND tunnuksetID='" . $kayttajatid . "'");
if ($tulos2 = mysql_fetch_array($kysely2)) {
    ?>
    <div class="floatleft" style="margin-left:20px;">
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?" . get_to_string(); ?>" method="post">
            <input type="hidden" name="ohjaa" value="32" />
            <input type="hidden" name="tunnusid" value="<?php echo $kayttajatid; ?>" />
            <select name="oikeudet[]" multiple="multiple" size="5">
                <?php
                do {
                    echo"<option value='" . $tulos2['id'] . "'>" . $tulos2['nimi'] . "</option>";
                } while ($tulos2 = mysql_fetch_array($kysely2));
                ?>
            </select><br />
            <input type="submit" value="Poista oikeudet" />
        </form>
    </div>
    <?php
}
?>
<div class="clear"></div>
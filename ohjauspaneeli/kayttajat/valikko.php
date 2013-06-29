<?php
$kysely = kysely($yhteys, "SELECT id, login FROM tunnukset WHERE login!=''");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <form id="kayttajanvalinta" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="sivuid" value="<?php echo $okayttajat; ?>" />
        <input type="hidden" name="mode" value="muokkaa" />
        <div class="vasen">Valitse käyttäjä</div>
        <div class="vasen"><select name="kayttajatid">
                <?php
                if (!isset($_GET['kayttajatid'])) {
                    echo"<option></option>";
                }
                do {
                    echo"<option value=\"" . $tulos['id'] . "\"" . (isset($_GET['kayttajatid']) && $_GET['kayttajatid'] == $tulos['id'] ? " SELECTED" : "") . ">" . $tulos['login'] . "</option>";
                } while ($tulos = mysql_fetch_array($kysely));
                ?>
            </select>
        </div>
        <div class="clear"></div>
    </form>
    <?php
}
?>

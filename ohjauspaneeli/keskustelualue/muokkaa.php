<?php
$kysely = kysely($yhteys, "SELECT id, nimi FROM keskustelualueet WHERE keskustelualueryhmatID='" . $keskustelualueryhmatid . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr />
    <div class="ala_otsikko">Keskustelualueen muokkaus</div>
    <form name="muokkaa" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okeskustelualue . "&mode=muokkaa&keskustelualueryhmatid=" . $keskustelualueryhmatid; ?>" method="post">
        <input type="hidden" name="ohjaa" id="ohjaa2" />
        Valitse Keskustelualue:
        <select name="keskustelualue">
            <?php
            do {
                echo"<option value=\"" . $tulos['id'] . "\">" . $tulos['nimi'] . "</option>";
            } while ($tulos = mysql_fetch_array($kysely));
            ?>
        </select>
        <br />
        Keskustelualueen nimi: <input type="text" name="nimi" value="<?php echo $_POST['nimi']; ?>" /><br />
        Keskustelualueen kuvaus: <input type="text" name="kuvaus" value="<?php echo $_POST['kuvaus']; ?>" /><br />
        <input type="checkbox" value="1" name="julkinen"<?php echo ($_POST['julkinen'] ? " CHECKED" : ""); ?> />Julkinen<br />
        <input type="button" value="Muokkaa" onclick="laheta('muokkaa', ['ohjaa2'], ['9'])" />
        <input type="button" value="Poista" onclick="laheta('muokkaa', ['ohjaa2'], ['10'])" />
        <br />
        Minne siirret‰‰n:
        <select name="mryhma">
            <?php
            $kysely = kysely($yhteys, "SELECT * FROM keskustelualueryhmat WHERE otsikko!='Joukkueet'");
            while ($tulos = mysql_fetch_array($kysely)) {
                echo"<option value=\"" . $tulos['id'] . "\">" . $tulos['otsikko'] . "</option>";
            }
            ?>
        </select>
        <input type="button" value="Siirr‰" onclick="laheta('muokkaa', ['ohjaa2'], ['11'])" />
    </form>
    <?php
}
?>
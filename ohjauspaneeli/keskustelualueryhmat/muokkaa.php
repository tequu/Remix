<?php
$kysely = kysely($yhteys, "SELECT * FROM keskustelualueryhmat WHERE otsikko != 'Joukkueet'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <h4 class="ala_otsikko">Muokkaa ryhm‰‰</h4>
    <form name="muokkaakeskustelualueryhmaa" action="<?php echo $_SERVER['PHP_SELF'] . "?".  get_to_string(); ?>" method="post">
        <input type="hidden" name="ohjaa" id="ohjaa" />
        <div class="vasen">Valitse ryhm‰</div><div class="oikea"><select name="ryhma">
                <?php
                do {
                    echo"<option value=\"" . $tulos['id'] . "\"" . ($_POST['ryhma'] == $tulos['id'] ? "selected=\"selected\"" : "") . ">" . $tulos['otsikko'] . "</option>\n";
                } while ($tulos = mysql_fetch_array($kysely));
                ?>
            </select>
        </div>
        <div class="vasen">Uusi nimi</div><div class="oikea"><input type="text" name="otsikko" value="<?php echo $_POST['nimi']; ?>" /> <span class="error"><?php echo $error['muokkaa']['otsikko']; ?></span></div>
        <div class="clear"></div>
        <input type="button" value="Muokkaa nimi" onclick="laheta('muokkaakeskustelualueryhmaa', ['ohjaa'], ['6'])" />
        <input type="button" value="Poista" onclick="laheta('muokkaakeskustelualueryhmaa', ['ohjaa'], ['7'])" />
    </form>
    <?php
}
?>

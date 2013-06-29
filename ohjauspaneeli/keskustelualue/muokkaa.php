<?php
$kysely = kysely($yhteys, "SELECT id, nimi FROM keskustelualueet WHERE keskustelualueryhmatID='" . $keskustelualueryhmatid . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <h4 class="ala_otsikko">Keskustelualueen muokkaus</h4>


    <div class="vasen">Valitse Keskustelualue</div>
    <div class="oikea">
        <select id="keskustelualue">
            <?php
            do {
                echo"<option value=\"" . $tulos['id'] . "\">" . $tulos['nimi'] . "</option>";
            } while ($tulos = mysql_fetch_array($kysely));
            ?>
        </select>
    </div>

    <h5 class="ala_otsikko">Tietojen muokkaus</h5>
    <form id="muokkaaform" action="<?php echo $_SERVER['PHP_SELF'] . "?" . get_to_string(); ?>" method="post">
        <input type="hidden" name="ohjaa" id="ohjaatiedot" />
        <input type="hidden" name="keskustelualue" id="form_keskustelualue" />
        <div class="vasen">Keskustelualueen nimi</div><div class="oikea"><input type="text" name="nimi" value="<?php echo $_POST['nimi']; ?>" /> <span class="error"><?php echo $error['muokkaa']['nimi']; ?></span></div>
        <div class="vasen">Keskustelualueen kuvaus</div><div class="oikea"><input type="text" name="kuvaus" value="<?php echo $_POST['kuvaus']; ?>" /> <span class="error"><?php echo $error['muokkaa']['kuvaus']; ?></span></div>
        <div class="vasen">Julkinen</div><div class="oikea"><input type="checkbox" value="1" name="julkinen"<?php echo ($_POST['julkinen'] ? " CHECKED" : ""); ?> /></div>
        <div class="clear"></div>
        <input type="button" value="Muokkaa" id="muokkaa"/>
        <input type="button" value="Poista" id="poista" />
    </form>
    <form id="siirraform" action="<?php echo $_SERVER['PHP_SELF'] . "?" . get_to_string(array()); ?>" method="post">
        <input type="hidden" name="ohjaa" value="11" />
        <div class="vasen">Minne siirret‰‰n</div>
        <div class="oikea">
            <select name="mryhma">
                <?php
                $kysely = kysely($yhteys, "SELECT * FROM keskustelualueryhmat WHERE otsikko!='Joukkueet'");
                while ($tulos = mysql_fetch_array($kysely)) {
                    echo"<option value=\"" . $tulos['id'] . "\">" . $tulos['otsikko'] . "</option>";
                }
                ?>
            </select>
            <input type="button" value="Siirr‰" id="siirra" />
        </div>
        <div class="clear"></div>
    </form>
    <?php
}
?>
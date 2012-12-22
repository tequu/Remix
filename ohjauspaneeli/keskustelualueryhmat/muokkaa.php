<?php
$kysely = kysely($yhteys, "SELECT * FROM keskustelualueryhmat");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr />
    <div class="ala_otsikko">Muokkaa ryhm‰‰</div>
    <form name="muokkaakeskustelualueryhmaa" action="<?php echo $_SERVER['PHP_SELF']."?sivuid=".$okeskustelualueryhma; ?>" method="post">
        <input type="hidden" name="ohjaa" id="ohjaa" />
        Valitse ryhm‰: <select name="ryhma">
            <?php
            do {
                echo"<option value=\"" . $tulos['id'] . "\"" . ($_POST['ryhma'] == $tulos['id'] ? "selected=\"selected\"" : "") . ">" . $tulos['otsikko'] . "</option>\n";
            } while ($tulos = mysql_fetch_array($kysely));
            ?>
        </select><br /><br />
        Uusi nimi: <input type="text" name="otsikko" value="<?php echo $_POST['nimi']; ?>" /><br />
        <input type="button" value="Muokkaa nimi" onclick="laheta('muokkaakeskustelualueryhmaa', ['ohjaa'], ['6'])" /><input type="button" value="Poista" onclick="laheta('muokkaakeskustelualueryhmaa', ['ohjaa'], ['7'])" />
    </form>
    <?php
}
?>

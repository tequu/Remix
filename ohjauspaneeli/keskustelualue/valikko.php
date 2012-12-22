<?php
$kysely = kysely($yhteys, "SELECT id, otsikko FROM keskustelualueryhmat WHERE otsikko!='Joukkueet'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr />
    <form name="muokkaakeskustelualuetta" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="sivuid" value="<?php echo $okeskustelualue; ?>" />
        <input type="hidden" name="mode" value="muokkaa" />
        Valitse keskustelualue ryhmä:<select name="keskustelualueryhmatid" onchange="laheta('muokkaakeskustelualuetta',[],[])">
            <?php
            echo!isset($_GET['keskustelualueryhmatid']) ? "<option></option>" : "";
            do {
                echo"<option value=\"" . $tulos['id'] . "\"" . ($_GET['keskustelualueryhmatid'] == $tulos['id'] ? " SELECTED" : "") . ">" . $tulos['otsikko']. "</option>";
            } while ($tulos = mysql_fetch_array($kysely))
            ?>
        </select>
    </form>
    <?php
} else {
     echo "Et voi luoda uusia keskustelualueita ennen kuin on luotu vähintään yksi keskustelualue ryhmä.";
}
?>
<div id="takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $ojohtokunta; ?>')">Takaisin</div>
<div class="ala_otsikko">Muokkaa johtokunnan jäsentä</div>
<?php
$jasenetid = mysql_real_escape_string($_GET['jasenetid']);
$kysely = kysely($yhteys, "SELECT etunimi, sukunimi, arvo, sahkoposti, puhelinnumero FROM johtokunta WHERE id='" . $jasenetid . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <form aciton="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $ojohtokunta . "&mode=muokkaa&jasenetid=" . $jasenetid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="54" />
        <table>
            <tr>
                <td>Etunimi:</td>
                <td><input type="text" name="etunimi" value="<?php echo $tulos['etunimi']; ?>" /></td>
            </tr>
            <tr>
                <td>Sukunimi:</td>
                <td><input type="text" name="sukunimi" value="<?php echo $tulos['sukunimi']; ?>" /></td>
            </tr>
            <tr><td height="10px" colspan="2"></td></tr>
            <tr>
                <td>Arvonimi:</td>
                <td>
                    <input type="radio" id="meiarvonimea" name="arvovalinta" value="ei" onclick="$('#arvonimit').hide()"
                           <?php echo (!isset($_POST['arvovalinta']) && $tulos['arvo']) || $_POST['arvovalinta'] == "ei" ? " CHECKED" : ""; ?> />
                    <label for='meiarvonimea'>Ei arvonimeä</label><br />
                    <input type="radio" name="arvovalinta" value="luettelo" onclick="$('#arvonimit').hide()"
                    <?php
                    echo (!isset($_POST['arvovalinta']) && ($tulos['arvo'] == 'Puheenjohtaja' || $tulos['arvo'] == 'Sihteeri' ||
                    $tulos['arvo'] == 'Rahastonhoitaja' || $tulos['arvo'] == 'Varustevastaava' || $tulos['arvo'] == 'Tiedottaja')) ||
                    $_POST['arvovalinta'] == "luettelo" ? " CHECKED" : "";
                    ?>
                           />
                    <select name="arvonimil">
                        <option<?php echo (!isset($_POST['arvonimil']) && $tulos['arvo'] == "Puheenjohtaja") || $_POST['arvonimil'] == "Puheenjohtaja" ? " SELECTED" : ""; ?>>Puheenjohtaja</option>
                        <option<?php echo (!isset($_POST['arvonimil']) && $tulos['arvo'] == "Sihteeri") || $_POST['arvonimil'] == "Sihteeri" ? " SELECTED" : ""; ?>>Sihteeri</option>
                        <option<?php echo (!isset($_POST['arvonimil']) && $tulos['arvo'] == "Rahastonhoitaja") || $_POST['arvonimil'] == "Rahastonhoitaja" ? " SELECTED" : ""; ?>>Rahastonhoitaja</option>
                        <option<?php echo (!isset($_POST['arvonimil']) && $tulos['arvo'] == "Varustevastaava") || $_POST['arvonimil'] == "Varustevastaava" ? " SELECTED" : ""; ?>>Varustevastaava</option>
                        <option<?php echo (!isset($_POST['arvonimil']) && $tulos['arvo'] == "Tiedottaja") || $_POST['arvonimil'] == "Tiedottaja" ? " SELECTED" : ""; ?>>Tiedottaja</option>
                    </select>
                    <br />
                    <?php
                    if ((($tulos['arvo'] != 'ei' && $tulos['arvo'] != 'Puheenjohtaja' && $tulos['arvo'] != 'Sihteeri' && $tulos['arvo'] != 'Rahastonhoitaja' && $tulos['arvo'] != 'Varustevastaava' && $tulos['arvo'] != 'Tiedottaja') && (!isset($_POST['arvovalinta']))) || $_POST['arvovalinta'] == 'muu') {
                        ?>
                        
                        <input type="radio" name="arvovalinta" id="mmuu" value="muu" onclick="$('#arvonimit').show();" CHECKED /><label for="mmuu">Jokin muu, mikä?</label><br />
                        <div id="arvonimit">
                            <input type="text" name="arvonimit" value="<?php echo $tulos['arvo']; ?>" />
                        </div>
                        <?php
                    } else {
                        ?>
                        <input type="radio" name="arvovalinta" id="mmuu" value="muu" onclick="$('#arvonimit').show();" /><label for="mmuu">Jokin muu, mikä?</label><br />
                        <div id="arvonimit" style="display:none">
                            <input type="text" name="arvonimit" />
                        </div>
                        <?php
                    }
                    ?>
                    </td>
            </tr>
            <tr>
                <td height="10px" colspan="2"></td>
            </tr>
            <tr>
                <td>Sähköposti:</td>
                <td><input type="text" name="sahkoposti" value="<?php echo $tulos['sahkoposti']; ?>" /></td>
            </tr>
            <tr>
                <td>Puhelin:</td>
                <td><input type="text" name="puhelin" value="<?php echo $tulos['puhelinnumero']; ?>" /></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="Muuta" />
                <input type="button" value="Poista" onclick="siirry('<?php echo $_SERVER['PHP_SELF']."?sivuid=".$ojohtokunta."&mode=poista&jasenetid=".$jasenetid; ?>')"</td>
            </tr>
        </table>
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Johtokunnan jäsentä ei löydy.";
    siirry("virhe.php");
}
?>

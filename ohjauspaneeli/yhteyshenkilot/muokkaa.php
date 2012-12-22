<hr />
<div id="takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $oyhteyshenkilot . "&joukkue=" . $joukkue; ?>')">Takaisin</div>
<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Muokkaa yhteyshenkilöä</div>
<?php
$yhteyshenkilotid = mysql_real_escape_string($_GET['yhteyshenkilotid']);
$kysely = kysely($yhteys, "SELECT etunimi, sukunimi, syntymavuosi, email, enabled, rooli, tiedot FROM joukkueet j, yhteyshenkilot yh, tunnukset t ".
        "WHERE j.id=joukkueetID AND tunnuksetID=t.id AND j.id='" . $joukkue . "' AND t.id='" . $yhteyshenkilotid . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $oyhteyshenkilot . "&joukkue=" . $joukkue . "&mode=muokkaa&yhteyshenkilotid=" . $yhteyshenkilotid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="71" />
        <span id="bold">Etunimi: </span><?php echo ($tulos['enabled']?$tulos['etunimi']:"<input type=\"text\" name=\"etunimi\" value=\"".$tulos['etunimi']."\" />"); ?>
        <br />
        <span id="bold">Sukunimi: </span><?php echo ($tulos['enabled']?$tulos['sukunimi']:"<input type=\"text\" name=\"sukunimi\" value=\"".$tulos['sukunimi']."\" />"); ?>
        <br />
        <span id="bold">Syntymävuosi: </span><?php echo ($tulos['enabled']?$tulos['syntymavuosi']:"<input type=\"text\" name=\"syntymavuosi\" value=\"".$tulos['syntymavuosi']."\" />"); ?>
        <br />
        <span id="bold">Sähköposti: </span><?php echo ($tulos['enabled']?$tulos['email']:"<input type=\"text\" name=\"email\" value=\"".$tulos['email']."\" />"); ?>
        <br />
        <div><span id="bold">Lisätietoa: </span>(esim. Yhteystiedot)</div>
        <textarea name="tiedot" rows="4" cols="25"><?php echo $tulos['tiedot']; ?></textarea><br />
        <span id="bold">Rooli: </span>
        <?php
        foreach ($yhteyshenkiloroolit as $rooli) {
            echo "<input type=\"radio\" name=\"rooli\" value=\"" . $rooli . "\" " . ($rooli == $tulos['rooli'] || (!empty($tulos['rooli']) && !in_array($tulos['rooli'], $yhteyshenkiloroolit) && $rooli == $yhteyshenkiloroolit[count($yhteyshenkiloroolit) - 1]) ? "CHECKED " : "") . "/>" . $rooli . " ";
        }
        ?>
        <input type="text" name="muu"<?php echo (!in_array($tulos['rooli'], $yhteyshenkiloroolit) ? " value=\"" . $tulos['rooli'] . "\"" : "") ?> />
        <br />
        <input type="submit" value="Muokkaa" /><input type="button" value="Poista" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $oyhteyshenkilot . "&joukkue=" . $joukkue . "&mode=poista&yhteyshenkilotid=" . $yhteyshenkilotid; ?>')" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Yhteyshenkilöä ei löytynyt.";
    siirry("virhe.php");
}
?>

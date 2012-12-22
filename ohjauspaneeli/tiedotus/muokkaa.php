<hr />
<div id="takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otiedotus . "&joukkue=" . $joukkue; ?>')">Takaisin</div>
<div class="ala_otsikko">Muokkaa tiedotusta</div>
<div id="error"><?php echo $error; ?></div>
<?php
$tiedotuksetid = mysql_real_escape_string($_GET['tiedotuksetid']);
$kysely = kysely($yhteys, "SELECT tiedotus, UNIX_TIMESTAMP(vanhenemisaika) vanhenemisaika, eivanhenemisaikaa FROM tiedotukset t, joukkueet j " .
        "WHERE joukkueetID=j.id AND t.id='" . $tiedotuksetid . "' AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    if(!$tulos['eivanhenemisaikaa']){
        $aika = getdate($tulos['vanhenemisaika']);
        $paiva = $aika['mday'];
        $kuukausi = $aika['mon'];
        $vuosi = $aika['year'];
    }
    if (isset($_POST['tiedotus']))
        $tiedotus = mysql_real_escape_string($_POST['tiedotus']);
    else
        $tiedotus = preg_replace("/\<br \/\>/", "\n", $tulos['tiedotus']);
    if (!$tulos['eivanhenemisaikaa'])
        $vanhenemisaika = getdate($tulos['vanhenemisaika']);
    else
        $vanhenemisaika = getdate();
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otiedotus . "&joukkue=" . $joukkue . "&mode=muokkaa&tiedotuksetid=" . $tiedotuksetid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="42" />
        Anna vanhemis aika:<br />
        <?php
        include("/home/fbcremix/public_html/Remix/ohjelmat/paivamaaranvalitsin.php");
        ?>
        <br />
        <input type="checkbox" name="eivanhenemisaikaa" value="1"<?php echo (!isset($_POST['eivanhenemisaikaa']) && $tulos['eivanhenemisaikaa']) || $_POST['eivanhenemisaikaa'] ? " CHECKED" : ""; ?>>Ei vanhenemis päivää
        <br /><br />
        Tiedotus:<br />
        <textarea name="tiedotus" rows="10" cols="50"><?php echo $tiedotus; ?></textarea>
        <br />
        <input type="submit" value="Lähetä">
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Tiedotusta ei löytynyt.";
    siirry("virhe.php");
}
?>

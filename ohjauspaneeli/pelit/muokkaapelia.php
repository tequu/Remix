<hr />
<div id="takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelit . "&joukkue=" . $joukkue."&mode=muokkaa&peliryhmatid=".$peliryhmatid; ?>')">Takaisin</div>
<div id='error'><?php echo $error; ?></div>

<?php
$pelitid = $_GET['pelitid'];
$kysely = kysely($yhteys, "SELECT vastustaja, UNIX_TIMESTAMP(aika) aika, koti, kotimaalit, vierasmaalit, pelattu, pelipaikka, kotiturnaus FROM pelit p, peliryhmat pr, joukkueet j " .
        "WHERE peliryhmatID=pr.id AND joukkueetID=j.id AND p.id='" . $pelitid . "' AND pr.id='" . $peliryhmatid . "' AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelit/muokkaatulosta.php");
    $aika = getdate($tulos['aika']);
    $paiva = $aika['mday'];
    $kuukausi = $aika['mon'];
    $vuosi = $aika['year'];
    $tunti = $aika['hours'];
    $minuutti = $aika['minutes'];
    ?>
    <hr />
    <div class='ala_otsikko'>Muokkaa peliä</div>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelit . "&joukkue=" . $joukkue . "&mode=muokkaapelia&peliryhmatid=" . $peliryhmatid . "&pelitid=" . $pelitid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="46" />
        <?php
        include("/home/fbcremix/public_html/Remix/ohjelmat/paivamaaranvalitsin.php");
        ?>
        <br />
        <?php
        include("/home/fbcremix/public_html/Remix/ohjelmat/kellonaikavalitsin.php");
        ?>
    <br /><br />
    Vastustaja:<br /> <input name="vastustaja" type="text" value="<?php echo $tulos['vastustaja']; ?>" /><br /><br />
    FBC Remix:<br /> 
    <input name="kv" type="radio" value="koti"<?php echo ((!isset($_POST['kv']) && $tulos['koti']) || $_POST['kv'] == "koti"?" CHECKED":""); ?> />Koti
    <input name="kv" type="radio" value="vieras"<?php echo ((!isset($_POST['kv']) && !$tulos['koti']) || $_POST['kv'] == "vieras"?" CHECKED":""); ?> />Vieras
    <br /><br />
    <input type="checkbox" name="kotiturnaus" value="1"<?php echo ((!isset($_POST['kotiturnaus']) && $tulos['kotiturnaus']) || $_POST['kotiturnaus']?" CHECKED":""); ?> />Kotiturnaus
    <br />
    Paikka:<br /> <input name="paikka" type="text" value="<?php echo $tulos['pelipaikka']; ?>" /><br />
    <input type="submit" value="Muokkaa" /> <input type="button" value="Poista" onclick="siirry('<?php echo $_SERVER['PHP_SELF']."?sivuid=".$opelit."&joukkue=".$joukkue."&mode=poistapeli&peliryhmatid=".$peliryhmatid."&pelitid=".$pelitid; ?>')" />
</form>
<?php
} else {
    $_SESSION['virhe'] = "Peliä ei löytynyt.";
    siirry("virhe.php");
}
?>

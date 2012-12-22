<hr />
<div class='ala_otsikko'>Poista pelaaja</div>
<div id='error'><?php echo $error ?></div>
<?php
$yhteyshenkilotid = mysql_real_escape_string($_GET['yhteyshenkilotid']);
$kysely = kysely($yhteys, "SELECT etunimi, sukunimi, syntymavuosi, email, rooli FROM joukkueet j, yhteyshenkilot yh, tunnukset t ".
        "WHERE j.id=joukkueetID AND tunnuksetID=t.id AND t.id='".$yhteyshenkilotid."' AND j.id='".$joukkue."' AND kausi='".$kausi."'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
<span id="bold">Nimi:</span> <?php echo $tulos['etunimi']." ".$tulos['sukunimi']; ?><br />
<span id="bold">Syntymävuosi:</span> <?php echo $tulos['syntymavuosi']; ?><br />
<span id="bold">Sähköposti:</span> <?php echo $tulos['email']; ?><br />
<span id="bold">Rooli:</span> <?php echo $tulos['rooli']; ?><br />
(Pelaajan tunnusta ei poisteta jos se on aktivoitu. Jos taas tunnusta ei ole aktivoitu ja sitä ei ole yhdistetty muihin joukkueisiin niin se poistetaan.)<br />
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $oyhteyshenkilot . "&joukkue=" . $joukkue . "&mode=poista&yhteyshenkilotid=".$yhteyshenkilotid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="72" />
        <div id="bold">Haluatko varmasti poistaa?</div>
        <input type="submit" value="Kyllä" />
        <input type="button" value="En" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $oyhteyshenkilot . "&joukkue=" . $joukkue . "&mode=muokkaa&yhteyshenkilotid=".$yhteyshenkilotid; ?>')" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Sarjataulukkoa ei löytynyt.";
    siirry("virhe.php");
}
?>
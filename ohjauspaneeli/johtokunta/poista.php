<div class='ala_otsikko'>Poista johtokunnan jäsen</div>
<div id='error'><?php echo $error ?></div>
<?php
$jasenetid = mysql_real_escape_string($_GET['jasenetid']);
$kysely = kysely($yhteys, "SELECT etunimi, sukunimi, arvo, sahkoposti, puhelinnumero FROM johtokunta WHERE id='" . $jasenetid . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <span id="bold">Etunimi</span>: <?php echo $tulos['etunimi']; ?><br />
    <span id="bold">Sukunimi</span>: <?php echo $tulos['sukunimi']; ?><br />
    <span id="bold">Arvo</span>: <?php echo $tulos['arvo'] == "ei" ? "Ei arvonimeä" : $tulos['arvo']; ?><br />
    <span id="bold">Sähköposti</span>: <?php echo $tulos['sahkoposti']; ?><br />
    <span id="bold">Puhelinnumero</span>: <?php echo $tulos['puhelinnumero']; ?><br /><br />
    <form aciton="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $ojohtokunta . "&mode=poista&jasenetid=" . $jasenetid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="55" />
        Haluatko varmasti poistaa?<br />
        <input type="submit" value="Kyllä" />
        <input type="button" value="En" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $ojohtokunta."&mode=muokkaa&jasenetid=".$jasenetid; ?>')" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Johtokunnan jäsentä ei löytynyt.";
    siirry("virhe.php");
}
?>

<hr />
<div class='ala_otsikko'>Poista pelaaja</div>
<div id='error'><?php echo $error ?></div>
<?php
$pelaajatid = mysql_real_escape_string($_GET['pelaajatid']);
$kysely = kysely($yhteys, "SELECT etunimi, sukunimi, syntymavuosi, email, O, RLM, RLY, RM, S, M, plusmiinus FROM joukkueet j, tilastoryhmat tr, tilastot, tunnukset t ".
        "WHERE j.id=joukkueetID AND tr.id=tilastoryhmatID AND tunnuksetID=t.id AND j.id='" . $joukkue . "' AND tr.id='".$tilastoryhmatid."' AND t.id='" . $pelaajatid . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
<span id="bold">Nimi:</span> <?php echo $tulos['etunimi']." ".$tulos['sukunimi']; ?><br />
<span id="bold">Syntymävuosi:</span> <?php echo $tulos['syntymavuosi']; ?><br />
<span id="bold">Sähköposti:</span> <?php echo $tulos['email']; ?><br />
<span id="bold">Pelinumero:</span> <?php echo $tulos['pelinumero']; ?><br />
<span id="bold">Rooli:</span> <?php echo $tulos['rooli']; ?><br />
(Pelaajan tunnusta ei poisteta jos se on aktivoitu. Jos taas tunnusta ei ole aktivoitu ja sitä ei ole yhdistetty muihin joukkueisiin niin se poistetaan.)<br />
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otilastot . "&joukkue=" . $joukkue . "&mode=poistapelaaja&tilastoryhmatid=".$tilastoryhmatid."&pelaajatid=".$pelaajatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="60" />
        <div id="bold">Haluatko varmasti poistaa?</div>
        <input type="submit" value="Kyllä" />
        <input type="button" value="En" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otilastot . "&joukkue=" . $joukkue . "&mode=muokkaapelaajaa&tilastoryhmatid=".$tilastoryhmatid."&pelaajatid=".$pelaajatid; ?>')" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Sarjataulukkoa ei löytynyt.";
    siirry("virhe.php");
}
?>
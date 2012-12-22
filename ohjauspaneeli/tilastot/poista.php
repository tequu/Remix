<hr />
<div class='ala_otsikko'>Poista tilasto</div>
<?php
$kysely = kysely($yhteys, "SELECT tr.nimi nimi FROM tilastoryhmat tr, joukkueet j WHERE joukkueetID=j.id AND tr.id='" . $tilastoryhmatid . "' AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    $kysely = kysely($yhteys, "SELECT etunimi, sukunimi, O, RLM, RLY, RM, S, M, plusmiinus FROM tunnukset t, tilastot WHERE t.id=tunnuksetID AND tilastoryhmatID='" . $tilastoryhmatid . "'");
    tulostaTilasto($kysely, $tulos['nimi']);
    ?>
    <form action="<?php $_SERVER['PHP_SELF'] . "?sivuid=" . $otilastot . "&joukkue=" . $joukkue . "&mode=muokkaa&tilastoryhmatid=" . $tilastoryhmatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="62" />
        Haluatko varmasti poistaa?<br />
        <input type="submit" value="Kyllä" />
        <input type="button" value="En" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otilastot . "&joukkue=" . $joukkue."&mode=muokkaa&tilastoryhmatid=".$tilastoryhmatid; ?>')" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Pelilistaa ei löytynyt.";
    siirry("virhe.php");
}
?>

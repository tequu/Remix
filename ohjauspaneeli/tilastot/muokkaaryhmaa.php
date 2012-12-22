<?php
$kysely = kysely($yhteys, "SELECT tr.nimi nimi, oletus FROM tilastoryhmat tr, joukkueet j WHERE joukkueetID=j.id AND j.id='" . $joukkue . "' AND tr.id='" . $tilastoryhmatid . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <div class="ala_otsikko">Muokkaa tilastoa</div>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otilastot . "&joukkue=" . $joukkue . "&mode=muokkaa&tilastoryhmatid=" . $tilastoryhmatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="61" />
        Tilaston nimi:<input type="text" name="nimi" value="<?php echo $tulos['nimi']; ?>" />
        <br />
    Oletus <input type="checkbox" name="oletus" value="1"<?php echo (!isset($_POST['oletus']) && $tulos['oletus']) || $_POST['oletus']?" CHECKED":""; ?> /><br />
    <input type="submit" value="Muokkaa" /><input type="button" value="Poista" onclick="siirry('<?php echo $_SERVER['PHP_SELF']."?sivuid=".$otilastot."&joukkue=".$joukkue."&mode=poista&tilastoryhmatid=".$tilastoryhmatid; ?>')" />
</form>
<?php
} else {
    $_SESSION['virhe'] = "Tilastoa ei löydy.";
    siirry("virhe.php");
}
?>

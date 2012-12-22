<?php
$kysely = kysely($yhteys, "SELECT pr.nimi nimi, oletus FROM peliryhmat pr, joukkueet j WHERE joukkueetID=j.id AND j.id='" . $joukkue . "' AND pr.id='" . $peliryhmatid . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <div class="ala_otsikko">Muokkaa pelilistaa</div>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelit . "&joukkue=" . $joukkue . "&mode=muokkaa&peliryhmatid=" . $peliryhmatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="49" />
        Pelilistan nimi:<input type="text" name="nimi" value="<?php echo $tulos['nimi']; ?>" />
        <br />
    Oletus <input type="checkbox" name="oletus" value="1"<?php echo (!isset($_POST['oletus']) && $tulos['oletus']) || $_POST['oletus']?" CHECKED":""; ?> /><br />
    <input type="submit" value="Muokkaa" /><input type="button" value="Poista" onclick="siirry('<?php echo $_SERVER['PHP_SELF']."?sivuid=".$opelit."&joukkue=".$joukkue."&mode=poista&peliryhmatid=".$peliryhmatid; ?>')" />
</form>
<?php
}
?>

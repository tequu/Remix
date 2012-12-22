<div class='ala_otsikko'>Poista pelilista</div>
<?php
$kysely = kysely($yhteys, "SELECT pr.nimi nimi FROM peliryhmat pr, joukkueet j WHERE joukkueetID=j.id AND pr.id='" . $peliryhmatid . "' AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    $kysely = kysely($yhteys, "SELECT vastustaja, UNIX_TIMESTAMP(aika) aika, koti, kotimaalit, vierasmaalit, pelipaikka, pelattu FROM pelit WHERE peliryhmatID='" . $peliryhmatid . "' ORDER BY aika");
    tulostapelit($kysely, $tulos['nimi'], false, 0);
    ?>
    <form action="<?php $_SERVER['PHP_SELF'] . "?sivuid=" . $opelit . "&joukkue=" . $joukkue . "&mode=muokkaa&peliryhmatid=" . $peliryhmatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="50" />
        Haluatko varmasti poistaa?<br />
        <input type="submit" value="Kyllä" />
        <input type="button" value="En" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelit . "&joukkue=" . $joukkue."&mode=muokkaa&peliryhmatid=".$peliryhmatid; ?>')" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Pelilistaa ei löytynyt.";
    siirry("virhe.php");
}
?>

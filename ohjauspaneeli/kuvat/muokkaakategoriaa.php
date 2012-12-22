<?php
$kysely = kysely($yhteys, "SELECT kk.nimi nimi FROM kuvakategoriat kk ".
        "WHERE kk.id='" . $kuvakategoriatid . "' AND joukkueetID='".$joukkue."'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <div class="ala_otsikko">Muokkaa kuvakategoriaa</div>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okuvat . "&joukkue=" . $joukkue . "&mode=muokkaa&kuvakategoriatid=" . $kuvakategoriatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="64" />
        Kuvakategorian nimi:<input type="text" name="nimi" value="<?php echo $tulos['nimi']; ?>" />
        <br />
    <input type="submit" value="Muokkaa" /><input type="button" value="Poista" onclick="siirry('<?php echo $_SERVER['PHP_SELF']."?sivuid=".$okuvat."&joukkue=".$joukkue."&mode=poista&kuvakategoriatid=".$kuvakategoriatid; ?>')" />
</form>
<?php
} else {
    $_SESSION['virhe'] = "Kuvakategoriaa ei löydy.";
    siirry("virhe.php");
}
?>

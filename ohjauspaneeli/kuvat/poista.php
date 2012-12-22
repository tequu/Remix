<hr />
<div class="ala_otsikko">Poista kuvakategoria</div>
<div id="bold">Kuva kategoria sisältämän kuvat:</div>
<?php
$kysely = kysely($yhteys, "SELECT kk.nimi nimi FROM kuvakategoriat kk, joukkueet j WHERE joukkueetID=j.id AND kk.id='" . $kuvakategoriatid . "' AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    $kysely = kysely($yhteys, "SELECT kuva FROM kuvat WHERE kuvakategoriatID='" . $kuvakategoriatid . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        do {
            echo $tulos['kuva'] . "<br />";
        } while ($tulos = mysql_fetch_array($kysely));
    } else {
        echo "Ei kuvia<br />";
    }
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okuvat . "&joukkue=" . $joukkue . "&mode=poista&kuvakategoriatid=" . $kuvakategoriatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="65" />
        Haluatko varmasti poistaa?<br />
        <input type="submit" value="Kyllä" />
        <input type="button" value="En" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okuvat . "&joukkue=" . $joukkue . "&mode=muokkaa&kuvakategoriatid=" . $kuvakategoriatid; ?>')" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Kuvaa ei löytynyt.";
    siirry("virhe.php");
}
?>

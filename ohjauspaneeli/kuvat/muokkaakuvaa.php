<hr />
<div id="takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okuvat . "&joukkue=" . $joukkue . "&mode=muokkaa&kuvakategoriatid=" . $kuvakategoriatid; ?>')">Takaisin</div>
<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Muokkaa kuvaa</div>
<?php
$kuvatid = mysql_real_escape_string($_GET['kuvatid']);
if ($joukkue != 0) {
    $kysely = kysely($yhteys, "SELECT kuva, kuvateksti FROM kuvat k, kuvakategoriat kk, joukkueet j " .
            "WHERE kuvakategoriatid=.kk.id AND joukkueetID=j.id AND k.id='" . $kuvatid . "' AND kk.id='" . $kuvakategoriatid . "' AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
} else {
    $kysely = kysely($yhteys, "SELECT kuva, kuvateksti FROM kuvat k, kuvakategoriat kk ".
            "WHERE kuvakategoriatid=.kk.id AND k.id='" . $kuvatid . "' AND kk.id='" . $kuvakategoriatid . "' AND kk.joukkueetID='0'");
}
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <div><img src="/Remix/kuvat/kuvakategoriat/<?php echo $kuvakategoriatid . "/" . $tulos['kuva']; ?>" /></div>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okuvat . "&joukkue=" . $joukkue . "&mode=muokkaakuvaa&kuvakategoriatid=" . $kuvakategoriatid . "&kuvatid=" . $kuvatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="67" />
        <textarea name="kuvateksti"><?php echo $tulos['kuvateksti']; ?></textarea><br />
        <input type="submit" value="Muokkaa" />
        <input type="button" value="Poista" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okuvat . "&joukkue=" . $joukkue . "&mode=poistakuva&kuvakategoriatid=" . $kuvakategoriatid . "&kuvatid=" . $kuvatid; ?>')" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Kuvaa ei löytynyt.";
    siirry("virhe.php");
}
?>
<hr />
<div class="ala_otsikko">Poista kuva</div>
<?php
$kuvatid = mysql_real_escape_string(trim($_GET['kuvatid']));
if ($joukkue != 0) {
    $kysely = kysely($yhteys, "SELECT kuva, kuvateksti FROM kuvat k, kuvakategoriat kk, joukkueet j " .
            "WHERE kuvakategoriatid=.kk.id AND joukkueetID=j.id AND k.id='" . $kuvatid . "' AND kk.id='" . $kuvakategoriatid . "' AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
} else {
    $kysely = kysely($yhteys, "SELECT kuva, kuvateksti FROM kuvat k, kuvakategoriat kk " .
            "WHERE kuvakategoriatid=.kk.id AND k.id='" . $kuvatid . "' AND kk.id='" . $kuvakategoriatid . "' AND kk.joukkueetID='0'");
}
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <div>
        <img src="/Remix/kuvat/kuvakategoriat/<?php echo $kuvakategoriatid . "/" . $tulos['kuva']; ?>" /><br />
        <?php echo $tulos['kuvateksti']; ?>
    </div><br />
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okuvat . "&joukkue=" . $joukkue . "&mode=poistakuva&kuvakategoriatid=" . $kuvakategoriatid . "&kuvatid=" . $kuvatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="68" />
        <div id="bold">Haluatko varmasti poistaa?</div>
        <input type="submit" value="Kyllä" />
        <input type="button" value="En" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okuvat . "&joukkue=" . $joukkue . "&mode=muokkaakuvaa&kuvakategoriatid=" . $kuvakategoriatid . "&kuvatid=" . $kuvatid; ?>')" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Kuvakategoriaa ei löytynyt.";
    siirry("virhe.php");
}
?>

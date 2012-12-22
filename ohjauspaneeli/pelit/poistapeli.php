<hr />
<div class='ala_otsikko'>Poista peli</div>
<?php
$pelitid = mysql_real_escape_string($_GET['pelitid']);
$peliryhmatid = mysql_real_escape_string($_GET['peliryhmatid']);
$kysely = kysely($yhteys, "SELECT vastustaja, UNIX_TIMESTAMP(aika) aika, koti, kotimaalit, vierasmaalit, pelipaikka, pelattu, kotiturnaus FROM pelit p, peliryhmat pr, joukkueet j " .
        "WHERE peliryhmatID=pr.id AND joukkueetID=j.id AND p.id='" . $pelitid . "' AND pr.id='" . $peliryhmatid . "' AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
        ?>
        <span id="bold">Ottelu</span>:
        <?php
        echo $tulos['koti'] ? "FBC Remix - " . $tulos['vastustaja'] : $tulos['vastustaja'] . " - FBC Remix";
        ?>
        <br />
        <span id="bold">Kotiturnaus</span>
        <?php
        echo $tulos['kotiturnaus'] ? "Kyllä" : "Ei";
        ?>
        <br />
        <span id="bold">Peliaika</span>: <?php echo date("d.m.Y H:i", $tulos['aika']); ?><br />
        <span id="bold">Tulos</span>: <?php echo $tulos['kotimaalit'] . "-" . $tulos['vierasmaalit']; ?><br />
        <span id="bold">Pelipaikka</span>: <?php echo $tulos['pelipaikka']; ?>
        <br /><br />
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelit . "&joukkue=" . $joukkue . "&mode=poistapeli&peliryhmatid=" . $peliryhmatid . "&pelitid=" . $pelitid; ?>" method="post">
            <input type="hidden" name="ohjaa" value="78" />
            Haluatko varmasti poistaa tuloksen?<br />
            <input type="submit" value="Kyllä" />
            <input type="button" value="En" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelit . "&joukkue=" . $joukkue . "&mode=muokkaapelia&peliryhmatid=" . $peliryhmatid . "&pelitid=" . $pelitid; ?>')" />
        </form>
        <?php
} else {
    $_SESSION['virhe'] = "Peliä ei löytynyt.";
    siirry("virhe.php");
}
?>

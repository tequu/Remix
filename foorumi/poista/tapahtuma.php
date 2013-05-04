<?php
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
$keskustelu = mysql_real_escape_string($_GET['keskustelu']);
$kysely = kysely($yhteys, "SELECT otsikko FROM tapahtumat t, keskustelut k, keskustelualuekeskustelut kk " .
        "WHERE t.id=k.tapahtumatID AND k.id=kk.keskustelutID AND kk.keskustelualueetID='" . $keskustelualue . "' AND k.id='" . $keskustelu . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    if (tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
        ?>
        <div id="poistatapahtuma">
            <div id="otsikko">Poista tapahtuma</div>
            <div id="error"><?php echo $error; ?></div>
            <div id="laatikko">
                <div id="nimi"><?php echo $tulos['otsikko']; ?></div>
                <form id="poistatapahtuma-form" action="<?php echo $_SERVER['PHP_SELF'] . "?keskustelualue=" . $keskustelualue . "&keskustelu=" . $keskustelu . "&mode=tapahtuma"; ?>" method="post">
                    <input type="hidden" name="ohjaa" value="81" />
                    <input type="hidden" name="keskustelualue" value="<?php echo $keskustelualue; ?>" />
                    <input type="hidden" name="keskustelu" value="<?php echo $keskustelu; ?>" />
                    Haluatko varmasti poistaa tämän tapahtuman?
                    <div id="napit">
                        <div class="poista laheta" data-form="poistatapahtuma-form"></div><div class="takaisin"></div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    } else {
        echo "Ei oikeuksia.";
    }
} else {
    echo "Tapahtumaa ei löytynyt.";
}
?>
<?php
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
$keskustelu = mysql_real_escape_string($_GET['keskustelu']);
$kysely = kysely($yhteys, "SELECT otsikko FROM keskustelut k, keskustelualuekeskustelut kk " .
        "WHERE k.id=kk.keskustelutID AND kk.keskustelualueetID='" . $keskustelualue . "' AND k.id='" . $keskustelu . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    if (tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
        ?>
        <div id="poistakeskustelu">
            <div id="otsikko">Poista tapahtuma</div>
            <div id="error"><?php echo $error; ?></div>
            <div id="laatikko">
                <div id="nimi"><?php echo $tulos['otsikko']; ?></div>
                <form id="poistakeskustelu-form" action="<?php echo $_SERVER['PHP_SELF'] . "?keskustelualue=" . $keskustelualue . "&keskustelu=" . $keskustelu; ?>" method="post">
                    <input type="hidden" name="ohjaa" value="34" />
                    <input type="hidden" name="keskustelualue" value="<?php echo $keskustelualue; ?>" />
                    <input type="hidden" name="keskustelu" value="<?php echo $keskustelu; ?>" />
                    Haluatko varmasti poistaa tämän keskustelun?
                    <div id="napit">
                        <div class="poista laheta" data-form="poistakeskustelu-form"></div><div class="takaisin"></div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    } else {
        echo "Ei oikeuksia.";
    }
} else {
    echo "Keskustelua ei löytynyt.";
}
?>
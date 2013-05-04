<?php
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
$keskustelu = mysql_real_escape_string($_GET['keskustelu']);
$viesti = mysql_real_escape_string($_GET['viesti']);
$kysely = kysely($yhteys, "SELECT id, otsikko, viesti, tunnuksetID FROM viestit v, keskustelualuekeskustelut kk " .
        "WHERE v.keskustelutID=kk.keskustelutID AND id='" . $viesti . "' AND v.keskustelutID='" . $keskustelu . "' AND keskustelualueetID='" . $keskustelualue . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    if ($tulos['tunnuksetID'] == $kayttaja || tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
        ?>
        <div id="pohja">
            <div id="poistaviesti">
                <div id="otsikko">Poista viesti</div>
                <div id="error"><?php echo $error; ?></div>
                <div id="laatikko">
                    <div id="viestinotsikko">
                        <div class="nimi">Otsikko </div><div class="kentta"><?php echo $tulos['otsikko']; ?></div>
                    </div>
                    <div id="clear"></div>
                    <div id="teksti">
                        <div class="nimi">Viesti </div><div class="kentta"><?php echo $tulos['viesti']; ?></div>
                    </div>
                    <form id="poistaviesti-form" action="<?php echo $_SERVER['PHP_SELF'] . "?keskustelualue=" . $keskustelualue . "&keskustelu=" . $keskustelu . "&viesti=" . $viesti; ?>" method="post">
                        <input type="hidden" name="ohjaa" value="29" />
                        <input type="hidden" name="keskustelualue" value="<?php echo $keskustelualue; ?>" />
                        <input type="hidden" name="keskustelu" value="<?php echo $keskustelu; ?>" />
                        <input type="hidden" name="viesti" value="<?php echo $viesti; ?>" />
                        Haluatko varmasti poistaa tämän viestin?
                        <div id="napit">
                            <div class="poista laheta" data-form="poistaviesti-form"></div><div class="takaisin"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    } else {
        ?>
        Ei oikeuksia.
        <?php
    }
} else {
    ?>
    Viestiä ei löytynyt.
    <?php
}
?>
<?php
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
$keskustelu = mysql_real_escape_string($_GET['keskustelu']);
$viesti = mysql_real_escape_string($_GET['viesti']);
$kysely = kysely($yhteys, "SELECT otsikko, viesti FROM viestit WHERE id='".$viesti."' AND keskustelutID='".$keskustelu."'");
$tulos = mysql_fetch_array($kysely);
?>
<div id="muokkaaviestia">
    <div id="otsikko">Muokkaa viestiä</div>
    <div id="error"><?php echo $error; ?></div>
    <div id="lomake">
        <form id="muokkaaviestia-form" action="<?php echo $_SERVER['PHP_SELF'] . "?keskustelualue=" . $keskustelualue . "&keskustelu=" . $keskustelu . "&viesti=" . $viesti; ?>" method="post">
            <input type="hidden" name="ohjaa" value="28" />
            <input type="hidden" name="keskustelualue" value="<?php echo $keskustelualue; ?>" />
            <input type="hidden" name="keskustelu" value="<?php echo $keskustelu; ?>" />
            <input type="hidden" name="viesti" value="<?php echo $viesti; ?>" />
            <div id="viestinotsikko">
                <div class="nimi">Otsikko</div><div class="kentta"><input type="text" name="otsikko" value="<?php echo $tulos['otsikko']; ?>"/></div>
            </div>
            <div id="clear"></div>
            <div id="teksti">
                <div class="nimi">Viesti</div>
                <div class="kentta">
                    <textarea name="teksti" rows="7" cols="90"><?php echo $tulos['viesti']; ?></textarea>
                </div>
            </div>
            <div id="clear"></div>
            <div id="napit">
                <div class="muokkaa laheta" data-form="muokkaaviestia-form"></div><div class="tyhjenna"></div>
            </div>
        </form>
    </div>
</div>
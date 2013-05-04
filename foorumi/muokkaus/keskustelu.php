<?php
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
$keskustelu = mysql_real_escape_string($_GET['keskustelu']);
$kysely = kysely($yhteys, "SELECT otsikko FROM keskustelut k, keskustelualuekeskustelut kk WHERE k.id=kk.keskustelutID AND k.id='" . $keskustelu . "' AND kk.keskustelualueetID='" . $keskustelualue . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    if (tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
        ?>
        <div id="muokkaakeskustelua">
            <div id="otsikko">Muokkaa keskustelua</div>
            <div id="error"><?php echo $error; ?></div>
            <div id="lomake">
                <form id="uusikeskustelu-form" aciton="<?php echo $_SERVER['PHP_SELF'] . "?" . get_to_string(array()); ?>" method="post">
                    <input type="hidden" name="ohjaa" value="82" />
                    <input type="hidden" name="keskustelualue" value="<?php echo $keskustelualue; ?>" />
                    <input type="hidden" name="keskustelu" value="<?php echo $keskustelu; ?>" />
                    <div id="keskustelunotsikko">
                        <div class="nimi">Otsikko</div><div class="kentta"><input type="text" name="otsikko" value="<?php echo isset($_POST['otsikko']) ? $_POST['otsikko'] : $tulos['otsikko']; ?>"/></div>
                    </div>
                    <div id="teksti">

                    </div>
                    <div id="clear"></div>
                    <div id="napit">
                        <div class="muokkaa laheta" data-form="uusikeskustelu-form"></div>
                        <div class="takaisin liiku" data-url="keskustelualue.php?<?php echo get_to_string(array()); ?>"></div>
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
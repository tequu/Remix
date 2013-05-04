<?php
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
if (tarkistaNakyvyysOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
    ?>
    <div id="uusikeskustelu">
        <div id="otsikko">Uusi keskustelu</div>
        <div id="error"><?php echo $error; ?></div>
        <div id="lomake">
            <form id="uusikeskustelu-form" aciton="<?php echo $_SERVER['PHP_SELF'] . "?keskustelualue=" . $keskustelualue . "&mode=keskustelu"; ?>" method="post">
                <input type="hidden" name="ohjaa" value="3" />
                <input type="hidden" name="keskustelualue" value="<?php echo $keskustelualue; ?>" />
                <div id="keskustelunotsikko">
                    <div class="nimi">Otsikko</div><div class="kentta"><input type="text" name="otsikko" value="<?php echo $_POST['otsikko']; ?>"/></div>
                </div>
                <div id="teksti">
                    <div class="nimi">Viesti</div>
                    <div class="kentta"><textarea name="viesti"><?php echo $_POST['viesti']; ?></textarea></div>
                </div>
                <div id="clear"></div>
                <div id="napit">
                    <div class="lisaa laheta" data-form="uusikeskustelu-form"></div>
                    <div class="takaisin liiku" data-url="keskustelualue.php?<?php echo get_to_string(array()); ?>"></div>
                </div>
            </form>
        </div>
    </div>
    <?php
} else {
    echo "Ei oikeuksia keskustelualueelle.";
}
?>

<div id="uusiviesti">
    <div id="otsikko">Uusi viesti</div>
    <div id="error"><?php echo $error; ?></div>
    <div id="lomake">
        <form id="uusiviesti-form" action="<?php echo $_SERVER['PHP_SELF'] . "?".get_to_string(array()); ?>" method="post">
            <input type="hidden" name="ohjaa" value="4" />
            <input type="hidden" name="keskustelualue" value="<?php echo $keskustelualue; ?>" />
            <input type="hidden" name="keskustelu" value="<?php echo $keskustelu; ?>" />
            <div id="viestinotsikko">
                <div class="nimi">Otsikko</div><div class="kentta"><input type="text" name="otsikko" value="<?php echo $_POST['otsikko']; ?>"/></div>
            </div>
            <div id="clear"></div>
            <div id="teksti">
                <div class="nimi">Viesti</div>
                <div class="kentta"><textarea name="viesti" rows="7" cols="90"><?php echo $_POST['viesti']; ?></textarea></div>
            </div>
            <div id="clear"></div>
            <div id="napit">
                <div class="laheta" data-form="uusiviesti-form"></div><div class="takaisin liiku" data-url="keskustelu.php?<?php echo get_to_string(array()); ?>"></div>
            </div>
        </form>
    </div>
</div>
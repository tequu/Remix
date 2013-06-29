<hr />
<h4 class="ala_otsikko">Uusi keskustelualue</h4>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?" . get_to_string(); ?>" method="post">
    <input type="hidden" name="ohjaa" value="8" />
    <div class="vasen">Keskustelualueen nimi</div><div class="oikea"><input type="text" name="nimi" value="<?php echo $_POST['nimi']; ?>"/> <span class="error"><?php echo $error['lisaa']['nimi']; ?></span></div>
    <div class="vasen">Keskustelualueen kuvaus</div><div class="oikea"><input type="text" name="kuvaus" value ="<?php echo $_POST['kuvaus']; ?>"/> <span class="error"><?php echo $error['lisaa']['kuvaus'] ?></span></div>
    <div class="vasen">Julkinen</div><div class="oikea"><input type="checkbox" name="julkinen" value="1"<?php echo ($_POST['julkinen'] ? " CHECKED" : ""); ?> /></div>
    <input type="submit" value="Lisää" />
</form>

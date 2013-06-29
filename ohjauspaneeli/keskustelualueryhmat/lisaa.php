<h4 class="ala_otsikko">Uusi keskustelualue ryhmä</h4>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?".  get_to_string(); ?>" method="post">
    <input type="hidden" name="ohjaa" value="5" />
    <div class="vasen">Ryhmän nimi:</div><div class="oikea"> <input type="text" name="otsikko" /> <span class="error"><?php echo $error['lisaa']['otsikko']; ?></span></div>
    <div class="clear"></div>
    <input type="submit" value="Lisää" />
</form>
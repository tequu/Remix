<h4 class="ala_otsikko">Uusi keskustelualue ryhm�</h4>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?".  get_to_string(); ?>" method="post">
    <input type="hidden" name="ohjaa" value="5" />
    <div class="vasen">Ryhm�n nimi:</div><div class="oikea"> <input type="text" name="otsikko" /> <span class="error"><?php echo $error['lisaa']['otsikko']; ?></span></div>
    <div class="clear"></div>
    <input type="submit" value="Lis��" />
</form>
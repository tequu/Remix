<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Uusi keskustelualue ryhm�</div>
<form action="<?php echo $_SERVER['PHP_SELF']."?sivuid=".$okeskustelualueryhma; ?>" method="post">
    <input type="hidden" name="ohjaa" value="5" />
    Ryhm�n nimi: <input type="text" name="otsikko" />
    <input type="submit" value="Lis��" />
</form>
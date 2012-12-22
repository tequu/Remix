<hr />
<div class="ala_otsikko">Uusi keskustelualue</div>
<div id="error"><?php echo $error; ?></div>
<form action="<?php echo $_SERVER['PHP_SELF']."?sivuid=".$okeskustelualue."&mode=muokkaa&keskustelualueryhmatid=".$keskustelualueryhmatid; ?>" method="post">
    <input type="hidden" name="ohjaa" value="8" />
    Keskustelualueen nimi: <input type="text" name="nimi" value="<?php echo $_POST['nimi']; ?>"/><br />
    Keskustelualueen kuvaus: <input type="text" name="kuvaus" value ="<?php echo $_POST['kuvaus']; ?>"/><br />
    <input type="checkbox" name="julkinen" value="1"<?php echo ($_POST['julkinen'] ? " CHECKED" : ""); ?> />Julkinen<br />
    <input type="submit" value="Lisää" />
</form>

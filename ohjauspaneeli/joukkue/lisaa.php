<hr />
<div class="ala_otsikko">Joukkueen lisääminen</div>
<div id="error"><?php echo $error; ?></div>
<form aciton="<?php echo $_SERVER['PHP_SELF']; ?>?sivuid=<?php echo $ojoukkue; ?>" method="post">
    <input type="hidden" name="ohjaa" value="17" />
    Joukkueen nimi: <input type="text" name="nimi" /><br />
    <input type="checkbox" name="kerho" value="1" /> Kerho<br />
    <input type="submit" value="Lisää" />
</form>

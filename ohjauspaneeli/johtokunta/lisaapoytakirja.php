<hr />
<div class="ala_otsikko">Lisää pöytäkirja</div>
<div id="error"><?php echo $error; ?></div>
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $ojohtokunta; ?>" method="post">
    <input type="hidden" name="ohjaa" value="73" />
    <input type="file" name="poytakirja" />(Maksimi koko 1Mt)
    <br />
    <input type="submit" value="Lähetä" />
</form>

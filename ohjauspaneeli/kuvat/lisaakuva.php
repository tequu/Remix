<hr />
<div class="ala_otsikko">Kuvan lisääminen</div>
<div id="error"><?php echo $error; ?></div>
<form enctype="multipart/form-data" aciton="<?php echo $_SERVER['PHP_SELF']; ?>?sivuid=<?php echo $okuvat; ?>" method="post">
    <input type="hidden" name="ohjaa" value="66" />
    <span id="bold">Kuva: </span><input type="file" name="kuva" /><br />
    <div id="bold">Kuvaus:</div>
    <textarea name="kuvateksti" rows="3" cols="30"><?php echo $_POST['kuvaus']; ?></textarea>
    <br />
    <input type="submit" value="Lisää" />
</form>
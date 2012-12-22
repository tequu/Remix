<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Lis‰‰ uutinen</div>
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']."?sivuid=".$ouutiset; ?>" method="post">
    <input type="hidden" name="ohjaa" value="51" />
    Kuva:<br /><input type="file" name="kuva" />(Maksimi koko 1Mt, 420x230)<br />
    Otsikko:<br />
    <input type="text" name="otsikko" value="<?php echo $_POST['otsikko']; ?>" /><br />
    Kuvaus:<br />
    <textarea name="kuvaus" rows="4" cols="25"><?php echo $_POST['kuvaus']; ?></textarea>
    <br />
    Uutinen:<br />
    <textarea name="uutinen" rows="10" cols="50"><?php echo $_POST['uutinen']; ?></textarea>
    <br /><br />
    <input type="submit" value="L‰het‰" />
</form>

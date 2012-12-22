<div class='ala_otsikko'>Lisää tiedotus</div>
<div id='error'><?php echo $error ?></div>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otiedotus . "&joukkue=" . $joukkue; ?>" method="post">
    <input type='hidden' name='ohjaa' value='41'>
    Anna vanhemis aika:<br />
    <?php
    include("/home/fbcremix/public_html/Remix/ohjelmat/paivamaaranvalitsin.php");
    ?>
    <br />
    <input type="checkbox" name="eivanhenemisaikaa" value="1" <?php echo $_POST['eivanhenemisaikaa'] ? "CHECKED" : ""; ?> />Ei vanhenemis päivää<br />
    <br />
    <br />
    Tiedotus:<br />
    <textarea name="tiedotus" rows="10" cols="50"><?php echo $_POST['tiedotus']; ?></textarea>
    <br />
    <input type="submit" value="Lähetä">
</form>


<hr />
<div class="ala_otsikko">Lis‰‰ peli</div>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelit . "&joukkue=" . $joukkue . "&mode=muokkaa&peliryhmatid=" . $peliryhmatid; ?>" method="post">
    <input type="hidden" name="ohjaa" value="45" />
    <?php
    include("/home/fbcremix/public_html/Remix/ohjelmat/paivamaaranvalitsin.php");
    ?>
    <br />
    <?php
    include("/home/fbcremix/public_html/Remix/ohjelmat/kellonaikavalitsin.php");
    ?>
    <br /><br />
    Vastustaja:<br /> 
    <input name="vastustaja" type="text" value="<?php echo $_POST['vastustaja']; ?>" /><br /><br />
    FBC Remix:<br /> 
    <input type="radio" name="kv" value="koti"<?php echo ($_POST['kv'] == "koti" ? " CHECKED" : ""); ?> />Koti
    <input type="radio" name="kv" value="vieras"<?php echo ($_POST['kv'] == "vieras" ? " CHECKED" : ""); ?> />Vieras
    <br /><br />
    <input type="checkbox" name="kotiturnaus" value="1"<?php ($_POST['kotiturnaus']?" CHECKED":""); ?> />Kotiturnaus
    <br />
    Paikka:<br /> 
    <input name="paikka" type="text" value="<?php echo $_POST['paikka']; ?>" /><br />
    <input type="submit" value="Lis‰‰" />
</form>

<hr />
<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Lis�� uusi yhteyshenkil� joukkueeseen</div>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $oyhteyshenkilot . "&joukkue=" . $joukkue; ?>" method="post">
    <input type="hidden" name="ohjaa" value="69" />
    <span id="bold">Etunimi: </span><input type="text" name="etunimi" value="<?php echo $_POST['etunimi']; ?>" /><br />
    <span id="bold">Sukunimi: </span><input type="text" name="sukunimi" value="<?php echo $_POST['sukunimi']; ?>" /><br />
    <span id="bold">Syntym�vuosi: </span><input type="text" name="syntymavuosi" value="<?php echo $_POST['syntymavuosi']; ?>" /><br />
    <span id="bold">S�hk�posti: </span><input type="text" name="email" value="<?php echo $_POST['email']; ?>" /><br />
    <div><span id="bold">Lis�tietoa: </span>(esim. Yhteystiedot)</div>
    <textarea name="tiedot" rows="4" cols="25"><?php echo $_POST['tiedot']; ?></textarea><br />
    <?php
    foreach ($yhteyshenkiloroolit as $rooli) {
        echo "<input type=\"radio\" name=\"rooli\" value=\"" . $rooli . "\" " . ($_POST['rooli'] == $rooli ? "CHECKED " : "") . "/>" . $rooli . " ";
    }
    ?>
    <input type="text" name="muu" value="<?php $_POST['muu']; ?>" />
    <br />
    <input type="submit" value="Lis��" />
</form>
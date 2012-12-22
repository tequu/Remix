<hr />
<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Lisää uusi henkilö joukkueeseen</div>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelaajat . "&joukkue=" . $joukkue; ?>" method="post">
    <input type="hidden" name="ohjaa" value="21" />
    <span id="bold">Etunimi: </span><input type="text" name="etunimi" value="<?php echo $_POST['etunimi']; ?>" /><br />
    <span id="bold">Sukunimi: </span><input type="text" name="sukunimi" value="<?php echo $_POST['sukunimi']; ?>" /><br />
    <span id="bold">Syntymävuosi: </span><input type="text" name="syntymavuosi" value="<?php echo $_POST['syntymavuosi']; ?>" /><br />
    <span id="bold">Sähköposti: </span><input type="text" name="email" value="<?php echo $_POST['email']; ?>" /><br />
    <span id="bold">Pelinumero: </span><input type="text" name="pelinumero" value="<?php echo $_POST['pelinumero']; ?>" size="2" /><br />
    <span id="bold">Kapteeni: </span><input type="checkbox" name="kapteeni" value="1"<?php echo $_POST['kapteeni']?" CHECKED":""; ?> /><br />
    <?php
    foreach ($pelaajaroolit as $rooli) {
        echo "<input type=\"radio\" name=\"rooli\" value=\"" . $rooli . "\" " . ($_POST['rooli'] == $rooli ? "CHECKED " : "") . "/>" . $rooli . " ";
    }
    ?>
    <input type="text" name="muu" value="<?php $_POST['muu']; ?>" />
    <br />
    <input type="submit" value="Lisää" />
</form>
<hr />
<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Lisää uusi pelaaja tilastoon</div>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otilastot . "&joukkue=" . $joukkue."&mode=muokkaa&tilastoryhmatid=".$tilastoryhmatid; ?>" method="post">
    <input type="hidden" name="ohjaa" value="57" />
    <span id="bold">Etunimi: </span><input type="text" name="etunimi" value="<?php echo $_POST['etunimi']; ?>" /><br />
    <span id="bold">Sukunimi: </span><input type="text" name="sukunimi" value="<?php echo $_POST['sukunimi']; ?>" /><br />
    <span id="bold">Syntymävuosi: </span><input type="text" name="syntymavuosi" value="<?php echo $_POST['syntymavuosi']; ?>" /><br />
    <span id="bold">Sähköposti: </span><input type="text" name="email" value="<?php echo $_POST['email']; ?>" /><br />
    <input type="submit" value="Lisää" />
</form>
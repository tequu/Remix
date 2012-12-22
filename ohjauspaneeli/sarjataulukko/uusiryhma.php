<hr />
<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Luo uusi sarjataulukko</div>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $osarjataulukko . "&joukkue=" . $joukkue; ?>" method="post">
    <input type="hidden" name="ohjaa" value="35" />
    Sarjataulukon nimi:<input type="text" name="nimi" /><br />
    Oletus <input type="checkbox" name="oletus" value="1" /><br />
    <input type="submit" value="Luo" />
</form>

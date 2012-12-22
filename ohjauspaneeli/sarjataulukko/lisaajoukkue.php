<div class="ala_otsikko">Lis‰‰ joukkue sarjataulukkoon</div>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $osarjataulukko . "&joukkue=" . $joukkue . "&mode=muokkaa&sarjataulukkoryhmatid=" . $sarjataulukkoryhmatid; ?>" method="post">
    <input type="hidden" name="ohjaa" value="37" />
    Joukkueen nimi:<input type="text" name="nimi" />
    <br />
    <input type="submit" value="Lis‰‰" />
</form>
<hr />
<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Luo uusi kuvakategoria</div>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okuvat . "&joukkue=" . $joukkue; ?>" method="post">
    <input type="hidden" name="ohjaa" value="63" />
    Kuvakategorian nimi:<input type="text" name="nimi" /><br />
    <input type="submit" value="Luo" />
</form>

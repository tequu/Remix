<div class='ala_otsikko'>Lis‰‰/poista tulos</div>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelit . "&joukkue=" . $joukkue . "&mode=muokkaa&peliryhmatid=" . $peliryhmatid . "&pelitid=" . $pelitid; ?>" method="post">
    <input type="hidden" name="ohjaa" value="47" />
    <input type='text' name='kotimaalit' size='2' value="<?php echo $tulos['pelattu'] ? $tulos['kotimaalit'] : ""; ?>"/> - <input type='text' name='vierasmaalit' size='2' value="<?php echo $tulos['pelattu'] ? $tulos['vierasmaalit'] : ""; ?>" /><br />
    <input type="submit" value="<?php echo $tulos['pelattu'] ? "Muokkaa" : "Lis‰‰"; ?>" />
    <?php echo $tulos['pelattu'] ? "<input type=\"button\" value=\"Poista tulos\" onclick=\"siirry('" . $_SERVER['PHP_SELF'] . "?sivuid=" . $opelit . "&joukkue=" . $joukkue . "&mode=poistatulos&peliryhmatid=" . $peliryhmatid . "&pelitid=" . $pelitid . "')\" />" : ""; ?>
</form>

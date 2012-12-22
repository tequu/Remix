<hr />
<div id="bold">Admin oikeudet</div>
<form name="admin" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=".$okayttajat."&mode=muokkaa&kayttajatid=" . $kayttajatid; ?>" method="post">
    <input type="hidden" name="ohjaa" id="ohjaa2" />
    <input type="radio" name="oikeudet" value="Admin" CHECKED />Admin<br />
    <?php
    if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        ?>
        <input type="radio" name="oikeudet" value="Masteradmin" />Mastedadmin<br />
        <?php
    }
    ?>
    <input type="button" value="Lisää" onclick="laheta('admin', ['ohjaa2'], ['16'])" /><input type="button" value="Poista" onclick="laheta('admin', ['ohjaa2'], ['33'])" />
</form>
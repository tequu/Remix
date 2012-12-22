<form name="joukkueenvalinta" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <input type="hidden" name="sivuid" value="<?php echo $_GET['sivuid']; ?>" />
    Valitse joukkue:<select name="joukkue" onchange="laheta('joukkueenvalinta', [], [])">
        <?php
        if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
            $kysely = kysely($yhteys, "SELECT id, nimi FROM joukkueet");
        } else {
            $kysely = kysely($yhteys, "SELECT id, nimi FROM joukkueet j, oikeudet WHERE j.id=joukkueetID AND tunnuksetID='" . $kayttaja . "'");
        }
        $joukkueolemassa = false;
        if(isset($_GET['joukkue']) && tarkistaJoukkueenOlemassaOlo($yhteys, $joukkue)){
            $joukkueolemassa = true;
        }
        if (!isset($_GET['joukkue']) || ($seura && $joukkue != 0 && !$joukkueolemassa) || (!$seura && !$joukkueolemassa)) {
            echo "<option></option>";
        }
        if ($seura && tarkistaAdminOikeudet($yhteys, "Admin")) {
            echo "<option value=\"0\"" . (isset($_GET['joukkue']) && $_GET['joukkue'] == 0 ? " SELECTED" : "") . ">Seura</option>";
        }
        while ($tulos = mysql_fetch_array($kysely)) {
            echo"<option value=\"" . $tulos['id'] . "\"" . (isset($_GET['joukkue']) && $_GET['joukkue'] == $tulos['id'] ? " SELECTED" : "") . ">" . $tulos['nimi'] . "</option>";
        }
        ?>
    </select>
</form>

<?php
$uutisetid = mysql_real_escape_string($_GET['uutisetid']);
$kysely = kysely($yhteys, "SELECT uutinen, otsikko, kuvaus, kuva, tunnuksetID FROM uutiset WHERE id='" . $uutisetid . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    if(($_SESSION['id'] == 0 || $_SESSION['id'] != $tulos['tunnuksetID']) && !tarkistaAdminOikeudet($yhteys, "Masteradmin")){
        siirry("eioikeuksia.php");
    }
    ?>
    <div id="error"><?php echo $error; ?></div>
    <div class="ala_otsikko">Muokkaa uutista</div>
    <?php
    if($tulos['kuva'] != "ei" && !empty($tulos['kuva'])){
        echo $tulos['kuva'];
        ?>
        <input type="button" value="Poista kuva" onclick="siirry('<?php echo $_SERVER['PHP_SELF']."?sivuid=".$ouutiset."&mode=poistakuva&uutisetid=".$uutisetid; ?>')" />
        <?php
    }
    ?>
    <form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $ouutiset . "&mode=muokkaa&uutisetid=" . $uutisetid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="52" />
        Kuva: (Korvaa vanhan kuvan)<br /><input type="file" name="kuva" />(Maksimi koko 1Mt, 420x230)<br />
        Otsikko:<br />
        <input type="text" name="otsikko" value="<?php echo isset($_POST['otsikko']) ? $_POST['otsikko'] : $tulos['otsikko']; ?>" /><br />
        Kuvaus:<br />
        <textarea name="kuvaus" rows="4" cols="25"><?php echo isset($_POST['kuvaus']) ? $_POST['kuvaus'] : $tulos['kuvaus']; ?></textarea>
        <br />
        Uutinen:<br />
        <textarea name="uutinen" rows="10" cols="50"><?php echo isset($_POST['uutinen']) ? $_POST['uutinen'] : $tulos['uutinen']; ?></textarea>
        <br /><br />
        <input type="submit" value="Muokkaa" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Uutista ei löydy.";
    siirry("virhe.php");
}
?>

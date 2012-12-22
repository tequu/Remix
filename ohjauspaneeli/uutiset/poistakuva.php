<?php
$uutisetid = mysql_real_escape_string($_GET['uutisetid']);
$kysely = kysely($yhteys, "SELECT otsikko, UNIX_TIMESTAMP(kirjoitusaika) kirjoitusaika, kuva, tunnuksetID FROM uutiset WHERE id='" . $uutisetid . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    if (($_SESSION['id'] == 0 || $_SESSION['id'] != $tulos['tunnuksetID']) && !tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
        siirry("eioikeuksia.php");
    }
    $kuvanotsikko = muutaTekstiHyvaksyttavaanMuotoon($tulos['otsikko']);
    ?>
    <div class="ala_otsikko">Poista uutisen kuva</div>
    <?php
    if ($tulos['kuva'] == "ei" || empty($tulos['kuva'])) {
        ?>
        Uutisella ei ole kuvaa. <a href="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $ouutiset . "&mode=muokkaa&uutisetid=" . $uutisetid; ?>">Takaisin</a>
        <?php
    } else {
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $ouutiset . "&mode=poistakuva&uutisetid=" . $uutisetid; ?>" method="post">
            <input type="hidden" name="ohjaa" value="79" />
            <img src="/Remix/kuvat/uutiset/uutinen_<?php echo date("dmY", $tulos['kirjoitusaika']) . "/" . $kuvanotsikko . "/" . $tulos['kuva']; ?>" /><br />
            Oletko varma että haluat poistaa kuvan?<br />
            <input type="submit" value="Poista" />
            <input type="button" value="Takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $ouutiset . "&mode=muokkaa&uutisetid=" . $uutisetid; ?>')" />
        </form>
        <?php
    }
} else {
    $_SESSION['virhe'] = "Uutista ei löydy.";
    siirry("virhe.php");
}
?>

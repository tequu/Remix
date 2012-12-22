<div class='ala_otsikko'>Poista tiedotus</div>
<div id='error'><?php echo $error ?></div>
<?php
$tiedotuksetid = mysql_real_escape_string($_GET['tiedotuksetid']);
$kysely = kysely($yhteys, "SELECT tiedotus, UNIX_TIMESTAMP(kirjoitusaika) kirjoitusaika FROM tiedotukset WHERE id='" . $tiedotuksetid . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <fieldset>
        <legend><?php echo date("d.m.Y H:i", $tulos['kirjoitusaika']); ?></legend>
        <?php echo $tulos['tiedotus']; ?>
    </fieldset>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otiedotus . "&joukkue=" . $joukkue . "&tiedotuksetid=" . $tiedotuksetid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="43" />
        <div id='keskelle'>Haluatko varmasti poistaa?<br />
            <input type='submit' value='Kyllä' />
            <input type='button' value='En' onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otiedotus . "&joukkue=" . $joukkue; ?>')" />
        </div>
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Tiedotusta ei löytynyt.";
    siirry("virhe.php");
}
?>

<hr />
<div class="ala_otsikko">Poista sarjataulukko</div> 
<?php
$sarjataulukkoryhmatid = mysql_real_escape_string($_GET['sarjataulukkoryhmatid']);
$kysely = kysely($yhteys, "SELECT sr.nimi nimi FROM sarjataulukkoryhmat sr, joukkueet j " .
        "WHERE joukkueetID=j.id AND sr.id='" . $sarjataulukkoryhmatid . "' AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    $kysely = kysely($yhteys, "SELECT joukkue, O, V, T, H, TM, PM, P FROM sarjataulukot WHERE sarjataulukkoryhmatID='" . $sarjataulukkoryhmatid . "' ORDER BY jarjestysnumero");
    tulostasarjataulukko($kysely, $tulos['nimi'], false, $joukkue, 0);
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $osarjataulukko . "&joukkue=" . $joukkue . "&mode=poista&sarjataulukkoryhmatid=" . $sarjataulukkoryhmatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="38" />
        Haluatko varmasti poistaa?<br />
        <input type="submit" value="Kyllä" />
        <input type="button" value="En" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $osarjataulukko . "&joukkue=" . $joukkue . "&mode=muokkaa&sarjataulukkoryhmatid=" . $sarjataulukkoryhmatid; ?>')" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Sarjataulukkoa ei löytynyt.";
    siirry("virhe.php");
}
?>

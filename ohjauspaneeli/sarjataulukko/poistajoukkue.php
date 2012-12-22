<hr />
<div class='ala_otsikko'>Poista sarjataulukon joukkue</div>
<div id='error'><?php echo $error ?></div>
<?php
$poistettavaid = $_POST['poistettavaid'];
$sarjataulukkoryhmatid = $_GET['sarjataulukkoryhmatid'];
$kysely = kysely($yhteys, "SELECT joukkue, O, V, T, H, TM, PM, P FROM sarjataulukot WHERE id='" . $poistettavaid . "' ORDER BY jarjestysnumero");
if ($tulos = mysql_fetch_array($kysely)){
    
    ?>
    <table>
        <tr>
            <td><span id="bold">Joukkue</span>:</td>
            <td><?php echo $tulos['joukkue']; ?></td>
    </tr>
    <tr>
        <td><span id="bold">Ottelut</span>:</td>
        <td><?php echo $tulos['O']; ?></td>
    </tr>
    <tr>
        <td><span id="bold">Voitot</span>:</td>
        <td><?php echo $tulos['V']; ?></td>
    </tr>
    <tr>
        <td><span id="bold">Tasapelit</span>:</td>
        <td><?php echo $tulos['H']; ?></td>
    </tr>
    <tr>
        <td><span id="bold">Maaliero</span>:</td>
        <td><?php echo $tulos['TM'] . "-" . $tulos['PM']; ?></td>
    </tr>
    <tr>
        <td><span id="bold">Pisteet</span></td>
        <td><?php echo $tulos['P']; ?></td>
    </tr>
</table>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $osarjataulukko . "&joukkue=" . $joukkue . "&mode=poista&sarjataulukkoryhmatid=" . $sarjataulukkoryhmatid; ?>" method="post">
    <input type="hidden" name="ohjaa" value="39" />
    <input type="hidden" name="poistettavaid" value="<?php echo $poistettavaid; ?>" />
    Haluatko varmasti poistaa?<br />
    <input type="submit" value="Kyllä" />
    <input type="button" value="En" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $osarjataulukko . "&joukkue=" . $joukkue . "&mode=muokkaa&sarjataulukkoryhmatid=" . $sarjataulukkoryhmatid; ?>')" />
</form>
<?php
} else {
    $_SESSION['virhe'] = "Sarjataulukon joukkuetta ei löytynyt.";
    siirry("virhe.php");
}
?>
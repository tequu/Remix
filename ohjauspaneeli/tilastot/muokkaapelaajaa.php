<hr />
<div id="takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otilastot . "&joukkue=" . $joukkue."&mode=muokkaa&tilastoryhmatid=".$tilastoryhmatid; ?>')">Takaisin</div>
<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Muokkaa pelaajaa</div>
<?php
$pelaajatid = mysql_real_escape_string($_GET['pelaajatid']);
$kysely = kysely($yhteys, "SELECT etunimi, sukunimi, syntymavuosi, email, enabled FROM joukkueet j, tilastoryhmat tr, tilastot, tunnukset t ".
        "WHERE j.id=joukkueetID AND tr.id=tilastoryhmatID AND tunnuksetID=t.id AND j.id='" . $joukkue . "' AND tr.id='".$tilastoryhmatid."' AND t.id='" . $pelaajatid . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=".$otilastot."&joukkue=" . $joukkue . "&mode=muokkaapelaajaa&tilastoryhmatid=".$tilastoryhmatid."&pelaajatid=" . $pelaajatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="59" />
        <span id="bold">Etunimi: </span><?php echo ($tulos['enabled']?$tulos['etunimi']:"<input type=\"text\" name=\"etunimi\" value=\"".$tulos['etunimi']."\" />"); ?>
        <br />
        <span id="bold">Sukunimi: </span><?php echo ($tulos['enabled']?$tulos['sukunimi']:"<input type=\"text\" name=\"sukunimi\" value=\"".$tulos['sukunimi']."\" />"); ?>
        <br />
        <span id="bold">Syntymävuosi: </span><?php echo ($tulos['enabled']?$tulos['syntymavuosi']:"<input type=\"text\" name=\"syntymavuosi\" value=\"".$tulos['syntymavuosi']."\" />"); ?>
        <br />
        <span id="bold">Sähköposti: </span><?php echo ($tulos['enabled']?$tulos['email']:"<input type=\"text\" name=\"email\" value=\"".$tulos['email']."\" />"); ?>
        <br />
        <span id="bold">Ottelut: </span><input type="text" name="o" />
        <br />
        <span id="bold">Rangaistulaukaus maalit: </span><input type="text" name="rlm" />
        <br />
        <span id="bold">Rangaistuslaukaus yritykset: </span><input type="text" name="rly" />
        <br />
        <span id="bold">Rangaistusminuutit: </span><input type="text" name="rm" />
        <br />
        <span id="bold">Syötöt: </span><input type="text" name="s" />
        <br />
        <span id="bold">Maalit: </span><input type="text" name="m" />
        <br />
        <span id="bold">+-: </span><input type="text" name="plusmiinus" />
        <br />
        <input type="submit" value="Muokkaa" />
        <input type="button" value="Poista" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otilastot . "&joukkue=" . $joukkue . "&mode=poistapelaaja&tilastoryhmatid=".$tilastoryhmatid."&pelaajatid=" . $pelaajatid; ?>')" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Pelaajaa ei löytynyt.";
    siirry("virhe.php");
}
?>

<hr />
<div id="takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $osarjataulukko . "&joukkue=" . $joukkue; ?>')">Takaisin</div>
<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Muokkaa sarjataulukkoa</div>
<?php
$sarjataulukkoryhmatid = mysql_real_escape_string($_GET['sarjataulukkoryhmatid']);
$kysely = kysely($yhteys, "SELECT  sr.nimi nimi, oletus FROM sarjataulukkoryhmat sr, joukkueet j WHERE joukkueetID=j.id AND sr.id='" . $sarjataulukkoryhmatid . "' AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <form name="poistajoukkue" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $osarjataulukko . "&joukkue=" . $joukkue . "&mode=poistajoukkue&sarjataulukkoryhmatid=" . $sarjataulukkoryhmatid; ?>" method="post">
        <input type='hidden' name='mode' value='poistajoukkue' />
        <input type='hidden' name='poistettavaid' id='poistettavaid' />
    </form>
    <form name="siirrajoukkue" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $osarjataulukko . "&joukkue=" . $joukkue . "&mode=muokkaa&sarjataulukkoryhmatid=" . $sarjataulukkoryhmatid; ?>" method="post">
        <input type='hidden' name='ohjaa' value='40' />
        <input type='hidden' name='siirrettavaid' id='siirrettavaid' />
        <input type='hidden' name='suunta' id='suunta' />
    </form>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $osarjataulukko . "&joukkue=" . $joukkue . "&mode=muokkaa&sarjataulukkoryhmatid=" . $sarjataulukkoryhmatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value='36' />
        <table width='90%' id='sarjataulukko' border='1' cellpadding='1' cellspacing='1'>
            <tr>
                <th colspan='9'>
                    <input type="text" name="nimi" value="<?php echo $tulos['nimi']; ?>" /> 
                    <input type="checkbox" name="oletus" value="1"<?php echo $tulos['oletus'] ? "CHECKED" : ""; ?> />Oletus
                    <input type="button" value="Poista" onclick="siirry('<?php echo $_SERVER['PHP_SELF']."?sivuid=".$osarjataulukko."&joukkue=".$joukkue."&mode=poista&sarjataulukkoryhmatid=".$sarjataulukkoryhmatid; ?>')" />
                </th>
            </tr>
            <tr>
                <td>Joukkue</td>
                <td>O</td>
                <td>V</td>
                <td>T</td>
                <td>H</td>
                <td>TM</td>
                <td>PM</td>
                <td>P</td>
                <td>Poista/Siirrä</td>
            </tr>
            <?php
            $kysely = kysely($yhteys, "SELECT joukkue, s.id AS sarjataulukotid, O, V, T, H,TM, PM, P FROM sarjataulukot s WHERE " .
                    "sarjataulukkoryhmatID='" . $sarjataulukkoryhmatid . "' ORDER BY jarjestysnumero");
            if ($tulos = mysql_fetch_array($kysely)) {
                do {
                    ?>
                    <tr>
                        <td><input type="text" size="5" name="joukkue_<?php echo $tulos['sarjataulukotid'] . "\" value=\"" . $tulos['joukkue']; ?>" /></td>
                        <td><input type="text" size="3" name="ottelut_<?php echo $tulos['sarjataulukotid'] . "\" value=\"" . $tulos['O']; ?>" /></td>
                        <td><input type="text" size="3" name="voitot_<?php echo $tulos['sarjataulukotid'] . "\" value=\"" . $tulos['V']; ?>" /></td>
                        <td><input type="text" size="3" name="tasapelit_<?php echo $tulos['sarjataulukotid'] . "\" value=\"" . $tulos['T']; ?>" /></td>
                        <td><input type="text" size="3" name="haviot_<?php echo $tulos['sarjataulukotid'] . "\" value=\"" . $tulos['H']; ?>" /></td>
                        <td><input type="text" size="3" name="tm_<?php echo $tulos['sarjataulukotid'] . "\" value=\"" . $tulos['TM']; ?>" /></td>
                        <td><input type="text" size="3" name="pm_<?php echo $tulos['sarjataulukotid'] . "\" value=\"" . $tulos['PM']; ?>" /></td>
                        <td><input type="text" size="3" name="pisteet_<?php echo $tulos['sarjataulukotid'] . "\" value=\"" . $tulos['P']; ?>" /></td>
                        <td>
                            <input type="button" value="Poista" onclick="poista('<?php echo $tulos['sarjataulukotid']; ?>', 'poistajoukkue')" />
                            <input type="button" value="Ylös" onclick="siirra('<?php echo $tulos['sarjataulukotid']; ?>', 'siirrajoukkue', 'ylos')" />
                            <input type="button" value="Alas" onclick="siirra('<?php echo $tulos['sarjataulukotid']; ?>', 'siirrajoukkue', 'alas')" />
                        </td>
                    </tr>
                    <?php
                } while ($tulos = mysql_fetch_array($kysely));
            } else {
                ?>
                <tr>
                    <td colspan="9">Sarjataulukossa ei ole joukkueita</td>
                </tr>

                <?php
            }
            ?>
        </table><br />
        <input type="submit" value="Muokkaa" />
    </form>
    <hr />
    <?php
    include("/home/fbcremix/public_html/Remix/ohjauspaneeli/sarjataulukko/lisaajoukkue.php");
} else {
    $_SESSION['virhe'] = "Sarjataulukkoa ei löytynyt.";
    siirry("virhe.php");
}
?>

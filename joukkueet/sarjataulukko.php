<?php
include("/home/fbcremix/public_html/Remix/yla.php");
include("/home/fbcremix/public_html/Remix/ohjelmat/oikea.php");
?>
<?php
include("/home/fbcremix/public_html/Remix/joukkueet/ohjelmat/valikko.php");
?>
<div id="content">
    <div id="sarjataulukko_pohja">
        <div class="otsikko" style="background-image:URL('/Remix/kuvat/sarjataulukko.png')"></div>
        <?php
        if (isset($_GET['sarjataulukkoryhma'])) {
            $sarjataulukkoryhma = mysql_real_escape_string($_GET['sarjataulukkoryhma']);
            $kysely = kysely($yhteys, "SELECT sr.id id, sr.nimi nimi, kausi FROM sarjataulukkoryhmat sr, joukkueet j " .
                    "WHERE joukkueetID=j.id AND j.id='" . $joukkueid . "' AND sr.id='" . $sarjataulukkoryhma . "'");
        } else {
            $kysely = kysely($yhteys, "SELECT sr.id id, sr.nimi nimi, kausi FROM sarjataulukkoryhmat sr, joukkueet j WHERE joukkueetID=j.id AND oletus='1' AND j.id='" . $joukkueid . "'");
        }
        if (mysql_num_rows($kysely) == 0) {
            $kysely = kysely($yhteys, "SELECT sr.id id, sr.nimi nimi, kausi FROM sarjataulukkoryhmat sr, joukkueet j WHERE joukkueetID=j.id AND j.id='" . $joukkueid . "'");
        }
        $tulos = mysql_fetch_array($kysely);
        $sarjataulukkoryhma = $tulos['id'];
        $sarjataulukonnimi = $tulos['nimi'];
        $sarjataulukonkausi = $tulos['kausi'];
        $kysely = kysely($yhteys, "SELECT joukkue, O, V, T, H, TM, PM, P FROM sarjataulukot s, sarjataulukkoryhmat sr " .
                "WHERE sarjataulukkoryhmatID=sr.id AND sr.id='" . $tulos['id'] . "' ORDER BY jarjestysnumero");
        include("/home/fbcremix/public_html/Remix/joukkueet/ohjelmat/sarjataulukko.php");
        $kysely = kysely($yhteys, "SELECT id, nimi FROM sarjataulukkoryhmat WHERE joukkueetID='" . $joukkueid . "'");
        if ($tulos = mysql_fetch_array($kysely)) {
            ?>
            <div id="sarjataulukkoryhma">
                Valitse sarjataulukkoryhmä:
                <form action="/Remix/joukkueet/sarjataulukko.php" method="get" name="sarjataulukkoryhmanvalinta">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <select name="sarjataulukkoryhma" onchange="laheta('sarjataulukkoryhmanvalinta', [], []);">
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $tulos['id']; ?>"<?php echo ($sarjataulukkoryhma == $tulos['id'] ? " SELECTED" : ""); ?>><?php echo $tulos['nimi']; ?></option>
                            <?php
                        } while ($tulos = mysql_fetch_array($kysely));
                        ?>
                    </select>
                </form>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>

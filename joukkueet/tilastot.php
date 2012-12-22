<?php
include("/home/fbcremix/public_html/Remix/yla.php");
include("/home/fbcremix/public_html/Remix/ohjelmat/oikea.php");
include("/home/fbcremix/public_html/Remix/joukkueet/ohjelmat/valikko.php");
?>
<div id="content">
    <div id="tilastot_pohja">
        <?php
        if (isset($_GET['tilastoryhma'])) {
            $tilastoryhma = mysql_real_escape_string($_GET['tilastoryhma']);
            $kysely = kysely($yhteys, "SELECT tr.id id, tr.nimi nimi, kausi FROM tilastoryhmat tr, joukkueet j " .
                    "WHERE joukkueetID=j.id AND j.id='" . $joukkueid . "' AND tr.id='" . $tilastoryhma . "'");
        } else {
            $kysely = kysely($yhteys, "SELECT tr.id id, tr.nimi nimi, kausi FROM tilastoryhmat tr, joukkueet j WHERE joukkueetID=j.id AND oletus='1' AND j.id='" . $joukkueid . "'");
        }
        if (mysql_num_rows($kysely) == 0) {
            $kysely = kysely($yhteys, "SELECT tr.id id, tr.nimi nimi, kausi FROM tilastoryhmat tr, joukkueet j WHERE joukkueetID=j.id AND j.id='" . $joukkueid . "'");
        }
        $tulos = mysql_fetch_array($kysely);
        $tilastoryhma = $tulos['id'];
        $tilastonnimi = $tulos['nimi'];
        $tilastonkausi = $tulos['kausi'];
        $kysely = kysely($yhteys, "SELECT etunimi, sukunimi, O, RLM, RLY, RM, S, M, plusmiinus FROM tunnukset tu, tilastot t, tilastoryhmat tr " .
                "WHERE tunnuksetID=tu.id AND tilastoryhmatID=tr.id AND tr.id='" . $tulos['id'] . "' ORDER BY (S+M) DESC, M DESC, S DESC");
        include("/home/fbcremix/public_html/Remix/joukkueet/ohjelmat/tilastot.php");
        $kysely = kysely($yhteys, "SELECT id, nimi FROM tilastoryhmat WHERE joukkueetID='" . $joukkueid . "'");
        if ($tulos = mysql_fetch_array($kysely)) {
            ?>
            <div id="tilastoryhma">
                Valitse tilastoryhmä:
                <form action="/Remix/joukkueet/tilastot.php" method="get" name="tilastoryhmanvalinta">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <select name="tilastoryhma" onchange="laheta('tilastoryhmanvalinta', [], [])">
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $tulos['id']; ?>"<?php echo ($tilastoryhma == $tulos['id'] ? " SELECTED" : ""); ?>><?php echo $tulos['nimi']; ?></option>
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
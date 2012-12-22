<?php
include("/home/fbcremix/public_html/Remix/yla.php");
include("/home/fbcremix/public_html/Remix/ohjelmat/oikea.php");
include("/home/fbcremix/public_html/Remix/joukkueet/ohjelmat/valikko.php");
?>
<div id="content">
    <div id="pelit_pohja">
        <div class="otsikko" style="background-image:URL('/Remix/kuvat/pelit.png')"></div>
        <?php
        if (isset($_GET['peliryhma'])) {
            $peliryhma = mysql_real_escape_string($_GET['peliryhma']);
            $kysely = kysely($yhteys, "SELECT pr.id id, pr.nimi nimi, kausi FROM peliryhmat pr, joukkueet j " .
                    "WHERE joukkueetID=j.id AND j.id='" . $joukkueid . "' AND pr.id='" . $peliryhma . "'");
        } else {
            $kysely = kysely($yhteys, "SELECT pr.id id, pr.nimi nimi, kausi FROM peliryhmat pr, joukkueet j WHERE joukkueetID=j.id AND oletus='1' AND j.id='" . $joukkueid . "'");
        }
        if (mysql_num_rows($kysely) == 0) {
            $kysely = kysely($yhteys, "SELECT pr.id id, pr.nimi nimi, kausi FROM peliryhmat pr, joukkueet j WHERE joukkueetID=j.id AND j.id='" . $joukkueid . "'");
        }
        $tulos = mysql_fetch_array($kysely);
        $peliryhma = $tulos['id'];
        $peliryhmannimi = $tulos['nimi'];
        $peliryhmankausi = $tulos['kausi'];
        $kysely = kysely($yhteys, "SELECT vastustaja, UNIX_TIMESTAMP(aika) aika, koti, kotimaalit, vierasmaalit, pelattu, pelipaikka, kotiturnaus FROM pelit p, peliryhmat pr " .
                "WHERE peliryhmatID=pr.id AND pr.id='" . $tulos['id'] . "' ORDER BY aika");
        include("/home/fbcremix/public_html/Remix/joukkueet/ohjelmat/pelit.php");
        $kysely = kysely($yhteys, "SELECT id, nimi FROM peliryhmat WHERE joukkueetID='" . $joukkueid . "'");
        if ($tulos = mysql_fetch_array($kysely)) {
            ?>
            <div id="peliryhma">
                Valitse peliryhmä:
                <form action="/Remix/joukkueet/pelit.php" method="get" name="peliryhmanvalinta">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <select name="peliryhma" onchange="laheta('peliryhmanvalinta', [], []);">
                        <?php
                        do {
                            ?>
                            <option value="<?php echo $tulos['id']; ?>"<?php echo ($peliryhma == $tulos['id'] ? " SELECTED" : ""); ?>><?php echo $tulos['nimi']; ?></option>
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

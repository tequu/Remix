<?php
include("/home/fbcremix/public_html/Remix/yla.php");
include("/home/fbcremix/public_html/Remix/ohjelmat/oikea.php");
include("/home/fbcremix/public_html/Remix/joukkueet/ohjelmat/valikko.php");
?>
<div id="content">
    <div id="pelaajat">
        <div class="otsikko" style="background-image:URL('/Remix/kuvat/pelaajat.png')"></div>
        <?php
        $kysely = kysely($yhteys, "SELECT kapteeni FROM joukkueet WHERE id='" . $joukkueid . "'");
        $tulos = mysql_fetch_array($kysely);
        $kapteeni = $tulos['kapteeni'];
        $kysely = kysely($yhteys, "SELECT tunnuksetID id, etunimi, sukunimi, rooli, pelinumero, kuva FROM pelaajat, tunnukset " .
                "WHERE tunnuksetID=tunnukset.id AND joukkueetID='" . $joukkueid . "' ORDER BY sukunimi,etunimi");
        $i = 1;
        while ($tulos = mysql_fetch_array($kysely)) {
            ?>
            <div id="pelaaja" title="<?php echo $tulos['id']; ?>"<?php echo $i % 4 == 0 ? " style=\"margin-right:0px;\"" : ""; ?>>
                <div id="kuva" <?php echo ($tulos['kuva'] != null ? "style=\"background-image: URL('/Remix/kuvat/pelaajakortit/" . $joukkueid . "/mini_pelaajat/" . $tulos['kuva'] . "')\"" : ""); ?>>
                    <?php
                    if ($tulos['rooli'] == $pelaajaroolit[1] && $kapteeni == $tulos['id']) {
                        ?>
                        <div id="kapteenijamaalivahti"></div>
                        <?php
                    } elseif ($kapteeni == $tulos['id']) {
                        ?>
                        <div id="kapteeni"></div>
                        <?php
                    } elseif ($tulos['rooli'] == $pelaajaroolit[1]) {
                        ?>
                        <div id="maalivahti"></div>
                        <?php
                    }
                    ?>
                </div>
                <div id = "nimi">#<?php echo $tulos['pelinumero'] . " " . $tulos['sukunimi'] . " " . $tulos['etunimi'];
                    ?></div>
            </div>
            <?php
            $i++;
        }
        ?>
        <div id="clear"></div>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>

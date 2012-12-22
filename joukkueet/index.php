<?php
include("/home/fbcremix/public_html/Remix/yla.php");
include("/home/fbcremix/public_html/Remix/ohjelmat/oikea.php");
include("/home/fbcremix/public_html/Remix/joukkueet/ohjelmat/valikko.php");
?>
<div id="content">
    <div id="joukkue_tiedotukset">
        <div class="otsikko" style="background-image:URL('/Remix/kuvat/tiedotukset.png')"></div>
        <?php
        $aika_nyt = time();
        $aika_nyt -= (60 * 60 * 24);
        $kysely = kysely($yhteys, "SELECT tiedotus, UNIX_TIMESTAMP(kirjoitusaika) kirjoitusaika FROM tiedotukset " .
                "WHERE (eivanhenemisaikaa = '1' OR UNIX_TIMESTAMP(vanhenemisaika) >= '" . $aika_nyt . "') AND joukkueetID='" . $joukkueid . "' ORDER BY kirjoitusaika DESC");
        while ($tulos = mysql_fetch_array($kysely)) {
            ?>
            <div id="joukkue_tiedotus_kirjoitusaika"><?php
        echo date("d.m.Y", $tulos['kirjoitusaika']);
        if ($tulos['kirjoitusaika'] >= $aika_nyt - 6 * 24 * 60 * 60) {
                ?>
                    <span id="joukkue_uusi_tiedotus">Uusi</span>
                    <?php
                }
                ?>
            </div>
            <div id="joukkue_tiedotus">
                <?php echo $tulos['tiedotus']; ?>
            </div>
            <?php
        }
        ?>
    </div>
    <div id="etusivu_kuvat">
        <div class="otsikko" style="background-image:URL('/Remix/kuvat/uusimmat-kuvat.png')"></div>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
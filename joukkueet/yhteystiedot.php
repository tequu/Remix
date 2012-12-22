<?php
include("/home/fbcremix/public_html/Remix/yla.php");
include("/home/fbcremix/public_html/Remix/ohjelmat/oikea.php");
include("/home/fbcremix/public_html/Remix/joukkueet/ohjelmat/valikko.php");
?>
<div id="content">
    <div id="yhteystiedot">
        <div class="otsikko" style="background-image:URL('/Remix/kuvat/yhteystiedot.png')"></div>
        <div id="yhteyshenkilot">
            <div id="otsikko">Valmentajat</div>
            <?php
            $yhteyshenkilot = array();
            $omat = array();
            $kysely = kysely($yhteys, "SELECT etunimi, sukunimi, tiedot, rooli FROM yhteyshenkilot y, tunnukset t " .
                    "WHERE tunnuksetID=t.id AND joukkueetID='" . $joukkueid . "' AND rooli!=''");
            while ($tulos = mysql_fetch_array($kysely)) {
                if ($tulos['rooli'] == "Yhteyshenkilö") {
                    $yhteyshenkilot[] = array("etunimi" => $tulos['etunimi'], "sukunimi" => $tulos['sukunimi'], "tiedot" => $tulos['tiedot']);
                    continue;
                } else if ($tulos['rooli'] != "Valmentaja") {
                    $omat[$tulos['rooli']][] = array("etunimi" => $tulos['etunimi'], "sukunimi" => $tulos['sukunimi'], "tiedot" => $tulos['tiedot']);
                    continue;
                }
                ?>
                <div id="henkilo">
                    <div id="nimi"><?php echo $tulos['sukunimi'] . " " . $tulos['etunimi']; ?></div>
                    <div id="tiedot"><?php echo $tulos['tiedot']; ?></div>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        if (!empty($yhteyshenkilot)) {
            ?>
            <div id="yhteyshenkilot">
                <div id="otsikko">Yhteyshenkilöt</div>
                <?php
                for ($i = 0; $i < count($yhteyshenkilot); $i++) {
                    ?>
                    <div id="henkilo">
                        <div id="nimi"><?php echo $yhteyshenkilot[$i]['sukunimi'] . " " . $yhteyshenkilot[$i]['etunimi']; ?></div>
                        <div id="tiedot"><?php echo $yhteyshenkilot[$i]['tiedot']; ?></div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        if (!empty($omat)) {
            foreach ($omat as $rooli => $henkilot) {
                ?>
                <div id="yhteyshenkilot">
                    <div id="otsikko"><?php echo $rooli; ?></div>
                    <?php
                    foreach ($henkilot as $henkilo) {
                        ?>
                        <div id="henkilo">
                            <div id="nimi"><?php echo $henkilo['sukunimi'] . " " . $henkilo['etunimi']; ?></div>
                            <div id="tiedot"><?php echo $henkilo['tiedot']; ?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>

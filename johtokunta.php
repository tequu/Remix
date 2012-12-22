<?php
include("/home/fbcremix/public_html/Remix/yla.php");
include("/home/fbcremix/public_html/Remix/ohjelmat/oikea.php");
?>
<div id="content">
    <div id="johtokunta">
        <div class="otsikko" style="background-image:URL('/Remix/kuvat/johtokunta.png')"></div>
        <?php
        $muut = false;
        $kysely = kysely($yhteys, "SELECT etunimi, sukunimi, arvo, sahkoposti, puhelinnumero FROM johtokunta ORDER BY jarjestysnumero");
        while ($tulos = mysql_fetch_array($kysely)) {
            ?>
            <div id="henkilo">
                <div id="arvonimi">
                    <?php
                    if ($tulos['arvo'] != "ei") {
                        echo $tulos['arvo'];
                    } elseif (!$muut) {
                        echo "Muut jäsenet";
                        $muut = true;
                    }
                    ?>
                </div>
                <div id="nimi"><?php echo $tulos['sukunimi'] . " " . $tulos['etunimi']; ?></div>
                <div id="tiedot"><?php echo $tulos['sahkoposti'] . ", " . $tulos['puhelinnumero']; ?></div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>

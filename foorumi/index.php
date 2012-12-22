<?php
//Tuodaan yla.php
include("/home/fbcremix/public_html/Remix/foorumi/yla.php");
?>
<div id="levea_content">
    <div id="foorumi">
        <div id="pohja">
            <?php
            $kysely = kysely($yhteys, "SELECT id, otsikko FROM keskustelualueryhmat");
            while ($tulos = mysql_fetch_array($kysely)) {
                ?>
                <div class="keskustelualueet">
                    <div class="otsikko"><?php echo $tulos['otsikko']; ?></div>
                    <?php
                    $kysely2 = kysely($yhteys, "SELECT id, nimi, kuvaus FROM keskustelualueet WHERE keskustelualueryhmatID='" . $tulos['id'] . "'");
                    while ($tulos2 = mysql_fetch_array($kysely2)) {
                        ?>
                        <div class="keskustelualue">
                            <div class="vasen">
                                <div class="nimi"><a href="/Remix/foorumi/keskustelualue.php?keskustelualue=<?php echo $tulos2['id']; ?>"><?php echo $tulos2['nimi']; ?></a></div>
                                <div class="kuvaus"><?php echo $tulos2['kuvaus']; ?></div>
                            </div>
                            <div class="keski">
                                <div class="tapahtumia">Tapahtumia: 10</div>
                                <div class="aiheita">Aiheita: 20</div>
                                <div class="viesteja">Viestejä: 50</div>
                            </div>
                            <div class="oikea">
                                <div class="viimeinen_viesti">Viimeisin viesti</div>
                                <div class="kirjoittaja">Kirjoittaja</div>
                                <div class="aika">1.12.2012 13:15</div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            <!--            <div id="otsikko">Joukkueet</div>
                        <div id="keskustelualue">
                            <div id="nimi"><a href="/Remix/foorumi/keskustelualue.php">Miehet edustus</a></div>
                            <div id="tiedot">
                                <div id="tapahtumia">Tapahtumia: 10</div>
                                <div id="aiheita">Aiheita: 20</div>
                                <div id="viesteja">Viestejä: 50</div>
                            </div>
                            <div id="viimeinen_viesti">
                                <div id="keskustelualue">Viimeisin viesti</div>
                                <div id="kirjoittaja">Kirjoittaja</div>
                                <div id="aika">1.12.2012 13:15</div>
                            </div>
                        </div>-->
        </div>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
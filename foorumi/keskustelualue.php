<?php
if (!isset($_GET['keskustelualue']))
    header("Location: /Remix/foorumi");
include("/home/fbcremix/public_html/Remix/foorumi/yla.php");
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
?>
<div id="levea_content">
    <div id="foorumi">
        <?php
        $kysely = kysely($yhteys, "SELECT nimi FROM keskustelualueet WHERE id='" . $keskustelualue . "'");
        $tulos = mysql_fetch_array($kysely);
        ?>
        <div id="otsikko"><div class="liiku uusikeskustelu-nappi" data-url="uusi.php?keskustelualue=<?php echo $keskustelualue; ?>&mode=keskustelu"></div><div class="liiku uusitapahtuma-nappi" data-url="uusi.php?keskustelualue=<?php echo $keskustelualue; ?>&mode=tapahtuma"></div><?php echo $tulos['nimi']; ?></div>
        <div id="pohja">
            <div id="keskustelut">
                <?php
                $eka = true;
                $kysely = kysely($yhteys, "SELECT id, otsikko FROM keskustelut k, keskustelualuekeskustelut kk WHERE k.id=kk.keskustelutID AND kk.keskustelualueetID='" . $keskustelualue . "'");
                while ($tulos = mysql_fetch_array($kysely)) {
                    if (!$eka) {
                        ?>
                        <div class="viiva"></div>
                        <?php
                    }
                    $eka = false;
                    ?>
                    <div class="keskustelu">
                        <div class="vasen">
                            <div class="nimi"><a href="/Remix/foorumi/keskustelu.php?keskustelualue=<?php echo $keskustelualue; ?>&keskustelu=<?php echo $tulos['id']; ?>"><?php echo $tulos['otsikko']; ?></a></div>
                            <div class="tiedot">Aloittaja, 13.12.2012 14:19</div>
                        </div>
                        <div class="keski">
                            <div class="vastauksia">Vastauksia: 8</div>
                            <div class="in">In: 14</div>
                            <div class="out">Out: 10</div>
                        </div>
                        <div class="oikea">
                            <div class="viimeinen_vastaaja">Viimeinen vastaaja</div>
                            <div class="aika">15.12.2012 15:45</div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
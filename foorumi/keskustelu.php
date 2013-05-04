<?php
if (!isset($_GET['keskustelualue']) || !isset($_GET['keskustelu']))
    header("Location: /Remix/foorumi/index.php");
include("/home/fbcremix/public_html/Remix/foorumi/apu/yla.php");
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
$keskustelu = mysql_real_escape_string($_GET['keskustelu']);
$hallintaoikeudet = false;
?>
<div id="levea_content">
    <div id="foorumi">
        <div id="keskustelu">
            <?php
            $kysely = kysely($yhteys, "SELECT keskustelualueetID FROM keskustelualuekeskustelut WHERE keskustelualueetID='" . $keskustelualue . "' AND keskustelutID='" . $keskustelu . "'");
            if ($tulos = mysql_fetch_array($kysely)) {
                $siirry = false;
                $hallintaoikeudet = tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue);
                $siirry = true;
                if ($hallintaoikeudet || tarkistaNakyvyysOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
                    $kysely = kysely($yhteys, "SELECT otsikko, tapahtumatID FROM keskustelut WHERE id='" . $keskustelu . "'");
                    $tulos = mysql_fetch_array($kysely);
                    if ($tulos['tapahtumatID']) {
                        include("/home/fbcremix/public_html/Remix/foorumi/apu/tapahtuma.php");
                    } else {
                        ?>
                        <div class="takaisin" onclick="parent.location = '<?php echo $_SERVER['HTTP_REFERER']; ?>'"></div>
                        <div id="clear"></div>
                        <?php
                    }
                    $kysely2 = kysely($yhteys, "SELECT count(id) maara FROM viestit WHERE keskustelutID='" . $keskustelu . "'");
                    $tulos2 = mysql_fetch_array($kysely2);
                    $maara = $tulos2['maara'];
                    $sivu = mysql_real_escape_string($_GET['sivu']);
                    $ysm = 10;
                    $kysely2 = kysely($yhteys, "SELECT t.id tunnuksetID, v.id id, otsikko, viesti, UNIX_TIMESTAMP(lahetysaika) lahetysaika, login, isadmin, UNIX_TIMESTAMP(rpaivamaara) rpaivamaara, vm.maara viesteja " .
                            "FROM viestit v, tunnukset t, (SELECT tunnuksetID, count(id) maara FROM viestit GROUP BY tunnuksetID) vm " .
                            "WHERE v.tunnuksetID=t.id AND t.id=vm.tunnuksetID AND keskustelutID='" . $keskustelu . "' ORDER BY lahetysaika LIMIT " . $sivu * $ysm . ", " . $ysm);
                    if ($tulos2 = mysql_fetch_array($kysely2)) {
                        if ($maara > $ysm) {
                            ?>
                            <div id="sivunumerot">
                                <?php
                                $linkki = $_SERVER['PHP_SELF'] . "?" . get_to_string(array("sivu")) . "&";
                                tulostasivunumerot($maara, $ysm, $sivu, $linkki, false, true, true);
                                ?>
                            </div>
                            <?php
                        }
                        if ($tulos['tapahtumatID']) {
                            ?>
                            <div id="valiotsikko">Kommentit</div>
                            <?php
                        } else {
                            ?>
                            <div id="otsikko"><?php echo $tulos['otsikko']; ?></div>
                            <?php
                        }
                        ?>
                        <div id="clear"></div>
                        <div id="pohja"> 
                            <div id="viestit">
                                <?php
                                do {
                                    ?>
                                    <div class="viesti" data-id="<?php echo $tulos2['id']; ?>">
                                        <div class="header">
                                            <div class="kirjoitusaika"><?php echo date("d/m/y", $tulos2['lahetysaika']) . " klo " . date("H:i", $tulos2['lahetysaika']); ?></div> <div class="otsikko"><?php echo $tulos2['otsikko']; ?></div>
                                            <?php
                                            $siirry = false;
                                            if ($tulos2['tunnuksetID'] == $kayttaja || tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
                                                ?>
                                                <div class="poista" title="Poista"></div>
                                                <div class="muokkaa" title="Muokkaa"></div>
                                                <?php
                                            }
                                            ?>
                                            <!--<div class="kopioi" title="Kopioi"></div>-->
                                            <div class="vastaa" title="Vastaa"></div>
                                        </div>
                                        <div class="vasen">
                                            <div class="kirjoittaja">
                                                <div class="nimi"><?php echo $tulos2['login']; ?></div>
                                                <div class="arvo"><?php echo $tulos2['isadmin']; ?></div>
                                            </div>
                                            <div class="kirjoittajantiedot">
                                                <div class="liittymispaiva">Liittymis päivä: <?php echo date("d/m/y", $tulos2['rpaivamaara']); ?></div>
                                                <div class="viesteja">Viestäjä: <?php echo $tulos2['viesteja']; ?></div>
                                            </div>
                                        </div>
                                        <div class="oikea">
                                            <div class="teksti">
                                                <?php echo $tulos2['viesti']; ?>
                                            </div>
                                            <div class="allekirjoitus"></div>
                                        </div>
                                        <div id="clear"></div>
                                    </div>
                                    <?php
                                } while ($tulos2 = mysql_fetch_array($kysely2));
                                ?>
                            </div>
                            <script type="text/javascript">
                            $(document).ready(function() {
                                $("#viestit .viesti .poista").click(function() {
                                    var id = $(this).parents(".viesti").data("id");
                                    parent.location = "/Remix/foorumi/poista.php?<?php echo get_to_string(array()); ?>&viesti=" + id + "&mode=viesti";
                                });
                                $("#viestit .viesti .muokkaa").click(function() {
                                    var id = $(this).parents(".viesti").data("id");
                                    parent.location = "/Remix/foorumi/muokkaa.php?<?php echo get_to_string(array()); ?>&viesti=" + id + "&mode=viesti";
                                });
                                $("#viestit .viesti .vastaa").click(function() {
                                    var id = $(this).parents(".viesti").data("id");
                                    parent.location = "/Remix/foorumi/uusi.php?<?php echo get_to_string(array()); ?>&viesti=" + id + "&mode=viesti";
                                });
                            });
                            </script>
                        </div>
                        <?php
                    }
                    if (isset($_SESSION['id'])) {
                        ?>

                        <!--Uusi viesti-->
                        <div id="pohja">
                            <?php
                            include("/home/fbcremix/public_html/Remix/foorumi/uusi/viesti.php");
                            ?>
                        </div>
                        <?php
                    }
                } else {
                    echo "Ei oikeuksia.";
                }
            } else {
                ?>
                Keskustelua ei löytynyt.
                <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
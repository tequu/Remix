<?php
if (!isset($_GET['keskustelualue']))
    header("Location: /Remix/foorumi");
include("/home/fbcremix/public_html/Remix/foorumi/apu/yla.php");
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
$hallintaoikeudet = false;
$sivu = mysql_real_escape_string($_GET['sivu']);
$ysm = 10;
?>
<div id="levea_content">
    <div id="foorumi">
        <div id="keskustelualue">
            <?php
            $kysely = kysely($yhteys, "SELECT nimi, count(kk.keskustelutID) maara FROM keskustelualueet k, keskustelualuekeskustelut kk " .
                    "WHERE k.id=kk.keskustelualueetID AND id='" . $keskustelualue . "' AND kk.keskustelutID IN " .
                    "(SELECT k.id FROM keskustelut k INNER JOIN viestit v ON k.id=v.keskustelutID GROUP BY k.id HAVING count(v.id)>0)");
            if ($tulos = mysql_fetch_array($kysely)) {
                $siirry = false;
                $hallintaoikeudet = tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue);
                $siirry = true;
                if ($hallintaoikeudet || tarkistaNakyvyysOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
                    ?>
                    <div id="otsikko">
                        <div class="liiku uusikeskustelu-nappi" data-url="uusi.php?keskustelualue=<?php echo $keskustelualue; ?>&mode=keskustelu"></div>
                        <?php
                        if ($hallintaoikeudet) {
                            ?>
                            <div class="liiku uusitapahtuma-nappi" data-url="uusi.php?keskustelualue=<?php echo $keskustelualue; ?>&mode=tapahtuma"></div>
                            <?php
                        }
                        ?>
                        <?php echo $tulos['nimi']; ?>
                    </div>
                    <?php
                    if ($tulos['maara'] > $ysm) {
                        ?>
                        <div id="sivunumerot">
                            <?php
                            $linkki = $_SERVER['PHP_SELF'] . "?" . get_to_string(array("sivu")) . "&";
                            tulostasivunumerot($tulos['maara'], $ysm, $sivu, $linkki, false, true, true);
                            ?>
                        </div>
                        <div id="clear"></div>
                        <?php
                    }
                    ?>
                    <div id="keskustelut">
                        <?php
                        $kysely = kysely($yhteys, "SELECT k.id id, k.otsikko otsikko, k.tapahtumatID tapahtumatID, UNIX_TIMESTAMP(aloitusaika) aloitusaika, t.login aloittaja, " .
                                "v.login login, UNIX_TIMESTAMP(v.lahetysaika) lahetysaika, vm.maara vmmaara " .
                                "FROM keskustelualuekeskustelut kk, keskustelut k, " .
                                "(SELECT v.lahetysaika lahetysaika, t.login login, k.id keskustelutID FROM viestit v, tunnukset t, keskustelut k, keskustelualuekeskustelut kk " .
                                "WHERE v.tunnuksetID=t.id AND v.keskustelutID=k.id AND k.id=kk.keskustelutID AND kk.keskustelualueetID='" . $keskustelualue . "' " .
                                "ORDER BY lahetysaika DESC) v, tunnukset t, " .
                                "(SELECT keskustelutID, count(id) maara FROM viestit GROUP BY keskustelutID) vm " .
                                "WHERE k.id=kk.keskustelutID AND k.aloittaja=t.id AND k.id=v.keskustelutID AND k.id=vm.keskustelutID AND kk.keskustelualueetID='" . $keskustelualue . "' " .
                                " GROUP BY k.id ORDER BY lahetysaika DESC LIMIT " . $sivu * $ysm . ", " . $ysm);
                        while ($tulos = mysql_fetch_array($kysely)) {
                            ?>
                            <div class="keskustelu" data-id="<?php echo $tulos['id']; ?>">
                                <?php
                                $sql = "SELECT t.login aloittaja, UNIX_TIMESTAMP(aloitusaika) aloitusaika, vika.login vikalogin, UNIX_TIMESTAMP(vika.lahetysaika) vikalahetysaika " .
                                        "FROM keskustelut k, " .
                                        "tunnukset t, " .
                                        "(SELECT login, lahetysaika, keskustelutID FROM viestit v, tunnukset t " .
                                        "WHERE t.id=v.tunnuksetID AND keskustelutID='" . $tulos['id'] . "' ORDER BY lahetysaika DESC) vika " .
                                        "WHERE t.id=k.aloittaja AND k.id=vika.keskustelutID";
                                $kysely2 = kysely($yhteys, $sql);
                                if ($tulos['tapahtumatID']) {
                                    $kysely3 = kysely($yhteys, "SELECT count(lasna) maara FROM paikallaolo WHERE lasna='1' AND tapahtumatID='" . $tulos['tapahtumatID'] . "'");
                                    $kysely4 = kysely($yhteys, "SELECT count(lasna) maara FROM paikallaolo WHERE lasna='0' AND tapahtumatID='" . $tulos['tapahtumatID'] . "'");
                                    $tulos3 = mysql_fetch_array($kysely3);
                                    $tulos4 = mysql_fetch_array($kysely4);
                                }

                                $tulos2 = mysql_fetch_array($kysely2);
                                ?>
                                <div class="vasen">
                                    <div class="nimi">
                                        <?php
                                        $otsikko = katkaiseTeksti($tulos['otsikko'], 40);
                                        ?>
                                        <a href="/Remix/foorumi/keskustelu.php?keskustelualue=<?php echo $keskustelualue; ?>&keskustelu=<?php echo $tulos['id']; ?>"><?php echo $otsikko . (strlen($otsikko) != strlen($tulos['otsikko']) ? "..." : "") ?></a>
                                    </div>
                                    <?php
                                    $siirry = false;
                                    if (tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
                                        ?>
                                        <div class="napit">
                                            <div class="muokkaa" title="Muokkaa"></div>
                                            <div class="poista" title="Poista"></div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div id="clear"></div>
                                    <?php echo $tulos['tapahtumatID'] ? "" : "<div class=\"tiedot\">" . $tulos2['aloittaja'] . ", " . date("d.m.y H:i", $tulos2['aloitusaika']) . "</div>"; ?>
                                </div>
                                <div class="keski">
                                    <div class="vastauksia"><?php echo $tulos['tapahtumatID'] ? "Viestejä: " . $tulos['vmmaara'] : "Vastauksia:" . ($tulos['vmmaara'] - 1); ?></div>
                                    <?php
                                    if ($tulos['tapahtumatID']) {
                                        ?>
                                        <div class="in">In: <?php echo $tulos3['maara'] ? $tulos3['maara'] : "0"; ?></div>
                                        <div class="out">Out: <?php echo $tulos4['maara'] ? $tulos4['maara'] : "0"; ?></div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="oikea">
                                    <div class="viimeinen_vastaaja"><?php echo $tulos2['vikalogin']; ?></div>
                                    <div class="aika"><?php echo date("d.m.y H:i", $tulos2['vikalahetysaika']); ?></div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $("#keskustelut .keskustelu .muokkaa").click(function() {
                                var id = $(this).parents(".keskustelu").data("id");
                                parent.location = "/Remix/foorumi/muokkaa.php?keskustelualue=<?php echo $keskustelualue; ?>&keskustelu=" + id + "&mode=keskustelu";
                            });
                            $("#keskustelut .keskustelu .poista").click(function() {
                                var id = $(this).parents(".keskustelu").data("id");
                                parent.location = "/Remix/foorumi/poista.php?keskustelualue=<?php echo $keskustelualue; ?>&keskustelu=" + id + "&mode=keskustelu";
                            });
                        });
                    </script>
                </div>
                <?php
            } else {
                echo "Sinulla ei ole oikeuksia keskustelualueelle.";
            }
        } else {
            echo "Keskustelualuetta ei löytynyt.";
        }
        ?>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
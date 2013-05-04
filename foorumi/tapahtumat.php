<?php
$kirjautunut = true;
include("/home/fbcremix/public_html/Remix/foorumi/apu/yla.php");
?>
<div id="levea_content">
    <div id="foorumi">
        <div id="tapahtumat">
            <?php
            $lajit = array("harkat" => "Harkat", "peli" => "Peli", "turnaus" => "Turnaus", "muu" => "Muu");
            $kysely = kysely($yhteys, "SELECT keskustelualueetID FROM joukkueet WHERE id='" . $joukkue . "'");
            $tulos = mysql_fetch_array($kysely);
            $keskustelualue = $tulos['keskustelualueetID'];
            $sivu = mysql_real_escape_string($_GET['sivu']);
            $ysm = 10;
            if (isset($_GET['joukkue']) && $_GET['joukkue'] != 0) {
                $kysely = kysely($yhteys, "SELECT count(t.id) maara FROM tapahtumat t, keskustelut k, keskustelualuekeskustelut kk, joukkueet j " .
                        "WHERE t.id=k.tapahtumatID AND k.id=kk.keskustelutID AND kk.keskustelualueetID=j.keskustelualueetID " .
                        " AND j.id='" . $joukkue . "' " .
                        ($_GET['kaikki'] == 1 ? "" : "AND UNIX_TIMESTAMP(alkamisaika) >= '" . time() . "'"));
                $tulos = mysql_fetch_array($kysely);
                $maara = $tulos['maara'];
                $kysely = kysely($yhteys, "SELECT t.id id, k.id keskustelutID, k.otsikko nimi, t.tapahtuma tapahtuma, UNIX_TIMESTAMP(t.alkamisaika) aika, UNIX_TIMESTAMP(ilmotakaraja) ilmotakaraja, ilmomaxmaara, inmaara, outmaara " .
                        "FROM tapahtumat t, keskustelut k, keskustelualuekeskustelut kk, joukkueet j " .
                        "WHERE t.id=k.tapahtumatID AND k.id=kk.keskustelutID AND kk.keskustelualueetID=j.keskustelualueetID " .
                        " AND j.id='" . $joukkue . "' " .
                        ($_GET['kaikki'] == 1 ? "" : "AND UNIX_TIMESTAMP(alkamisaika) >= '" . time() . "' ") .
                        "ORDER BY alkamisaika LIMIT " . $sivu * $ysm . ", " . $ysm);
            } else {
                $kysely = kysely($yhteys, "SELECT count(t.id) maara FROM tapahtumat t, keskustelut k, keskustelualuekeskustelut kk " .
                        "WHERE t.id=k.tapahtumatID AND k.id=kk.keskustelutID " .
                        "AND kk.keskustelualueetID NOT IN (SELECT keskustelualueetID FROM joukkueet) " .
                        ($_GET['kaikki'] == 1 ? "" : "AND UNIX_TIMESTAMP(alkamisaika) >= '" . time() . "'"));
                $tulos = mysql_fetch_array($kysely);
                $maara = $tulos['maara'];
                $kysely = kysely($yhteys, "SELECT t.id id, k.id keskustelutID, k.otsikko nimi, t.tapahtuma tapahtuma, UNIX_TIMESTAMP(t.alkamisaika) aika, inmaara, outmaara " .
                        "FROM tapahtumat t, keskustelut k, keskustelualuekeskustelut kk " .
                        "WHERE t.id=k.tapahtumatID AND k.id=kk.keskustelutID " .
                        "AND kk.keskustelualueetID NOT IN (SELECT keskustelualueetID FROM joukkueet) " .
                        ($_GET['kaikki'] == 1 ? "" : "AND UNIX_TIMESTAMP(alkamisaika) >= '" . time() . "'") .
                        "ORDER BY alkamisaika LIMIT " . $sivu * $ysm . ", " . $ysm);
            }
            ?>
            <div id="otsikko">
                <?php
                $siirry = false;
                if (isset($_GET['joukkue']) && $joukkue != 0 && tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
                    ?>
                    <div class="liiku uusitapahtuma-nappi" data-url="uusi.php?keskustelualue=<?php echo $keskustelualue; ?>&mode=tapahtuma"></div>
                    <?php
                }
                echo $joukkue == 0 ? "Seura" : haeJoukkueenNimi($yhteys, $joukkue);
                ?> 
                - Tapahtumat
            </div>
            <?php
            $oikeudet = false;
            if (tarkistaNakyvyysOikeudetJoukkueeseen($yhteys, $joukkue) || (isset($_GET['joukkue']) && $joukkue == 0)) {
                $oikeudet = true;
            }
            if ($oikeudet && $maara > $ysm) {
                ?>
                <div id="sivunumerot">
                    <?php
                    $linkki = $_SERVER['PHP_SELF'] . "?" . get_to_string(array("sivu")) . "&";
                    tulostasivunumerot($maara, $ysm, $sivu, $linkki, false, true, true);
                    ?>
                </div>
                <?php
            }
            ?>
            <div id="joukkueenvalintavalikko">
                <?php
                $lisakentat = array("kaikki" => "<input type=\"checkbox\" id=\"kaikki\" name=\"kaikki\" value=\"1\" " . ($_GET['kaikki'] == 1 ? "CHECKED " : "") . "/> Näytä kaikki",);
                tulosta_joukkueet_valinta_jasenille($kayttaja, $kausi, array(), $lisakentat, true, false);
                ?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#kaikki").change(function() {
                            laheta('joukkueenvalinta', [], []);
                        });
                    });
                </script>
            </div>
            <div id="clear"></div>
            <?php
            if ($oikeudet) {
                if ($tulos = mysql_fetch_array($kysely)) {
                    do {
                        $nimi = katkaiseTeksti($tulos['nimi'], 40);
                        ?>
                        <div class="tapahtuma">
                            <div id="laji" class="<?php echo $tulos['tapahtuma']; ?>"><?php echo $lajit[$tulos['tapahtuma']]; ?></div>
                            <div id="nimi"><a href="/Remix/foorumi/keskustelu.php?keskustelualue=<?php echo $keskustelualue; ?>&keskustelu=<?php echo $tulos['keskustelutID']; ?>"><?php echo $nimi . (strlen($nimi) != strlen($tulos['nimi']) ? "..." : "") ?></a></div>
                            <div id="<?php echo $tulos['ilmotakaraja'] != 0 ? "aikajatakaraja" : "aika"; ?>">
                                <?php
                                tulostapaivamaara($tulos['aika'], false, true);
                                if ($tulos['ilmotakaraja'] != 0) {
                                    ?>
                                    <div id="takaraja"><?php
                                        tulostapaivamaara($tulos['ilmotakaraja'], false, true);
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                            $kysely2 = kysely($yhteys, "SELECT lasna FROM paikallaolo WHERE tapahtumatID='".$tulos['id']."' AND tunnuksetID='".$kayttaja."'");
                            $tulos2 = mysql_fetch_array($kysely2);
                            ?>
                            <div id="in_maara">IN: <?php echo $tulos['inmaara'] . ($tulos['ilmomaxmaara'] != 0 ? " (" . $tulos['ilmomaxmaara'] . ")" : ""); ?></div>
                            <div id="out_maara">OUT: <?php echo $tulos['outmaara']; ?></div>
                            <?php
                            tulostailmoittautumisnapit($tulos2['lasna'], isset($tulos2['lasna']) && !$tulos2['lasna'], $tulos['id']);
                            ?>
                        </div>
                        <?php
                    } while ($tulos = mysql_fetch_array($kysely));
                } else {
                    ?>
                    Ei tapahtumia
                    <?php
                }
            } elseif(isset($_GET['joukkue'])) {
                echo "Ei oikeuksia joukkueeseen.";
            }
            ?>
        </div>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
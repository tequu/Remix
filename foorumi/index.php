<?php
//Tuodaan yla.php
include("/home/fbcremix/public_html/Remix/foorumi/apu/yla.php");
?>
<div id="levea_content">
    <div id="foorumi">
        <div id="etusivu">
            <?php
            $siirry = false;
            if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
                $sql = "SELECT id, otsikko FROM keskustelualueryhmat ";
            } else {
                $sql = "SELECT id, otsikko FROM keskustelualueryhmat WHERE ";
                if (isset($_SESSION['id'])) {
                    $sql .= "id IN (SELECT keskustelualueryhmatID FROM keskustelualueet ka, oikeudet o WHERE ka.id=o.keskustelualueetID AND o.tunnuksetID='" . $kayttaja . "') " .
                            "OR id IN (SELECT keskustelualueryhmatID FROM keskustelualueet ka, keskustelualueoikeudet ko WHERE ka.id=ko.keskustelualueetID AND ko.tunnuksetID='" . $kayttaja . "') " .
                            "OR id IN (SELECT keskustelualueryhmatID FROM keskustelualueet ka, joukkueet j, pelaajat p WHERE ka.id=j.keskustelualueetID AND j.id=p.joukkueetID AND p.tunnuksetID='" . $kayttaja . "') " .
                            "OR id IN (SELECT keskustelualueryhmatID FROM keskustelualueet ka, joukkueet j, yhteyshenkilot y WHERE ka.id=j.keskustelualueetID AND j.id=y.joukkueetID AND y.tunnuksetID='" . $kayttaja . "') OR ";
                }
                $sql .= "id IN (SELECT keskustelualueryhmatID FROM keskustelualueet WHERE julkinen='1') ";
            }
            $sql .= "ORDER BY jarjestysnumero";
            $kysely = kysely($yhteys, $sql);
            while ($tulos = mysql_fetch_array($kysely)) {
                ?>
                <div class="keskustelualueet">
                    <div class="otsikko"><?php echo $tulos['otsikko']; ?></div>
                    <?php
                    $sql = "SELECT id, nimi, kuvaus, kmaara, tmaara, vmaara FROM (((keskustelualueet ka LEFT OUTER JOIN " .
                            "(SELECT keskustelualueetID, count(keskustelutID) kmaara FROM keskustelualuekeskustelut " .
                            "WHERE keskustelutID IN (SELECT keskustelutID FROM viestit GROUP BY keskustelutID) " .
                            "GROUP BY keskustelualueetID) k ON ka.id=k.keskustelualueetID) " .
                            "LEFT OUTER JOIN (SELECT kk.keskustelualueetID, count(keskustelutID) tmaara FROM keskustelualuekeskustelut kk, keskustelut k " .
                            "WHERE kk.keskustelutID=k.id AND tapahtumatID IS NOT NULL GROUP BY kk.keskustelualueetID) t ON ka.id=t.keskustelualueetID) " .
                            "LEFT OUTER JOIN (SELECT keskustelualueetID, sum(v.maara) vmaara FROM keskustelualuekeskustelut kk, keskustelut k, " .
                            "(SELECT keskustelutID, count(id) maara FROM viestit GROUP BY keskustelutID) v " .
                            "WHERE kk.keskustelutID=k.id AND k.id=v.keskustelutID GROUP BY keskustelualueetID) v ON ka.id=v.keskustelualueetID) " .
                            "WHERE ";
                    if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
                        $sql .= "keskustelualueryhmatID='" . $tulos['id'] . "' ";
                    } else {
                        $sql .= "(";
                        if (isset($_SESSION['id'])) {
                            $sql .= "id IN (SELECT keskustelualueetID FROM oikeudet WHERE tunnuksetID='" . $kayttaja . "') " .
                                    "OR id IN (SELECT keskustelualueetID FROM keskustelualueoikeudet WHERE tunnuksetID='" . $kayttaja . "') " .
                                    "OR id IN (SELECT keskustelualueetID FROM joukkueet j, pelaajat p WHERE j.id=p.joukkueetID AND p.tunnuksetID='" . $kayttaja . "') " .
                                    "OR id IN (SELECT keskustelualueetID FROM joukkueet j, yhteyshenkilot y WHERE j.id=y.joukkueetID AND y.tunnuksetID='" . $kayttaja . "') OR ";
                        }
                        $sql .= "julkinen='1') AND keskustelualueryhmatID='" . $tulos['id'] . "' ";
                    }
                    $sql .= "ORDER BY jarjestysnumero";
                    $kysely2 = kysely($yhteys, $sql);
                    while ($tulos2 = mysql_fetch_array($kysely2)) {
                        $kysely3 = kysely($yhteys, "SELECT login kirjoittaja, UNIX_TIMESTAMP(lahetysaika) aika FROM tunnukset t, viestit v, keskustelualuekeskustelut kk " .
                                "WHERE t.id=v.tunnuksetID AND v.keskustelutID=kk.keskustelutID AND kk.keskustelualueetID='" . $tulos2['id'] . "' ORDER BY lahetysaika DESC LIMIT 0,1");
                        $tulos3 = mysql_fetch_array($kysely3);
                        ?>
                        <div class="keskustelualue">
                            <div class="vasen">
                                <div class="nimi"><a href="/Remix/foorumi/keskustelualue.php?keskustelualue=<?php echo $tulos2['id']; ?>"><?php echo $tulos2['nimi']; ?></a></div>
                                <div class="kuvaus"><?php echo $tulos2['kuvaus']; ?></div>
                            </div>
                            <div class="keski">
                                <div class="tapahtumia">Tapahtumia: <?php echo $tulos2['tmaara'] ? $tulos2['tmaara'] : "0"; ?></div>
                                <div class="aiheita">Aiheita: <?php echo $tulos2['kmaara'] ? $tulos2['kmaara'] : "0"; ?></div>
                                <div class="viesteja">Viestejä: <?php echo $tulos2['vmaara'] ? $tulos2['vmaara'] : "0"; ?></div>
                            </div>
                            <div class="oikea">
                                <div class="viimeinen_viesti"><?php echo $tulos3['kirjoittaja'] ? "Viimeisin viesti" : ""; ?></div>
                                <div class="kirjoittaja"><?php echo $tulos3['kirjoittaja']; ?></div>
                                <div class="aika"><?php echo $tulos3['aika'] ? date("d.m.y H:i", $tulos3['aika']) : ""; ?></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
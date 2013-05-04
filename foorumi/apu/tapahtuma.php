<?php
$kaikkipelaajat = array();
$in = array();
$out = array();
$eiilmoittautunut = array();
$kin = false;
$kout = false;
$kommentti = "";
$kysely2 = kysely($yhteys, "SELECT t.id id, login FROM tunnukset t, pelaajat p WHERE t.id=p.tunnuksetID AND p.joukkueetID IN " .
        "(SELECT id FROM joukkueet j, keskustelualuekeskustelut kk WHERE j.keskustelualueetID=kk.keskustelualueetID AND kk.keskustelutID='" . $keskustelu . "')");
while ($tulos2 = mysql_fetch_array($kysely2)) {
    $kaikkipelaajat[$tulos2['id']] = $tulos2['login'];
}
$kysely2 = kysely($yhteys, "SELECT id, login, lasna, kommentti FROM tunnukset t, paikallaolo po " .
        "WHERE t.id=po.tunnuksetID AND tapahtumatID='" . $tulos['tapahtumatID'] . "'");
while ($tulos2 = mysql_fetch_array($kysely2)) {
    if ($tulos2['lasna']) {
        if ($kayttaja == $tulos2['id']) {
            $kin = true;
            $kommentti = $tulos2['kommentti'];
        }
        $in[] = array($tulos2['id'], $tulos2['login'], $tulos2['kommentti']);
    } else {
        if ($kayttaja == $tulos2['id']) {
            $kout = true;
            $kommentti = $tulos2['kommentti'];
        }
        $out[] = array($tulos2['id'], $tulos2['login'], $tulos2['kommentti']);
    }
    if ($kaikkipelaajat[$tulos2['id']]) {
        $kaikkipelaajat[$tulos2['id']] = false;
    }
}
foreach ($kaikkipelaajat as $id => $login) {
    if ($login) {
        $eiilmoittautunut[] = array($id, $login);
    }
}
$kysely2 = kysely($yhteys, "SELECT UNIX_TIMESTAMP(alkamisaika) alkamisaika, UNIX_TIMESTAMP(loppumisaika) loppumisaika, kuvaus, UNIX_TIMESTAMP(ilmotakaraja) takaraja, ilmomaxmaara, lisatieto, paikka, inmaara, outmaara " .
        "FROM tapahtumat WHERE id='" . $tulos['tapahtumatID'] . "'");
$tulos2 = mysql_fetch_array($kysely2);
?>
<div id="tapahtuma">
    <?php
    $siirry = false;
    if (tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
        ?>
        <div id="napit">
            <div class="takaisin" onclick="parent.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"></div>
            <div id="muokkaa"></div>
            <div id="poista"></div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#tapahtuma #muokkaa").click(function() {
                    parent.location = "/Remix/foorumi/muokkaa.php?keskustelualue=<?php echo $keskustelualue."&keskustelu=".$keskustelu;?>&mode=tapahtuma";
                });
                $("#tapahtuma #poista").click(function() {
                    parent.location = "/Remix/foorumi/poista.php?keskustelualue=<?php echo $keskustelualue."&keskustelu=".$keskustelu;?>&mode=tapahtuma";
                });
            });
        </script>
        <?php
    }
    ?>
    <div id="clear"></div>
    <div id="otsikko">
        <?php
        tulostailmoittautumisnapit($kin, $kout, $tulos['tapahtumatID']);
        if ($tulos2['takaraja']) {
            ?>
            <div id="ilmoittatuminentakaraja" <?php echo $tulos2['takaraja'] > time() - 3 * 24 * 60 * 60 ? "style=\"color: red;\" " : ""; ?>>
                <?php tulostapaivamaara($tulos2['takaraja'], true, false); ?>
            </div>
            <?php
        }
        echo $tulos['otsikko'];
        ?>
        <div id="clear"></div>
        <div id="ilmoittautumis-error"></div>
    </div>
    <div id="clear"></div>
    <div id="aikajapaikka">
        <div id="aika">
            <?php
            $paiva1 = getdate($tulos2['alkamisaika']);
            $paiva2 = getdate($tulos2['loppumisaika']);
            tulostapaivamaara($tulos2['alkamisaika'], true, true);
            if ($tulos2['loppumisaika']) {
                echo " - ";
                if ($paiva1['mday'] == $paiva2['mday'] && $paiva1['mon'] == $paiva2['mon'] && $paiva1['year'] == $paiva2['year']) {
                    echo date("H:i", $tulos2['loppumisaika']);
                } else {
                    tulostapaivamaara($tulos2['loppumisaika'], TRUE, TRUE);
                }
            }
            ?>
        </div>
        <div id="paikka"><?php echo $tulos2['paikka']; ?></div>
        <div id="clear"></div>
    </div>
    <div id="kuvaus">
        <?php
        echo $tulos2['kuvaus'];
        ?>
    </div>
    <div id="ilmoittautumiset">
        <div id="pohja">
            <div id="in">
                <div id="header">IN: <?php echo $tulos2['inmaara'] . ($tulos2['ilmomaxmaara'] != 0 ? " (Max: " . $tulos2['ilmomaxmaara'] . ")" : ""); ?></div>
                <div id="pelaajat">
                    <?php
                    for ($i = 0; $i < count($in); $i++) {
                        echo "<div class=\"pelaaja\" id=\"" . $in[$i][0] . "\"".(!empty($in[$i][2])?" title=\"" . $in[$i][2] . "\"":"").">" . $in[$i][1] .(!empty($in[$i][2])?" *":""). "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="pohja">
            <div id="out">
                <div id="header">OUT: <?php echo $tulos2['outmaara']; ?></div>
                <div id="pelaajat">
                    <?php
                    for ($i = 0; $i < count($out); $i++) {
                        echo "<div class=\"pelaaja\" id=\"" . $out[$i][0] . "\"".(!empty($out[$i][2])?" title=\"" . $out[$i][2] . "\"":"").">" . $out[$i][1] .(!empty($out[$i][2])?" *":""). "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="kommentti"<?php echo!($kin || $kout) ? " class=\"hidden\"" : ""; ?>>
            <div id="kommentointi-error"></div>
            <div id="kommentointi-ok">Kommentoitu</div>
            <div id="teksti">Kommentti</div> 
            <input type="text" class="kommenttikentta" value="<?php echo $kommentti; ?>">
            <div class="laheta" id="kommentoi" data-id="<?php echo $tulos['tapahtumatID']; ?>"></div>
        </div>
        <div id="pelaajat">
            <?php
            for ($i = 0; $i < count($eiilmoittautunut); $i++) {
                echo "<div class=\"pelaaja\" id=\"" . $eiilmoittautunut[$i][0] . "\">" . $eiilmoittautunut[$i][1] . "</div>";
            }
            ?>
        </div>
    </div>
    <div id="lisatieto">
        <?php
        echo $tulos2['lisatieto'];
        ?>
    </div>
</div>
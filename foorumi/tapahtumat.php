<?php
$kirjautunut = true;
include("/home/fbcremix/public_html/Remix/yla.php");
?>
<div id="container">
    <?php
    $kysely = kysely($yhteys, "SELECT count(t.id) maara FROM Tapahtumat t, Keskustelut k, Keskustelualueet_keskustelut kk, Joukkueet j, Joukkueenjasenet jj " .
            "WHERE t.id=k.tapahtumatID AND k.id=kk.keskustelutID AND kk.keskustelualueetID=j.keskustelualueetID AND j.id=jj.joukkueetID AND tunnuksetID='" . $kayttaja . "'");
    $tulos = mysql_fetch_array($kysely);
    $ysm = 10;
    $sivu = mysql_real_escape_string($_GET['sivu']);
    if ($tulos['maara'] > 0) {
        $maara = $tulos['maara'];
        ?>
        <div id="keskusteluvalikko">
            <div id="oikea">
                <div id="sivunvaihto">
                    <?php
                    if ($sivu > ceil($maara / $ysm))
                        $sivu = ceil($maara / $ysm);
                    if ($sivu < 1)
                        $sivu = 1;
                    if ($sivu != 1) {
                        ?>
                        <a href="/Foorumit/keskustelu.php?keskustelu=<?php echo $keskustelu; ?>&sivu=1"><div id="sivunumero">Ensimmäinen</div></a>
                        <a href="/Foorumit/keskustelu.php?keskustelu=<?php echo $keskustelu . "&sivu=" . ($sivu - 1); ?>"><div id="sivunumero"><</div></a>
                        <?php
                    }
                    tulostaSivuNumerot($maara, $sivu, $ysm, "/Foorumit/keskustelu.php?keskustelu=" . $keskustelu, false);
                    if ($sivu != ceil($maara / $ysm)) {
                        ?>
                        <a href="/Foorumit/keskustelu.php?keskustelu=<?php echo $keskustelu . "&sivu=" . ($sivu + 1); ?>"><div id="sivunumero">></div></a>
                        <a href="/Foorumit/keskustelu.php?keskustelu=<?php echo $keskustelu . "&sivu=" . ceil($maara / $ysm); ?>"><div id="sivunumero">Viimeinen</div></a>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id="clear"></div>
        </div>
        <?php
        $kysely = kysely($yhteys, "SELECT t.id id, t.nimi nimi, alkamisaika, loppumisaika, t.kuvaus kuvaus, paikka FROM Tapahtumat t, Keskustelut k, Keskustelualueet_keskustelut kk, Joukkueet j, Joukkueenjasenet jj " .
                "WHERE t.id=k.tapahtumatID AND k.id=kk.keskustelutID AND kk.keskustelualueetID=j.keskustelualueetID AND j.id=jj.joukkueetID AND tunnuksetID='" . $kayttaja . "' ORDER BY alkamisaika");
        while ($tulos = mysql_fetch_array($kysely)) {
            ?>
            <div id="tapahtuma">
                <div id="nimi"><?php echo $tulos['nimi']; ?></div>
                <div id="aikapaikka"><?php echo $tulos['alkamisaika'] . ($tulos['loppumisaika'] ? "" : "-" . $tulos['loppumisaika']) . ", " . $tulos['paikka']; ?></div>
                <hr />
                <div id="lasnaolevat">
                    <?php
                    $kysely2 = kysely($yhteys, "SELECT login FROM Paikallaolo, Tunnukset t WHERE tunnuksetID=t.id AND tapahtumatID='" . $tulos['tid'] . "' AND lasna='1'");
                    while ($tulos2 = mysql_fetch_array($kysely3)) {
                        echo"<div id=\"tunnus\">" . $tulos2['login'] . "</div>";
                    }
                    ?>
                    <div id="clear"></div>
                </div>
                <hr />
                <?php
                $kysely2 = kysely($yhteys, "SELECT lasna FROM Paikallaolo WHERE tapahtumatID='" . $tulos['id'] . "' AND tunnuksetID='" . $kayttaja . "'");
                $tulos2 = mysql_fetch_array($kysely2);
                ?>
                <input type="radio" name="lasna_<?php echo $tulos['id']; ?>" value="1"<?php echo($tulos2 && $tulos2['lasna'] ? " CHECKED" : ""); ?> onclick="ilmoittauduTapahtumaan('<?php echo $tulos['id']; ?>', '1')" />Läsnä <input type="radio" name="lasna_<?php echo $tulos['id']; ?>" value="0"<?php echo($tulos2 && !$tulos2['lasna'] ? " CHECKED" : ""); ?> onclick="ilmoittauduTapahtumaan('<?php echo $tulos['id']; ?>', '0')" />Poissa <span id="tila_<?php echo $tulos['id']; ?>"></span>
                <?php
                ?>
            </div>
            <?php
        }
    } else {
        echo"Sinulla ei ole tapahtumia";
    }
    ?>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
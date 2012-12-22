<?php
include("/home/fbcremix/public_html/Remix/yla.php");
include("/home/fbcremix/public_html/Remix/ohjelmat/oikea.php");
?>
<div id="content">
    <div id="pelit_pohja" style="margin-top: 0px">
         <div class="otsikko" style="background-image:URL('/Remix/kuvat/pelit.png')"></div>
        <?php
        $aika = mysql_real_escape_string(trim($_GET['aika'])) / 1000;
        if (empty($aika)) {
            $paiva = getdate();
            $aika = mktime(0, 0, 0, $paiva['mon'], $paiva['mday'], $paiva['year']);
        }
        $paiva = getdate($aika);
        $peliryhmannimi = "Pelit " . $paiva['mday'] . "." . $paiva['mon'] . "." . $paiva['year'];
        $peliryhmankausi = $kausi;
        $kysely = kysely($yhteys, "SELECT j.nimi nimi, vastustaja, UNIX_TIMESTAMP(aika) AS aika, koti, kotimaalit, vierasmaalit, pelipaikka, pelattu, kotiturnaus ".
                "FROM pelit p, peliryhmat pr, joukkueet j WHERE p.peliryhmatID=pr.id AND joukkueetID=j.id ".
                "AND UNIX_TIMESTAMP(aika)>='" . $aika . "' AND UNIX_TIMESTAMP(aika)<'" . ($aika + 60 * 60 * 24) . "' ORDER BY j.nimi, aika");
        include("/home/fbcremix/public_html/Remix/ohjelmat/pelit.php");
        ?>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
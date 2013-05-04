<?php
include("/home/fbcremix/public_html/Remix/yla.php");
?>
<div id="levea_content">
    <div id="vieraskirja">
    <?php
    if ($_GET['mode'] == "kirjoita") {
        include("/home/fbcremix/public_html/Remix/vieraskirja/kirjoita.php");
    } else if($_GET['mode'] == "saannot"){
        include("/home/fbcremix/public_html/Remix/vieraskirja/saannot.php");
    }else {
        ?>
            <div class="otsikko" style="background-image:URL('/Remix/kuvat/vieraskirja.png')"></div>
            <div id="linkit">
                <div id="saannot" onclick="parent.location='/Remix/vieraskirja.php?mode=saannot'"></div>
                <div id="uusiviesti" onclick="parent.location='/Remix/vieraskirja.php?mode=kirjoita'"></div>
                <div id="sivunumerot">
                    <?php
                    $kysely = kysely($yhteys, "SELECT count(id) maara FROM vieraskirja WHERE enabled = '1' AND poistettu = '0'");
                    $tulos = mysql_fetch_array($kysely);
                    /** Sivutuksen asetukset */
                    $maara = $tulos['maara'];
                    $ysm = 10;
                    $sivu = $_GET['sivu'];
                    $linkki = "/Remix/vieraskirja.php?";
                    $viimeinen = false;
                    $nuolet = true;
                    $ejav = true;
                    tulostasivunumerot($maara, $ysm, $sivu, $linkki, $viimeinen, $nuolet, $ejav);
                    ?>
                </div>
                <div id="clear"></div>
            </div>
            <?php 
            include("/home/fbcremix/public_html/Remix/vieraskirja/tulostaviestit.php");
        }
        ?>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>

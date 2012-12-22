<?php 
include("/home/fbcremix/public_html/Remix/yla.php");
include("/home/fbcremix/public_html/Remix/ohjelmat/oikea.php"); 
?>
<div id="content">
    <div id="joukkueet">
        <div id="osa">
            <div class="otsikko" style="background-image:URL('/Remix/kuvat/joukkueet.png')"></div>
            <?php
            $kerho = array();
            $valikkoid = 0;
            $kysely = kysely($yhteys, "SELECT nimi, kerho FROM joukkueet WHERE kausi='" . $kausi . "' ORDER BY jarjestysnumero");
            while ($tulos = mysql_fetch_array($kysely)) {
                if ($tulos['kerho']) {
                    $kerho[] = array($valikkoid, $tulos['nimi']);
                    $valikkoid++;
                    continue;
                }
                ?>
                <div id="nimi"><a href="/Remix/joukkueet/index.php?id=<?php echo $valikkoid; ?>"><?php echo $tulos['nimi']; ?></a></div>
                <?php
                $valikkoid++;
            }
            ?>
        </div>
        <div id="osa">
            <div class="otsikko" style="background-image:URL('/Remix/kuvat/kerhot.png');"></div>
            <?php
            foreach ($kerho as $a) {
                ?>
                <div id="nimi"><a href="/Remix/joukkueet/index.php?id=<?php echo $a[0]; ?>"><?php echo $a[1]; ?></a></div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<?php include("/home/fbcremix/public_html/Remix/ala.php"); ?>

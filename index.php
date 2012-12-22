<?php
include("/home/fbcremix/public_html/Remix/yla.php");
include("/home/fbcremix/public_html/Remix/ohjelmat/oikea.php");
?>
<div id="content">
    <div id="etusivu_uutiset">
        <div class="otsikko" style="background-image:URL('/Remix/kuvat/uutiset.png')"></div>
        <?php
        $kysely = kysely($yhteys, "SELECT id, uutinen, UNIX_TIMESTAMP(kirjoitusaika) kirjoitusaika, otsikko, kuvaus, kuva FROM uutiset ORDER BY kirjoitusaika DESC LIMIT 0,4");
        if ($tulos = mysql_fetch_array($kysely)) {
            $maxkirjaimia = 210;
            $kuvaus = $tulos['kuvaus'];
            if (strlen($tulos['kuvaus']) >= $maxkirjaimia) {
                $kuvaus = substr($kuvaus, 0, strrpos($kuvaus, " ", $maxkirjaimia - strlen($kuvaus)));
            } elseif (strlen($tulos['uutinen']) + strlen($tulos['kuvaus']) >= $maxkirjaimia) {
                $lopetus = strlen($tulos['uutinen']) - ($maxkirjaimia - strlen($tulos['kuvaus']));
                $uutinen = substr($tulos['uutinen'], 0, strrpos($tulos['uutinen'], " ", -$lopetus));
            } else {
                $uutinen = $tulos['uutinen'];
            }
            ?>
            <div id="etusivu_uutis_otsikko"><span id="kirjoitusaika"><?php echo date("d.m.Y", $tulos['kirjoitusaika']); ?></span> 
                <a href="/Remix/uutiset.php?uutisid=<?php echo $tulos['id']; ?>"><?php echo $tulos['otsikko']; ?></a></div>
            <div id="etusivu_uutinen"<?php echo ($tulos['kuva'] == "ei" || empty($tulos['kuva']) ? " style=\"height: 55px;\"" : ""); ?>>
                <?php
                if ($tulos['kuva'] != "ei" && !empty($tulos['kuva'])) {
                    ?>
                    <div id="kuva">
                        <img src="/Remix/kuvat/uutiset/uutinen_<?php echo date("dmY", $tulos['kirjoitusaika']) . "/" . muutaTekstiHyvaksyttavaanMuotoon($tulos['otsikko']) . "/mini_" . $tulos['kuva']; ?>" />
                    </div>
                    <?php
                }
                ?>
                <span id="kuvaus"><?php echo $kuvaus; ?></span><?php echo (empty($uutinen) ? "..." : ""); ?>
                <?php
                if (!empty($uutinen)) {
                    ?>
                    <span id="teksti"><?php echo $uutinen; ?>...</span>
                    <?php
                }
                ?>
                <span id="luelisaa"><a href="/Remix/uutiset.php?uutisid=<?php echo $tulos['id']; ?>">Lue lis�� >></a></span>
            </div>
            <?php
            while ($tulos = mysql_fetch_array($kysely)) {
                ?>
                <div id="etusivu_uutis_otsikko"><span id="kirjoitusaika"><?php echo date("d.m.Y", $tulos['kirjoitusaika']); ?></span> 
                    <a href="/Remix/uutiset.php?uutisid=<?php echo $tulos['id']; ?>"><?php echo $tulos['otsikko']; ?></a></div>
                <?php
            }
        }
        ?>
    </div>
    <div id="etusivu_kuvat">
        <div class="otsikko" style="background-image:URL('/Remix/kuvat/uusimmat-kuvat.png')"></div>
    </div>  
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
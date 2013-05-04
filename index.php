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
            $kuvaus = katkaiseTeksti($tulos['kuvaus'], $maxkirjaimia);
            if(strlen($kuvaus) < $maxkirjaimia){
                $uutinen = katkaiseTeksti($tulos['uutinen'], $maxkirjaimia - strlen($kuvaus));
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
                <span id="luelisaa"><a href="/Remix/uutiset.php?uutisid=<?php echo $tulos['id']; ?>">Lue lisää >></a></span>
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
        <?php
        $kysely = kysely($yhteys, "SELECT kuva, kuvakategoriatID FROM kuvat ORDER BY lahetysaika DESC LIMIT 0,4");
        while ($tulos = mysql_fetch_array($kysely)) {
            ?>
            <a href="/Remix/kuvagalleria.php"><div class="kuva" style="background-image: URL('/Remix/kuvat/kuvakategoriat/<?php echo $tulos['kuvakategoriatID'] . "/" . $tulos['kuva']; ?>')"></div></a>
                <?php
            }
            ?>
    </div>  
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
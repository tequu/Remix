<?php
include("/home/fbcremix/public_html/Remix/yla.php");
?>

<div id="levea_content">
    <div class="otsikko" style="background-image:URL('/Remix/kuvat/uutiset.png')"></div>
    <div id='uutisetsivu_vasen'>
        <div id="sivunumerot">
            <?php
            $kysely = kysely($yhteys, "SELECT count(id) maara FROM uutiset");
            $tulos = mysql_fetch_array($kysely);
            $maara = $tulos['maara'];
            $sivu = $_GET['sivu'];
            $ysm = 10;
            $linkki = "/Remix/uutiset.php?";
            $viimeinen = false;
            $nuolet = true;
            ;
            $ejav = false;
            include("/home/fbcremix/public_html/Remix/ohjelmat/sivunumerot.php");
            ?>
        </div>
        <div id="clear"></div>
        <?php
        $lahto = $sivu * $ysm;
        $laskuri = 0;
        $siirry = false;
        $kysely = kysely($yhteys, "SELECT id, UNIX_TIMESTAMP(kirjoitusaika) AS kirjoitusaika, otsikko, kuvaus, tunnuksetID FROM uutiset ORDER BY kirjoitusaika DESC LIMIT " . $lahto . ", " . $ysm);
        while ($tulos = mysql_fetch_array($kysely)) {
            if ($laskuri != 0)
                echo"<hr />";
            $laskuri++;
            ?>
            <div id="uutis_otsikko">
                <div id="otsikko">
                    <a onclick="haeUutinen('<?php echo $tulos['id']; ?>')" style="cursor:pointer;"><?php echo ucfirst($tulos['otsikko']); ?></a>
                </div>
                <div id="kirjoitusaika">
                    <?php
                    echo date("d.m.Y H:i", $tulos['kirjoitusaika']);
                    if (tarkistaAdminOikeudet($yhteys, "Masteradmin") || (isset($_SESSION['id']) && $_SESSION['id'] != 0 && $_SESSION['id'] == $tulos['tunnuksetID'])) {
                        ?>
                        <div class="muokkaa" onclick="siirry('/Remix/ohjauspaneeli.php?sivuid=<?php echo $ouutiset; ?>&mode=muokkaa&uutisetid=<?php echo $tulos['id']; ?>')" ></div>
                        <?php
                    }
                    ?>
                </div>
                <div id="kuvaus"><?php echo $tulos['kuvaus']; ?></div>
                <div id="clear"></div>
                <div id="luelisaa">
                    <a onclick="haeUutinen('<?php echo $tulos['id']; ?>')">Lue lisää >></a> 
                </div>
            </div>
            <?php
        }
        ?>
        <div id="sivunumerot" style="margin-top: 15px;">
            <?php include("/home/fbcremix/public_html/Remix/ohjelmat/sivunumerot.php"); ?>
        </div>
    </div>
    <div id="uutisetsivu_oikea">
    </div>
    <?php
    if (isset($_GET['uutisid']))
        echo"<script type='text/javascript'>
				$(document).ready(haeUutinen(" . mysql_real_escape_string($_GET['uutisid']) . "));
			</script>";
    ?>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
<?php
$kirjautunut = true;
if (!isset($_GET['keskustelualue']))
    header("Location: /Remix/foorumi/index.php");
include("/home/fbcremix/public_html/Remix/foorumi/apu/yla.php");
?>
<div id="levea_content">
    <div id="foorumi">
        <div id="pohja">
            <?php
            if ($_GET['mode'] == "keskustelu") {
                include("/home/fbcremix/public_html/Remix/foorumi/uusi/keskustelu.php");
            } elseif ($_GET['mode'] == "tapahtuma") {
                include("/home/fbcremix/public_html/Remix/foorumi/uusi/tapahtuma.php");
            } elseif ($_GET['mode'] == "viesti") {
                $keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
                $keskustelu = mysql_real_escape_string($_GET['keskustelu']);
                $kysely = kysely($yhteys, "SELECT keskustelutID FROM keskustelualuekeskustelut WHERE keskustelualueetID='" . $keskustelualue . "' AND keskustelutID='" . $keskustelu . "'");
                if ($tulos = mysql_fetch_array($kysely)) {
                    if (tarkistaNakyvyysOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
                        include("/home/fbcremix/public_html/Remix/foorumi/uusi/viesti.php");
                    } else {
                        echo "Ei oikeuksia keskustelualueelle.";
                    }
                } else {
                    echo "Keskustelua ei löydy.";
                }
            }
            ?>
        </div>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
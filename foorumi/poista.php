<?php
$kirjautunut = true;
include("/home/fbcremix/public_html/Remix/foorumi/apu/yla.php");
?>
<div id="levea_content">
    <div id="foorumi">
        <?php
        if ($_GET['mode'] == "viesti") {
            include("/home/fbcremix/public_html/Remix/foorumi/poista/viesti.php");
        } elseif ($_GET['mode'] == "tapahtuma") {
            include("/home/fbcremix/public_html/Remix/foorumi/poista/tapahtuma.php");
        } else if ($_GET['mode'] == "keskustelu") {
            include("/home/fbcremix/public_html/Remix/foorumi/poista/keskustelu.php");
        }
        ?>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
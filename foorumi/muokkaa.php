<?php
$kirjautunut = true;
include("/home/fbcremix/public_html/Remix/foorumi/apu/yla.php");
?>
<div id="levea_content">
    <div id="foorumi">
        <div id="pohja">
            <?php
            if($_GET['mode'] == "viesti"){
                include("/home/fbcremix/public_html/Remix/foorumi/muokkaus/viesti.php");
            } elseif($_GET['mode'] == "tapahtuma"){
                include("/home/fbcremix/public_html/Remix/foorumi/muokkaus/tapahtuma.php");
            } elseif($_GET['mode'] == "keskustelu"){
                include("/home/fbcremix/public_html/Remix/foorumi/muokkaus/keskustelu.php");
            }
            ?>
        </div>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
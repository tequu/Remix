<?php
if (!isset($_GET['keskustelualue']))
    header("Location: /Remix/foorumi/index.php");
include("/home/fbcremix/public_html/Remix/foorumi/yla.php");
?>
<div id="levea_content">
    <div id="foorumi">
        <div id="pohja">
            <?php
            if($_GET['mode'] == "keskustelu"){
                include("/home/fbcremix/public_html/Remix/foorumi/uusi/keskustelu.php");
            } elseif($_GET['mode'] == "tapahtuma"){
                include("/home/fbcremix/public_html/Remix/foorumi/uusi/tapahtuma.php");
            }
            ?>
        </div>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
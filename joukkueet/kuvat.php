<?php
include("/home/fbcremix/public_html/Remix/yla.php");
?>
<div id="levea_content">
    <div class="kuvagalleria">
        <div class="otsikko" style="background-image:URL('/Remix/kuvat/kuvagalleria.png')"></div>
        <div id="takaisin"></div><div id="sivunumerot"></div>
        <div id="clear"></div>
        <div class="kaikki_kuvat"></div>
        <?php
//        if(!isset($_GET['kategoriatid'])){
        include("/home/fbcremix/public_html/Remix/kuvagalleria/index.php");
//        } else {
            include("/home/fbcremix/public_html/Remix/kuvagalleria/kategoria.php");
//        }
        ?>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
<?php
include("/home/fbcremix/public_html/Remix/yla.php");
?>
<div id="container">
    <?php
    echo $_SESSION['eioikeuksia'];
    ?>
    <div class="virhe_otsikko">Sinulla ei ole oikeuksia kyseiselle sivulle. Voit pyytt‰‰ niit‰ keskustelualueen yll‰pidolta tai jos kyseess‰ on joukkue, niin joukkueen yll‰pidolta. Mene takaisin etusivulle <a href="/Foorumit/index.php">t‰st‰</a></div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
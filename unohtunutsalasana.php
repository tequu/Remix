<?php include("/home/fbcremix/public_html/Remix/yla.php"); ?>
<div id="content">
    <div id="unohtunutsalasana">
        <div class="otsikko" style="background-image: URL('/Remix/kuvat/unohtunutsalasana.png');"></div>
        <div id="error"><?php echo $error; ?></div>
        <form action="/Remix/unohtunutsalasana.php" name="unohtunutsalasana" method="post">
            <input type="hidden" name="ohjaa" value="76" />
            Anna s�hk�postiosoite, jonka olet antanut rekister�itymisen yhteydess�<br />
            S�hk�postiosoite:<input type="text" name="email" />
            <br />
            <div class="laheta" onclick="laheta('unohtunutsalasana', [], []);" ></div>
        </form>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
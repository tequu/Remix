<?php
include("/home/fbcremix/public_html/Remix/yla.php");

echo"<div id='container'>";
include("/home/fbcremix/public_html/Remix/oikeabanneri.php");
if(isset($_GET['kuva']))
    include("/home/fbcremix/public_html/Remix/kuvaohjelmat/kuva.php");
elseif(isset($_GET['kategoria']))
    include("/home/fbcremix/public_html/Remix/kuvaohjelmat/kategoria.php");
else
    include("/home/fbcremix/public_html/Remix/kuvaohjelmat/index.php");
echo"<div style='clear:both'></div>
    </div>";
include("/home/fbcremix/public_html/Remix/ala.php");
?>
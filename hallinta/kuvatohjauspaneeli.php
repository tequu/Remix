<?php
echo"<div class='otsikko'>Kuvat ohjauspaneeli</div>";
if(in_array("Masteradmin",$_SESSION['oikeudet']) || (($joukkueid==0 && in_array("Admin",$_SESSION['oikeudet'])) || in_array($joukkueid,$_SESSION['oikeudet']))){
    if(isset($_GET['mode']))
        include("/home/fbcremix/public_html/Remix/ohjelmat/kuvatohjauspaneeli/index.php");
    else{
        $kysely = mysql_query("SELECT id, nimi FROM kategoriat WHERE joukkueid='".$joukkueid."' AND kausi='".$kausi."'") or die("Hae seuran kategoriat: ".mysql_error());
        echo"<div id='ohjauspaneeli_ala'>
            <div class='ala_otsikko' id='keskelle'>Kuvien lis‰ys, muokkaus ja poisto</div>
            <a href='/Remix/ohjauspaneeli.php?ohjelmaid=9&mode=lisaakategoria".($joukkueid!=0?"&joukkueid=".$joukkueid:"")."'>Lis‰‰ kategoria</a><br /><br />";
        if($tulos = mysql_fetch_array($kysely)){
            echo"<form action='/Remix/ohjauspaneeli.php?ohjelmaid=9&mode=muokkaakategoriaa".($joukkueid!=0?"&joukkueid=".$joukkueid:"")."' method='post'>
                Valitse kategoria:<br />
                <select name='kategoriatid'>";
            do
                echo"<option value='".$tulos['id']."'>".$tulos['nimi']."</option>";
            while($tulos = mysql_fetch_array($kysely));
            echo"</select><br />
                <input type='submit' value='Muokkaa' />
                </form>";
        }
    }
}
elseif(!isset($_SESSION['id']))
    echo"<div style='font-size:30px;text-align:center;'>Kirjaudu sis‰‰n</div>";
else
    echo"<div style='font-size:30px;text-align:center;'>Ei oikeuksia!</div>";
?>

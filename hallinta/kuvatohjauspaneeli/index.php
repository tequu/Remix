<?php
if($_GET['mode'] == 'lisaakategoria')
    include("/home/fbcremix/public_html/Remix/ohjelmat/kuvatohjauspaneeli/lisaakategoria.php");
elseif($_GET['mode'] == 'lisaakuva' && (isset($_GET['kategoriatid']) || isset($_POST['kategoriatid'])))
    include("/home/fbcremix/public_html/Remix/ohjelmat/kuvatohjauspaneeli/lisaakuva.php");
elseif($_GET['mode'] == 'muokkaakategoriaa' && (isset($_GET['kategoriatid']) || isset($_POST['kategoriatid'])))
    include("/home/fbcremix/public_html/Remix/ohjelmat/kuvatohjauspaneeli/muokkaakategoriaa.php");
elseif($_GET['mode'] == 'muokkaanimea' && (isset($_GET['kategoriatid']) || isset($_POST['kategoriatid'])))
    include("/home/fbcremix/public_html/Remix/ohjelmat/kuvatohjauspaneeli/muokkaanimea.php");
elseif($_GET['mode'] == 'poistakategoria' && (isset($_GET['kategoriatid']) || isset($_POST['kategoriatid'])))
    include("/home/fbcremix/public_html/Remix/ohjelmat/kuvatohjauspaneeli/poistakategoria.php");
elseif($_GET['mode'] == 'poistakuvat' && (isset($_GET['kategoriatid']) || isset($_POST['kategoriatid'])))
    include("/home/fbcremix/public_html/Remix/ohjelmat/kuvatohjauspaneeli/poistakuvat.php");
elseif($_GET['mode'] == 'poistakuvia' && (isset($_GET['kategoriatid']) || isset($_POST['kategoriatid'])))
    include("/home/fbcremix/public_html/Remix/ohjelmat/kuvatohjauspaneeli/poistakuvia.php");

else{
    $kysely = mysql_query("SELECT id, nimi FROM kategoriat WHERE joukkueid='".$joukkueid."' AND kausi='".$kausi."'") or die("Hae seuran kategoriat: ".mysql_error());
    echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."\"'>Takaisin</div><div id='ohjauspaneeli_ala'>
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


?>

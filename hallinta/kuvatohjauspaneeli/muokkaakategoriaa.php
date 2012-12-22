<?php
$kategoriatid = mysql_real_escape_string(isset($_POST['kategoriatid'])?$_POST['kategoriatid']:$_GET['kategoriatid']);
$kysely = mysql_query("SELECT nimi, joukkueid FROM kategoriat WHERE id='".$kategoriatid."'") or die("Hae kategorian nimi:". mysql_error());
if($tulos = mysql_fetch_array($kysely)){
    if($tulos['joukkueid']==$joukkueid)
    echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."\"'>Takaisin</div><br />
            <div style='margin-left:40px;'>
            <div class='ala_otsikko'>Muokkaa kategoriaa</div><b>Kategorian nimi:</b> ".$tulos['nimi']."</div><br />
            <div id='ohjauspaneeli_linkki' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=lisaakuva&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."\"'>Lis‰‰ kuva</div>
            <div id='ohjauspaneeli_linkki' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=poistakuvia&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."\"'>Poista kuvia</div>
            <div id='ohjauspaneeli_linkki' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=muokkaanimea&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."\"'>Muokkaa nime‰</div>
            <div id='ohjauspaneeli_linkki' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=poistakategoria&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."\"'>Poista kategoria</div>";
    else
        echo"Virheellinen joukkueid. Palaa <a href='".$_SERVER['PHP_SELF']."'>takasin</a> ja yrit‰ uudelleen.";
}
else {
    echo"Virhe haettaessa tietoja. Palaa <a href='".$_SERVER['PHP_SELF']."'>takaisin</a> ja yrit‰ uudelleen.";
}
?>

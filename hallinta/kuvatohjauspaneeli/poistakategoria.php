<?php
$kategoriatid = mysql_real_escape_string(isset($_POST['kategoriatid'])?$_POST['kategoriatid']:$_GET['kategoriatid']);
$kysely = mysql_query("SELECT nimi, joukkueid FROM kategoriat WHERE id='".$kategoriatid."'") or die("Hae kategorian nimi: ".mysql_error());
if($tulos = mysql_fetch_array($kysely)){
    if($tulos['joukkueid']==$joukkueid){
        $kysely = mysql_query("SELECT kuva FROM kuvat WHERE kategoriatid='".$kategoriatid."' ORDER BY id") or die("Hae kuvat: ".mysql_error());
        echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=muokkaakategoriaa&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."\"'>Takaisin</div>
            <br />
            <br />
            <div style='margin-left:40px;'>
            <div class='ala_otsikko'>Poista kategoria</div>
            <div id='error'>".$error."</div>
            <b>Kategoria sis‰lt‰‰ seuraavat kuvat</b>:<br />";
        if($tulos = mysql_fetch_array($kysely)){
            do
                echo $tulos['kuva']."<br />";
            while($tulos = mysql_fetch_array($kysely));
        }
        else
            echo"Kategoriassa ei ole kuvia.<br />";
        echo"<br />Oletko varma ett‰ haluat poistaa kategorian ja kaikki sen sis‰lt‰m‰t kuvat?<br />
            <form action='".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=poistakategoria&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."' method='post'>
                <input type='hidden' name='lahetetty' value='50' />
                <input type='hidden' name='kategoriatid' value='".$kategoriatid."' />
                <input type='submit' value='Kyll‰' />
                <input type='button' value='En' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=muokkaakategoriaa&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."\"' />
            </form></div>";
    }
    else
        echo"Virheellinen joukkueid. Palaa <a href='".$_SERVER['PHP_SELF']."'>takaisin</a> ja yrit‰ uudelleen.";
}
else
    echo"Virhe haettaessa kategorian tietoja. Palaa <a href='".$_SERVER['PHP_SELF']."'>takaisin</a>.";
?>

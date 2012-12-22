<?php
$kategoriatid = mysql_real_escape_string(isset($_POST['kategoriatid'])?$_POST['kategoriatid']:$_GET['kategoriatid']);
$kysely = mysql_query("SELECT nimi, joukkueid FROM kategoriat WHERE id='".$kategoriatid."'") or die("Hae kategorian nimi: ".mysql_error());
if($tulos = mysql_fetch_array($kysely)){
    if($tulos['joukkueid']==$joukkueid){
        $kysely = mysql_query("SELECT id, kuva FROM kuvat WHERE kategoriatid='".$kategoriatid."' ORDER BY id") or die("Hae kuvat: ".mysql_error());
        echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=muokkaakategoriaa&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."\"'>Takaisin</div>
            <br />
            <br />
            <div style='margin-left:40px;'><div class='ala_otsikko'>Poista kuvia</div>
            <div id='error'>".$error."</div>";
        if($tulos = mysql_fetch_array($kysely)){
            echo"<form action='".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=poistakuvat&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."' method='post'>
            <input type='hidden' name='kategoriatid' value='".$kategoriatid."' />
            Valitse poistettavat kuvat:<br />";
            do{
                echo "<input type='checkbox' name='kuvatidt[]' value='".$tulos['id']."' />".$tulos['kuva']."<br />";
            }
            while($tulos = mysql_fetch_array($kysely));  
            echo"<input type='submit' value='Poista' /></form>";
        }
        else
            echo"Kategoriassa ei ole kuvia.";
        echo"</div>";
    }
    else
        echo"Virheellinen joukkueid. Palaa <a href='".$_SERVER['PHP_SELF']."'>takaisin</a> ja yritä uudelleen.";
}
else
    echo"Virhe haettaessa kuvan tietoja. Palaa <a href='".$_SERVER['PHP_SELF']."'>takaisin</a>.";
?>

<?php
$kategoriatid = mysql_real_escape_string(isset($_POST['kategoriatid'])?$_POST['kategoriatid']:$_GET['kategoriatid']);
$kuvatidt = $_POST['kuvatidt'];
$kysely = mysql_query("SELECT nimi, joukkueid FROM kategoriat WHERE id='".$kategoriatid."'") or die("Hae kategorian nimi: ".mysql_error());
if($tulos = mysql_fetch_array($kysely)){
    if($tulos['joukkueid']==$joukkueid){
        echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=poistakuvia&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."\"'>Takaisin</div>
            <br />
            <br />
            <div style='margin-left:40px;'><div class='ala_otsikko'>Poista kuvat</div>
            <br />Oletko varma että haluat poistaa seuraavat kuvat?<br />
            <form action='".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=poistakuvia&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."' method='post'>
                <input type='hidden' name='lahetetty' value='51' />
                <input type='hidden' name='kategoriatid' value='".$kategoriatid."' />";
        foreach($kuvatidt as $kuvatid){
            $kuvatid = mysql_real_escape_string($kuvatid);
            $kysely = mysql_query("SELECT kuva, kategoriatid FROM kuvat WHERE id='".$kuvatid."'") or die("Hae kuvan nimi: ".mysql_error());
            $tulos = mysql_fetch_array($kysely);
            echo"<input type='hidden' name='kuvatidt[]' value='".$kuvatid."' />
                ".$tulos['kuva']."<br />";
        }
                echo"<br /><input type='submit' value='Kyllä' />
                    <input type='button' value='En' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=poistakuvia&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."\"' />
                    </form></div>";
    }
    else
        echo"Virheellinen joukkueid. Palaa <a href='".$_SERVER['PHP_SELF']."'>takaisin</a> ja yritä uudelleen.";
}
else
    echo"Virhe haettaessa kuvan tietoja. Palaa <a href='".$_SERVER['PHP_SELF']."'>takaisin</a>.";
?>

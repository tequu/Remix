<?php
$kategoriatid = mysql_real_escape_string(isset($_POST['kategoriatid'])?$_POST['kategoriatid']:$_GET['kategoriatid']);
$kysely = mysql_query("SELECT joukkueid FROM kategoriat WHERE id='".$kategoriatid."'") or die("Hae kategoriatid: ".mysql_error());
if($tulos = mysql_fetch_array($kysely)){
    if($tulos['joukkueid'] == $joukkueid)
        echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=muokkaakategoriaa&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."\"'>Takaisin</div>
            <div style='margin-left:40px;'><div class='ala_otsikko'>Lis‰‰ kuva</div>
            <div id='error'>".$error."</div>
            <form enctype='multipart/form-data' action='/Remix/ohjauspaneeli.php?ohjelmaid=9&mode=lisaakuva&kategoriatid=".$_GET['kategoriatid'].($joukkueid!=0?"&joukkueid=".$joukkueid:"")."' method='post'>
                <input type='hidden' name='lahetetty' value='18' />
                <input type='hidden' name='kategoriatid' value='".$kategoriatid."' />
                Kuva:<input type='file' name='kuva' />(Maksimi 600x450, 1Mt)<br />
                Kuvateksti:<br /><textarea name='kuvateksti' rows='5' cols='40'></textarea><br />
                <input type='submit' value='L‰het‰' />
            </form></div>";
    else
        echo"Joukkueid on virheellinen, mene <a href='".$_SERVER['PHP_SELF']."'>takaisin</a> ja yrit‰ uudelleen.";
}
else {
    echo"Virhe mene <a href='".$_SERVER['PHP_SELF']."'>takaisin</a> ja yrit‰ uudelleen.";
}
?>

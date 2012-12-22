<?php
$kategoriatid = mysql_real_escape_string(isset($_POST['kategoriatid'])?$_POST['kategoriatid']:$_GET['kategoriatid']);
$kysely = mysql_query("SELECT nimi, joukkueid FROM kategoriat WHERE id='".$kategoriatid."'") or die("Hae kategorian nimi: ".mysql_error());
if($tulos = mysql_fetch_array($kysely)){
    if($tulos['joukkueid']==$joukkueid)
        echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=muokkaakategoriaa&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."\"'>Takaisin</div>
            <br />
            <br />
            <div style='margin-left:40px;'>
            <div class='ala_otsikko'>Muokkaa kategorian nimeä</div>
            <div id='error'>".$error."</div>
            <form action='/Remix/ohjauspaneeli.php?ohjelmaid=9&mode=muokkaanimea&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:"")."' method='post'>
                <input type='hidden' name='lahetetty' value='49' />
                <input type='hidden' name='kategoriatid' value='".$kategoriatid."' />
                <input type='text' name='nimi' value='".$tulos['nimi']."' />
                <br />
                <input type='submit' value='Muokkaa' />
            </form></div>";
    else
        echo"Virheellinen joukkueid. Palaa <a href='".$_SERVER['PHP_SELF']."'>takaisin</a> ja yritä uudelleen.";
}
else
    echo"Virhe haettaessa kategorian tietoja. Palaa <a href='".$_SERVER['PHP_SELF']."'>takaisin</a>.";
?>

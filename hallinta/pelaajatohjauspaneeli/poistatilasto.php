<?php
echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."\"'>Takaisin</div><div class='keskelle'><div class='ala_otsikko'>Poista tilasto</div>";
if($tilastotnimetid == 'joukkue')
    echo"Ei voida poistaa joukkuetta, mene <a href='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."'>takaisin</a> ja valitse tilasto";
else{
    $kysely = mysql_query("SELECT nimi FROM tilastotnimet WHERE joukkueid='".$joukkueid."' AND id='".$tilastotnimetid."'") or die("Hae tilaston nimi: ".mysql_error());
    if($tulos = mysql_fetch_array($kysely)){
        $otsikko = $tulos['nimi'];
        $kysely = mysql_query("SELECT etunimi, sukunimi, O, RLM, RLY, RM, S, M, plusmiinus FROM pelaajat INNER JOIN (tilastot INNER JOIN tilastotnimet ON tilastotnimet.id=tilastotnimetid) ON pelaajat.id=tilastot.pelaajatid WHERE joukkueid='".$joukkueid."' AND tilastotnimet.id='".$tilastotnimetid."'") or die("Hae tilaston tiedot: ".mysql_error());
        tulostatilasto($kysely, $otsikko);
        echo"<div id='keskelle'><form action='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."' method='post'>
        <input type='hidden' name='lahetetty' value='42' />
        <input type='hidden' name='tilastotnimetid' value='".$tilastotnimetid."' />
        Haluatko varmasti poistaa?<br />
        <input type='submit' value='Kyllä' />
        <input type='button' value='En' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."\"' />
        </form></div>";
    }
}
echo"</div>";
?>

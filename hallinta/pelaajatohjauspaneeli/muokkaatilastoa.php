<?php
 echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."\"'>Takaisin</div>";
 if($tilastotnimetid != 'joukkue'){
    echo"<div class='ala_otsikko'>Lis‰‰ pelaaja tilastoon</div>
        <form action='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."' method='post'>
        <input type='hidden' name='lahetetty' value='19' />
        <input type='hidden' name='tilastotnimetid' value='".$tilastotnimetid."' />
        <table>
            <tr>
                <td>Etunimi:</td><td><input type='text' name='etunimi' value='".$_POST['etunimi']."' /></td>
            </tr>
            <tr>
                <td>Sukunimi:</td><td><input type='text' name='sukunimi' value='".$_POST['sukunimi']."' /><br /></td>
            </tr>
            <tr>
                <td>Syntym‰vuosi(yyyy):</td><td><input type='text' name='syntymavuosi' value='".$_POST['syntymavuosi']."' /><br /></td>
            </tr>
        </table>
        <input type='submit' value='Lis‰‰' />
        </form><hr />";
}
if($tilastotnimetid == 'joukkue')
    $kysely = mysql_query("SELECT joukkueenpelaajat.id AS id, etunimi, sukunimi FROM joukkueenpelaajat INNER JOIN pelaajat ON pelaajat.id=joukkueenpelaajat.pelaajatid WHERE joukkueid='".$joukkueid."' AND kausi='".$kausi."'") or die("Hae joukkueenpelaajat: ".mysql_error());
else
    $kysely = mysql_query("SELECT tilastot.id AS id, etunimi, sukunimi FROM tilastotnimet INNER JOIN (tilastot INNER JOIN pelaajat ON pelaajat.id=tilastot.pelaajatid) ON tilastot.tilastotnimetid=tilastotnimet.id WHERE tilastotnimet.id='".$tilastotnimetid."' AND joukkueid='".$joukkueid."' AND tilastotnimet.kausi='".$kausi."'") or die("Hae tilaston pelaajat: ".mysql_error());
 if($tulos = mysql_fetch_array($kysely)){
    echo"<div class='ala_otsikko'>Lis‰‰ pisteit‰, muokkaa ja poista pelaaja</div>
    <form id='form1' action='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."' method='post'>
    <input type='hidden' name='mode' id='mode' />
    <input type='hidden' name='tilastotnimetid' value='".$tilastotnimetid."' />
    Valitse pelaaja:<br />
    <select name='nimiid'>
        <option value='".$tulos['id']."'>".ucfirst($tulos['etunimi'])." ".ucfirst($tulos['sukunimi'])."</option>";
    while($tulos = mysql_fetch_array($kysely))
        echo"<option value='".$tulos['id']."'>".$tulos['etunimi']." ".$tulos['sukunimi']."</option>";
    echo"</select><br />
    <input type='button' value='Hae tiedot' onclick='laheta(\"muokkaa\",\"form1\")' /> 
    <input type='button' value='poista' onclick='laheta(\"poista\",\"form1\")' />";
    if($tilastotnimetid != 'joukkue')
        echo"<input type='button' value='Lis‰‰ pisteit‰' onclick='laheta(\"lisaapisteita\",\"form1\")' />
    </form>";
}
elseif($tilastotnimetid == 'joukkue')
    echo"<h4>Joukkueessa ei ole pelaajia mene <a href='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."'>takaisin</a> ja lis‰‰ pelaaja.</h4>";
?>

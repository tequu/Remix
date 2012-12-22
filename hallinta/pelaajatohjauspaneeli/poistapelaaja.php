<?php
$nimiid = mysql_real_escape_string($_POST['nimiid']);
if($tilastotnimetid == 'joukkue')
    $kysely = mysql_query("SELECT etunimi, sukunimi, pelinumero, kapteeni, syntymavuosi, kuva FROM pelaajat INNER JOIN (joukkueenpelaajat INNER JOIN joukkueet ON joukkueet.id=joukkueid) ON pelaajatid=pelaajat.id WHERE joukkueenpelaajat.id='".$nimiid."' AND joukkueet.id='".$joukkueid."'") or die("Hae joukkueen pelaajan tiedot: ".mysql_error());
else
    $kysely = mysql_query("SELECT etunimi, sukunimi, syntymavuosi FROM pelaajat INNER JOIN (tilastot INNER JOIN tilastotnimet ON tilastotnimet.id=tilastotnimetid) ON pelaajatid=pelaajat.id WHERE joukkueid='".$joukkueid."' AND tilastot.id='".$nimiid."'") or die("Hae tilaston pelaaajan tiedot: ".mysql_error());
if($tulos = mysql_fetch_array($kysely)){
    echo"<div class='keskelle'><div class='ala_otsikko'>Poista pelaaja</div>
    <b>Etunimi</b>: ".$tulos['etunimi']."<br />
    <b>Sukunimi</b>: ".$tulos['sukunimi']."<br />
    <b>Syntymävuosi</b>: ".$tulos['syntymavuosi']."<br />";
    if($tilastotnimetid == 'joukkue'){
        echo"<b>Pelinumero</b>: ".$tulos['pelinumero']."<br />
            <b>Kapteeni</b>: ";
        if($nimiid == $tulos['kapteeni'])
            echo"Kyllä<br />";
        else
            echo"Ei<br />";
    }
    if($tilastotnimetid=='joukkue'){
        if($tulos['kuva']!=null)
            echo"<b>Pelaajakortti kuva</b>: ".$tulos['kuva']."<br />";

        $kysely2 = mysql_query("SELECT nimi, sisalto FROM pelaajakortti_lisatieto WHERE joukkueenpelaajatID='".$nimiid."'") or die("Hae pelaaja lisatiedot: ".mysql_error());
        if($tulos2 = mysql_fetch_array($kysely2)){
            echo"<b>Lisätiedot:</b><br />";
            $i=1;
            do{
                echo $i.". ".$tulos2['nimi']." - ".$tulos2['sisalto']."<br />";
                $i++;
            }
            while($tulos2 = mysql_fetch_array($kysely2));
        }
    }
    echo"<br />
        <form action='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."&mode=muokkaatilastoa&tilastotnimetid=".$tilastotnimetid."' method='post'>
        <input type='hidden' name='lahetetty' value='21' />
        <input type='hidden' name='nimiid' value='".$nimiid."' />
        <input type='hidden' name='tilastotnimetid' value='".$tilastotnimetid."' />
        Haluatko varmasti poistaa?<br />
        <input type='submit' value='Kyllä' />
        <input type='button' value='En' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."&mode=muokkaatilastoa&tilastotnimetid=".$tilastotnimetid."\"' />
        </form>
        </div>";
}
else
    echo"<h4>Virhe poistettaessa pelaajan tietoja, palaa <a href='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."&mode=muokkaatilastoa&tilastotnimetid=".$tilastotnimetid."'>takaisin</a> ja yritä uudelleen</h4>";

?>

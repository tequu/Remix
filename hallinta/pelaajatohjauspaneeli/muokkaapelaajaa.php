<?php

echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."&mode=muokkaatilastoa&tilastotnimetid=".$tilastotnimetid."\"'>Takaisin</div><div class='ala_otsikko'>Muokkaa pelaajaa</div>";
//haetaan k‰ytt‰j‰n l‰hett‰m‰ nimi	
$nimiid = mysql_real_escape_string($_POST['nimiid']);
if($tilastotnimetid == 'joukkue')
    $kysely = mysql_query("SELECT etunimi, sukunimi, pelinumero, kapteeni, syntymavuosi, kuva FROM pelaajat INNER JOIN (joukkueenpelaajat INNER JOIN joukkueet ON joukkueid=joukkueet.id) ON pelaajatid=pelaajat.id WHERE joukkueenpelaajat.id='".$nimiid."'") or die("Hae joukkueen pelaajan tiedot: ".mysql_error());
else
    $kysely = mysql_query("SELECT etunimi, sukunimi, syntymavuosi FROM pelaajat INNER JOIN tilastot ON pelaajatid=pelaajat.id WHERE tilastot.id='".$nimiid."'") or die("Hae tilaston pelaajan tiedot: ".mysql_error());
if($tulos = mysql_fetch_array($kysely)){
    //luodaan lis‰tt‰ville tiedoille lomake
    //nimi ja pelinumero
    echo"<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."' method='post'>
    <input type='hidden' name='nimiid' value='".$nimiid."' />
    <input type='hidden' name='lahetetty' value='20' />
    <input type='hidden' name='tilastotnimetid' value='".$tilastotnimetid."' />
    <input type='hidden' name='mode' value='muokkaa' />
    <table>
        <tr>
            <td>Etunimi:</td><td><input type='text' name='etunimi' value='".$tulos['etunimi']."' /></td>
        </tr>
        <tr>
            <td>Sukunimi:</td><td><input type='text' name='sukunimi' value='".$tulos['sukunimi']."' /></td>
        </tr>
        <tr>
            <td>Syntym‰vuosi(yyyy):</td><td><input type='text' name='syntymavuosi' value='".$tulos['syntymavuosi']."' /></td>
        </tr>";
    if($tilastotnimetid == 'joukkue')
        echo"<tr>
                <td>Pelinumero:</td><td><input type='text' name='pelinumero' value='".$tulos['pelinumero']."' /></td>
            </tr>";
    echo"</table>";

    if($tilastotnimetid == 'joukkue'){
        //kapteeni
        echo "<input type='checkbox' name='kapteeni' value='ok' ";
        if($nimiid == $tulos['kapteeni'])
                echo"CHECKED";
        echo" />Kapteeni<br />
            <input type='button' value='Lis‰‰ lis‰tieto' class='lisaalisatieto' />";
        $kysely2 = mysql_query("SELECT id, nimi, sisalto FROM pelaajakortti_lisatieto WHERE joukkueenpelaajatID='".$nimiid."' ORDER BY jarjestysnumero") or die("Hae pelaajan lis‰tiedot: ".mysql_error());
        echo"<div id='lisatieto'>";
        $mones = 0;
        if($tulos2 = mysql_fetch_array($kysely2)){
            do{
                echo"<div id='lisatieto_kentta_".$mones."'>".($mones+1).". Lis‰tieto:<br />Nimi:<input type='text' name='pelaajakortti_kentta_".$mones."' value='".$tulos2['nimi']."' id='pelaajakortti_kentta_".$mones."' /> Tieto:<input type='text' name='pelaajakortti_kentta_arvo_".$mones."' value='".$tulos2['sisalto']."' id='pelaajakortti_kentta_arvo_".$mones."' />".($mones!=0?"<input type='button' value='Siirr‰ ylˆs' onclick='lisatietosiirraylos(".$mones.")' />":"").($mones!=7?"<input type='button' value='Siirr‰ alas' onclick='lisatietosiirraalas(".$mones.")' />":"")."<input type='button' value='Poista' onclick='poistalisatieto(".$mones.")' /><input type='hidden' name='pelaajakortti_id_".$mones."' value='".$tulos2['id']."' /></div>";
                $mones++;
            }
            while($tulos2 = mysql_fetch_array($kysely2));
            echo"<script type='text/javascript'>
                <!--
                for(var i=0;i<".$mones.";i++)
                    lisatiedot[i] = true;
                //-->
                </script>";
        }
        echo"</div>";
        if($tulos['kuva']!=null)
            echo"Poista pelaajakortti kuva:<br /><input type='checkbox' name='poistapelaajakorttikuva' value='1' />".$tulos['kuva']."<br /><br />";
        echo"Lis‰‰ kuva:<br />
        <input type='file' name='kuva' />(Koko: 215x300)<br />";
    }
   
    
    
    echo"<input type='submit' value='Muokkaa'>
        </form>";
}
else
    echo"<h4>Virhe haettaessa pelaajan tietoja, palaa <a href='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."&mode=muokkaatilastoa&tilastotnimetid=".$tilastotnimetid."'>takaisin</a> ja yrit‰ uudelleen</h4>";
?>

<?php
if(in_array("Masteradmin",$_SESSION['oikeudet']) || in_array($joukkueid, $_SESSION['oikeudet'])){
    echo"<div class=\"otsikko\">Lisää, Muokkaa ja poista yhteystietoja</div>";
    if($joukkueid == 0)
        echo"<div id='nerror'>Joukkueid on virheellinen</div>";
    else{
        echo"<div id='error'>".$error."</div>";
        if((isset($_POST['mode'])&&$_POST['mode']=='muokkaa')||$errors=='muokkaa'){
            $nimiid = mysql_real_escape_string($_POST['nimiid']);
            $kysely = mysql_query("SELECT etunimi, sukunimi, sahkoposti, puhelin, valmentaja, yhteyshenkilo FROM yhteystiedot WHERE joukkueID='".$joukkueid."' AND id='".$nimiid."'") or die("Hae yhteystiedon tiedot muokkaa: ".mysql_error());
            if($tulos = mysql_fetch_array($kysely)){
                echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."\"'>Takaisin</div>
                <form action='".$_SERVER['PHP_SELF']."?ohjelmaid=8&joukkueid=".$joukkueid."' method='post'>
                <input type='hidden' name='lahetetty' value='16' />
                <input type='hidden' name='nimiid' value='".$nimiid."' />
                <input type='checkbox' name='valmentaja' value='1'";
                if($tulos['valmentaja'])
                echo" CHECKED";
                echo" />Valmentaja
                <br />
                <input type='checkbox' name='yhteyshenkilo' value='1'";
                if($tulos['yhteyshenkilo'])
                echo" CHECKED";	
                echo" />Yhteyshenkilö
                <br />
                Etunimi: <input type='text' name='etunimi' value='".$tulos['etunimi']."' />
                <br />
                Sukunimi: <input type='text' name='sukunimi' value='".$tulos['sukunimi']."' />
                <br />
                Sähköposti: <input type='text' name='sahkoposti' value='".$tulos['sahkoposti']."' />
                <br />
                Puhelin: <input type='text' name='puhelin' value='".$tulos['puhelin']."' />
                <br />
                <input type='submit' value='Muokkaa' />
                </form>";
            }
            else
                echo"Yhteystietoja ei löytynyt";
        }
        elseif((isset($_POST['mode'])&&$_POST['mode']=='poista')||$errors=='poista'){
            $nimiid = mysql_real_escape_string($_POST['nimiid']);
            $kysely = mysql_query("SELECT etunimi, sukunimi, sahkoposti, puhelin, valmentaja, yhteyshenkilo FROM yhteystiedot WHERE id='".$nimiid."' AND joukkueID='".$joukkueid."'") or die("Hae yhteystiedot poista: ".mysql_error());
            if($tulos = mysql_fetch_array($kysely)){
                if($tulos['valmentaja'])
                        echo"Valmentaja<br />";
                if($tulos['yhteyshenkilo'])
                        echo"Yhteyshenkilö<br />";
                echo"</h4></b><b>Etunimi</b>: ".$tulos['etunimi']."<br />
                <b>Sukunimi</b>: ".$tulos['sukunimi']."<br />
                <b>Sähköposti</b>: ".$tulos['sahkoposti']."<br />
                <b>Puhelin</b>: ".$tulos['puhelin']."<br /><br />
                <form action='".$_SERVER['PHP_SELF']."?ohjelmaid=8&joukkueid=".$joukkueid."' method='post'>
                <input type='hidden' name='lahetetty' value='17' />
                <input type='hidden' name='nimiid' value='".$nimiid."' />
                Haluatko varmasti poistaa?<br />
                <input type='submit' value='Kyllä' />
                <input type='button' value='En' onclick='parent.location=\"".$_SERVER['PHP_SELF']."\"'/>
                </form>";
            }
            else
                echo"Yhteystietoja ei löytynyt";
        }
        else{
            echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."\"'>Takaisin</div><div class='ala_otsikko'>Uusi yhteystieto</div><form action='".$_SERVER['PHP_SELF']."?ohjelmaid=8&joukkueid=".$joukkueid."' method='post'>
            <input type='hidden' name='lahetetty' value='15' />
            <table><tr>
            <td colspan='2'><input type='checkbox' name='valmentaja' value='1' />Valmentaja</td>
            </tr>
            <tr>
            <td colspan='2'><input type='checkbox' name='yhteyshenkilo' value='1' />Yhteyshenkilö</td>
            </tr>
            <tr>
            <td>Etunimi:</td><td><input type='text' name='etunimi' /></td>
            </tr>
            <tr>
            <td>Sukunimi:</td><td><input type='text' name='sukunimi' /></td>
            </tr>
            <tr>
            <td>Sähköposti:</td><td><input type='text' name='sahkoposti' /></td>
            </tr>
            <tr>
            <td>Puhelin:</td><td><input type='text' name='puhelin' /></td>
            </tr>
            <tr>
            <td><input type='submit' value='Lisää' /></td><td></td>
            </tr>
            </table>
            </form>";
        }
    }
}
elseif(!isset($_SESSION['id']))
    echo"<div style='font-size:30px;text-align:center;'>Kirjaudu sisään</div>";
else
    echo"<div style='font-size:30px;text-align:center;'>Ei oikeuksia!</div>";
?>

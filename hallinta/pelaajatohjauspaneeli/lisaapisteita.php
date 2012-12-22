<?php
$nimiid = mysql_real_escape_string($_POST['nimiid']);
$kysely = mysql_query("SELECT etunimi, sukunimi, O, RLM, RLY, RM, S, M, plusmiinus FROM pelaajat INNER JOIN (tilastot INNER JOIN tilastotnimet ON tilastotnimet.id=tilastotnimetid) ON pelaajat.id=tilastot.pelaajatid WHERE tilastotnimet.joukkueid='".$joukkueid."' AND tilastot.id='".$nimiid."'") or die("Hae pelaajan tilastot: ".mysql_error());
if($tulos = mysql_fetch_array($kysely)){
    $pisteet = $tulos['S']+$tulos['M'];
    echo "<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."&mode=muokkaatilastoa&tilastotnimetid=".$tilastotnimetid."\"'>Takaisin</div><div class='ala_otsikko'>Lisää pisteitä</div><div class='ala_otsikko'>Vanhat tiedot</div>Nimi: ".$tulos['etunimi']." ".$tulos['sukunimi']."<br />
        O: ".$tulos['O']."<br />
        RLM: ".$tulos['RLM']."<br />
        RLY: ".$tulos['RLY']."<br />
        RM: ".$tulos['RM']."<br />
        P: ".$tulos['M']."+".$tulos['S']."=".$pisteet."<br />
        +-: ".$tulos['plusmiinus']."<br /><br />";
    //luodaan lisättäville tiedoille lomake
    echo "<div class='ala_otsikko'>Uudet tiedot</div>
        <form action='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."&mode=lisaapisteita&tilastotnimetid=".$tilastotnimetid."' method='post'>
            <input type='hidden' name='nimiid' value='".$nimiid."' />
            <input type='hidden' name='tilastotnimetid' value='".$tilastotnimetid."' />
            <input type='hidden' name='lahetetty' value='27' />
            <table>
                <tr>
                    <td>Nimi: </td><td>".$tulos['etunimi']." ".$tulos['sukunimi']."</td>
                </tr>
                <tr>
                    <td>Ottelut:</td><td><input type='text' name='ottelut' /></td>
                </tr>
                <tr>
                    <td>Rangaistuslaukaus maalit:</td><td><input type='text' name='rangaistuslaukausmaalit' /></td>
                </tr>
                <tr>
                    <td>Rangaistuslaukaus yritykset:</td><td><input type='text' name='rangaistuslaukausyritykset' /></td>
                </tr>
                <tr>
                    <td>Rangaistusminuutit:</td><td><input type='text' name='rangaistusminuutit' /></td>
                </tr>
                <tr>
                    <td>Syötöt:</td><td><input type='text' name='syotot' /></td>
                </tr>
                <tr>
                    <td>Maalit:</td><td><input type='text' name='maalit' /></td>
                </tr>
                <tr>
                    <td>+-:</td><td><input type='text' name='plusmiinus' /></td>
                </tr>
                <tr>
                    <td><input type='submit' value='Lisää pisteet' /></td><td></td>
                </tr>
            </table>
        </form>";
}
else
    echo"<h4>Virhe haettaessa pelaajan tietoja, palaa <a href='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."&mode=muokkaatilastoa&tilastotnimetid=".$tilastotnimetid."'>takaisin</a> ja yritä uudelleen</h4>";
?>

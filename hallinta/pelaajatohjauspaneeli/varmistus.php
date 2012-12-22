<?php
echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid.(isset($tilastotnimetid)?"&mode=muokkaatilastoa&tilastotnimetid=".$tilastotnimetid:"")."\"'>Takaisin</div><div id='keskelle'><div class='ala_otsikko'>Lis‰‰ pelaaja</div>";
$laskuri = 0;
foreach($olemassa as $z){
    $joukkuekaudet = array();
    $tilastokaudet = array();
    $kysely = mysql_query("SELECT joukkueenpelaajat.kausi AS kausi, joukkue FROM pelaajat INNER JOIN (joukkueenpelaajat INNER JOIN joukkueet ON joukkueid=joukkueet.id) ON pelaajatid=pelaajat.id WHERE pelaajat.id='".$z[3]."'") or die("Hae kaudet ja joukkueet, miss‰ pelaaja on pelannut: ".mysql_error());
    while($tulos = mysql_fetch_array($kysely))
        $joukkuekaudet[$tulos['kausi']][] = $tulos['joukkue'];
    $kysely = mysql_query("SELECT tilastot.kausi AS kausi, tilastotnimet.nimi AS nimi FROM pelaajat INNER JOIN (tilastot INNER JOIN (tilastotnimet INNER JOIN joukkueet ON tilastotnimet.joukkueid=joukkueet.id) ON tilastot.tilastotnimetid=tilastotnimet.id) ON tilastot.pelaajatid=pelaajat.id WHERE pelaajat.id='".$z[3]."'") or die("Hae kaudet ja tilastot miss‰ pelaaja on pelannut: ".mysql_error());
    while($tulos = mysql_fetch_array($kysely))
        $tilastokaudet[$tulos['kausi']][] = $tulos['nimi'];
    echo"<b>Etunimi</b>: ".$z[0]."<br />
        <b>Sukunimi</b>: ".$z[1]."<br />
        <b>Syntym‰vuosi</b>: ".$z[2]."<br />
        <b>Pelatut kaudet</b>:<br />";
    tulostaPelatutKaudet($joukkuekaudet, "Joukkueessa");
    tulostaPelatutKaudet($tilastokaudet, "Tilastoissa");
    echo"<br />
        <form action='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."' method='post'>
        <input type='hidden' name='lahetetty' value='".$_POST['lahetetty']."' />";
    echo"<input type='hidden' name='varmista' id='varmista' />
        <input type='hidden' name='pelaajatid' value='".$z[3]."' />";
    if(isset($_POST['nimiid']))
        echo"<input type='hidden' name='nimiid' value='".$_POST['nimiid']."' />";
    echo"<input type='hidden' name='etunimi' value='".$_POST['etunimi']."' />
        <input type='hidden' name='sukunimi' value='".$_POST['sukunimi']."' />
        <input type='hidden' name='syntymavuosi' value='".$_POST['syntymavuosi']."' />";
    if(!isset($tilastotnimetid)){
        echo"<input type='hidden' name='pelinumero' value='".$_POST['pelinumero']."' />
        <input type='hidden' name='kapteeni' value='".$_POST['kapteeni']."' />";
        for($i=0;$i<8;$i++){
            if(!isset($_POST['pelaajakortti_kentta_'.$i]) && !isset($_POST['pelaajakortti_kentta_arvo_'.$i]))
                break;
            else
                echo"<input type='hidden' name='pelaajakortti_kentta_".$i."' value='".$_POST['pelaajakortti_kentta_'.$i]."' /><input type='hidden' name='pelaajakortti_kentta_arvo_".$i."' value='".$_POST['pelaajakortti_kentta_arvo_'.$i]."' />";
        }
        $kysely = mysql_query("SELECT id FROM tilastotnimet WHERE joukkueid='".$joukkueid."' AND kausi='".$kausi."'") or die("Hae tilastojen nimien id: ".mysql_error());
        while($tulos = mysql_fetch_array($kysely)){
            if(isset($_POST['lisaatilastoon_'.$tulos['id']]))
                echo"<input type='hidden' name='lisaatilastoon_".$tulos['id']."' value='1' />";
        }	
    }
    else
        echo"<input type='hidden' name='tilastotnimetid' value='".$tilastotnimetid."' />";
    echo"Onko kyseess‰ sama pelaaja?<br />
    <input type='button' value='Kyll‰' onclick='pelaajanlisaaminenjamuokkaus(1,".$laskuri.")' />
    <input type='button' value='Ei' onclick='pelaajanlisaaminenjamuokkaus(0,".$laskuri.")' />
    </form><br />";
    $laskuri++;
}
echo"<script type='text/javascript'>
function pelaajanlisaaminenjamuokkaus(i,f){
        var lisataanko = document.getElementById('varmista');
        lisataanko.value=i;
        document.forms[f].submit();
}
</script>
</div>";
?>

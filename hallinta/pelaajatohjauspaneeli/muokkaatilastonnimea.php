<?php
if($tilastotnimetid == 'joukkue')
    echo"<h4>Ei voida muokata joukkueen nimeä, palaa <a href='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."'>takaisin</a> ja valitse tilasto";
else{
    $kysely = mysql_query("SELECT nimi, oletus, kokonaistilastoon FROM tilastotnimet WHERE id='".$tilastotnimetid."' AND joukkueid='".$joukkueid."'") or die("Hae tilaston tiedot: ".mysql_error());
    if($tulos = mysql_fetch_array($kysely)){
        echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."\"'>Takaisin</div><div class='ala_otsikko'>Muokkaa tilaston nimeä</div>
            <form action='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."&mode=muokkaanimea' method='post'>
            <input type='hidden' name='lahetetty' value='43' />
            <input type='hidden' name='tilastotnimetid' value='".$tilastotnimetid."' />
            Tilaston uusi nimi:<input type='text' name='nimi' value='".$tulos['nimi']."' /><br />
            Oletus <input type='checkbox' name='oletus' value='1'";
        if($tulos['oletus'])
            echo" CHECKED";
        echo" /><br />
            Laske mukaan kokonais tilastoon <input type='checkbox' name='kokonaistilastoon' value='1'";
        if($tulos['kokonaistilastoon'])
            echo" CHECKED";
        echo" /><br />
            <input type='submit' value='Muokkaa' />";
    }
    else
        echo"<h4>Virhe haettaessa tilastoa, palaa <a href='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."'>takaisin</a> ja yritä uudelleen</h4>";
}
?>

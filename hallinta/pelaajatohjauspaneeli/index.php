<?php
//uuden pelaaja luominen
echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."\"'>Takaisin</div><div class='ala_otsikko'>Lis‰‰ pelaaja joukkueeseen</div>
    <form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."' method='post'>
        <input type='hidden' name='lahetetty' value='19' />
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
            <tr>
                <td>Pelinumero:</td><td><input type='text' name='pelinumero' size='2' maxlength='2' value='".$_POST['pelinumero']."' /></td>
            </tr>
        </table>
        <input type='checkbox' name='kapteeni' value='ok'";
            if($_POST['kapteeni'] == 'ok')
                echo" CHECKED";
            echo" />Kapteeni<br />
        <input type='button' value='Lis‰‰ lis‰tieto' class='lisaalisatieto' />
        <div id='lisatieto'></div>";
            
$kysely = mysql_query("SELECT id, nimi FROM tilastotnimet WHERE joukkueid='".$joukkueid."' AND kausi='".$kausi."'") or die("Hae tilastojen nimet: ".mysql_error());
if($tulos = mysql_fetch_array($kysely)){
    echo"Lis‰‰ pelaaja tilastoon:<br />";
    $tilastottaulu = array();
    do{
        $tilastottaulu[] = array('id' => $tulos['id'], 'nimi' => $tulos['nimi']);
        echo"<input type='checkbox' name='lisaatilastoon[]' value='".$tulos['id']."' />".$tulos['nimi']."<br />";
    }
    while($tulos = mysql_fetch_array($kysely));
}
echo"<input type='submit' value='Lis‰‰' />
    </form><hr />";

//uusi tilasto
echo"<form action='".$_SERVER['PHP_SELf']."?ohjelmaid=5&joukkueid=".$joukkueid."' method='post'>
    <input type='hidden' name='lahetetty' value='41' />
    Tilaston nimi:<input type='text' name='nimi' /><br />
    Oletus <input type='checkbox' name='oletus' value='1' /><br />
    Laske mukaan kokonais tilastoon <input type='checkbox' name='kokonaistilastoon' value='1' /><br />
    <br />Hae pelaajat tilastoon?<br />
    <input type='radio' name='haetilastoon' value='ei' CHECKED />Ei<br />
    <input type='radio' name='haetilastoon' value='joukkue' />Joukkue<br />";
if(!empty($tilastottaulu))
    foreach($tilastottaulu AS $a)
        echo"<input type='radio' name='haetilastoon' value='".$a['id']."' />".$a['nimi']."<br />";
echo"<input type='submit' value='Luo' />
    </form>";

//tilaston tai joukkueen muokkaus
echo"<hr />
    <div class='ala_otsikko'>Muokkaa tilastoa tai joukkueen pelaajia</div>
    <form id='form1' action='".$_SERVER['PHP_SELF']."?ohjelmaid=5&joukkueid=".$joukkueid."' method='post'>
    <input type='hidden' name='mode' id='mode' />
    Valitse tilasto:<br />
    <select name='tilastotnimetid'>
    <option value='joukkue'>Joukkue</option>";
if(!empty($tilastottaulu))
    foreach($tilastottaulu as $a)
    echo"<option value='".$a['id']."'>".$a['nimi']."</option>";
echo"</select><br />
    <input type='button' value='Hae tiedot' onclick='laheta(\"muokkaatilastoa\",\"form1\")' /> 
    <input type='button' value='poista' onclick='laheta(\"poistatilasto\",\"form1\")' />
    <input type='button' value='Muokkaa nime‰' onclick='laheta(\"muokkaanimea\",\"form1\")' />
    </form>";
?>

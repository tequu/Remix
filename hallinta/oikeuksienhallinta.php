<?php
if(in_array("Masteradmin",$_SESSION['oikeudet'])){
    echo"<div class='otsikko'>Oikeuksien hallinta</div>
        <a href='".$_SERVER['PHP_SELF']."'>Takaisin</a>
        <div id='error'>".$error."</div>";
    //tulostetaan lomake
    tulostatunnukset();
    if(isset($_POST['tunnusid'])){
    //jos valittu jo nimi
        $poistettavat = array();
        //tulostetaan lomakkeen alku
        echo"<hr /><div class='ala_otsikko'>Tunnuksen tiedot</div>
            <form action='".$_SERVER['PHP_SELF']."?ohjelmaid=4' method='post'>
                <input type='hidden' name='lahetetty' value='8' />";
        //otetaan talteen lähetetty id
        $tunnusid = mysql_real_escape_string($_POST['tunnusid']);
        //haetaan id:llä tarvittavat tiedot
        $kysely = mysql_query("SELECT login, etunimi, sukunimi, email, is_admin FROM tunnukset WHERE id='".$tunnusid."'") or die("Hae tunnuksen tiedot1: ".mysql_error());
        $tulos = mysql_fetch_array($kysely);
        $admin = $tulos['is_admin'];
        //tulostetaan tunnus ja admin oikeudet
        echo"<input type='hidden' name='tunnusid' value='".$tunnusid."' />
            Tunnus: <font color='0000ff'>".$tulos['login']."</font>
            <br />
            Nimi: ".$tulos['etunimi']." ".$tulos['sukunimi']."
            <br />Sähköposti: ".$tulos['email']."
            <br />
            <br />";
        echo"<div class='ala_otsikko'>Anna oikeuksia</div>";
        if($admin != "Masteradmin"){
            echo"<fieldset>
            <legend>Admin</legend>";
            if($admin != 'Admin')
                echo"<input type='radio' name='admin' value='Admin' />Admin
            <br />";
            if($admin != 'Masteradmin')
                echo"<input type='radio' name='admin' value='Masteradmin' />Masteradmin";
            echo"</fieldset>
            <input type='submit' value='Lisää' />
            </form>";
        }

        //haetaan joukkueid tunnukselle oikeudet taulusta jos sellainen on
        $kysely = mysql_query("SELECT oikeudet.joukkueid AS joukkueid, joukkueet.joukkue AS joukkue FROM oikeudet INNER JOIN joukkueet ON oikeudet.joukkueid=joukkueet.id WHERE tunnusid='".$tunnusid."' AND kausi='".$kausi."'") or die("Hae tunnuksen tiedot2: ".mysql_error());
        while($tulos = mysql_fetch_array($kysely))
            $poistettavat[$tulos['joukkue']] = $tulos['joukkueid'];
        if($admin != 'Masteradmin'){	
            //haetaan joukkueet ja niiden id:t
            $kysely = mysql_query("SELECT id, joukkue FROM joukkueet WHERE kausi='".$kausi."'") or die("Hae tunnuksen tiedot3: ".mysql_error());
            $i = 0;
            while($tulos = mysql_fetch_array($kysely)){
                if(!in_array($tulos['id'], $poistettavat)){
                    if($i==0){
                        //tulostetaan lomakkeen kentät millä voidaan lisätä oikeuksia joukkueisiin
                        echo"<br />
                            <form action='".$_SERVER['PHP_SELF']."?ohjelmaid=4' method='post'>
                            <input type='hidden' name='lahetetty' value='9' />
                            <input type='hidden' name='tunnusid' value='".$tunnusid."' />
                            <fieldset>
                            <legend>Joukkueet</legend>";
                    }
                    echo"<input type='radio' name='joukkue' value='".$tulos['id']."' />".$tulos['joukkue']."<br />";
                    $i++;
                }
            }
            if($i != 0)
                echo"</fieldset>
                    <input type='submit' value='Lisää' />
                    </form>";
        }
        //tulostetaan poisto napit jos valittu tunnus on admin tai sillä on oikeudet jollekin joukkueelle
        if($admin != 'Perus' || !empty($poistettavat)){
            echo"<hr /><div class='ala_otsikko'>Poista oikeuksia</div>";
            //jos admin oikeudet
            if($admin != 'Perus')
            echo"<form action='".$_SERVER['PHP_SELF']."?ohjelmaid=4' method='post'>
                <input type='hidden' name='lahetetty' value='10' />
                <input type='hidden' name='tunnusid' value='".$tunnusid."' />
                <input type='checkbox' name='poistaadmin' value='1' />Poista admin oikeudet<br />
                <input type='submit' value='Poista' />
                </form>
                <br />";
            //jos oikeudet johonkin joukkueeseen, tulostetaan ne joukkueet
            if(!empty($poistettavat)){
                echo"<form action='".$_SERVER['PHP_SELF']."?ohjelmaid=4' method='post'>
                <input type='hidden' name='lahetetty' value='11' />
                <input type='hidden' name='tunnusid' value='".$tunnusid."' />
                <fieldset>
                <legend>Joukkueet</legend>";
                foreach($poistettavat as $a=>$b)
                    echo"<input type='radio' name='joukkue' value='".$b."' />".$a."<br />";
                echo"</fieldset>
                <input type='submit' value='poista' /></form>";
            }
        }
    }
}
elseif(!isset($_SESSION['id']))
    echo"<div style='font-size:30px;text-align:center;'>Kirjaudu sisään</div>";
else
    echo"<div style='font-size:30px;text-align:center;'>Ei oikeuksia!</div>";
?>
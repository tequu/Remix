<?php
if(in_array("Masteradmin",$_SESSION['oikeudet'])){
    echo"<div class='otsikko'>Joukkueohjauspaneeli</div>
        <div id='error'>".$error."</div>";
    if($_POST['mode'] == 'muokkaa' || $errors == 'muokkaa'){
        echo"<a href='".$_SERVER['PHP_SELF']."?ohjelmaid=3'>Takaisin</a><br /><br /><div class='ala_otsikko'>Muokkaa joukkuetta</div>";
        $joukkueenid = mysql_real_escape_string($_POST['joukkueid']);
        $kysely = mysql_query("SELECT joukkue, kerho FROM joukkueet WHERE kausi='".$kausi."' AND id='".$joukkueenid."'") or die("Muokkaa joukkuetta: ".mysql_error());
        if($tulos = mysql_fetch_array($kysely)){
            echo"<form action='".$_SERVER['PHP_SELF']."?ohjelmaid=3' method='post'>
            <input type='hidden' name='joukkueenid' value='".$joukkueenid."' />
            <input type='hidden' name='lahetetty' value='33' />
            Joukkue:<input type='text' name='joukkue' value='";
            if(isset($_POST['joukkue']))
                echo $_POST['joukkue'];
            else
                echo $tulos['joukkue'];
            echo"' /><br />
            <input type='checkbox' name='kerho' value='1'";
            if($_POST['kerho'] || $tulos['kerho'])
                echo" CHECKED";
            echo" /> Kerho<br />
            <input type='submit' value='Muokkaa' />
            </form>";
        }
        else
            echo"<h4>Virhe haettaessa joukkueen tietoja, palaa <a href='".$_SERVER['PHP_SELF']."?ohjelmaid=3'>takaisin</a> ja yrit‰ uudelleen</h4>";
    }
    elseif($_POST['mode'] == 'poista' || $errors == 'poista'){
        $joukkueenid = mysql_real_escape_string($_POST['joukkueid']);
        $kysely = mysql_query("SELECT joukkue, kerho FROM joukkueet WHERE kausi='".$kausi."' AND id='".$joukkueenid."'") or die("Poista joukkue: ".mysql_error());
        if($tulos = mysql_fetch_array($kysely)){
            echo"<div class='ala_otsikko'>Poista joukkue</div>
            <b>Joukkue</b>: ".$tulos['joukkue']."<br />";
            if($tulos['kerho'])
                    echo"<b>Kerho</b><br />";
            echo"<form action='".$_SERVER['PHP_SELF']."?ohjelmaid=3' method='post'>
            <input type='hidden' name='lahetetty' value='13' />
            <input type='hidden' name='joukkue' value='".$joukkueenid."' />
            Haluatko varmasti poistaa?<br />
            <input type='submit' value='Kyll‰' />
            <input type='button' value='En' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?ohjelmaid=3' />
            </form>";
        }
        else
            echo"<h4>Virhe poistettaessa joukkueen tietoja, palaa <a href='".$_SERVER['PHP_SELF']."?ohjelmaid=3'>takaisin</a> ja yrit‰ uudelleen</h4>";
    }
    else{
        echo"<a href='".$_SERVER['PHP_SELF']."'>Takaisin</a><br /><div class='ala_otsikko'>Lis‰‰ joukkue</div>
        <form action='".$_SERVER['PHP_SELF']."?ohjelmaid=3' method='post'>
        <input type='hidden' name='lahetetty' value='12' />
        Joukkue: <input type='text' name='joukkue' value='".$_POST['joukkue']."' /><br />
        <input type='checkbox' name='kerho' value='1'";
        if($_POST['kerho'])
            echo" CHECKED";
        echo" /> Kerho<br />
        <input type='submit' value='Lis‰‰' />
        </form>";
        $kysely = mysql_query("SELECT id, joukkue FROM joukkueet WHERE kausi='".$kausi."'") or die("Lis‰‰ joukkue: ".mysql_error());
        $tulos = mysql_fetch_array($kysely);
        if(!empty($tulos)){
            echo"<hr /><div class='ala_otsikko'>Poista joukkue</div>
            <form id='form1' action='".$_SERVER['PHP_SELF']."?ohjelmaid=3' method='post'>
            <input type='hidden' name='mode' id='mode' />
            <select name='joukkueid'>";
            do
                echo"<option value='".$tulos['id']."'>".$tulos['joukkue']."</option>";
            while($tulos = mysql_fetch_array($kysely));
            echo"</select><br />
            <input type='button' value='Hae tiedot' onclick='laheta(\"muokkaa\",\"form1\")' />
            <input type='button' value='Poista' onclick='laheta(\"poista\",\"form1\")' />
            </form>";
        }
    }
}
elseif(!isset($_SESSION['id']))
    echo"<div style='font-size:30px;text-align:center;'>Kirjaudu sis‰‰n</div>";
else
    echo"<div style='font-size:30px;text-align:center;'>Ei oikeuksia!</div>";
?>
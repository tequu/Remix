<div id='tunnus_nav'>
    <?php
    if (!isset($_SESSION['id']))
        echo"<div id='nappula' class='kirjaudu'>Kirjaudu</div>
				<div id='nappula' class='rekisteroidy'>Rekisteröidy</div>";
    else{
        if(!empty($_SESSION['oikeudet']))
            echo"<div id='nappula' class='ohjauspaneeli' onclick='parent.location=\"/Remix/ohjauspaneeli.php\"'>Ohjauspaneeli</div>";
        echo"<div id='nappula' onclick='parent.location=\"/Remix/kirjaudu_ulos.php\"'>Kirjaudu ulos</div>";
    }
    ?>
</div>
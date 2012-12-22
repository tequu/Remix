<?php
echo"<div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."\"'>Takaisin</div>
    <br />
    <br />
    <div style='margin-left:40px;'><div class='ala_otsikko'>Lisää kategoria</div>
    <div id='error'>".$error."</div>
    <form action='/Remix/ohjauspaneeli.php?ohjelmaid=9&mode=lisaakategoria".($joukkueid!=0?"&joukkueid=".$joukkueid:"")."' method='post'>
    <input type='hidden' name='lahetetty' value='48' />
    Nimi:<input type='text' name='nimi' /><br />
    <input type='submit' value='Lisää' />
    </form></div>"
?>

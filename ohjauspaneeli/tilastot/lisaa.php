<hr />
<div class="ala_otsikko">Lisää pelaajia tilastoon hakemalla</div>
<form name="lisaavalitutpelaajat" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otilastot . "&joukkue=" . $joukkue."&mode=muokkaa&tilastoryhmatid=".$tilastoryhmatid; ?>" method="post">
    <input type="hidden" name="ohjaa" value="58" />
    <input type="hidden" name="pelaajat" id="pelaajat" /> 
    Hae:<input type="text" id="haku" onkeyup="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')"/><br />
    Järjestä:<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="0" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" CHECKED/>Etunimellä<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="1" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Sukunimellä<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="2" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Tunnuksella<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="3" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Sähköpostilla<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="4" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Syntymävuodella<br />
    <hr />
    <div id="bold">Valitse lisättävät pelaajat:</div>
    <div id="tulokset"></div>
    <hr />
    <div id="bold">Lisättävät:</div>
    <div id="lisattavat"></div>
    <input type="button" value="Lisää pelaajat" onclick="lahetaPelaajat('lisaavalitutpelaajat')" />
</form>
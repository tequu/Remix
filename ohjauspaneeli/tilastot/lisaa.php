<hr />
<div class="ala_otsikko">Lis�� pelaajia tilastoon hakemalla</div>
<form name="lisaavalitutpelaajat" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otilastot . "&joukkue=" . $joukkue."&mode=muokkaa&tilastoryhmatid=".$tilastoryhmatid; ?>" method="post">
    <input type="hidden" name="ohjaa" value="58" />
    <input type="hidden" name="pelaajat" id="pelaajat" /> 
    Hae:<input type="text" id="haku" onkeyup="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')"/><br />
    J�rjest�:<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="0" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" CHECKED/>Etunimell�<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="1" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Sukunimell�<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="2" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Tunnuksella<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="3" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />S�hk�postilla<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="4" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Syntym�vuodella<br />
    <hr />
    <div id="bold">Valitse lis�tt�v�t pelaajat:</div>
    <div id="tulokset"></div>
    <hr />
    <div id="bold">Lis�tt�v�t:</div>
    <div id="lisattavat"></div>
    <input type="button" value="Lis�� pelaajat" onclick="lahetaPelaajat('lisaavalitutpelaajat')" />
</form>
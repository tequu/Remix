<hr />
<div class="ala_otsikko">Lisää henkilöitä joukkueeseen hakemalla</div>
<form name="lisaavalitutpelaajat" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelaajat . "&joukkue=" . $joukkue; ?>" method="post">
    <input type="hidden" name="ohjaa" value="20" />
    <input type="hidden" name="pelaajat" id="pelaajat" /> 
    Hae:<input type="text" id="haku" onkeyup="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')"/><br />
    Järjestä:<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="0" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" CHECKED/>Etunimellä<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="1" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Sukunimellä<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="2" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Tunnuksella<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="3" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Sähköpostilla<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="4" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Syntymävuodella<br />
    <hr />
    <?php
    $kysely = kysely($yhteys, "SELECT id, nimi FROM tilastoryhmat WHERE joukkueetID='" . $joukkue . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        ?>
        <div id="bold">Lisää tilastoihin:</div>
        <?php
        do {
            echo "<input type=\"checkbox\" name=\"tilastoon_" . $tulos['id'] . "\" value=\"1\" />" . $tulos['nimi'] . "<br />";
        } while ($tulos = mysql_fetch_array($kysely));
    }
    ?>
    <div id="bold">Valitse lisättävät pelaajat:</div>
    <div id="tulokset"></div>
    <hr />
    <div id="bold">Lisättävät:</div>
    <div id="lisattavat"></div>
    <input type="button" value="Lisää pelaajat" onclick="lahetaPelaajat('lisaavalitutpelaajat')" />
</form>
<hr />
<div class="ala_otsikko">Lis�� yhteyshenkil�it� joukkueeseen hakemalla</div>
<form name="lisaavalitutyhteyshenkilot" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $oyhteyshenkilot . "&joukkue=" . $joukkue; ?>" method="post">
    <input type="hidden" name="ohjaa" value="70" />
    <input type="hidden" name="yhteyshenkilot" id="pelaajat" /> 
    Hae:<input type="text" id="haku" onkeyup="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')"/><br />
    J�rjest�:<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="0" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" CHECKED/>Etunimell�<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="1" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Sukunimell�<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="2" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Tunnuksella<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="3" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />S�hk�postilla<br />
    <input type="radio" name="jarjesta" id="jarjesta" value="4" onchange="haeKayttajatJoukkueeseen('haku','jarjesta','tulokset')" />Syntym�vuodella<br />
    <hr />
    <?php
    $kysely = kysely($yhteys, "SELECT id, nimi FROM tilastoryhmat WHERE joukkueetID='" . $joukkue . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        ?>
        <div id="bold">Lis�� tilastoihin:</div>
        <?php
        do {
            echo "<input type=\"checkbox\" name=\"tilastoon_" . $tulos['id'] . "\" value=\"1\" />" . $tulos['nimi'] . "<br />";
        } while ($tulos = mysql_fetch_array($kysely));
    }
    ?>
    <div id="bold">Valitse lis�tt�v�t pelaajat:</div>
    <div id="tulokset"></div>
    <hr />
    <div id="bold">Lis�tt�v�t:</div>
    <div id="lisattavat"></div>
    <input type="button" value="Lis�� pelaajat" onclick="lahetaPelaajat('lisaavalitutyhteyshenkilot')" />
</form>
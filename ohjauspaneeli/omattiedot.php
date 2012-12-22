<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Omien tietojen hallinta</div>
<?php
$kysely = kysely($yhteys, "SELECT id, nimi FROM joukkueet WHERE id NOT IN (SELECT joukkueetID id FROM pelaajat WHERE tunnuksetID='" . $kayttaja . "') AND kausi='".$kausi."'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <div id="bold">Liity joukkueeseen</div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?sivuid=<?php echo $oomattiedot; ?>" method="post">
        <input type="hidden" name="ohjaa" value="24" />
        Valitse joukkue:<select name="joukkue">
            <?php
            do {
                echo"<option value=\"" . $tulos['id'] . "\">" . $tulos['nimi'] . "</option>";
            } while ($tulos = mysql_fetch_array($kysely))
            ?>
        </select>
        <br />
        Rooli: <input type="radio" name="rooli" value="pelaaja" CHECKED />Pelaaja <input type="radio" name="rooli" value="Yhteyshenkilo" />Yhteyshenkilö<br />
        Salasana: <input type="password" name="salasana" /><br />
        <input type="submit" value="Liity" />
    </form>
    <?php
}
?>
<hr />
<?php
$kysely = kysely($yhteys, "SELECT login, t.email email, t.etunimi etunimi, t.sukunimi sukunimi, t.syntymavuosi syntymavuosi, n.email nemail, n.etunimi netunimi, " .
        "n.sukunimi nsukunimi, n.syntymavuosi nsyntymavuosi FROM tunnukset t, nakyvattiedot n WHERE nakyvattiedotID=n.id AND t.id='" . $kayttaja . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <div id="bold">Tietojen muokkaus</div>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $oomattiedot; ?>" method="post">
        <input type="hidden" name="ohjaa" value="25" />
        <div id = "tekstivasempaan">
            <div id = "rivi">
                <div id = "kentannimi">Käyttäjätunnus:</div>
                <div id = "kentta"><?php echo $tulos['login']; ?></div>
            </div>
            <div id="clear"></div>
            <div id="rivi">
                <div id="kentannimi">Sähköposti:</div>
                <div id="kentta"><?php echo $tulos['email']; ?></div>

            </div>
            <div id="clear"></div>
            <div id="rivi">
                <div id="kentannimi">Etunimi:</div>
                <div id="kentta"><input type="text" name="etunimi" value="<?php echo $tulos['etunimi']; ?>" size="16" /></div>

            </div>
            <div id="clear"></div>
            <div id="rivi">
                <div id="kentannimi">Sukunimi:</div>
                <div id="kentta"><input type="text" name="sukunimi" value="<?php echo $tulos['sukunimi']; ?>" size="16" /></div>

            </div>
            <div id="clear"></div>
            <div id="rivi">
                <div id="kentannimi">Syntymävuosi:</div>
                <div id="kentta"><input type="text" name="syntymavuosi" value="<?php echo $tulos['syntymavuosi']; ?>" size="16" /></div>

            </div>
            <div id="clear"></div>
            <div><input type="checkbox" name="nakyyetunimi" value="1" <?php echo ($tulos['netunimi'] ? "CHECKED" : ""); ?> />Etunimi näkyy kirjautuneille</div>
            <div><input type="checkbox" name="nakyysukunimi" value="1" <?php echo ($tulos['nsukunimi'] ? "CHECKED" : ""); ?> />Sukunimi näkyy kirjautuneille</div>
            <div><input type="checkbox" name="nakyysyntymavuosi" value="1" <?php echo ($tulos['nsyntymavuosi'] ? "CHECKED" : ""); ?> />Syntymävuosi näkyy kirjautuneille</div>
            <div><input type="checkbox" name="nakyysahkoposti" value="1" <?php echo ($tulos['nemail'] ? "CHECKED" : ""); ?> />Sähköposti näkyy kirjautuneille</div>
        </div>
        <input type="submit" value="Muokkaa tiedot" />
    </form>
    <hr />
    <div id="bold">Salasanan vaihto</div>
    <form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $oomattiedot; ?>" method="post">
        <input type="hidden" name="ohjaa" value="74" />
        <div id="tekstivasempaan">
            <div id = "rivi">
                <div id = "kentannimi">Vanha salasana:</div>
                <div id = "kentta"><input type="password" name="salasana" size="16" /></div>
            </div>
            <div id="clear"></div>
            <div id = "rivi">
                <div id = "kentannimi">Uusi salasana:</div>
                <div id = "kentta"><input type="password" name="uusisalasana" size="16" /></div>
            </div>
            <div id = "clear"></div>
            <div id = "rivi">
                <div id = "kentannimi">Vahvista salasana:</div>
                <div id = "kentta"><input type="password" name="vahvistauusisalasana" size="16" /></div>
            </div>
            <div id="clear"></div>
        </div>
        <input type="submit" value="Vaihda" />
    </form>
    <?php
}
?>
<div id="clear"></div>

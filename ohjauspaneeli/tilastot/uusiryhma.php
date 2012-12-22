<hr />
<div id='error'><?php echo $error ?></div>
<div class="ala_otsikko">Luo uusi tilasto</div>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $otilastot . "&joukkue=" . $joukkue; ?>" method="post">
    <input type="hidden" name="ohjaa" value="56" />
    <span id="bold">Tilaston nimi: </span><input type="text" name="nimi" /><br />
    <span id="bold">Oletus </span><input type="checkbox" name="oletus" value="1" /><br />
<!--    <span id="bold">Laske mukaan kokonais tilastoon </span><input type='checkbox' name='kokonaistilastoon' value='1' /><br />-->
    Hae pelaajat tilastoon?<br />
    <select name="haetilastoon">
        <option value="ei">Ei</option>
        <option value="0">Joukkue</option>
        <?php
        $kysely = kysely($yhteys, "SELECT tr.id id, tr.nimi nimi FROM tilastoryhmat tr, joukkueet j WHERE joukkueetID=j.id AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
        while ($tulos = mysql_fetch_array($kysely)) {
            echo "<option value=\"" . $tulos['id'] . "\">" . $tulos['nimi'] . "</option>";
        }
        ?>
    </select><br />
    <input type="submit" value="Luo" />
</form>


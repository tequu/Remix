<hr />
<div id="takaisin" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelaajat . "&joukkue=" . $joukkue; ?>')">Takaisin</div>
<div id="error"><?php echo $error; ?></div>
<div class="ala_otsikko">Muokkaa pelaajaa</div>
<?php
$pelaajatid = mysql_real_escape_string($_GET['pelaajatid']);
$kysely = kysely($yhteys, "SELECT etunimi, sukunimi, syntymavuosi, email, rooli, pelinumero, kapteeni, kuva, enabled FROM joukkueet j, pelaajat p, tunnukset t WHERE j.id=joukkueetID AND tunnuksetID=t.id AND j.id='" . $joukkue . "' AND t.id='" . $pelaajatid . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>

    <form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=".$opelaajat."&joukkue=" . $joukkue . "&mode=muokkaa&pelaajatid=" . $pelaajatid; ?>" method="post">
        <input type="hidden" name="ohjaa" value="22" />
        <span id="bold">Etunimi: </span><?php echo  ($tulos['enabled']?$tulos['etunimi']:"<input type=\"text\" name=\"etunimi\" value=\"".$tulos['etunimi']."\" />"); ?>
        <br />
        <span id="bold">Sukunimi: </span><?php echo  ($tulos['enabled']?$tulos['sukunimi']:"<input type=\"text\" name=\"sukunimi\" value=\"".$tulos['sukunimi']."\" />"); ?>
        <br />
        <span id="bold">Syntymävuosi: </span><?php echo  ($tulos['enabled']?$tulos['syntymavuosi']:"<input type=\"text\" name=\"syntymavuosi\" value=\"".$tulos['syntymavuosi']."\" />"); ?>
        <br />
        <span id="bold">Sähköposti: </span><?php echo  ($tulos['enabled']?$tulos['email']:"<input type=\"text\" name=\"email\" value=\"".$tulos['email']."\" />"); ?>
        <br />
        <span id="bold">Pelinumero: </span><input type="text" name="pelinumero" value="<?php echo $tulos['pelinumero']; ?>" size="2" />
        <br />
        <span id="bold">Kapteeni: </span><input type="checkbox" name="kapteeni" value="1"<?php echo $tulos['kapteeni'] == $pelaajatid ? " CHECKED" : ""; ?> /><br />
        <span id="bold">Rooli: </span>
        <?php
        foreach ($pelaajaroolit as $rooli) {
            echo "<input type=\"radio\" name=\"rooli\" value=\"" . $rooli . "\" " . ($rooli == $tulos['rooli'] || (!empty($tulos['rooli']) && !in_array($tulos['rooli'], $pelaajaroolit) && $rooli == $pelaajaroolit[count($pelaajaroolit) - 1]) ? "CHECKED " : "") . "/>" . $rooli . " ";
        }
        ?>
        <input type="text" name="muu"<?php echo (!in_array($tulos['rooli'], $pelaajaroolit) ? " value=\"" . $tulos['rooli'] . "\"" : "") ?> />
        <br />
        <?php
        if ($tulos['kuva'] != null) {
            ?>
            Poista pelaajakortti kuva:<br /><input type='checkbox' name='poistapelaajakorttikuva' value='1' /><?php echo $tulos['kuva']; ?><br /><br />
            <?php
        }
        ?>
            <span id="bold">Lisää kuva:</span>
        <input type='file' name='kuva' />(Koko: 380x570)<br /><br />
        <input type='button' value='Lisää lisätieto' class='lisaalisatieto' />
        <div id='lisatieto'>
            <?php
            $kysely = kysely($yhteys, "SELECT nimi, lisatieto FROM pelaajakorttilisatieto WHERE joukkueetID='" . $joukkue . "' AND tunnuksetid='" . $pelaajatid . "' ORDER BY jarjestysnumero");
            $montako = 0;
            if ($tulos = mysql_fetch_array($kysely)) {
                do {
                    ?>
                    <div id="lisatieto_kentta_<?php echo $montako; ?>"><?php echo ($montako + 1); ?>. Lisätieto:<br />
                        Nimi:<input type="text" name="pelaajakortti_kentta_<?php echo $montako; ?>" value="<?php echo $tulos['nimi']; ?>" id="pelaajakortti_kentta_<?php echo $montako; ?>" />
                        Tieto:<input type="text" name="pelaajakortti_kentta_arvo_<?php echo $montako; ?>" value="<?php echo $tulos['lisatieto']; ?>" id="pelaajakortti_kentta_arvo_<?php echo $montako; ?>" />
                        <?php
                        if ($montako != 0) {
                            ?>
                            <input type="button" value="Siirrä ylös" onclick="lisatietosiirraylos(<?php echo $montako; ?>)" />
                            <?php
                        }
                        if ($montako != 7) {
                            ?>
                            <input type="button" value="Siirrä alas" onclick="lisatietosiirraalas(<?php echo $montako; ?>)" />
                            <?php
                        }
                        ?>
                        <input type="button" value="Poista" onclick="poistalisatieto(<?php echo $montako; ?>)" />
                    </div>
                    <?php
                    $montako++;
                } while ($tulos = mysql_fetch_array($kysely));
                ?>
                <script type='text/javascript'>
                    <!--
                    for(var i=0;i<<?php echo $montako; ?>;i++)
                    lisatiedot[i] = true;
                    //-->
                </script>
                <?php
            }
            ?>
        </div>
        <input type="submit" value="Muokkaa" /><input type="button" value="Poista" onclick="siirry('<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $opelaajat . "&joukkue=" . $joukkue . "&mode=poista&pelaajatid=" . $pelaajatid; ?>')" />
    </form>
    <?php
} else {
    $_SESSION['virhe'] = "Pelaajaa ei löytynyt.";
    siirry("virhe.php");
}
?>

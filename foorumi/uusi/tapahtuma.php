<?php
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
$kausiosat = explode("-", $kausi);
if (tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $keskustelualue)) {
    ?>
    <div id="uusitapahtuma">
        <div id="otsikko">Uusi tapahtuma</div>
        <div id="error"><?php echo $error; ?></div>
        <div id="lomake">
            <form id="uusitapahtuma-form" action="<?php echo $_SERVER['PHP_SELF'] . "?keskustelualue=" . $keskustelualue . "&mode=tapahtuma"; ?>" method="post">
                <input type="hidden" name="ohjaa" value="27" />
                <input type="hidden" name="keskustelualue" value="<?php echo $keskustelualue; ?>" />
                <div id="vasen">*Tapahtuman nimi</div><div id="oikea"><input id="tekstikentta" type="text" name="nimi" value="<?php echo $_POST['nimi']; ?>" /></div>
                <div id="vasen">*Tapahtuma</div><div id="oikea"><input type="radio" name="tapahtuma" value="harkat"<?php echo($_POST['tapahtuma'] == "harkat" || empty($_POST['tapahtuma']) ? " CHECKED" : ""); ?> />Harkat
                    <input type="radio" name="tapahtuma" value="peli"<?php echo($_POST['tapahtuma'] == "peli" ? " CHECKED" : ""); ?> />Peli
                    <input type="radio" name="tapahtuma" value="turnaus"<?php echo($_POST['tapahtuma'] == "turnaus" ? " CHECKED" : ""); ?> />Turnaus
                    <input type="radio" name="tapahtuma" value="muu"<?php echo($_POST['tapahtuma'] == "muu" ? " CHECKED" : ""); ?> />Muu</div>
                <div id="vasen">*Milloin alkaa</div>
                <div id="oikea_ei_margin">
                    <?php
                    paivaMaaraValitsin("a", false, $kausiosat);
                    aikaValitsin("a", false);
                    ?>
                </div>
                <div id="vasen"><input type="checkbox" name="loppuu" value="1"<?php echo($_POST['loppuu'] ? " CHECKED" : ""); ?> />Milloin loppuu</div>
                <div id="oikea_ei_margin">
                    <?php
                    paivaMaaraValitsin("l", false, $kausiosat);
                    aikaValitsin("l", false);
                    ?>
                </div>
                <div id="vasen"><input type="checkbox" name="takailmoo" value="1"<?php echo($_POST['takailmoo'] ? " CHECKED" : ""); ?> />Ilmoittautumisen takaraja</div>
                <div id="oikea_ei_margin">
                    <?php
                    paivaMaaraValitsin("t", false, $kausiosat);
                    aikaValitsin("t", false);
                    ?>
                </div>
                <div id="vasen"><input type="checkbox" name="maxilmoo" value="1"<?php echo($_POST['maxilmoo'] ? " CHECKED" : ""); ?> />Ilmoittautujien maksimi m‰‰r‰</div> <div id="oikea"><input id="tekstikentta" type="text" name="maxilmo" value="<?php echo $_POST['maxilmo']; ?>" /></div>
                <div id="vasen">Kuvaus (max. 120 merkki‰)</div> <div id="oikea"><textarea id="tekstikentta" name="kuvaus" maxlength="120"><?php echo $_POST['kuvaus']; ?></textarea></div>
                <div id="vasen">Lis‰tieto</div> <div id="oikea"><textarea id="tekstikentta" name="lisatieto"><?php echo $_POST['lisatieto']; ?></textarea></div>
                <div id="vasen">Paikka</div> <div id="oikea"><input id="tekstikentta" type="text" name="paikka" value="<?php echo $_POST['paikka']; ?>" /></div>
                <div id="napit">
                    <div class="lisaa laheta" data-form="uusitapahtuma-form"></div><div class="takaisin liiku" onclick="parent.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"></div>
                </div>
            </form>
        </div>
    </div>
    <?php
} else {
    echo "Ei oikeuksia keskustelualueelle.";
}
?>
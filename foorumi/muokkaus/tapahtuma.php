<?php
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
$keskustelu = mysql_real_escape_string($_GET['keskustelu']);
$kausiosat = explode("-", $kausi);
$nyt = getdate();
$kysely = kysely($yhteys, "SELECT nimi, tapahtuma, UNIX_TIMESTAMP(alkamisaika) alkamisaika, UNIX_TIMESTAMP(loppumisaika) loppumisaika, kuvaus, UNIX_TIMESTAMP(ilmotakaraja) ilmotakaraja, 
    ilmomaxmaara, lisatieto, paikka FROM tapahtumat t, keskustelut k, keskustelualuekeskustelut kk WHERE t.id = k.tapahtumatID AND k.id=kk.keskustelutID AND k.id='" . $keskustelu . "' 
        AND kk.keskustelualueetID='" . $keskustelualue . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <div id="muokkaatapahtumaa">
        <div id="otsikko">Muokkaa tapahtumaa</div>
        <div id="error"><?php echo $error; ?></div>
        <div id="lomake">
            <form id="muokkaatapahtumaa-form" action="<?php echo $_SERVER['PHP_SELF'] . "?keskustelualue=" . $keskustelualue."&keskustelu=".$keskustelu."&mode=tapahtuma"; ?>" method="post">
                <input type="hidden" name="ohjaa" value="80" />
                <input type="hidden" name="keskustelualue" value="<?php echo $keskustelualue; ?>" />
                <input type="hidden" name="keskustelu" value="<?php echo $keskustelu; ?>" />
                <div id="vasen">*Tapahtuman nimi</div>
                <div id="oikea">
                    <input id="tekstikentta" type="text" name="nimi" value="<?php echo isset($_POST['nimi']) ? $_POST['nimi'] : $tulos['nimi']; ?>" />
                </div>
                <div id="vasen">*Tapahtuma</div>
                <div id="oikea">
                    <input type="radio" name="tapahtuma" value="harkat"<?php echo$_POST['tapahtuma'] == "harkat" || (!isset($_POST['tapahtuma']) && $tulos['tapahtuma'] == "harkat") ? " CHECKED" : ""; ?> />Harkat
                    <input type="radio" name="tapahtuma" value="peli"<?php echo $_POST['tapahtuma'] == "peli" || (!isset($_POST['tapahtuma']) && $tulos['tapahtuma'] == "peli") ? " CHECKED" : ""; ?> />Peli
                    <input type="radio" name="tapahtuma" value="muu"<?php echo $_POST['tapahtuma'] == "muu" || (!isset($_POST['tapahtuma']) && $tulos['tapahtuma'] == "muu") ? " CHECKED" : ""; ?> />Muu
                </div>
                <div id="vasen">*Milloin alkaa</div>
                <div id="oikea_ei_margin">
                    <?php
                        paivaMaaraValitsin("a", getdate($tulos['alkamisaika']), $kausiosat);
                        aikaValitsin("a", getdate($tulos['alkamisaika']));
                    ?>
                </div>
                <div id="vasen">
                    <input type="checkbox" name="loppuu" value="1"<?php echo($_POST['loppuu'] || (!isset($_POST['loppuu']) && $tulos['loppumisaika'])? " CHECKED" : ""); ?> />Milloin loppuu
                </div>
                <div id="oikea_ei_margin">
                    <?php
                        paivaMaaraValitsin("l", $tulos['loppumisaika']?getdate($tulos['loppumisaika']):false, $kausiosat);
                        aikaValitsin("l", $tulos['loppumisaika']?getdate($tulos['loppumisaika']):false);
                    ?>
                </div>
                <div id="vasen"><input type="checkbox" name="takailmoo" value="1"<?php echo($_POST['takailmoo'] || (!isset($_POST['takailmoo']) && $tulos['ilmotakaraja'])? " CHECKED" : ""); ?> />Ilmoittautumisen takaraja</div>
                <div id="oikea_ei_margin">
                    <?php
                        paivaMaaraValitsin("t", $tulos['ilmotakaraja']?getdate($tulos['ilmotakaraja']):false, $kausiosat);
                        aikaValitsin("t", $tulos['ilmotakaraja']?getdate($tulos['ilmotakaraja']):false);
                    ?>
               </div>
                <div id="vasen"><input type="checkbox" name="maxilmoo" value="1"<?php echo($_POST['maxilmoo'] || (!isset($_POST['maxilmoo']) && $tulos['ilmomaxmaara'])? " CHECKED" : ""); ?> />Ilmoittautujien maksimi m‰‰r‰</div> 
                <div id="oikea">
                    <input id="tekstikentta" type="text" name="maxilmo" value="<?php echo isset($_POST['maxilmo'])?$_POST['maxilmo']:($tulos['ilmomaxmaara']?$tulos['ilmomaxmaara']:""); ?>" />
                </div>
                <div id="vasen">Kuvaus</div> 
                <div id="oikea">
                    <textarea id="tekstikentta" name="kuvaus"><?php echo isset($_POST['kuvaus'])?$_POST['kuvaus']:$tulos['kuvaus']; ?></textarea>
                </div>
                <div id="vasen">Lis‰tieto</div> 
                <div id="oikea">
                    <textarea id="tekstikentta" name="lisatieto"><?php echo isset($_POST['lisatieto'])?$_POST['lisatieto']:$tulos['lisatieto']; ?></textarea>
                </div>
                <div id="vasen">Paikka</div> 
                <div id="oikea">
                    <input id="tekstikentta" type="text" name="paikka" value="<?php echo isset($_POST['paikka'])?$_POST['paikka']:$tulos['paikka']; ?>" />
                </div>
                <div id="napit">
                    <div class="muokkaa laheta" data-form="muokkaatapahtumaa-form"></div><div class="tyhjenna"></div>
                </div>
            </form>
        </div>
    </div>
    <?php
}
?>
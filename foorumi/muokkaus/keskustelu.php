<?php
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
$keskustelu = mysql_real_escape_string($_POST['keskustelu']);
$kysely = kysely($yhteys, "SELECT otsikko FROM keskustelut k, keskustelualuekeskustelut kk WHERE k.id=kk.keskustelutID AND k.id='".$keskustelu."' AND kk.keskustelualueetID='".$keskustelualue."'");
$tulos = mysql_fetch_array($kysely);
?>
<div id="uusikeskustelu">
    <div id="otsikko">Muokkaa keskustelualuetta</div>
    <div id="error"><?php echo $error; ?></div>
    <div id="lomake">
        <form id="uusikeskustelu-form" aciton="<?php echo $_SERVER['PHP_SELF'] . "?keskustelualue=" . $keskustelualue."&mode=keskustelu"; ?>" method="post">
            <input type="hidden" name="ohjaa" value="3" />
            <input type="hidden" name="keskustelualue" value="<?php echo $keskustelualue; ?>" />
            <div id="keskustelunotsikko">
                <div class="nimi">Otsikko</div><div class="kentta"><input type="text" name="otsikko" value="<?php echo isset($_POST['otsikko'])?$_POST['otsikko']:$tulos['otsikko']; ?>"/></div>
            </div>
            <div id="teksti">
                
            </div>
            <div id="clear"></div>
            <div id="napit">
                <div class="lisaa laheta" data-form="uusikeskustelu-form"></div><div class="tyhjenna"></div>
            </div>
        </form>
    </div>
</div>
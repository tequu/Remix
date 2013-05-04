<hr />
<div id="bold">Keskustelualueiden hallinta oikeudet</div>
<div id="error"></div>
<form name="kah" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okayttajat . "&mode=muokkaa&kayttajatid=" . $kayttajatid; ?>" method="post">
    <input type="hidden" name="ohjaa" id="ohjaa" />
    <input type="hidden" name="kayttajatid" value="<?php echo $kayttajatid; ?>" />
    <div>
        Valitse ryhmä:
        <select name="ryhmatid" id="ryhmat1">
            <?php
            tulostaValinnat($ryhmat);
            ?>
        </select>
    </div>
    <div id="floatleft">
        Lisättävät<br />
        <select name="lisattavatoikeudet[]" id="kahlisays" multiple="multiple" size="5">
        </select><br />
        <input type="button" value="Lisää" onclick="laheta('kah', ['ohjaa'], ['14'])" />
    </div>
    <div id="floatleft" style="margin-left:20px">
        Poistettavat<br />
        <select name="poistettavatoikeudet[]" id="kahpoisto" multiple="multiple" size="5">
        </select><br />
        <input type="button" value="Poista" onclick="laheta('kah', ['ohjaa'], ['31'])" />
    </div>
    <div id="clear"></div>
</form>
<hr />
<div id="bold">Keskustelualueiden näkyvyys oikeudet</div>
<form name="kan" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okayttajat . "&mode=muokkaa&kayttajatid=" . $kayttajatid; ?>" method="post">
    <input type="hidden" name="ohjaa" id="ohjaa" />
    <input type="hidden" name="kayttajatid" value="<?php echo $kayttajatid; ?>" />
    <div>
        Valitse ryhmä:
        <select name="ryhmatid" id="ryhmat2">
            <?php
            tulostaValinnat($ryhmat);
            ?>
        </select>
    </div>
    <div id="floatleft">
        Lisättävät<br />
        <select name="lisattavatoikeudet[]" id="kanlisays" multiple="multiple" size="5">
        </select><br />
        <input type="button" value="Lisää" onclick="laheta('kan', ['ohjaa'], ['13'])" /><br />
    </div>
    <div id="floatleft" style="margin-left:20px">
        Poistettavat<br />
        <select name="poistettavatoikeudet[]" id="kanpoisto" multiple="multiple" size="5">
        </select><br />
        <input type="button" value="Poista" onclick="laheta('kan', ['ohjaa'], ['30'])" /><br />
    </div>
    <div id="clear"></div>
</form>
<script type="text/javascript">
            $(document).ready(function() {
                var kayttaja = <?php echo $kayttajatid; ?>;
                $("#ryhmat1").change(function() {
                    paivitaKeskustelualueet(kayttaja, $("#ryhmat1 option:selected").val(), "kahlisays", 1);
                    paivitaKeskustelualueet(kayttaja, $("#ryhmat1 option:selected").val(), "kahpoisto", 2);
                });
                $("#ryhmat2").change(function() {
                    paivitaKeskustelualueet(kayttaja, $("#ryhmat2 option:selected").val(), "kanlisays", 3);
                    paivitaKeskustelualueet(kayttaja, $("#ryhmat2 option:selected").val(), "kanpoisto", 4);
                });
                paivitaKeskustelualueet(kayttaja, $("#ryhmat1 option:selected").val(), "kahlisays", 1);
                paivitaKeskustelualueet(kayttaja, $("#ryhmat1 option:selected").val(), "kahpoisto", 2);
                paivitaKeskustelualueet(kayttaja, $("#ryhmat2 option:selected").val(), "kanlisays", 3);
                paivitaKeskustelualueet(kayttaja, $("#ryhmat2 option:selected").val(), "kanpoisto", 4);
            });
</script>
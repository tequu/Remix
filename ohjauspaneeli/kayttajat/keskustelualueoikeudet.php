<hr />
<div id="bold">Keskustelualueiden oikeudet</div>
<div id="error"></div>
<form name="keskustelualueenoikeudet" action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $okayttajat . "&mode=muokkaa&kayttajatid=" . $kayttajatid; ?>" method="post">
    <input type="hidden" name="ohjaa" id="ohjaa" />
    <div>
        Valitse ryhmä:
        <select name="ryhmatid" id="ryhmat" onchange="paivitaKeskustelualueet()">
            <?php
            tulostaValinnat($ryhmat);
            ?>
        </select>
    </div>
    <div id="floatleft">
        Lisättävät<br />
        <select name="lisattavatoikeudet[]" id="keskustelualueetoikeudetlisays" multiple="multiple" size="5">
        </select><br />
        <input type="button" value="Lisää näkyvyys oikeudet" onclick="laheta('keskustelualueenoikeudet', ['ohjaa'], ['13'])" /><br />
        <input type="button" value="Lisää hallinta oikeudet" onclick="laheta('keskustelualueenoikeudet', ['ohjaa'],['14'])" />
    </div>
    <div id="floatleft" style="margin-left:20px">
        Poistettavat<br />
        <select name="poistettavatoikeudet[]" id="keskustelualueetoikeudetpoisto" multiple="multiple" size="5">
        </select><br />
        <input type="button" value="Poista näkyvyys oikeudet" onclick="laheta('keskustelualueenoikeudet',['ohjaa'], ['30'])" /><br />
        <input type="button" value="Poista hallinta oikeudet" onclick="laheta('keskustelualueenoikeudet', ['ohjaa'], ['31'])" />
    </div>
    <div id="clear"></div>

</form>
<script>
    function paivitaKeskustelualueet(){
        var ryhmatid = $("#ryhmat option:selected").val();
        var lisaysvalikko = "keskustelualueetoikeudetlisays";
        var poistovalikko = "keskustelualueetoikeudetpoisto";
 
        var funktio = function(tiedot){
            $("#"+lisaysvalikko+" option").remove();
            $(tiedot).find("keskustelualue").each(function()
            {
                $("#"+lisaysvalikko).append('<option value="' + $(this).find("id").text() + '">' + $(this).find("nimi").text() + '</option>');
            });
        }
        var data = {
            ryhmatid: ryhmatid,
            tunnuksetid: <?php echo $kayttajatid; ?>,
            kyselyid: 1
        }
        paivitaKeskustelualueetValikkoon(data, funktio, 1);
        data.kyselyid = 2;
        var funktio = function(tiedot){
            $("#"+poistovalikko+" option").remove();
            $(tiedot).find("keskustelualue").each(function()
            {
                $("#"+poistovalikko).append('<option value="' + $(this).find("id").text() + '">' + $(this).find("nimi").text() + '</option>');
            });
        }
        paivitaKeskustelualueetValikkoon(data, funktio, 2);
    }
    $(document).ready(paivitaKeskustelualueet());
</script>
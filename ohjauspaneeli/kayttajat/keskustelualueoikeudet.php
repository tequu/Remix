<div id="keskustelualueoikeudethallinta">
    <hr />
    <h3>Keskustelualueiden oikeuksien hallinta</h3>
    <div id="ryhmanvalintavalikko">
        <form id="keskustelualueoikeudetform" action="<?php echo $_SERVER['PHP_SELF'] . "?" . get_to_string(); ?>" method="post">
            <input type="hidden" name="ohjaa" id="keskustelualueoikeudet_ohjaa" />
            <input type="hidden" name="tunnusid" id="tunnusid" value="<?php echo $kayttajatid; ?>" />
            <input type="hidden" name="oikeudet" id="oikeudet" />
            Valitse ryhmä:
            <select id="keskustelualueryhmat">
                <option></option>
                <?php
                $kysely2 = kysely($yhteys, "SELECT * FROM keskustelualueryhmat ");
                while ($tulos2 = mysql_fetch_array($kysely2)) {
                    echo "<option value=\"" . $tulos2['id'] . "\">" . $tulos2['otsikko'] . "</option>";
                }
                ?>
            </select>
        </form>
    </div>
    
    <div class="floatleft">
        <select id="kahlisays" multiple="multiple" size="5">
        </select>
        <br />
        <input type="button" class="kahlisaa" value="Lisää" />
    </div>
    
    <div class="floatleft" style="margin-left:20px">
        <select name="poistettavatoikeudet[]" id="kahpoisto" multiple="multiple" size="5">
        </select>
        <br />
        <input type="button" class="kahpoista" value="Poista" />
    </div>
    
    <div class="clear"></div>
    <hr />
    <h4>Keskustelualueiden näkyvyys oikeudet</h4>
    
    <div class="floatleft">
        <select id="kanlisays" multiple="multiple" size="5">
        </select>
        <br />
        <input type="button" class="kanlisaa" value="Lisää" /><br />
    </div>
    
    <div class="floatleft" style="margin-left:20px">
        <select id="kanpoisto" multiple="multiple" size="5">
        </select>
        <br />
        <input type="button" class="kanpoista" value="Poista" /><br />
    </div>
    <div class="clear"></div>
</div>
<div class="vasen">Valitse ryhmä</div>
<div class="oikea">
    <input type="hidden" id="sivuid" value="<?php echo $okeskustelualue; ?>" />
    <input type="hidden" id="mode" value="muokkaa" />
    <select id="ryhmavalikko">
        <?php
        echo!isset($_GET['keskustelualueryhmatid']) ? "<option></option>" : "";
        do {
            echo"<option value=\"" . $tulos['id'] . "\"" . ($_GET['keskustelualueryhmatid'] == $tulos['id'] ? " SELECTED" : "") . ">" . $tulos['otsikko'] . "</option>";
        } while ($tulos = mysql_fetch_array($kysely))
        ?>
    </select>
</div>
<div class="clear"></div>
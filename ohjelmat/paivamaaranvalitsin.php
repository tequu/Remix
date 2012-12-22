<?php
/**
 * Jos päivämäärä ei haluta menemään nykyisen päivämäärän perusteella, tulee haluttu päivämäärä asettaa muuttujiin $paiva, $kuukausi, $vuosi. 
 */
?>
Päivä: <br />
<select name='paiva'>
    <?php
    $aika = getdate();
    $kausiosat = explode("-", $kausi);
    for ($i = 1; $i < 32; $i++) {
        echo "<option" . ((empty($error) && ((isset($paiva) && $i == $paiva) || (!isset($paiva) && $i == $aika['mday']))) || $i == $_POST['paiva'] ? " SELECTED" : "") . ">" . $i . "</option>";
    }
    ?>
</select>
<select name='kuukausi'>
    <?php
    for ($i = 1; $i < 13; $i++) {
        echo "<option" . ((empty($error) && ((isset($kuukausi) && $i == $kuukausi) || (!isset($kuukausi) && $i == $aika['mon']))) || $i == $_POST['kuukausi'] ? " SELECTED" : "") . ">" . $i . "</option>";
    }
    ?>
</select>
<select name='vuosi'>
    <?php
    for ($i = 0; $i < 2; $i++) {
        echo "<option" . ((empty($error) && ((isset($vuosi) && $kausiosat[$i] == $vuosi) || (!isset($vuosi) && $kausiosat[$i] == $aika['year']))) || $kausiosat[$i] == $_POST['vuosi'] ? " SELECTED" : "") . ">" . $kausiosat[$i] . "</option>";
    }
    ?>
</select>

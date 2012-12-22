<?php
/**
 * Jos halutaan asettaa tietty kellonaika, niin aika täytyy asettaa $tunti ja $minuutti muuttujiin. 
 */
?>
Kello:<br /> <select name='tunti'>
    <?php
for ($i = 0; $i < 24; $i++) {
    echo "<option value=\"".$i."\"".((empty($error) && $i == $tunti) || $i == $_POST['tunti']?" SELECTED":"").">".($i<10?"0":"").$i."</option>";
}
?>
</select>:<select name='minuutti'>
    <?php
for ($i = 0; $i < 60; $i++) {
    echo "<option value=\"".$i."\"".((empty($error) && $i == $minuutti) || $i == $_POST['minuutti']?" SELECTED":"").">".($i<10?"0":"").$i."</option>";
}
?>
</select>

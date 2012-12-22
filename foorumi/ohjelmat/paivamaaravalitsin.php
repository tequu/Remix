<?php
$nyt = getdate();
?>
<div id="paiva">
    <div id="teksti">Päivä</div>
    <select name="paiva_<?php echo $tunnus; ?>">
        <?php
        for ($i = 1; $i < 32; $i++) {
            echo "<option";
            if ((isset($_POST['paiva_'.$tunnus]) && $i == $_POST['paiva_'.$tunnus]) || (!isset($_POST['paiva_'.$tunnus]) && $valittu && $i == $valittu['mday']) || 
                    (!isset($_POST['paiva_'.$tunnus]) && !$valittu && $i == $nyt['mday'])) {
                echo " selected";
            }
            echo ">" . $i . "</option>";
        }
        ?>
    </select>
    <select name="kuukausi_<?php echo $tunnus; ?>">
        <?php
        for ($i = 1; $i < 13; $i++) {
            echo "<option";
            if ((isset($_POST['kuukausi_'.$tunnus]) && $i == $_POST['kuukausi_'.$tunnus]) || (!isset($_POST['kuukausi_'.$tunnus]) && $valittu && $i == $valittu['mon'])  || 
                    (!isset($_POST['kuukausi_'.$tunnus]) && !$valittu && $i == $nyt['mon'])) {
                echo " selected";
            }
            echo ">" . $i . "</option>";
        }
        ?>
    </select>
    <select name="vuosi_<?php echo $tunnus; ?>">
        <?php
        for ($i = 0; $i < count($vuodet); $i++) {
            echo "<option";
            if ((isset($_POST['vuosi_'.$tunnus]) && $vuodet[$i] == $_POST['vuosi_'.$tunnus]) || (!isset($_POST['vuosi_'.$tunnus]) && $valittu && $vuodet[$i] == $valittu['year'])  || 
                    (!isset($_POST['vuosi_'.$tunnus]) && !$valittu && $vuodet[$i] == $nyt['year'])) {
                echo " selected";
            }
            echo ">" . $vuodet[$i] . "</option>";
        }
        ?>
    </select>
</div>
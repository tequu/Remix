<?php
$nyt = getdate();
?>
<div id="aika">
    <div id="teksti">Aika</div>
    <select name="tunti_<?php echo $tunnus; ?>">
        <?php
        for ($i = 0; $i < 24; $i++) {
            echo "<option";
            if ((isset($_POST['tunti_' . $tunnus]) && $i == $_POST['tunti_' . $tunnus]) || (!isset($_POST['tunti_' . $tunnus]) && $valittu && $i == $valittu['hours']) ||
                    (!isset($_POST['tunti_' . $tunnus]) && !$valittu && $i == $nyt['hours'])) {
                echo " selected";
            }
            echo ">" . $i . "</option>";
        }
        ?>
    </select>
    <select name="minuutti_<?php echo $tunnus; ?>">
        <?php
        for ($i = 0; $i < 60; $i++) {
            echo "<option";
            if ((isset($_POST['minuutti_' . $tunnus]) && $i == $_POST['minuutti_' . $tunnus]) || (!isset($_POST['minuutti_' . $tunnus]) && $valittu && $i == $valittu['minutes']) ||
                    (!isset($_POST['tunti_' . $tunnus]) && !$valittu && $i == $nyt['minutes'])) {
                echo " selected";
            }
            echo ">" . $i . "</option>";
        }
        ?>
    </select>
</div>
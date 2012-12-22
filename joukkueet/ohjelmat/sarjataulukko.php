<div id="sarjataulukko">
    <div id="ryhma"><?php echo $sarjataulukonnimi; ?></div><div id="kausi"><?php echo $sarjataulukonkausi; ?></div><div style="clear:left"></div>
    <div id="tiedot">
        <div id="nimi">Joukkue</div>
        <div id="o">O</div>
        <div id="v">V</div>
        <div id="t">T</div>
        <div id="h">H</div>
        <div id="me">ME</div>
        <div id="p">P</div>
    </div>
    <?php
    $i = 0;
    while ($tulos = mysql_fetch_array($kysely)) {
        ?>
        <div id="joukkue"<?php echo ($i % 2 == 0 ? "" : " class=\"tumma\""); ?>>
            <?php
            if (strtolower($tulos['joukkue']) == "fbc remix") {
                ?>
                <div id="fbcremix">
                    <?php
                }
                ?>
                <div id = "nimi"><?php echo $tulos['joukkue']; ?></div>
                <div id="o"><?php echo $tulos['O']; ?></div>
                <div id="v"><?php echo $tulos['V']; ?></div>
                <div id="t"><?php echo $tulos['T']; ?></div>
                <div id="h"><?php echo $tulos['H']; ?></div>
                <div id="me"><?php echo $tulos['TM'] . "-" . $tulos['PM']; ?></div>
                <div id="p"><?php echo $tulos['P']; ?></div>
                <?php
                if (strtolower($tulos['joukkue']) == "fbc remix") {
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        $i++;
    }
    ?>
</div>
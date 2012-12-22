<div id="tilastot">
    <div id="ryhma"><?php echo $tilastonnimi; ?></div><div id="kausi"><?php echo $tilastonkausi; ?></div><div style="clear:left"></div>
    <div id="tiedot">
        <div id="sijoitus">#</div>
        <div id="nimi">Nimi</div>
        <div id="o">O</div>
        <div id="rl">RL</div>
        <div id="rm">RM</div>
        <div id="p">P</div>
        <div id="plusmiinus">+/-</div>
    </div>
    <?php
    $i = 0;
    while ($tulos = mysql_fetch_array($kysely)) {
        ?>
        <div id="pelaaja"<?php echo ($i % 2 == 0 ? "" : " class=\"tumma\""); ?>>
            <div id="sijoitus"><?php echo ($i + 1); ?></div>
            <div id="nimi"><?php echo $tulos['sukunimi'] . " " . $tulos['etunimi']; ?></div>
            <div id="o"><?php echo $tulos['O']; ?></div>
            <div id="rl"><?php echo $tulos['RLM'] . " (" . $tulos['RLY'] . ")"; ?></div>
            <div id="rm"><?php echo $tulos['RM']; ?></div>
            <div id="p"><?php echo $tulos['M'] . "+" . $tulos['S'] . "=" . ($tulos['M'] + $tulos['S']); ?></div>
            <div id="plusmiinus"><?php echo $tulos['plusmiinus']; ?></div>
        </div>
        <?php
        $i++;
    }
    ?>
</div>
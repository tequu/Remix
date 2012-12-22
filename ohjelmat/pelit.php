<div id="pelit">
    <div id="ryhma"><?php echo $peliryhmannimi; ?></div><div id="kausi"><?php echo $peliryhmankausi; ?></div><div style="clear:left"></div>
    <div id="tiedot">
        <div id="pvm">Pvm</div>
        <div id="aika">Aika</div>
        <div id="vastustaja">Vastustaja</div>
        <div id="paikka">Paikka</div>
        <div id="tulos">Tulos</div>
    </div>
    <?php
    $ejoukkue = "";
    $i = 0;
    while ($tulos = mysql_fetch_array($kysely)) {
        /*
         * Tarkistaa kumpiko voitti.
         * Vieras voitti = 0
         * Tasapeli = 1
         * FBC Remix voitti = 2
         */
        $voittaja = "havio";
        if ($tulos['kotimaalit'] == $tulos['vierasmaalit']) {
            $voittaja = "tasapeli";
        } elseif (($tulos['koti'] && $tulos['kotimaalit'] > $tulos['vierasmaalit']) || (!$tulos['koti'] && $tulos['vierasmaalit'] > $tulos['kotimaalit'])) {
            $voittaja = "voitto";
        }
        if ($ejoukkue != $tulos['nimi']) {
            $ejoukkue = $tulos['nimi'];
            ?>
            <div id="joukkue"<?php echo ($i % 2 == 0 ? "" : " class=\"tumma\""); ?> style="padding-top:6px;">
                <div id="harmaa">
                    <div id="nimi"><?php echo $ejoukkue; ?></div>
                </div>
            </div>
            <?php
            $i++;
        }
        ?>
        <div id="joukkue"<?php echo ($i % 2 == 0 ? "" : " class=\"tumma\""); ?>>
            <div id="pvm"><?php echo date("d.m.", $tulos['aika']); ?></div>
            <div id="aika"><?php echo date("H:i", $tulos['aika']); ?></div>
            <div id="vastustaja"><?php echo $tulos['vastustaja']; ?></div>
            <div id="paikka"><?php echo $tulos['pelipaikka']; ?></div>
            <div id="<?php echo $tulos['pelattu']?$voittaja:""; ?>">
                <div id="tulos"><?php echo ($tulos['pelattu'] ? $tulos['kotimaalit'] . " - " . $tulos['vierasmaalit'] : ""); ?></div>
            </div>
        </div>
        <?php
        $i++;
    }
    ?>
</div>
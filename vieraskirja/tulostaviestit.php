<?php
$sivu = $sivu * $ysm;
$kysely = kysely($yhteys, "SELECT kirjoittaja, unix_timestamp(aika) aika, seura, joukkue, viesti FROM vieraskirja " .
        "WHERE enabled='1' AND poistettu='0' ORDER BY aika DESC LIMIT " . $sivu . ", " . $ysm);
// tulostetaan sivulla olevat viestit oikeasta kohdasta alkaen 
while ($tulos = mysql_fetch_array($kysely)) {
    // tulostetaan viestin tiedot yksinkertaisesti muotoiltuna 
    ?>
    <div id="viesti">
        <div id="nimimerkki"><span>Nimimerkki:</span> <?php echo $tulos['kirjoittaja']; ?></div>
        <div id="seura"><span>Seura:</span> <?php echo $tulos['seura']; ?></div>
        <div id="joukkue"><span>Joukkue:</span> <?php echo $tulos['joukkue']; ?></div>
        <div id="aika"><span>Aika:</span> <?php echo date("d.m.y H:i", $tulos['aika']); ?></div>
        <div id="teksti"><div>Viesti:</div><?php echo $tulos['viesti']; ?></div>
    </div>
    <?php
}
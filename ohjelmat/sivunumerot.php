<?php
/**
 *
 * @param type $maara Kokonais määrä.
 * @param type $ysm Yhdellä sivulla olevien määrä.
 * @param type $sivu Sivu jolla ollaan nyt.
 * @param type $linkki Linkki jolle numeroa klikatessa siirrytään. ?-merkillä varustettuna, sekä &-merkillä jos on aikaisempia välitettäviä parametriä.
 * @param type $viimeinen Näytetäänkö viimeinen sivu viimeisenä.
 * @param type $nuolet Näytetäänkö nuoli linkit edelliselle ja seuraavalle sivulle.
 * @param type $ejav Näytetäänkö napit ensimmäiselle ja viimeiselle sivulle.
 */
$sivuja = ceil($maara / $ysm);
if ($sivu >= 0 && $sivu < $sivuja) {
    if ($_GET['sivu'] < $sivu)
        $sivu = intval(mysql_real_escape_string($sivu));
}
else {
    $sivu = 0;
}
if ($sivu != 0) {
    if ($ejav) {
        ?>
        <div id="ensimmainen" onclick="siirry('<?php echo $linkki; ?>sivu=0');"></div>
        <?php
    }
    if ($nuolet) {
        ?>
        <div id="edellinen" onclick="siirry('<?php echo $linkki; ?>sivu=<?php echo $sivu - 1; ?>');"></div>
        <?php
    }
}
$aloitus = $sivu - 2;
//Jos aloitus sivu menee alle yhden nii palataa 1
if ($aloitus < 0)
    $aloitus = 0;
$lopetus = $aloitus + 3;
//Tulostetaan pisteitä jos sivunumerot eivät ala 1
if ($aloitus > 1) {
    ?>
    <div id="sivunumero">...</div>
    <?php
} else if ($aloitus == 1) {
    ?>
    <div id="sivunumero"><a href="<?php echo $linkki; ?>sivu=<?php echo ($aloitus - 1); ?>"><?php echo $aloitus; ?></a></div>
    <?php
}
while ($aloitus <= $lopetus) {
    ?>
    <div id="sivunumero"<?php echo ($aloitus == $sivu ? " style=\"color: #e86d1c;\"" : ""); ?>>
        <?php
        if ($aloitus != $sivu) {
            ?>
            <a href="<?php echo $linkki; ?>sivu=<?php echo $aloitus; ?>">
                <?php
            }
            echo $aloitus + 1;
            if ($aloitus != $sivu) {
                ?>
            </a>
            <?php
        }
        ?>
    </div>
    <?php
    $aloitus++;
    if ($aloitus >= $sivuja - 1) {
        break;
    }
}
//Tulostetaan loppuun pisteitä jos ei tulostettu viimeistä sivunumeroa
if ($aloitus < $sivuja - 1) {
    ?>
    <div id="sivunumero">...</div>
    <?php
} elseif ($aloitus == $sivuja - 1) {
    ?>
    <div id="sivunumero"<?php echo ($aloitus == $sivu ? " style=\"color: #e86d1c;\"" : ""); ?>>
        <?php
        if ($sivuja - 1 != $sivu) {
            ?>
            <a href="<?php echo $linkki; ?>sivu=<?php echo ($sivuja - 1); ?>">
                <?php
            }
            echo $sivuja;
            if ($sivuja - 1 != $sivu) {
                ?>
            </a>
            <?php
        }
        ?>
    </div>
    <?php
}
if (($viimeinen && $aloitus < $sivuja)) {
    ?>
    <div id="sivunumero">
        <?php
        if ($sivuja == $sivu) {
            ?>
            <a href="<?php echo $linkki; ?>sivu=<?php echo $sivuja; ?>">
                <?php
            }
            echo $sivuja;
            if ($sivuja == $sivu) {
                ?>
            </a>
            <?php
        }
        ?>
    </div>
    <?php
}
if ($sivu != ($sivuja - 1)) {
    if ($nuolet) {
        ?>
        <div id="seuraava" onclick="siirry('<?php echo $linkki; ?>sivu=<?php echo $sivu + 1; ?>');"></div>
        <?php
    }
    if ($ejav) {
        ?>
        <div id="viimeinen" onclick="siirry('<?php echo $linkki; ?>sivu=<?php echo $sivuja - 1; ?>');"></div>
        <?php
    }
}
?>

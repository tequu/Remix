<?php
$kirjautunut = true;
include("/home/fbcremix/public_html/Remix/yla.php");
?>
<div id="container">
    <?php
    if (isset($_GET['viesti'])) {
        $viesti = mysql_real_escape_string(trim($_GET['viesti']));
        $sivu = mysql_real_escape_string(trim($_GET['sivu']));
        if (empty($sivu))
            $sivu = 1;
        $kysely = kysely($yhteys, "SELECT k.id kid, v.otsikko otsikko, viesti, lahetysaika, keskustelualueetID FROM Viestit v, Keskustelut k, Keskustelualueet_keskustelut kk WHERE v.keskustelutID=k.id AND k.id=kk.keskustelutID AND v.id='" . $viesti . "'");
        if ($tulos = mysql_fetch_array($kysely)) {
            if (tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $tulos['keskustelualueetID'])) {
                ?>
                <div id="keskelle">
                    Oletko varma että halut poistaa tämän viestin?
                    <div id=viesti>
                        <?php
                        echo "<span id=\"bold\">Kirjoitusaika:</span>" . $tulos['lahetysaika'] .
                        "<div id=\"kayttajantiedot\"><span id=\"bold\">Kirjoittaja:</span>" . $tulos['login'] .
                        "</div><hr /><div id=\"teksti\"><span id=\"bold\">Otsikko:</span>" . $tulos['otsikko'] .
                        "<br /><span id=\"bold\">Viesti:</span>" . $tulos['viesti'] . "</div>";
                        ?>
                        <div id=clear></div>
                    </div>
                    <form action="/Foorumit/poista.php?viesti=<?php echo $viesti . "&sivu=" . $sivu; ?>" method="post">
                        <input type="hidden" name="ohjaa" value="29" />
                        <input type="submit" value="Kyllä" /> <input type="button" value="En" onclick="siirry('/Foorumit/keskustelu.php?keskustelu=<?php echo $tulos['kid'] . "&sivu=" . $sivu; ?>')" />
                    </form>
                </div>
                <?php
            } else {
                siirry("eioikeuksia.php");
            }
        } else {
            siirry("index.php");
        }
    } elseif (isset($_GET['keskustelu'])) {
        $keskustelu = mysql_real_escape_string(trim($_GET['keskustelu']));
        $sivu = mysql_real_escape_string(trim($_GET['sivu']));
        if (empty($sivu))
            $sivu = 1;
        $kysely = kysely($yhteys, "SELECT otsikko, keskustelualueetID FROM Keskustelut k, Keskustelualueet_keskustelut kk WHERE k.id=kk.keskustelutID AND id='" . $keskustelu . "'");
        if ($tulos = mysql_fetch_array($kysely)) {
            if (tarkistaHallintaOikeudetKeskustelualueelle($yhteys, $tulos['keskustelualueetID'])) {
                ?>
                <div id="keskelle">
                    Oletko varma että haluat poistaa tämän keskustelun?<br />
                    Keskustelun otsikko: <?php echo $tulos['otsikko']; ?><br />
                    <form action="/Foorumit/poista.php?keskustelu=<?php echo $keskustelu . "&sivu=" . $sivu; ?>" method="post">
                        <input type="hidden" name="ohjaa" value="34" />
                        <input type="submit" value="Poista" /><input type="button" value="En" onclick="siirry('/Foorumit/keskustelualue.php?keskustelualue=<?php echo $tulos['keskustelualueetID']."&sivu=".$sivu; ?>')" />
                    </form>
                </div>
                <?php
            } else {
                siirry("eioikeuksia.php");
            }
        } else {
            siirry("index.php");
        }
    } else {
        siirry("index.php");
    }
    ?>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
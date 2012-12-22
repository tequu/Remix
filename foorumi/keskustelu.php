<?php
if (!isset($_GET['keskustelualue']) || !isset($_GET['keskustelu']))
    header("Location: /Remix/foorumi/index.php");
include("/home/fbcremix/public_html/Remix/foorumi/yla.php");
$keskustelualue = mysql_real_escape_string($_GET['keskustelualue']);
$keskustelu = mysql_real_escape_string($_GET['keskustelu']);
?>
<div id="levea_content">
    <div id="foorumi">
        <?php
        $kysely = kysely($yhteys, "SELECT otsikko, tapahtumatID FROM keskustelut WHERE id='" . $keskustelu . "'");
        $tulos = mysql_fetch_array($kysely);
        if ($tulos['tapahtumatID']) {
            ?>
            <div id="otsikko">
                <div id="ilmoittaudu">
                    <div class="in"></div>
                    <div class="out_ilmoittautunut"></div>
                </div>
                <?php echo $tulos['otsikko']; ?>
            </div>
            <div id="tapahtuma">
                <div id="pohja">
                    <div id="in">
                        <div id="header">IN</div>
                        <div id="pelaajat">
                            <div class="pelaaja">ytrj</div>
                            <div class="pelaaja">löuy</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div><div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                        </div>
                    </div>
                </div>
                <div id="pohja">
                    <div id="out">
                        <div id="header">OUT</div>
                        <div id="pelaajat">
                            <div class="pelaaja">gfad</div>
                            <div class="pelaaja">pquy</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div><div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                            <div class="pelaaja">vmcx,q</div>
                        </div>
                    </div>
                </div>
                <div id="kommentti"><div id="teksti">Kommentti</div> <input type="text" id="kommenttikentta"><div id="laheta"></div></div>
                <div id="pelaajat">
                    <div class="pelaaja">gfadgöfadigfa</div><div class="pelaaja">guitrmb</div><div class="pelaaja">vmcx,q</div>
                    <div class="pelaaja">vmcx,q</div>
                    <div class="pelaaja">vmcx,q</div>
                    <div class="pelaaja">vmcx,q</div>
                    <div class="pelaaja">vmcx,q</div><div class="pelaaja">vmcx,q</div>
                    <div class="pelaaja">vmcx,q</div>
                    <div class="pelaaja">vmcx,q</div>
                    <div class="pelaaja">vmcx,q</div>
                    <div class="pelaaja">vmcx,q</div>
                    <div class="pelaaja">vmcx,q</div>
                    <div class="pelaaja">vmcx,q</div>
                    <div class="pelaaja">vmcx,q</div>
                </div>
            </div>
            <?php
        }
        $kysely2 = kysely($yhteys, "SELECT otsikko, viesti, UNIX_TIMESTAMP(lahetysaika) lahetysaika FROM viestit WHERE keskustelutID='" . $keskustelu . "'");
        if ($tulos2 = mysql_fetch_array($kysely2)) {
            if($tulos['tapahtumatID']){
            ?>
            <div id="valiotsikko">Kommentit</div>
            <?php
            } else {
                ?>
                <div id="otsikko"><?php echo $tulos['otsikko']; ?></div>
                <?php
            }
            ?>
            <div id="pohja"> 
                <div id="viestit">
                    <?php
                    do {
                        ?>
                        <div class="viesti">
                            <div class="header">
                                <div class="kirjoitusaika"><?php echo date("d/m/y", $tulos2['lahetysaika']) . " klo " . date("H:i", $tulos['lahetysaika']); ?></div> <div class="otsikko"><?php echo $tulos2['otsikko']; ?></div>
                                <div class="kopioi"></div><div class="vastaa"></div>
                            </div>
                            <div class="vasen">
                                <div class="kirjoittaja">
                                    <div class="nimi">Kijoittaja</div>
                                    <div class="arvo">Admin</div>
                                </div>
                                <div class="kirjoittajantiedot">
                                    <div class="liittymispaiva">Liittymis päivä: 11/5/2012</div>
                                    <div class="viesteja">Viestäjä: 50</div>
                                </div>
                            </div>
                            <div class="oikea">
                                <div class="teksti">
                                    <?php echo $tulos2['viesti']; ?>
                                </div>
                                <div class="allekirjoitus"></div>
                            </div>
                            <div id="clear"></div>
                        </div>
                        <?php
                    } while ($tulos2 = mysql_fetch_array($kysely2));
                    ?>
                </div>
            </div>
            <?php
        }
        ?>

        <!--Uusi viesti-->
        <div id="pohja">
            <div id="uusiviesti">
                <div id="otsikko">Uusi viesti</div>
                <div id="error"><?php echo $error; ?></div>
                <div id="lomake">
                    <form id="uusiviesti-form" action="<?php echo $_SERVER['PHP_SELF'] . "?keskustelualue=" . $keskustelualue . "&keskustelu=" . $keskustelu; ?>" method="post">
                        <input type="hidden" name="ohjaa" value="4" />
                        <input type="hidden" name="keskustelualue" value="<?php echo $keskustelualue; ?>" />
                        <input type="hidden" name="keskustelu" value="<?php echo $keskustelu; ?>" />
                        <div id="viestinotsikko">
                            <div class="nimi">Otsikko</div><div class="kentta"><input type="text" name="otsikko" /></div>
                        </div>
                        <div id="clear"></div>
                        <div id="teksti">
                            <div class="nimi">Viesti</div>
                            <div class="kentta"><textarea name="viesti" rows="7" cols="90"></textarea></div>
                        </div>
                        <div id="clear"></div>
                        <div id="napit">
                            <div id="laheta"></div><div id="tyhjenna"></div>
                        </div>
                    </form>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $("#laheta").click(function() {
                                $("#uusiviesti-form").submit();
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
<?php
include("/home/fbcremix/public_html/Remix/yla.php");
?>
<div id="levea_content">
    <div id="pohja">
        <?php
        if (isset($_GET['email']) && isset($_GET['token']) && isset($_GET['id'])) {
            $email = mysql_real_escape_string($_GET['email']);
            $token = mysql_real_escape_string($_GET['token']);
            $tunnusid = mysql_real_escape_string($_GET['id']);
            $kysely = kysely($yhteys, "SELECT id FROM tunnukset, tokenit WHERE id=tunnuksetID AND id='" . $tunnusid . "' AND email='" . $email . "' AND token='" . $token . "'");
            if ($tulos = mysql_fetch_array($kysely)) {
                if (!isset($_POST['lahetetty'])) {
                    ?>
                    <form name="aktivointi" action="<?php $_SERVER['PHP_SELF'] . "?email=" . $email . "&token=" . $token . "&id=" . $tunnusid; ?>" method="post">
                        <input type="hidden" name="lahetetty" value="1" />
                        K�yt�t asiallista teksti� kirjoittaessasi vieraskirjaan tai foorumeille, et loukkaa muita tai k�yt� muuta ep�asiallista teksti�. Yll�pito ker�� kaikkien ip-osoitteen talteen ja se tai tunnus voidaan est�� kokonaan toistuvien rikkomusten takia.<br /><hr />
                        <input type="checkbox" name="hyvaksy" value="1" />Olen lukenut s��nn�t ja hyv�ksyn ne<br />
                        <div class="laheta" onclick="laheta('aktivointi', [], [])"></div>
                    </form>
                    <?php
                } elseif ($_POST['hyvaksy']) {
                    ?>
                    <form name="aktivointi" action="<?php $_SERVER['PHP_SELF'] . "?email=" . $email . "&token=" . $token . "&id=" . $tunnusid; ?>" method="post">
                        <input type="hidden" name="hyvaksy" value="1" />
                        <input type="hidden" name="lahetetty" value="1" />
                        <input type="hidden" name="ohjaa" value="77" />
                        <div id="tekstivasempaan">
                            <div id="error"><?php echo $error; ?></div>
                            <div id="rivi">
                                <div id="kentannimi">K�ytt�j�tunnus:</div>
                                <div id="kentta"><input type="text" name="login" value="<?php echo $_POST['login']; ?>" size="16" /></div>
                            </div>
                            <div id="clear"></div>
                            <div id="rivi">
                                <div id="kentannimi">Salasana:</div>
                                <div id="kentta"><input type="password" name="salasana" size="16" /></div>
                            </div>
                            <div id="clear"></div>
                            <div id="rivi">
                                <div id="kentannimi">Vahvista salasana:</div>
                                <div id="kentta"><input type="password" name="vahvistasalasana" size="16" /></div>
                            </div>
                            <div id="clear"></div>
                            <div id="rivi">
                                <div id="lisakentta"><input type="checkbox" name="nakyyetunimi" value="1" <?php echo ($_POST['nakyyetunimi'] ? "CHECKED" : ""); ?> />Etunimi n�kyy kirjautuneille</div>
                            </div>
                            <div id="clear"></div>
                            <div id="rivi">
                                <div id="lisakentta"><input type="checkbox" name="nakyysukunimi" value="1" <?php echo ($_POST['nakyysukunimi'] ? "CHECKED" : ""); ?> />Sukunimi n�kyy kirjautuneille</div>
                            </div>
                            <div id="clear"></div>
                            <div id="rivi">
                                <div id="lisakentta"><input type="checkbox" name="nakyysyntymavuosi" value="1" <?php echo ($_POST['nakyysyntymavuosi'] ? "CHECKED" : ""); ?> />Syntym�vuosi n�kyy kirjautuneille</div>
                            </div>
                            <div id="clear"></div>
                            <div id="rivi">
                                <div id="lisakentta"><input type="checkbox" name="nakyysahkoposti" value="1" <?php echo ($_POST['nakyysahkoposti'] ? "CHECKED" : ""); ?> />S�hk�posti n�kyy kirjautuneille</div>
                            </div>
                            <div id="clear"></div>
                        </div>
                        <div id="clear"></div>
                        <div class="laheta" onclick="laheta('aktivointi', [], []);"></div>
                    </form>
                <?php } else { ?>
                    Et hyv�ksynyt s��nt�j�. Mene takaisin <a href="index.php">etusivulle</a>.
                    <?php
                }
            } else {
                ?>
                Tiedot virheelliset
                <?php
            }
        } else {
            ?>
            Tiedot virheelliset
            <?php
        }
        ?>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>

<hr />
<div id="kayttajantiedot">
    <div id="muokkaakayttajaa">
        <h4>Muokkaa käyttäjää</h4>
        <form id="muokkaaform" aciton="<?php echo $_SERVER['PHP_SELF'] . "?".get_to_string(); ?>" method="post">
            <input type="hidden" name="tunnusid" value="<?php echo $kayttajatid; ?>" />
            <input type="hidden" name="ohjaa" value="83" />
            <div class="vasen">Tunnus</div><div class="oikea"><input type="text" name="login" value="<?php echo $tulos['login']; ?>" /> <?php echo ($tulos['isadmin'] != "Perus" ? "(" . $tulos['isadmin'] . ")" : "") . ($tulos['estetty'] ? "(Estetty)" : ""); ?> <span class="error"><?php echo $error['muokkaa']['login']; ?></span></div>
            <div class="vasen">Sähköposti</div><div class="oikea"><input type="text" name="email" value="<?php echo $tulos['email']; ?>" /> <?php echo ($tulos['enabled'] ? "" : "(Vahvistamaton)"); ?> <span class="error"><?php echo $error['muokkaa']['email']; ?></span></div>
            <div class="vasen">Etunimi</div><div class="oikea"><input type="text" name="etunimi" value="<?php echo $tulos['etunimi']; ?>" /> <span class="error"><?php echo $error['muokkaa']['etunimi']; ?></span></div>
            <div class="vasen">Sukunimi</div><div class="oikea"><input type="text" name="sukunimi" value="<?php echo $tulos['sukunimi']; ?>" /> <span class="error"><?php echo $error['muokkaa']['sukunimi']; ?></span></div>
            <div class="vasen">Syntymävuosi</div><div class="oikea"><input type="text" name="syntymavuosi" value="<?php echo $tulos['syntymavuosi']; ?>" /> <span class="error"><?php echo $error['muokkaa']['syntymavuosi']; ?></span></div>
            <input type="button" value="Muokkaa" id="muokkaa"/>
        </form>
    </div>
    <hr />
    <div id="kayttajanhallinta">
        <h4>Hallitse käyttäjää</h4>
        <div class="floatleft">
            <form id="estaform" aciton="<?php echo $_SERVER['PHP_SELF'] . "?".  get_to_string(); ?>" method="post">
                <input type="hidden" name="tunnusid" value="<?php echo $kayttajatid; ?>" />
                <input type="hidden" name="ohjaa" value="12" />
                <input type="button" value="<?php echo($tulos['estetty'] ? "Poista esto" : "Estä"); ?>" id="esta"/>
            </form>
        </div>
        <?php
        if (!$tulos['enabled'] && trim($tulos['email']) != "") {
            ?>
            <div class="floatleft">
                <form id="uusivahvistusviestiform" aciton="<?php echo $_SERVER['PHP_SELF'] . "?".  get_to_string(); ?>" method="post">
                    <input type="hidden" name="tunnusid" value="<?php echo $kayttajatid; ?>" />
                    <input type="hidden" name="ohjaa" value="84" />
                    <input type="submit" value="Lähetä uusi vahvistus viesti"/>
                </form>
            </div>
            <?php
        }
        ?>
        <div class="clear"></div>
    </div>
</div>
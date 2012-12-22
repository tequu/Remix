<hr />
<div id="kayttajantiedot">
    <span id="bold">Tunnus: </span><?php echo $tulos['login'] . ($tulos['isadmin'] != "Perus" ? "(" . $tulos['isadmin'] . ")" : "") . ($tulos['estetty'] ? "(Estetty)" : ""); ?><br />
    <span id="bold">Sähköposti: </span><?php echo $tulos['email'] . ($tulos['enabled'] ? "" : "(Vahvistamaton)"); ?><br />
    <span id="bold">Nimi: </span><?php echo $tulos['etunimi'] . " " . $tulos['sukunimi']; ?><br />
    <span id="bold">Syntymävuosi: </span><?php echo $tulos['syntymavuosi']; ?><br />
    <form name="kayttajanestojapoisto" aciton="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=".$okayttajat."&mode=muokkaa&kayttajatid=" . $kayttajatid; ?>" method="post">
        <input type="hidden" name="ohjaa" id="ohjaa" />
        <input type="button" value="<?php echo($tulos['estetty'] ? "Poista esto" : "Estä"); ?>" onclick="laheta('kayttajanestojapoisto',['ohjaa'],['12'])" />
    </form>
</div>
<?php
$valikko = array(
    array($ojohtokunta, "Johtokunnan hallinta", array("admin" => true, "joukkue" => false, "kirjautunut" => false)),
    array($ojoukkue, "Joukkueiden hallinta", array("admin" => false, "joukkue" => true, "kirjautunut" => false)),
    array($okeskustelualue, "Keskustelualueiden hallinta", array("admin" => false, "joukkue" => false, "kirjautunut" => false)),
    array($okeskustelualueryhma, "Keskustelualueiden ryhmien hallinta", array("admin" => false, "joukkue" => false, "kirjautunut" => false)),
    array($okuvat, "Kuvien hallinta", array("admin" => true, "joukkue" => true, "kirjautunut" => false)),
    array($okayttajat, "Käyttäjien hallinta", array("admin" => false, "joukkue" => false, "kirjautunut" => false)),
    array($oomattiedot, "Omien tietojen hallinta", array("admin" => true, "joukkue" => true, "kirjautunut" => true)),
    array($opelaajat, "Pelaajien hallinta", array("admin" => false, "joukkue" => true, "kirjautunut" => false)),
    array($opelit, "Pelien hallinta", array("admin" => false, "joukkue" => true, "kirjautunut" => false)),
    array($osarjataulukko, "Sarjataulukkojen hallinta", array("admin" => false, "joukkue" => true, "kirjautunut" => false)),
    array($otiedotus, "Tiedotusten hallinta", array("admin" => false, "joukkue" => true, "kirjautunut" => false)),
    array($otilastot, "Tilastojen hallinta", array("admin" => false, "joukkue" => true, "kirjautunut" => false)),
    array($ouutiset, "Uutisten hallinta", array("admin" => true, "joukkue" => true, "kirjautunut" => false)),
    array($oyhteyshenkilot, "Yhteyshenkilöiden hallinta", array("admin" => false, "joukkue" => true, "kirjautunut" => false))
);
$oikeudet = array("masteradmin" => false, "admin" => false, "joukkue" => false);

if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
    $oikeudet["masteradmin"] = true;
} else {
    if (tarkistaAdminOikeudet($yhteys, "Admin")) {
        $oikeudet["admin"] = true;
    }
    if (tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0)) {
        $oikeudet["joukkue"] = true;
    }
}
?>
<div id="ohjauspaneelin_valinta_valikko">
    <select id="ohjauspaneeli_valikko">
        <?php
        foreach ($valikko as $a) {
            if ($oikeudet["masteradmin"] || ($a[2]["admin"] && $oikeudet["admin"]) || ($a[2]["joukkue"] && $oikeudet["joukkue"]) || ($a[2]["kirjautunut"])) {
                echo "<option value=\"" . $a[0] . "\"" . ($_GET['sivuid'] == $a[0] ? " SELECTED" : "") . ">" . $a[1] . "</option>\n";
            }
        }
        ?>
    </select>
</div>
<?php

//Lis�� hallinta oikeudet k�ytt�j�lle
function lisaaHallintaOikeudet($yhteys) {
    global $error, $okayttajat;
    tarkistaOikeudet($yhteys, "admin", "MasterAdmin", "Sinulla ei ole oikeuksia antaa oikeuksia");
    $tunnusid = mysql_real_escape_string($_POST['tunnusid']);
    $oikeudet = json_decode(str_replace("\\", "", $_POST['oikeudet']))->{"oikeudet"};
    //Tarkistetaan tunnuksen olemassa olo
    if (!tarkistaTunnuksenOlemassaOlo($yhteys, $tunnusid)) {
        $_SESSION['virhe'] = "K�ytt�j�� ei l�ytynyt.";
        siirry("virhe.php");
    }
    //Jos saatiin oikeuksia mit� halutaan antaa
    if (!empty($oikeudet)) {
        //Luodaan kaikista yksi sql-lause
        $sql = "INSERT INTO oikeudet (" . ($_POST['ohjaa'] == 14 ? "keskustelualueetID" : "joukkueetID") . ", tunnuksetID) VALUES ";
        foreach ($oikeudet as $oikeus) {
            $oikeus = mysql_real_escape_string($oikeus);
            $sql .= "('" . $oikeus . "', '" . $tunnusid . "'), ";
        }
        $sql = substr($sql, 0, -2);
        kysely($yhteys, $sql);
    }
    ohjaaOhajuspaneeliin($okayttajat, "&mode=muokkaa&kayttajatid=" . $tunnusid);
}

//Lis�� admin oikeudet
function lisaaAdminOikeudet($yhteys) {
    global $okayttajat;
    $kayttajatid = mysql_real_escape_string($_GET['kayttajatid']);
    $oikeudet = mysql_real_escape_string($_POST['oikeudet']);
    //Tarkistetaan ett� on oikean tasoiset oikeudet
    if (($oikeudet == "Admin" && tarkistaAdminOikeudet($yhteys, "Admin")) || ($oikeudet == "Masteradmin" && tarkistaAdminOikeudet($yhteys, "Masteradmin"))) {
        kysely($yhteys, "UPDATE tunnukset SET isadmin='" . $oikeudet . "' WHERE id='" . $kayttajatid . "'");
    } else {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata k�ytt�jien oikeuksia.";
        siirry("eioikeuksia.php");
    }
    ohjaaOhajuspaneeliin($okayttajat, "&mode=muokkaa&kayttajatid=" . $kayttajatid);
}

function poistaNakyvyysOikeudet($yhteys) {
    global $okayttajat;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa k�ytt�jien oikeuksia.";
        siirry("eioikeuksia.php");
    }
    $kayttajatid = mysql_real_escape_string($_GET['kayttajatid']);
    //Tarkistetaan ett� tunnus on olemassa
    tarkistaTunnuksenOlemassaOlo($yhteys, $kayttajatid);
    $oikeudet = $_POST['poistettavatoikeudet'];
    //Poistetaan valitut oikeudet
    foreach ($oikeudet as $oikeus) {
        $oikeus = mysql_real_escape_string($oikeus);
        kysely($yhteys, "DELETE FROM keskustelualueoikeudet WHERE keskustelualueetID='" . $oikeus . "' AND tunnuksetID='" . $kayttajatid . "'");
    }
    ohjaaOhajuspaneeliin($okayttajat, "&mode=muokkaa&kayttajatid=" . $kayttajatid);
}

function poistaHallintaOikeudetKeskustelualueelle($yhteys) {
    global $okayttajat;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa k�ytt�jien oikeuksia.";
        siirry("eioikeuksia.php");
    }
    $kayttajatid = mysql_real_escape_string($_GET['kayttajatid']);
    tarkistaTunnuksenOlemassaOlo($yhteys, $kayttajatid);
    $oikeudet = $_POST['poistettavatoikeudet'];
    //Poistetaan valitut oikeudet
    foreach ($oikeudet as $oikeus) {
        $oikeus = mysql_real_escape_string($oikeus);
        kysely($yhteys, "DELETE FROM oikeudet WHERE keskustelualueetID='" . $oikeus . "' AND tunnuksetID='" . $kayttajatid . "'"); 
    }
    ohjaaOhajuspaneeliin($okayttajat, "&mode=muokkaa&kayttajatid=" . $kayttajatid);
}

function poistaHallintaOikeudetJoukkueeseen($yhteys) {
    global $okayttajat;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa oikeuksia k�ytt�jilt�.";
        siirry("eioikeuksia.php");
    }
    $kayttajatid = mysql_real_escape_string($_GET['kayttajatid']);
    tarkistaTunnuksenOlemassaOlo($yhteys, $kayttajatid);
    $oikeudet = $_POST['oikeudet'];
    //Poistetaan valitut oikeudet
    if (!empty($oikeudet)) {
        foreach ($oikeudet as $oikeus) {
            $oikeus = mysql_real_escape_string($oikeus);
            kysely($yhteys, "DELETE FROM oikeudet WHERE tunnuksetID='" . $kayttajatid . "' AND joukkueetID='" . $oikeus . "'");
        }
        ohjaaOhajuspaneeliin($okayttajat, "&mode=muokkaa&kayttajatid=" . $kayttajatid);
    }
}

function poistaAdminOikeudet($yhteys) {
    global $okayttajat;
    $kayttajatid = mysql_real_escape_string($_GET['kayttajatid']);
    $oikeudet = mysql_real_escape_string($_POST['oikeudet']);
    //Tarkistetaan ett� on oikeudet poistaa oikeudet
    if (($oikeudet == "Admin" && tarkistaAdminOikeudet($yhteys, "Admin")) || ($oikeudet == "Masteradmin" && tarkistaAdminOikeudet($yhteys, "Masteradmin"))) {
        kysely($yhteys, "UPDATE tunnukset SET isadmin='Perus' WHERE id='" . $kayttajatid . "'");
        ohjaaOhajuspaneeliin($okayttajat, "&mode=muokkaa&kayttaja=" . $kayttajatid);
    }
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa k�ytt�jien oikeuksia.";
    siirry("eioikeuksia.php");
}

?>

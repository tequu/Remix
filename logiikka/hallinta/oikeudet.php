<?php

//Lisää hallinta oikeudet käyttäjälle
function lisaaHallintaOikeudet($yhteys) {
    global $error, $okayttajat;
    tarkistaOikeudet($yhteys, "admin", "MasterAdmin", "Sinulla ei ole oikeuksia antaa oikeuksia");
    $tunnusid = mysql_real_escape_string($_POST['tunnusid']);
    $oikeudet = json_decode(str_replace("\\", "", $_POST['oikeudet']))->{"oikeudet"};
    //Tarkistetaan tunnuksen olemassa olo
    if (!tarkistaTunnuksenOlemassaOlo($yhteys, $tunnusid)) {
        $_SESSION['virhe'] = "Käyttäjää ei löytynyt.";
        siirry("virhe.php");
    }
    //Jos saatiin oikeuksia mitä halutaan antaa
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

//Lisää admin oikeudet
function lisaaAdminOikeudet($yhteys) {
    global $okayttajat;
    $kayttajatid = mysql_real_escape_string($_GET['kayttajatid']);
    $oikeudet = mysql_real_escape_string($_POST['oikeudet']);
    //Tarkistetaan että on oikean tasoiset oikeudet
    if (($oikeudet == "Admin" && tarkistaAdminOikeudet($yhteys, "Admin")) || ($oikeudet == "Masteradmin" && tarkistaAdminOikeudet($yhteys, "Masteradmin"))) {
        kysely($yhteys, "UPDATE tunnukset SET isadmin='" . $oikeudet . "' WHERE id='" . $kayttajatid . "'");
    } else {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia muokata käyttäjien oikeuksia.";
        siirry("eioikeuksia.php");
    }
    ohjaaOhajuspaneeliin($okayttajat, "&mode=muokkaa&kayttajatid=" . $kayttajatid);
}

function poistaNakyvyysOikeudet($yhteys) {
    global $okayttajat;
    if (!tarkistaAdminOikeudet($yhteys, "Admin")) {
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa käyttäjien oikeuksia.";
        siirry("eioikeuksia.php");
    }
    $kayttajatid = mysql_real_escape_string($_GET['kayttajatid']);
    //Tarkistetaan että tunnus on olemassa
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
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa käyttäjien oikeuksia.";
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
        $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa oikeuksia käyttäjiltä.";
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
    //Tarkistetaan että on oikeudet poistaa oikeudet
    if (($oikeudet == "Admin" && tarkistaAdminOikeudet($yhteys, "Admin")) || ($oikeudet == "Masteradmin" && tarkistaAdminOikeudet($yhteys, "Masteradmin"))) {
        kysely($yhteys, "UPDATE tunnukset SET isadmin='Perus' WHERE id='" . $kayttajatid . "'");
        ohjaaOhajuspaneeliin($okayttajat, "&mode=muokkaa&kayttaja=" . $kayttajatid);
    }
    $_SESSION['eioikeuksia'] = "Sinulla ei ole oikeuksia poistaa käyttäjien oikeuksia.";
    siirry("eioikeuksia.php");
}

?>

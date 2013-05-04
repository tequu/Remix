<?php

//Funktio suorittaa halutun funktion joka saatiin $_POST parametrinä
function ohjaa($yhteys) {
    if ($_POST['ohjaa'] == 0)
//Rekisteroi
        rekisteroi($yhteys);
    if ($_POST['ohjaa'] == 1)
//Kirjaudu
        kirjaudu($yhteys);
    if ($_POST['ohjaa'] == 2)
//Uusi keskustelualue
        uusiKeskustelualue($yhteys);
    if ($_POST['ohjaa'] == 3)
//Uusi keskustelu
        uusiKeskustelu($yhteys);
    if ($_POST['ohjaa'] == 4)
//Uusi viesti
        uusiViesti($yhteys);
    if ($_POST['ohjaa'] == 5)
//Uusi keskustelualue ryhmä
        uusiKeskustelualueRyhma($yhteys);
    if ($_POST['ohjaa'] == 6)
//Muokkaa keskustelualueen ryhmän nimeä
        muokkaaKeskustelualueenRyhmanNimea($yhteys);
    if ($_POST['ohjaa'] == 7)
//Poista keskustelualue ryhmä
        poistaKeskustelualueRyhma($yhteys);
    if ($_POST['ohjaa'] == 8)
//Uusi keskustelualue
        uusiKeskustelualue($yhteys);
    if ($_POST['ohjaa'] == 9)
//Muokkaa keskustelualueen tietoja
        muokkaaKeskustelualuetta($yhteys);
    if ($_POST['ohjaa'] == 10)
//Poista keskustelualue
        poistaKeskustelualue($yhteys);
    if ($_POST['ohjaa'] == 11)
//Siirrä keskustelualue toiseen ryhmään
        siirraKeskustelualue($yhteys);
    if ($_POST['ohjaa'] == 12)
//Estää tai poistaa eston käyttäjältä
        vaihdaEsto($yhteys);
    if ($_POST['ohjaa'] == 13)
//Lisää näkyvyys oikeudet käyttäjälle
        lisaaNakyvyysOikeudet($yhteys);
    if ($_POST['ohjaa'] == 14 || $_POST['ohjaa'] == 15)
//Lisää hallinta oikeudet käyttäjälle
        lisaaHallintaOikeudet($yhteys);
    if ($_POST['ohjaa'] == 16)
//Lisää admin oikeudet
        lisaaAdminOikeudet($yhteys);
    if ($_POST['ohjaa'] == 17)
//Lisää uusi joukkue
        lisaaJoukkue($yhteys);
    if ($_POST['ohjaa'] == 18)
//Muokkaa joukkueen tietoja
        muokkaaJoukkueenTietoja($yhteys);
    if ($_POST['ohjaa'] == 19)
//Poista joukkue
        poistaJoukkue($yhteys);
    if ($_POST['ohjaa'] == 20)
//Lisää jasen joukkueeseen
        lisaaPelaajat($yhteys);
    if ($_POST['ohjaa'] == 21)
//Lisää uusi jasen joukkueeseen
        lisaaUusiPelaajaJoukkueeseen($yhteys);
    if ($_POST['ohjaa'] == 22)
//Muokkaa joukkueen jasenen tietoja
        muokkaaPelaaja($yhteys);
    if ($_POST['ohjaa'] == 23)
//Poista joukkueen jasen
        poistaPelaaja($yhteys);
    if ($_POST['ohjaa'] == 24)
//Liitä henkilö joukkueen jäseneksi
        liitaHenkiloJoukkueeseen($yhteys);
    if ($_POST['ohjaa'] == 25)
//Muokkaa käyttäjän omat tiedot
        muokkaaOmatTiedot($yhteys);
    if ($_POST['ohjaa'] == 26)
//Uusi keskustelu
        uusiKeskustelu($yhteys);
    if ($_POST['ohjaa'] == 27)
//Uusi tapahtuma
        uusiTapahtuma($yhteys);
    if ($_POST['ohjaa'] == 28)
//Muokkaa viestiä
        muokkaaViestia($yhteys);
    if ($_POST['ohjaa'] == 29)
//Poista viesti
        poistaViesti($yhteys);
    if ($_POST['ohjaa'] == 30)
//Poista näkyvyysoikudet
        poistaNakyvyysOikeudet($yhteys);
    if ($_POST['ohjaa'] == 31)
//Poista hallinta oikeudet keskustelualueelle
        poistaHallintaOikeudetKeskustelualueelle($yhteys);
    if ($_POST['ohjaa'] == 32)
//Poista hallinta oikeudet joukkueeseen
        poistaHallintaOikeudetJoukkueeseen($yhteys);
    if ($_POST['ohjaa'] == 33)
//Poista admin oikeudet
        poistaAdminOikeudet($yhteys);
    if ($_POST['ohjaa'] == 34)
//Poista keskustelu
        poistaKeskustelu($yhteys);
    if ($_POST['ohjaa'] == 35) {
        luoSarjataulukko($yhteys);
    }
    if ($_POST['ohjaa'] == 36) {
        muokkaaSarjataulukko($yhteys);
    }
    if ($_POST['ohjaa'] == 37) {
        lisaaJoukkueSarjataulukkoon($yhteys);
    }
    if ($_POST['ohjaa'] == 38) {
        poistaSarjataulukko($yhteys);
    }
    if ($_POST['ohjaa'] == 39) {
        poistaJoukkueSarjataulukosta($yhteys);
    }
    if ($_POST['ohjaa'] == 40) {
        siirraJoukkuettaSarjataulukossa($yhteys);
    }
    if ($_POST['ohjaa'] == 41) {
        lisaaTiedotus($yhteys);
    }
    if ($_POST['ohjaa'] == 42) {
        muokkaaTiedotusta($yhteys);
    }
    if ($_POST['ohjaa'] == 43) {
        poistaTiedotus($yhteys);
    }
    if ($_POST['ohjaa'] == 44) {
        luoPeliryhma($yhteys);
    }
    if ($_POST['ohjaa'] == 45) {
        lisaaPeli($yhteys);
    }
    if ($_POST['ohjaa'] == 46) {
        muokkaaPelia($yhteys);
    }
    if ($_POST['ohjaa'] == 47) {
        lisaaTulos($yhteys);
    }
    if ($_POST['ohjaa'] == 48) {
        poistaTulos($yhteys);
    }
    if ($_POST['ohjaa'] == 49) {
        muokkaaPeliryhmanNimea($yhteys);
    }
    if ($_POST['ohjaa'] == 50) {
        poistaPeliryhma($yhteys);
    }
    if ($_POST['ohjaa'] == 51) {
        lisaaUutinen($yhteys);
    }
    if ($_POST['ohjaa'] == 52) {
        muokkaaUutinen($yhteys);
    }
    if ($_POST['ohjaa'] == 53) {
        lisaaJohtokuntaanJasen($yhteys);
    }
    if ($_POST['ohjaa'] == 54) {
        muokkaaJohtokunnanJasenta($yhteys);
    }
    if ($_POST['ohjaa'] == 55) {
        poistaJasenJohtokunnasta($yhteys);
    }
    if ($_POST['ohjaa'] == 56) {
        luoTilasto($yhteys);
    }
    if ($_POST['ohjaa'] == 57) {
        lisaaUusiPelaajaTilastoon($yhteys);
    }
    if ($_POST['ohjaa'] == 58) {
        lisaaPelaajaTilastoon($yhteys);
    }
    if ($_POST['ohjaa'] == 59) {
        muokkaaTilastonPelaaja($yhteys);
    }
    if ($_POST['ohjaa'] == 60) {
        poistaPelaajaTilastosta($yhteys);
    }
    if ($_POST['ohjaa'] == 61) {
        muokkaaTilastoa($yhteys);
    }
    if ($_POST['ohjaa'] == 62) {
        poistaTilasto($yhteys);
    }
    if ($_POST['ohjaa'] == 63) {
        lisaaKuvaKategoria($yhteys);
    }
    if ($_POST['ohjaa'] == 64) {
        muokkaaKuvaKategoriaa($yhteys);
    }
    if ($_POST['ohjaa'] == 65) {
        poistaKuvaKategoria($yhteys);
    }
    if ($_POST['ohjaa'] == 66) {
        lisaaKuvaKuvakategoriaan($yhteys);
    }
    if ($_POST['ohjaa'] == 67) {
        muokkaaKuvaKategorianKuvaa($yhteys);
    }
    if ($_POST['ohjaa'] == 68) {
        poistaKuvaKategorianKuva($yhteys);
    }
    if ($_POST['ohjaa'] == 69) {
        lisaaUusiYhteyshenkilo($yhteys);
    }
    if ($_POST['ohjaa'] == 70) {
        lisaaYhteyshenkilo($yhteys);
    }
    if ($_POST['ohjaa'] == 71) {
        muokkaaYhteyshenkiloa($yhteys);
    }
    if ($_POST['ohjaa'] == 72) {
        poistaYhteyshenkilo($yhteys);
    }
    if ($_POST['ohjaa'] == 73) {
        lisaaPoytakirja($yhteys);
    }
    if ($_POST['ohjaa'] == 74) {
        vaihdasalasana($yhteys);
    }
    if ($_POST['ohjaa'] == 75) {
        uusiViestiVieraskirjaan($yhteys);
    }
    if ($_POST['ohjaa'] == 76) {
        unohtunutsalasana($yhteys);
    }
    if ($_POST['ohjaa'] == 77) {
        aktivoi($yhteys);
    }
    if ($_POST['ohjaa'] == 78) {
        poistaPeli($yhteys);
    }
    if ($_POST['ohjaa'] == 79) {
        poistaUutisenKuva($yhteys, TRUE);
    }
    if ($_POST['ohjaa'] == 80) {
        muokkaaTapahtumaa($yhteys);
    }
    if($_POST['ohjaa'] == 81){
        poistaTapahtuma($yhteys);
    }
    if($_POST['ohjaa'] == 82){
        muokkaaKeskustelua($yhteys);
    }
}

function tarkistaOnkoJoukkueNykysellaKaudella($yhteys, $joukkue) {
    global $kausi;
    $kysely = kysely($yhteys, "SELECT id FROM joukkueet WHERE id='" . $joukkue . "' AND kausi='" . $kausi . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        return true;
    }
    return false;
}

function tarkistaOnkoPelaajaJoTilastossa($yhteys, $pelaaja, $tilastoryhmatid) {
    $kysely = kysely($yhteys, "SELECT tunnuksetID FROM tilastot WHERE tilastoryhmatID='" . $tilastoryhmatid . "' AND tunnuksetID='" . $pelaaja . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        return true;
    }
    return false;
}

function haeTilastonNimi($yhteys, $tilastoryhmatid) {
    $tilastoryhmatid = mysql_real_escape_string($tilastoryhmatid);
    $kysely = kysely($yhteys, "SELECT nimi FROM tilastoryhmat WHERE id='" . $tilastoryhmatid . "'");
    $tulos = mysql_fetch_array($kysely);
    return $tulos['nimi'];
}

//Hakee annettua id:tä vastaavan joukkueen nimen
function haeJoukkueenNimi($yhteys, $joukkueid) {
    $joukkueid = mysql_real_escape_string($joukkueid);
    $kysely = kysely($yhteys, "SELECT nimi FROM joukkueet WHERE id='" . $joukkueid . "'");
    $tulos = mysql_fetch_array($kysely);
    return $tulos['nimi'];
}

//Tarkistetaan onko keskustelualue julkinen
function tarkistaOnkoJulkinenKeskustelualue($yhteys, $keskustelualue) {
    $keskustelualue = mysql_real_escape_string($keskustelualue);
    $kysely = kysely($yhteys, "SELECT id FROM keskustelualueet WHERE julkinen='1' AND id='" . $keskustelualue . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        return true;
    }
    return false;
}

//Tarkistetaan tapahtuman olemassa olo
function tarkistaTapahtumanOlemassaOlo($yhteys, $tapahtuma) {
    $tapahtuma = mysql_real_escape_string($tapahtuma);
    $kysely = kysely($yhteys, "SELECT id FROM tapahtumat WHERE id='" . $tapahtuma . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        return true;
    }
    return false;
}

//Verrataan aikoja kumpi tulee ennemmin
function vertaaAjat($aika, $verrattava) {
    if (mktime(0, 0, 0, $aika[1], $aika[0], $aika[2]) < $verrattava) {
        return false;
    }
    return true;
}

//Suorittaa kyselyn tietokannasta, tulostaa virheen ja yritetyn kyselyn jos epäonnistui
function kysely($yhteys, $kysely) {
    $tulos = mysql_query($kysely) or die("Kysely epäonnistui: " . $kysely . ": " . mysql_error());
    return $tulos;
}

//Tulostaa option valinnat select valikkoon
function tulostaValinnat($arvot) {
    foreach ($arvot as $arvo => $nimi) {
        echo "<option value=\"" . $arvo . "\">" . $nimi . "</option>";
    }
}

//Ohjaa käyttäjän ohjaupaneeliin
function ohjaaOhajuspaneeliin($sivuid, $get) {
    siirry("ohjauspaneeli.php?sivuid=" . $sivuid . $get);
}

//Ohjaa käyttäjän joko php header funktiolla, jos ei vielä lähetetty headeria, halutulle sivulle tai muute javasciptillä
function siirry($osoite) {
    global $headers;
    if ($headers) {
        echo "<script>siirry(\"/Remix/" . $osoite . "\")</script>";
    } else {
        header("Location: /Remix/" . $osoite);
    }
    echo "Sivusto vaatii toimiakseen javascriptin!";
    die();
}

//Luo halutun pituisen, satunnaisen merkkijonon
function luoSatunnainenMerkkijono($pituus) {
    $merkit = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!#%&?";
    $merkkijono = "";
    for ($i = 0; $i < $pituus; $i++) {
        $merkkijono .= $merkit[rand(0, strlen($merkit) - 1)];
    }
    return $merkkijono;
}

//Muuntaa ajan tietokanta muotoon
function paivamaaraTietokantaMuotoon($aika) {
    return $aika[2] . "-" . $aika[1] . "-" . $aika[0] . " " . $aika[3] . ":" . $aika[4] . ":00";
}

//Tarkistaa perus tiedot, ettei ne ole tyhjiä ja että syntymä aika on korkeintaan sata vuotta taakse päin ja enintään meneillä oleva vuosi
function tarkistaPerusTiedot($etunimi, $sukunimi, $syntymavuosi) {
    global $error;
    $aika = getdate();
    if (empty($etunimi))
        $error .= "Etunimi kenttä on tyhjä<br />";
    if (empty($sukunimi))
        $error .= "Sukunimi kenttä on tyhjä<br />";
    if (empty($syntymavuosi) || $syntymavuosi < ($aika['year'] - 100) || $syntymavuosi > $aika['year'])
        $error .= "Syntymävuosi on virheellinen<br />";
    if (!empty($error)) {
        return false;
    }
    return true;
}

//Tarkistaa ettei sana sisällä ÅÄÖåäö-kirjaimia
function tarkistaEiSallitutMerkit($sana) {
    return preg_match("/[ÅÄÖåäö]/", $sana);
}

//Tulostaa kolme select valikkoa joista päivät, kuukaudet ja vuodet
function tulostaAjanValinta($lisake) {
    $aika = getdate();
    echo"Päivä:<select name=\"paiva" . $lisake . "\">";
    for ($i = 1; $i <= 31; $i++) {
        echo"<option value=\"" . $i . "\">" . $i . "</option>";
    }
    echo"</select><select name=\"kuukausi" . $lisake . "\">";
    for ($i = 1; $i <= 12; $i++) {
        echo"<option value=\"" . $i . "\">" . $i . "</option>";
    }
    echo"</select><select name=\"vuosi" . $lisake . "\">";
    for ($i = $aika['year']; $i < $aika['year'] + 1; $i++) {
        echo"<option value=\"" . $i . "\">" . $i . "</option>";
    }
    echo"</select><br />Aika:<select name=\"tunti" . $lisake . "\">";
    for ($i = 0; $i <= 23; $i++) {
        echo"<option value=\"" . $i . "\">" . $i . "</option>";
    }
    echo"</select><select name=\"minuutit" . $lisake . "\">";
    for ($i = 0; $i <= 59; $i++) {
        echo"<option value=\"" . $i . "\">" . $i . "</option>";
    }
    echo"</select>";
}

//tulostaa pelatut kaudet
function tulostaPelatutKaudet($kaudet, $nimi) {
    if (!empty($kaudet)) {
        echo $nimi . ":<br />";
        foreach ($kaudet as $a => $b) {
            echo "<b>" . $a . "</b>: ";
            for ($x = 0; $x < count($b); $x++) {
                echo ucfirst($b[$x]);
                if ($x < count($b) - 1)
                    echo", ";
            }
            echo"<br />";
        }
    }
}

//etsii löytyykö annettu arvo taulusta
function loytyyko($taulu, $etsi) {
    foreach ($taulu as $arvo)
        if ($arvo == $etsi)
            return true;
    return false;
}

//palauttaa merkkijonona GET sisällön
function get_to_string($pois) {
    $i = 0;
    $palautettava = "";
    foreach ($_GET as $key => $value) {
        if ($pois == null || !in_array($key, $pois)) {
            if ($i != 0)
                $palautettava .= "&";
            $palautettava .= $key . "=" . $value;
            $i++;
        }
    }
    return $palautettava;
}

function tulosta_get_input($pois) {
    foreach ($_GET as $avain => $arvo) {
        if ($pois == null || !in_array($avain, $pois)) {
            echo "<input type=\"hidden\" name=\"" . $avain . "\" value=\"" . $arvo . "\" />";
        }
    }
}

//muuntaa merkit XML muotoon
function muunnaTeksiXmlMuotoon($teksti) {
    $teksti = str_replace("<", "&lt;", $teksti);
    $teksti = str_replace(">", "&gt;", $teksti);
    $teksti = str_replace("&", "&amp;", $teksti);
    $teksti = str_replace("'", "&apos;", $teksti);
    $teksti = str_replace('"', '&quot;', $teksti);
    return $teksti;
}

//vieraskirjan viestit
function uusiViestiVieraskirjaan($yhteys) {
    global $error;
    $nimi = mysql_real_escape_string($_POST['nimi']);
    $viesti = $_POST['viesti'];
    $seura = mysql_real_escape_string($_POST['seura']);
    $joukkue = mysql_real_escape_string($_POST['joukkue']);
    $ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
    if (empty($nimi))
        $error .= "*Nimimerkki kenttä on tyhjä<br />";
    if (isset($_SESSION['id'])) {
        $enabled = 1;
        $id = $_SESSION['id'];
    } else {
        $email = mysql_real_escape_string($_POST['email']);
        if (empty($email))
            $error .= "*Sähköposti kenttä on tyhjä<br />";
        $token = md5(microtime());
        $enabled = 0;
    }
    if (empty($viesti))
        $error .= "*Viesti kenttä on tyhjä<br />";
    if (!empty($error))
        return false;
    $viesti = stripslashes($viesti);
    $viesti = htmlspecialchars($viesti);
    $viesti = str_replace("\n", "<br />", $viesti);
    $kirosanat = array('putain', 'ptain', 'klote', 'kIote',
        'arschl', 'wichs', 'fick', 'fikk', 'siktirgit', 'gvd',
        'hitler', 'nutte', 'dick', 'cock', 'faitchier', 'bordel', 'shit',
        'encul', 'sucks', 'a.q', 'conerie', 'scheise', 'scheis',
        'baskasole', 'cocugu', 'kodugumun', 'cazo', 'hoer', 'bitch',
        'penis', 'fotze', 'frese', 'pizda', 'gay', 'fuck', 'tyfus',
        'sugi', 'cacat', 'pisat', 'labagiu', 'gaozar',
        'pédé', 'cunt', 'godve', 'godfe', 'kudt',
        'apara', 'vittu', 'saatana',
        'boobs', 'idioot', 'idiot', 'idiootti',
        'perkele', 'homo', 'piru', 'kyrpa', 'kulli', 'helvetti', 'helveti',
        'mulkku', 'runkkari', 'kakka', 'pylly', 'nekru', 'neekeri', 'tussu',
        'ryssa', 'hintti', 'psk', 'lutka', 'negro',
        'pussy', 'noob', 'paska', 'pillu', 'huora', 'runkku', 'perse', 'helkkari',
        'asshole', 'vitu', 'tuhero', 'hoora',
        'hinti', 'nigga', 'prkl', 'kristus');
    for ($i = 0; $i < count($kirosanat); $i++) {
        $nimi = str_replace($kirosanat[$i], "", $nimi);
        $viesti = str_replace($kirosanat[$i], "", $viesti);
        $seura = str_replace($kirosanat[$i], "", $seura);
        $joukkue = str_replace($kirosanat[$i], "", $joukkue);
    }
//otetaan aika talteen
    $viesti = mysql_real_escape_string($viesti);
    $aikaleima = time();
    $aika = date("Y-m-d H:i", $aikaleima);
    $kysely = kysely($yhteys, "INSERT INTO vieraskirja (kirjoittaja, email, aika, seura, joukkue, viesti, ip, poistettu, enabled, tunnuksetID) VALUES " .
            "('" . $nimi . "', '" . $email . "', '" . $aika . "', '" . $seura . "', '" . $joukkue . "', '" . $viesti . "', '" . $ip . "', '0', '" . $enabled . "', '" . $id . "')");
    if (!isset($_SESSION['id'])) {
        $asetettuid = mysql_insert_id();
        $kysely = kysely($yhteys, "INSERT INTO tokenit (token, vieraskirjaID) VALUES ('" . $token . "', '" . $asetettuid . "')");
        $linkki = "http://www.fbcremix.com/Remix/vahvista.php?viestiid=" . $asetettuid . "&token=" . $token;
        $sahkoposti = "Ole hyvä ja vahvista viestisi vieraskirjaan seuraavasta linkistä. " . $linkki . "\n\nTämä on automaattinen viesti, älä vastaa tähän.";
        mail($email, "FBC Remixin vieraskirjan viestin vahvistus", $sahkoposti);
    }
    siirry("vieraskirja.php");
}

function unohtunutsalasana($yhteys) {
    global $error;
    $merkit = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10');
    $email = mysql_real_escape_string(trim($_POST['email']));
    $uusisalasana = "";
    $kysely = kysely($yhteys, "SELECT id FROM tunnukset WHERE email='" . $email . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        for ($i = 0; $i < 8; $i++)
            $uusisalasana .= $merkit[rand(0, (count($merkit) - 1))];
        $uusisalasana2 = $uusisalasana;
        $uusisalasana = crypt($uusisalasana);
        $kysely = kysely($yhteys, "UPDATE tunnukset SET salasana='" . $uusisalasana . "' WHERE id='" . $tulos['id'] . "'");
        $viesti = "Uusi salasanasi FBC Remixin sivuille on " . $uusisalasana2 . "\n
		Vaihda se mahdollisimman pian toiseen.";
        mail($email, "Uusi salasanasi", $viesti);
        siirry("index.php");
    }
    $error = "*Sähköpostiosoitetta ei löydy";
    return false;
}

function tulostaSeuranTiedotukset($yhteys) {
    global $kausi;
//ensin tulostetaan seuran tiedotukset
    echo"<div id=\"tiedotus\">
	<div id=\"etusivu_otsikko\">Seuran Tiedoitukset</div>";
//otetaan aika talteen
    $aika_nyt = time();
    $aika_nyt -= (60 * 60 * 24);
//haetaan tiedotus ja kirjoitusaika niistä viesteistä joilla ei ole vanhenemis aikaa tai vanhenemisaika ei ole ummessa ja joukkueID = 0
    $kysely = kysely($yhteys, "SELECT tiedotus,UNIX_TIMESTAMP(kirjoitusaika) AS kirjoitusaika FROM tiedotukset " .
            "WHERE (eivanhenemisaikaa='1' OR UNIX_TIMESTAMP(vanhenemisaika)>='" . $aika_nyt . "') AND  joukkueetID='0' ORDER BY kirjoitusaika DESC");

    $tulos = mysql_fetch_array($kysely);
//käydään läpi tiedotukset
    do {
        if (empty($tulos))
            echo "Ei tiedotuksia<br /><hr />";
        else {
            echo"<span";
            if ($tulos['kirjoitusaika'] >= $aika_nyt - 6 * 24 * 60 * 60)
                echo" class='tiedotus_uusi'";
            echo ">" . $tulos['tiedotus'] . "</span><br /><hr />";
        }
    }
    while ($tulos = mysql_fetch_array($kysely));

    echo "</div>";
}

//hakee 5 uusimman uutisen otsikon ja tulostaa ne
function tulostauutisotsikot($yhteys) {
    echo"<div id=\"uutis_otsikot\">
	<div id=\"etusivu_otsikko\">Uutiset</div>";
//haetaan uutisen id, otsikko ja kirjoitusaika, viisi ensimmäistä
    $kysely = kysely($yhteys, "SELECT id, otsikko, kuvaus, UNIX_TIMESTAMP(kirjoitusaika) AS kirjoitusaika FROM uutiset ORDER BY kirjoitusaika DESC LIMIT 0,5");
//käydään uutisotsikot läpi
    if ($tulos = mysql_fetch_array($kysely)) {
        echo "<div id=\"viimeisin_uutisotsikko\"><span id=\"kirjoitusaika\">" . date("d.m.Y H:i", $tulos['kirjoitusaika']) . "</span> <a href=\"uutiset.php?uutisid=" . $tulos['id'] . "\" id=\"otsikko\">" . $tulos['otsikko'] . "</a><div id=\"kuvaus\">" . $tulos['kuvaus'] . "</div></div>";
        while ($tulos = mysql_fetch_array($kysely))
            echo "<div id=\"aikaisemmat_uutisotsikot\"><span id=\"kirjoitusaika\">" . date("d.m.Y H:i", $tulos['kirjoitusaika']) . "</span> <a href=\"/uutiset.php?uutisid=" . $tulos['id'] . "\" id=\"otsikko\">" . $tulos['otsikko'] . "</a></div>";
    }
    echo"</div>";
}

//tulostaa sarjataulukon
function tulostasarjataulukko($kysely, $nimi, $etusivu, $joukkue, $id) {
    global $kausi;
    echo"<table id='sarjataulukko' border='1' cellpadding='1' cellspacing='1'>
		<tr><th colspan='7'>" . $nimi . "</th></tr>
			<tr><td id='sarjataulukko_saraka_joukkue'>Joukkue</td><td id='sarjataulukko_saraka'>O</td><td id='sarjataulukko_saraka'>V</td><td id='sarjataulukko_saraka'>T</td><td id='sarjataulukko_saraka'>H</td><td id='sarjataulukko_saraka_ME'>ME</td><td id='sarjataulukko_saraka'>P</td></tr>";
    if ($tulos = mysql_fetch_array($kysely)) {
        do {
            echo"<tr" . (strtolower($tulos['joukkue']) == 'fbc remix' ? " id='fbcremix'" : "") . "><td>" . $tulos['joukkue'] . "</td><td>" . $tulos['O'] . "</td><td>" . $tulos['V'] . "</td><td>" . $tulos['T'] . "</td><td>" . $tulos['H'] . "</td><td>" . $tulos['TM'] . "- " . $tulos['PM'] . "</td><td>" . $tulos['P'] . "</td></tr>";
        } while ($tulos = mysql_fetch_array($kysely));
    }
    else
        echo"<tr><td colspan='8'>Sarjataulukossa ei ole joukkueita</td></tr>";
    if ($etusivu) {
        $kysely2 = mysql_query("SELECT nimi FROM sarjataulukkonimet WHERE joukkueid='" . $joukkue . "' ORDER BY id") or die("Kysely ei onnistu: " . mysql_error());
        if ($tulos = mysql_fetch_array($kysely2)) {
            $laskuri = 0;
            echo"<tr><td colspan='7'><form action='" . $_SERVER['PHP_SELF'] . ($etusivu ? "?id=" . $id : "?joukkueid=" . $joukkue) . "' method='get'>";
            if ($etusivu)
                echo"<input type='hidden' name='id' value='" . $id . "' />";
            else
                echo"<input type='hidden' name='joukkueid' value='" . $joukkue . "' />";

            echo"<select name='sarjataulukkoid'>";
            do {
                echo"<option value='" . $laskuri . "'";
                if (strtolower($nimi) == strtolower($tulos['nimi']))
                    echo" SELECTED ";
                echo">" . $tulos['nimi'] . "</option>";
                $laskuri++;
            }
            while ($tulos = mysql_fetch_array($kysely2));
            echo"</select><input type='submit' value='Vaihda' /></form></td></tr>";
        }
    }
    echo"</table>";
    return true;
}

function muutaTekstiHyvaksyttavaanMuotoon($merkkijono) {
    $merkkijono = preg_replace("/ä/", "a", $merkkijono);
    $merkkijono = preg_replace("/ö/", "o", $merkkijono);
    $merkkijono = preg_replace("/Ä/", "a", $merkkijono);
    $merkkijono = preg_replace("/Ö/", "o", $merkkijono);
    $merkkijono = preg_replace("/ /", "_", $merkkijono);
    $merkkijono = preg_replace("/\?/", "", $merkkijono);
    $merkkijono = preg_replace("/\//", "", $merkkijono);
    $merkkijono = preg_replace("/!/", "", $merkkijono);
    return $merkkijono;
}

function siirraKuva($kuvanosoite, $maxleveys, $maxkorkeus, $minleveys, $minkorkeus, $minikuvaosoite, $minikuvaleveys, $minikuvakorkeus) {
    global $error;
    $hyvaksytyt = array("image/png", "image/jpeg", "image/jpg", "image/gif");
    if (!in_array($_FILES['kuva']['type'], $hyvaksytyt)) {
        $error = "*Kuvan tiedotomuoto on väärä. Sallittuja on .jpg, .jpeg, .gif ja .png<br />";
        return false;
    }
    if ($_FILES['kuva']['size'] > 1000000) {
        $error = "*Kuvan koko on liian suuri. Maksimi koko on 1Mt<br />";
        return false;
    }
    $koko = getimagesize($_FILES['kuva']['tmp_name']);
    if ($koko[0] > $maxleveys || $koko[1] > $maxkorkeus || $koko[0] < $minleveys || $koko[1] < $minkorkeus) {
        $error = "*Kuvan leveys tai korkeus on liian suuri. Kuva täytyy olla vähintään " . $minleveys . "x" . $minkorkeus . " ja korkeintaan " . $maxleveys . "x" . $maxkorkeus . ".";
        return false;
    }
    luoKansiotJosEiOlemassa($kuvanosoite);
    if (!empty($minikuvaosoite)) {
        luoKansiotJosEiOlemassa($minikuvaosoite);
        luouusikuva($_FILES['kuva']['tmp_name'], $minikuvaosoite, $minikuvaleveys, $minikuvakorkeus);
    }
    if (move_uploaded_file($_FILES['kuva']['tmp_name'], $kuvanosoite)) {
        return true;
    } else {
        $error = "*Kuvan lähetys ei onnistunut<br />";
        return false;
    }
}

function luoKansiotJosEiOlemassa($kansio) {
    $eiluoda = array("", "home", "fbcremix", "public_html", "kuvat", "uutiset", "pelaajakortit", "seura", "sponsorit", "joukkueet", "Remix");
    $kansiot = split("/", $kansio);
    $nykyinen = "";
    for ($i = 1; $i < count($kansiot) - 1; $i++) {
        $nykyinen .= "/" . $kansiot[$i];
        if (!in_array($kansiot[$i], $eiluoda)) {
            if (!is_dir($nykyinen)) {
                mkdir($nykyinen);
                $tiedosto = fopen($nykyinen . "/index.html", "a");
                fclose($tiedosto);
            }
        }
    }
}

function haeJoukkueenTilastoIdt($yhteys, $joukkueid) {
    global $kausi;
    $idt = array();
    $kysely = kysely($yhteys, "SELECT id FROM tilastotnimet WHERE joukkueid='" . $joukkueid . "' AND kausi='" . $kausi . "'");
    while ($tulos = mysql_fetch_array($kysely))
        $idt[] = $tulos['id'];
    return $idt;
}

function tarkistaEttaPelaajaKuuluuJoukkueeseen($yhteys, $joukkue, $pelaaja) {
    global $error, $siirry;
    $joukkue = mysql_real_escape_string($joukkue);
    $pelaaja = mysql_real_escape_string($pelaaja);
    if (!tarkistaTunnuksenOlemassaOlo($yhteys, $pelaaja)) {
        $_SESSION['virhe'] = "Tunnusta ei löytynyt.";
        if ($siirry) {
            siirry("virhe.php");
        }
        $error = $_SESSION['virhe'];
        return false;
    }
    $kysely = kysely($yhteys, "SELECT joukkueetID FROM pelaajat WHERE joukkueetID='" . $joukkue . "' AND tunnuksetID='" . $pelaaja . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        return true;
    }
    return false;
}

function haeKapteeni($yhteys, $joukkue) {
    $kysely = kysely($yhteys, "SELECT kapteeni FROM joukkueet WHERE id='" . $joukkue . "'");
    $tulos = mysql_fetch_array($kysely);
    return $tulos['kapteeni'];
}

//tulostaa pelilistan
function tulostapelit($kysely, $otsikko, $kaikki, $l_peli) {
    if ($tulos = mysql_fetch_array($kysely)) {
        echo"<table id='pelittaulu' border='5'>
		<tr><th colspan='";
        if ($kaikki)
            echo"4";
        else
            echo"5";
        echo"'>" . $otsikko . "</th></tr>
		<tr>";
        if (!$kaikki)
            echo"<td>Pvm</td>";
        echo"<td>Klo</td>
		<td>Ottelu</td>
		<td>Tulos</td>
		<td>Paikka</td>
	  </tr>";
        $joukkue = "";
        do {
            if ($kaikki) {
                if ($joukkue != $tulos['joukkue']) {
                    echo"<tr><th colspan='4'>" . $tulos['joukkue'] . "</th></tr>";
                    $joukkue = $tulos['joukkue'];
                }
                echo"<tr" . ($tulos['kotiturnaus'] ? " class='kotiturnaus'" : "") . ">";
            } else {
                $tamapaiva = getdate($tulos['aika']);
                $paiva = $tamapaiva['mday'];
                $kuukausi = $tamapaiva['mon'];
                $vuosi = $tamapaiva['year'];
                $pvm2 = mktime(0, 0, 0, $kuukausi, $paiva, $vuosi);
                echo"<tr";
                if ($pvm2 == $l_peli / 1000)
                    echo" id='fbcremix'";
                echo($tulos['kotiturnaus'] ? " class='kotiturnaus'" : "") . "><td>" . date("d.m.Y", $tulos['aika']) . "</td>";
            }
            echo"<td>" . date("H:i", $tulos['aika']) . "</td>
                    <td>";
            if ($tulos['koti'])
                echo"FBC Remix - " . $tulos['vastustaja'];
            else
                echo $tulos['vastustaja'] . " - FBC Remix";
            echo"</td>
                    <td>";
            if ($tulos['pelattu'])
                echo $tulos['kotimaalit'] . "-" . $tulos['vierasmaalit'];
            else
                echo"-";
            echo"</td>
                    <td>" . $tulos['pelipaikka'] . "</td>
                    </tr>";
        }
        while ($tulos = mysql_fetch_array($kysely));
    }
    else {
        echo"<table id='pelittaulu' border='5'><tr><td colspan='";
        if ($kaikki)
            echo"4";
        else
            echo"5";
        echo"'>Ei peliä</td></tr>";
    }
    echo"</table>";
}

function tulostaTilasto($kysely, $otsikko) {
    if ($tulos = mysql_fetch_array($kysely)) {
        echo"<table id='tilastottaulu' border='5'>
	<tr><th colspan='10'>" . $otsikko . "</th></tr>
	<tr>
	<td id='tilastototsikko'>#</td>
	<td id='tilastototsikko'>Pelaaja</td>
	<td id='tilastototsikko'>O</td>
	<td id='tilastototsikko'>RL</td>
	<td id='tilastototsikko'>RM</td>
	<td id='tilastototsikko'>P</td>
        <td id='tilastototsikko'>+-</td>
	</tr>";
        do {
            $etunimi = $tulos['etunimi'];
            $sukunimi = $tulos['sukunimi'];

            $pelaaja = $sukunimi . " " . $etunimi;
            $data[] = array("maalit" => $tulos['M'], "syotot" => $tulos['S'], "pelaaja" => $pelaaja, "plusmiinus" => $tulos['plusmiinus'], "ottelut" => $tulos['O'], "rankkaritm" => $tulos['RLM'], "rankkarity" => $tulos['RLY'], "minuutit" => $tulos['RM']);
        } while ($tulos = mysql_fetch_array($kysely));
        foreach ($data as $values) {
            // tee tässä kaikki laskusuoritukset mitä pitää tehdä
            $pisteet = $values['maalit'] + $values['syotot'];

            $data2[$pisteet][$values['maalit']][$values['syotot']][] = array("pelaaja" => $values['pelaaja'], "plusmiinus" => $values['plusmiinus'], "ottelut" => $values['ottelut'], "rankkaritm" => $values['rankkaritm'], "rankkarity" => $values['rankkarity'], "minuutit" => $values['minuutit']);
        }
        $u = 1;
        krsort($data2); //pisteet järjestykseen
        foreach ($data2 as $pisteet => $maalit) {
            krsort($maalit); // maalit järjestykseen
            foreach ($maalit as $maalit2 => $syotot) {
                krsort($syotot); // syötöt järjestykseen
                foreach ($syotot as $syotot2 => $nimi) {
                    sort($nimi); //nimi järjestykseen

                    for ($x = 0; $x < count($nimi); $x++) {  // viimenen silaus
                        if ($pisteet == $v_pisteet && $maalit2 == $v_maalit && $syotot2 == $v_syotot && $u != 1)
                            $u--;
                        echo"<tr>";
                        print "\t<td>" . $u . "<td>" . $nimi[$x]['pelaaja'] . "</td><td>" . $nimi[$x]['ottelut'] . "</td><td>" . $nimi[$x]['rankkaritm'] . " (" . $nimi[$x]['rankkarity'] . ")</td><td>" . $nimi[$x]['minuutit'] . "</td><td>" . $maalit2 . "+" . $syotot2 . "=" . $pisteet . "</td><td>" . $nimi[$x]['plusmiinus'] . "</td>\n"; // tehdään table
                        print "</tr>\n";
                        $v_pisteet = $pisteet;
                        $v_maalit = $maalit2;
                        $v_syotot = $syotot2;
                        $u++;
                    }
                }
            }
        }
    }
    else
        echo"<table id='tilastottaulu' border='5'><tr><td colspan='9'>Ei tilastoja</td></tr>";
    echo"</table>";
}

function tarkistaOnkoAdmin($yhteys, $tunnus, $admin) {
    $tunnus = mysql_real_escape_string($tunnus);
    $kysely = kysely($yhteys, "SELECT isadmin FROM tunnukset WHERE id='" . $tunnus . "'");
    if ($tulos = mysql_fetch_array($kysely)) {
        if ($tulos['isadmin'] == $admin) {
            return true;
        }
    }
    return false;
}

function onkoKansioTyhja($kansio, $ohitettavat) {
    $dir = dir($kansio);
    while (($tiedosto = $dir->read()) !== false) {
        if ($tiedosto != "." && $tiedosto != ".." && !in_array($tiedosto, $ohitettavat)) {
            return false;
        }
    }
    return true;
}

function paivaMaaraValitsin($tunnus, $valittu, $vuodet) {
    include("/home/fbcremix/public_html/Remix/foorumi/ohjelmat/paivamaaravalitsin.php");
}

function aikaValitsin($tunnus, $valittu) {
    include("/home/fbcremix/public_html/Remix/foorumi/ohjelmat/aikavalitsin.php");
}

function katkaiseTeksti($teksti, $maxkirjaimia) {
    if (strlen($teksti) <= $maxkirjaimia) {
        return $teksti;
    }
    return substr($teksti, 0, strrpos($teksti, " ", $maxkirjaimia - strlen($teksti)));
}

function tulosta_joukkueet_valinta_jasenille($kayttaja, $kausi, $lisakentatalkuun, $lisakentatjalkeen, $seura, $siirry) {
    include("/home/fbcremix/public_html/Remix/ohjelmat/joukkueenvalintajasenet.php");
}

function onkotanaan($paiva) {
    $tanaan = getdate();
    $paiva = getdate($paiva);
    if ($tanaan['mday'] == $paiva['mday'] && $tanaan['mon'] == $paiva['mon'] && $tanaan['year'] == $paiva['year']) {
        return true;
    }
    return false;
}

function onkohuomenna($paiva) {
    $huomenna = getdate(time() + 24 * 60 * 60);
    $paiva = getdate($paiva);
    if ($huomenna['mday'] == $paiva['mday'] && $huomenna['mon'] == $paiva['mon'] && $huomenna['year'] == $paiva['year']) {
        return true;
    }
    return false;
}

function onkoeilen($paiva) {
    $eilen = getdate(time() - 24 * 60 * 60);
    $paiva = getdate($paiva);
    if ($huomenna['mday'] == $paiva['mday'] && $huomenna['mon'] == $paiva['mon'] && $huomenna['year'] == $paiva['year']) {
        return true;
    }
    return false;
}

function tulostailmoittautumisnapit($kin, $kout, $tapahtumatid) {
    include("/home/fbcremix/public_html/Remix/foorumi/apu/ilmoittautumisnapit.php");
}

function tulostapaivamaara($aikaleima, $vuosi, $kello) {
    if(onkoeilen($aikaleima)){
        echo "Eilen";
    } elseif (onkotanaan($aikaleima)) {
        echo "Tänään";
    } elseif (onkohuomenna($aikaleima)) {
        echo "Huomenna";
    } else {
        $format = "d.m" . ($vuosi ? ".Y" : "");
        echo date($format, $aikaleima);
    }
    if ($kello) {
        echo " klo " . date("H:i", $aikaleima);
    }
}

function tulostasivunumerot($maara, $ysm, $sivu, $linkki, $viimeinen, $nuolet, $ejav){
    include("/home/fbcremix/public_html/Remix/ohjelmat/sivunumerot.php");
}
?>

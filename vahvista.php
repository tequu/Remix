<?php
include("/home/fbcremix/public_html/Remix/yla.php");
?>
<div id="levea_content">
    <div id="vahvistus">
        <?php
        if ((isset($_GET['email']) || isset($_GET['viestiid'])) && isset($_GET['token'])) {
            $varmistusvirhe = "";
            //otetaan token talteen
            $token = mysql_real_escape_string($_GET['token']);
            //tarkistetaan mik‰ pit‰‰ varmistaa ja laitetaan tavittavat tiedot muuttujiin
            if (isset($_GET['email'])) {
                $kentta = "email";
                $tokenkentta = "tunnuksetID";
                $taulu = "tunnukset";
                $kenttaarvo = mysql_real_escape_string($_GET['email']);
            } else {
                $kentta = "id";
                $kenttaarvo = mysql_real_escape_string($_GET['viestiid']);
                $tokenkentta = "vieraskirjaID";
                $taulu = "vieraskirja";
            }
            $kysely = kysely($yhteys, "SELECT id, enabled FROM " . $taulu . " WHERE " . $kentta . "='" . $kenttaarvo . "'");
            //tarkistetaan, ett‰ saatiin jotain
            if ($tulos = mysql_fetch_array($kysely)) {
                $tokenkentanarvo = $tulos['id'];
                //tarkistetaan, ett‰ onko tunnus tai viesti jo aktivoitu
                if ($tulos['enabled'] && isset($_GET['email']))
                    $varmistusvirhe = "Tunnus on jo aktivoitu.";
                elseif ($tulos['enabled'] && isset($_GET['viestiid']))
                    $varmistusvirhe = "Viesti on jo aktivoitu.";
                else {
                    //tarkistetaan ett‰ tokenit taulussa on l‰hetetty tokeni
                    $kysely = kysely($yhteys, "SELECT tunnuksetID FROM tokenit WHERE token='" . $token . "' AND " . $tokenkentta . "='" . $tokenkentanarvo . "'");
                    if ($tulos = mysql_fetch_array($kysely)) {
                        //p‰ivitet‰‰n enabled kentt‰‰n arvo 1 oikeassa taulussa
                        kysely($yhteys, "UPDATE " . $taulu . " SET enabled='1' WHERE id='" . $tokenkentanarvo . "'");
                        //poistetaan token tokenit taulusta
                        kysely($yhteys, "DELETE FROM tokenit WHERE token='" . $token . "' AND " . $tokenkentta . "='" . $tokenkentanarvo . "'");
                    }
                    else
                    //jos tokenia ei ollut annetaan virhesanoma
                        $varmistusvirhe = "Tarkista linkki. Jos ratkaisua ei lˆydy ota yhteys yll‰pitoon.";
                }
            }
            else {
                $varmistusvirhe = "Tarkista linkki. Jos ratkaisua ei lˆydy ota yhteys yll‰pitoon.";
            }
        } else {
            $varmistusvirhe = "Tarkista linkki. Jos ratkaisua ei lˆydy ota yhteys yll‰pitoon.";
        }
        if ($varmistusvirhe != "")
            echo $varmistusvirhe;
        elseif (isset($_GET['email'])) {
            ?>
            Aktivointi onnistui, voit nyt kirjautua sis‰‰n.
            <?php
        } else {
            ?>
            Aktivointi onnistu, mene vieraskirjaan <a href='/vieraskirja.php'>t‰st‰</a>
            <?php
        }
        ?>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
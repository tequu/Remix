<form id="joukkueenvalinta" name="joukkueenvalinta" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <?php
    $poistettavat = array("joukkue");
    foreach ($lisakentatjalkeen as $avain => $arvo) {
        $poistettavat[] = $avain;
    }
    foreach ($lisakentatalkuun as $avain => $arvo) {
        $poistettavat[] = $avain;
    }
    tulosta_get_input($poistettavat);
    foreach ($lisakentatalkuun as $arvo) {
        echo $arvo;
    }
    ?>
    Valitse joukkue:<select id="joukkue" name="joukkue">
        <?php
        $joukkueet = array();
        if (isset($_GET['kausi'])) {
            $haettu_kausi = mysql_real_escape_string($_GET['kausi']);
        }
        if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
            $kysely = kysely($yhteys, "SELECT id, nimi FROM joukkueet");
            while($tulos = mysql_fetch_array($kysely)){
                    $joukkueet[$tulos['id']] = $tulos['nimi'];
            }
        } elseif (isset($haettu_kausi)) {
            $kysely = kysely($yhteys, "SELECT id, nimi FROM joukkueet WHERE kausi='" . $haettu_kausi . "'");
            while($tulos = mysql_fetch_array($kysely)){
                if(!in_array($tulos['nimi'], $joukkueet)){
                    $joukkueet[$tulos['id']] = $tulos['nimi'];
                }
            }
        } elseif (isset($kayttaja)) {
            $kysely = kysely($yhteys, "SELECT id, nimi FROM joukkueet j, pelaajat p WHERE j.id=p.joukkueetID AND p.tunnuksetID='" . $kayttaja . "'");
            while($tulos = mysql_fetch_array($kysely)){
                if(!in_array($tulos['nimi'], $joukkueet)){
                    $joukkueet[$tulos['id']] = $tulos['nimi'];
                }
            }
            $kysely = kysely($yhteys, "SELECT id, nimi FROM joukkueet j, yhteyshenkilot y WHERE j.id=y.joukkueetID AND y.tunnuksetID='".$kayttaja."'");
            while($tulos = mysql_fetch_array($kysely)){
                if(!in_array($tulos['nimi'], $joukkueet)){
                    $joukkueet[$tulos['id']] = $tulos['nimi'];
                }
            }
            $kysely = kysely($yhteys, "SELECT id, nimi FROM joukkueet j, keskustelualueoikeudet ko WHERE j.keskustelualueetID=ko.keskustelualueetID AND ko.tunnuksetID='".$kayttaja."'");
            while($tulos = mysql_fetch_array($kysely)){
                if(!in_array($tulos['nimi'], $joukkueet)){
                    $joukkueet[$tulos['id']] = $tulos['nimi'];
                }
            }
            $kysely = kysely($yhteys, "SELECT id, nimi FROM joukkueet j, oikeudet o WHERE (j.id=o.joukkueetID OR j.keskustelualueetID=o.keskustelualueetID) AND o.tunnuksetID='".$kayttaja."'");
            while($tulos = mysql_fetch_array($kysely)){
                if(!in_array($tulos['nimi'], $joukkueet)){
                    $joukkueet[$tulos['id']] = $tulos['nimi'];
                }
            }
        } else {
            $kysely = kysely($yhteys, "SELECT id, nimi FROM joukkueet WHERE kausi='" . $kausi . "'");
            while($tulos = mysql_fetch_array($kysely)){
                if(!in_array($tulos['nimi'], $joukkueet)){
                    $joukkueet[$tulos['id']] = $tulos['nimi'];
                }
            }
        }
        $joukkueolemassa = false;
        if (isset($_GET['joukkue']) && tarkistaJoukkueenOlemassaOlo($yhteys, $joukkue)) {
            $joukkueolemassa = true;
        }
        if (!isset($_GET['joukkue']) || ($seura && $joukkue != 0 && !$joukkueolemassa) || (!$seura && !$joukkueolemassa)) {
            echo "<option></option>";
        }
        if ($seura) {
            echo "<option value=\"0\"" . (isset($_GET['joukkue']) && $_GET['joukkue'] == 0 ? " SELECTED" : "") . ">Seura</option>";
        }
        foreach($joukkueet as $id=>$nimi) {
            echo"<option value=\"" . $id . "\"" . (isset($_GET['joukkue']) && $_GET['joukkue'] == $id ? " SELECTED" : "") . ">" . $nimi . "</option>";
        }
        ?>
    </select>
    <?php
    foreach ($lisakentatjalkeen as $arvo) {
        echo $arvo;
    }
    ?>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $("#joukkue").change(function() {
            laheta('joukkueenvalinta', [], []);
        });
    });
</script>

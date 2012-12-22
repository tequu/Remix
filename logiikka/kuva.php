<?php

 function lisaakuva($yhteys, $joukkueid) {
    global $error, $kausi;
    $kategoriatid = mysql_real_escape_string($_POST['kategoriatid']);
    $kuvateksti = mysql_real_escape_string(trim($_POST['kuvateksti']));
    $uppikansio = "/home/fbcremix/public_html/Remix/kuvat/".($joukkueid!=0?"joukkueet/".$joukkueid:"seura");
    $hyvaksytyt = array("image/png", "image/jpeg", "image/jpg", "image/gif");
    if (in_array($_FILES['kuva']['type'], $hyvaksytyt)) {
        if ($_FILES['kuva']['size'] < 1000000) {
            $koko = getimagesize($_FILES['kuva']['tmp_name']);
            if ($koko[0] <= 600 && $koko[1] <= 450) {
                if (!is_dir($uppikansio)) {
                    mkdir($uppikansio);
                    $tiedosto = fopen($uppikansio . "/index.html", "a");
                    fclose($tiedosto);
                }
                $kysely = mysql_query("SELECT id FROM kuvat WHERE kuva='".basename($_FILES['kuva']['name'])."' AND kategoriatid='".$kategoriatid."'") or die("Tarkista onko kuva jo olemassa: ".mysql_error());
                if($tulos = mysql_fetch_array($kysely)){
                    $error = "*T‰m‰n niminen kuva on jo olemassa";
                    return false;
                }
                luouusikuva($_FILES['kuva']['tmp_name'], $uppikansio."/mini_".basename($_FILES['kuva']['name']),90,65);
                if (move_uploaded_file($_FILES['kuva']['tmp_name'], $uppikansio . "/" . basename($_FILES['kuva']['name']))){
                    $kuva = basename($_FILES['kuva']['name']);
                    $kysely = mysql_query("INSERT INTO kuvat(kuva,kuvateksti,kategoriatid,kausi) VALUES ('".$kuva."', '".$kuvateksti."', '".$kategoriatid."', '".$kausi."')") or die("Lis‰‰ kuva: ".mysql_error());
                    header("Location: ".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=lisaakuva&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:""));
                }
                else {
                    $error = "Kuvan l‰hetys ei onnistunut<br />";
                    return false;
                }
            } else {
                $error = "Kuvan leveys tai korkeus on liian suuri. Kuva saa olla korkeintaan 600x450<br />";
                return false;
            }
        } else {
            $error = "Kuvan koko on liian suuri. Maksimi koko on 1Mt<br />";
            return false;
        }
    } else {
        $error = "Kuvan tiedotomuoto on v‰‰r‰. Sallittuja on .jpg, .jpeg, .gif ja .png<br />";
        return false;
    }
}

//tekee mini kuvan, $tiedosto = vanhatiedosto, $kohdetiedosto = tallennus paikka
function luouusikuva($tiedosto, $kohdetiedosto, $max_leveys, $max_korkeus) {
    $kuvantiedot = getimagesize($tiedosto);
    if ($kuvantiedot[2] == 1)
        $kuva = imagecreatefromgif($tiedosto);
    elseif ($kuvantiedot[2] == 2)
        $kuva = imagecreatefromjpeg($tiedosto);
    elseif ($kuvantiedot[2] == 3)
        $kuva = imagecreatefrompng($tiedosto);
    else
        return false;
    $leveys_suhde = $max_leveys / $kuvantiedot[0];
    $korkeus_suhde = $max_korkeus / $kuvantiedot[1];
    if ($leveys_suhde <= $korkeus_suhde) {
        if ($leveys_suhde > 1)
            $leveys_suhde = 1;
        $uusi_leveys = $kuvantiedot[0] * $leveys_suhde;
        $uusi_korkeus = $kuvantiedot[1] * $leveys_suhde;
    }
    else {
        if ($korkeus_suhde > 1)
            $korkeus_suhde = 1;
        $uusi_leveys = $kuvantiedot[0] * $korkeus_suhde;
        $uusi_korkeus = $kuvantiedot[1] * $korkeus_suhde;
    }
    $uusi_kuva = imagecreatetruecolor($uusi_leveys, $uusi_korkeus);
    $black = imagecolorallocate($uusi_kuva, 0, 0, 0);
    imagecopyresampled($uusi_kuva, $kuva, 0, 0, 0, 0, $uusi_leveys, $uusi_korkeus, $kuvantiedot[0], $kuvantiedot[1]);
    if ($kuvantiedot[2] == 1)
        $kuva = imagegif($uusi_kuva, $kohdetiedosto);
    elseif ($kuvantiedot[2] == 2)
        $kuva = imagejpeg($uusi_kuva, $kohdetiedosto);
    elseif ($kuvantiedot[2] == 3)
        $kuva = imagepng($uusi_kuva, $kohdetiedosto);
    imagedestroy($uusi_kuva);
}

function poistaKategorianKuvia($yhteys, $joukkueid){
    global $error, $kausi;
    $kategoriatid = mysql_real_escape_string($_POST['kategoriatid']);
    $kuvatidt = $_POST['kuvatidt'];
    $kysely = mysql_query("SELECT joukkueid FROM kategoriat WHERE id='".$kategoriatid."' AND kausi='".$kausi."'") or die("Hae joukkueid: ".mysql_error());
    $tulos = mysql_fetch_array($kysely);
    if($tulos['joukkueid']!=$joukkueid){
        $error = "*Virhe poistettaessa kuvaa";
        return false;
    }
    foreach($kuvatidt as $kuvatid){
        $kuvatid = mysql_real_escape_string($kuvatid);
        $kysely = mysql_query("SELECT kuva, kategoriatid FROM kuvat WHERE id='".$kuvatid."'") or die("Hae kategoriatid kuvista: ".mysql_error());
        $tulos = mysql_fetch_array($kysely);
        if($tulos['kategoriatid']!=$kategoriatid){
            $error = "*Virhe poistettaessa kuvaa ".$tulos['kuva'];
            return false;
        }
        if(!poistaKuva($tulos['kuva'],$joukkueid))
            return false;
        $kysely = mysql_query("DELETE FROM kuvat WHERE id='".$kuvatid."'") or die("Poista kuva: ".mysql_error());
    }
    header("Location: ".$_SERVER['PHP_SELF']."?ohjelmaid=9&mode=muokkaakategoriaa&kategoriatid=".$kategoriatid.($joukkueid!=0?"&joukkueid=".$joukkueid:""));
}
function poistaKuva($kuva,$joukkueid){
    global $error, $errors;
    if(!unlink("/home/fbcremix/public_html/Remix/kuvat/".($joukkueid!=0?"joukkueet/".$joukkueid:"seura/").$kuva)){
        $error = "*Virhe poistettaessa kuvaa<br />";
        return false;
    }
    if(!unlink("/home/fbcremix/public_html/Remix/kuvat/".($joukkueid!=0?"joukkueet/".$joukkueid:"seura/")."mini_".$kuva)){
        $error = "*Virhe poistettaessa kuvaa<br />";
        return false;
    }
    return true;
}
?>

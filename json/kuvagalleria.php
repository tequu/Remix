<?php
header("Content-Type: text/plain; charset=UTF-8");
include("/home/fbcremix/public_html/Remix/mysql/yhteys.php");
if(isset($_GET['id'])){
    $id = mysql_real_escape_string($_GET['id']);
    $kysely = mysql_query("SELECT kuva, kuvateksti FROM kuvat WHERE id='".$id."' LIMIT 0,1");
    $tulos = mysql_fetch_array($kysely);
    $kuva = array("src" => $tulos['kuva'], "kuvaus" => $tulos['kuvateksti']);
    echo json_encode($kuva);
} elseif(isset($_GET['kategoriatid'])){
    $id = mysql_real_escape_string($_GET['kategoriatid']);
    $start = isset($_GET['alkaen'])?mysql_real_escape_string($_GET['alkaen']):0;
    $limit = isset($_GET['maara'])?mysql_real_escape_string($_GET['maara']):20;
    $kysely = mysql_query("SELECT id, kuva, kuvateksti FROM kuvat WHERE kuvakategoriatID='".$id."' LIMIT ".$start.", ".$limit);
    $kuvat = array();
    while($tulos = mysql_fetch_array($kysely)){
        $kuvat[] = array("src" => $tulos['kuva'], "kuvaus" => $tulos['kuvateksti']);
    }
    echo json_encode($kuvat);
} else {
    $joukkueid = isset($_GET['joukkueid'])?mysql_real_escape_string($_GET['joukkueid']):0;
    $kysely = mysql_query("SELECT kk.id id, nimi, maara kuvia FROM kuvakategoriat kk LEFT OUTER JOIN 
        (SELECT DISTINCT kuvakategoriatID, count(id) maara FROM kuvat) k ON kk.id=k.kuvakategoriatID
        WHERE joukkueetID='".$joukkueid."'");
    $kategoriat = array();
    while($tulos = mysql_fetch_array($kysely)){
        $kategoriat[] = array("id" => $tulos['id'], "otsikko" => $tulos['nimi'], "kuvia" => $tulos['kuvia']==null?0:$tulos['kuvia']);
    }
    echo json_encode($kategoriat);
}
mysql_close($yhteys);
?>

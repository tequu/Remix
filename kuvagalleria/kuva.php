<?php
$kuvaid = mysql_real_escape_string($_GET['kuva']);
$kysely = mysql_query("SELECT kuva, kuvateksti, kategoriatid  FROM kuvat WHERE id='".$kuvaid."'") or die("Hae kuva: ".mysql_error());
$tulos = mysql_fetch_array($kysely);
$kategoriatid = $tulos['kategoriatid'];
$kysely2 = mysql_query("SELECT id FROM kuvat WHERE id>'".$kuvaid."' AND kategoriatid='".$kategoriatid."' ORDER BY id LIMIT 0,1") or die("Hae edellinen kuva: ".mysql_error());
$kysely3 = mysql_query("SELECT id FROM kuvat WHERE id<'".$kuvaid."' AND kategoriatid='".$kategoriatid."' ORDER BY id DESC LIMIT 0,1") or die("Hae seuraava kuva:".mysql_error());
echo"<div id='kuvat'><div id='takaisin' onclick='parent.location=\"".$_SERVER['PHP_SELF']."?kategoria=".$kategoriatid.(isset($id)?"&id=".$id:"")."\"'>Takaisin</div>";
if($tulos2 = mysql_fetch_array($kysely2))
    echo"<div id='edellinen_kuva'><a href='".$_SERVER['PHP_SELF']."?kuva=".$tulos2['id'].(isset($id)?"&id=".$id:"")."'>Edellinen</a></div>";
if($tulos3 = mysql_fetch_array($kysely3))
    echo"<div id='seuraava_kuva'><a href='".$_SERVER['PHP_SELF']."?kuva=".$tulos3['id'].(isset($id)?"&id=".$id:"")."'>Seuraava</a></div>";
echo"<div id='kuvat_kuva'><img src='/Remix/kuvat/".($joukkueid==0?"seura":"joukkueet/".$joukkueid)."/".$tulos['kuva']."' alt='' /></div><div id='kuvat_kuvaus'>".$tulos['kuvateksti']."</div>
    <div style='clear:both'></div>";
echo"</div>";
?>

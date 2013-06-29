<?php
header("Cache-Control: no-cache, no-store, max-age=0, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Pragma: no-cache");
header("Content-Type: text/html; charset='windows-1252'");
session_start();
include("/home/fbcremix/public_html/Remix/mysql/yhteys.php");
include("/home/fbcremix/public_html/Remix/logiikka/asetetutarvot.php");
include("/home/fbcremix/public_html/Remix/logiikka/funktiot.php");
include("/home/fbcremix/public_html/Remix/logiikka/kayttaja.php");
include("/home/fbcremix/public_html/Remix/logiikka/kuva.php");
include("/home/fbcremix/public_html/Remix/logiikka/oikeudet.php");
include("/home/fbcremix/public_html/Remix/logiikka/olemassaolo.php");
include("/home/fbcremix/public_html/Remix/logiikka/tunnukset.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/kayttajat.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/johtokunta.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/joukkue.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/oikeudet.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/pelaaja.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/pelit.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/sarjataulukko.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/tiedotukset.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/tilasto.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/kuvat.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/uutiset.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/yhteyshenkilot.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/foorumi/keskustelualue.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/foorumi/keskustelu.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/foorumi/viesti.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/foorumi/tapahtuma.php");
include("/home/fbcremix/public_html/Remix/logiikka/hallinta/foorumi/keskustelualueryhma.php");
if (isset($kirjautunut)) {
    tarkistaKirjautuneenTunnus($yhteys);
}
if (getcwd() == "/home/fbcremix/public_html/Remix/joukkueet" && !isset($_GET['id']))
    siirry("joukkueet.php");
if (isset($_GET['id']) && $_GET['id'] >= 0) {
    $id = intval(mysql_real_escape_string($_GET['id']));
    $kysely = kysely($yhteys, "SELECT id FROM joukkueet WHERE kausi='" . $kausi . "' ORDER BY kerho, jarjestysnumero LIMIT " . $id . ",1");
    if ($tulos = mysql_fetch_array($kysely))
        $joukkueid = $tulos['id'];
    else {
        if (getcwd() == "/home/fbcremix/public_html/joukkueet")
            siirry("joukkueet.php");
        $id = -1;
    }
}
elseif (isset($_GET['joukkueid'])) {
    if ($_GET['joukkueid'] >= 1) {
        $joukkueid = mysql_real_escape_string($_GET['joukkueid']);
        $kysely = mysql_query("SELECT joukkue FROM joukkueet WHERE id='" . $joukkueid . "' AND kausi='" . $kausi . "'") or die("Tarkista joukkueen olemassa olo: " . mysql_error());
        if (!$tulos = mysql_fetch_array($kysely)) {
            $joukkueid = 0;
            $error = "Joukkuetta ei löydy";
        }
    } else {
        $joukkueid = 0;
        $error = "Joukkueid ei kelpaa";
    }
}
else
    $joukkueid = 0;
if (isset($_POST['ohjaa'])) {
    ohjaa($yhteys);
}
$headers = true;
?>
<!DOCTYPE HTML SYSTEM "about:legacy-compat">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=windows-1252"/>
        <meta name="description" content="FBC Remix - Salibandy Isostakyröstä"/>
        <meta name="keywords" content="FBC,FBC Remix,Remix,Salibandy,Isokyrö,Pohjanmaa"/>
        <meta name="author" content="Tero Kujala"/>
        <meta http-equiv="cache-control" content="no-cache" />
        <meta property="og:title" content="FBC Remix" />
        <meta property="og:type" content="sport" />
        <meta property="og:url" content="https://www.facebook.com/pages/FBC-Remix/227252960660794" />
        <meta property="og:image" content="" />
        <meta property="og:site_name" content="FBC Remix" />
        <meta property="fb:admins" content="1814612979" />
        <title>FBC Remix</title>
        <link rel='stylesheet' type='text/css' href='/Remix/css/kehykset.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/nav.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/kalenteri.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/tunnukset.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/pelaajakortit.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/uutiset.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/joukkueen_etusivu.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/info.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/vieraskirja.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/pelaajat.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/seuran_etusivu.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/joukkueen_valikko.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/sarjataulukko.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/yhteystiedot.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/johtokunta.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/pelit.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/sivunumerot.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/tilastot.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/joukkueet.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/kuvat.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/toimintalinja.css' />
        <link rel='stylesheet' type='text/css' href='/Remix/css/ohjauspaneeli.css' />

        <script type="text/javascript" src="/Remix/js/jquery.js"></script>
        <script type="text/javascript" src="/Remix/js/javascript.js"></script>
        <script type="text/javascript" src="/Remix/js/uutinen.js"></script>
        <script type="text/javascript" src="/Remix/js/nav.js"></script>
        <script type="text/javascript" src="/Remix/js/hoverIntent.js"></script>
        <script type="text/javascript" src="/Remix/js/kalenteri.js"></script>
        <script type="text/javascript" src="/Remix/js/tunnus.js"></script>
        <script type="text/javascript" src="/Remix/js/js.js"></script>
        <script type="text/javascript" src="/Remix/js/sponsorit.js"></script>
        <script type="text/javascript" src="/Remix/js/ohjauspaneeli.js"></script>
        <script type="text/javascript" src="/Remix/js/pelaajakortti.js"></script>
        <script type="text/javascript" src="/Remix/js/foorumi.js"></script>

        <script type='text/javascript'>
            <!--
            var joukkueid = <?php echo $joukkueid; ?>;
            var lisatiedot = new Array(false,false,false,false,false,false,false,false);
            var lisattavat = new Array();
    
    
<?php
//$kysely = kysely($yhteys, "SELECT kuva FROM sponsorit WHERE joukkueetID='" . $joukkueid . "'");
//$sponsorimaara = 0;
//$ekakuva = "";
//while ($tulos = mysql_fetch_array($kysely)) {
//    echo"var sponsoriimage" . ($sponsorimaara) . "=new Image();
//                    sponsoriimage" . ($sponsorimaara) . ".src=\"/kuvat/sponsorit/" . $tulos['kuva'] . "\";
//                        ";
//    if ($maara == 0)
//        $ekakuva = "/kuvat/sponsorit/" . $tulos['kuva'];
//    $sponsorimaara++;
//}
//echo"var sponsorimaara=" . $sponsorimaara . ";
//                var sponsoristep=0;";
?>
                
    //-->
        </script>
    </head>
    <body>
<!--        <div id="fb-root"></div>
        <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/fi_FI/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>-->
        <div id='header'>

        </div>
        <div style='clear:both'></div>
        <div id="container">
            <?php
            include("/home/fbcremix/public_html/Remix/ohjelmat/seuravalikko.php");
            ?>
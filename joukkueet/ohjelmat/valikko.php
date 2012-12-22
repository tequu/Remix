<?php 
$kysely = kysely($yhteys, "SELECT nimi FROM joukkueet WHERE id='".$joukkueid."'");
$tulos = mysql_fetch_array($kysely);
?>
<div id="joukkueen_valikko">
    <div id="nimi"><a href="/Remix/joukkueet/index.php?id=<?php echo $id; ?>"><?php echo $tulos['nimi']; ?></a></div>
    <div id="nav">
        <div id="nappi" style="background-image: URL('/Remix/kuvat/valikko/pelaajat.png');" onclick="siirry('/Remix/joukkueet/pelaajat.php?id=<?php echo $id; ?>')"></div>
        <div id="nappi" style="background-image: URL('/Remix/kuvat/valikko/yhteystiedot.png');" onclick="siirry('/Remix/joukkueet/yhteystiedot.php?id=<?php echo $id; ?>')"></div>
        <div id="nappi" style="background-image: URL('/Remix/kuvat/valikko/sarjataulukko.png');" onclick="siirry('/Remix/joukkueet/sarjataulukko.php?id=<?php echo $id; ?>')"></div>
        <div id="nappi" style="background-image: URL('/Remix/kuvat/valikko/pelit.png');" onclick="siirry('/Remix/joukkueet/pelit.php?id=<?php echo $id; ?>')"></div>
        <div id="nappi" style="background-image: URL('/Remix/kuvat/valikko/tilastot.png');" onclick="siirry('/Remix/joukkueet/tilastot.php?id=<?php echo $id; ?>')"></div>
        <div id="nappi" style="background-image: URL('/Remix/kuvat/valikko/kuvat.png');" onclick="siirry('/Remix/joukkueet/kuvat.php?id=<?php echo $id; ?>')"></div>
    </div>
</div>
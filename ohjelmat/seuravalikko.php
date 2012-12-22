<div id="seuravalikko">
    <ul>
        <li style="background-image:URL('/Remix/kuvat/valikko/etusivu.png')"><a id="nappi" href="/Remix/index.php"></a></li>
        <li style="background-image:URL('/Remix/kuvat/valikko/joukkueet.png')"><a id="nappi" href="/Remix/joukkueet.php"></a>
            <div id="alavalikko">
                <div id="yla"></div>
                <div id="valikko">

                    <?php
                    $i = 0;
                    $kerhot = array();
                    $kysely = kysely($yhteys, "SELECT nimi, kerho FROM joukkueet WHERE kausi='" . $kausi . "' ORDER BY kerho, jarjestysnumero");
                    while ($tulos = mysql_fetch_array($kysely)) {
                        if ($tulos['kerho']) {
                            $kerhot[] = array($i, $tulos['nimi']);
                            $i++;
                            continue;
                        }
                        ?>
                        <div id="osa"<?php echo (isset($_GET['id']) && $id == $i ? " style=\"background-image: none; background-color: #ff8331;\"" : ""); ?>><a href="/Remix/joukkueet/index.php?id=<?php echo $i; ?>"><?php echo $tulos['nimi']; ?></a></div>
                        <?php
                        $i++;
                    }
                    if (!empty($kerhot)) {
                        ?>
                        <div id="kerhot">Kerhot</div>
                        <?php
                        foreach ($kerhot as $kerho) {
                            ?>
                            <div id="osa"<?php echo (isset($_GET['id']) && $$kerho[0] == $i ? " style=\"background-image: none; background-color: #ff8331;\"" : ""); ?>><a href="/Remix/joukkueet/index.php?id=<?php echo $kerho[0]; ?>"><?php echo $kerho[1]; ?></a></div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <div id="ala"></div>
            </div>
        </li>
        <li style="background-image:URL('/Remix/kuvat/valikko/uutiset.png')"><a id="nappi" href="/Remix/uutiset.php"></a></li>
        <li style="background-image:URL('/Remix/kuvat/valikko/info.png')"><a id="nappi" href="/Remix/info.php"></a>
            <div id="alavalikko">
                <div id="yla"></div>
                <div id="valikko">
                    <div id="osa"><a href="/Remix/johtokunta.php">Johtokunta</a></div>
                    <div id="osa"><a href="/Remix/historia.php">Historia</a></div>
                    <div id="osa"><a href="/Remix/kuvagalleria.php">Kuvagalleria</a></div>
                    <div id="osa"><a href="/Remix/toimintalinja.php">Toimintalinja</a></div>
                    <div id="osa"><a href="/Remix/latauspankki.php">Latauspankki</a></div>
                </div>
                <div id="ala"></div>
            </div>
        </li>
        <li style="background-image:URL('/Remix/kuvat/valikko/vieraskirja.png')"><a id="nappi" href="/Remix/vieraskirja.php"></a></li>
    </ul>
    <div id="tunnus">
        <!--        <div style="background-image:URL('/Remix/kuvat/valikko/rekisteroidy.png')"><a href="/Remix/rekisteroi.php"></a></div>
                <div style="background-image:URL('/Remix/kuvat/valikko/kirjaudu.png')"><a href="/Remix/kirjaudu.php"></a></div>-->
        <?php
        if (isset($_SESSION['id'])) {
            ?>
            <div style="background-image: URL('/Remix/kuvat/valikko/hallinta.png');" onclick="siirry('/Remix/ohjauspaneeli.php')"></div>
            <div style="background-image: URL('/Remix/kuvat/valikko/ulos.png');" onclick="siirry('/Remix/kirjauduulos.php')"></div>
            <?php
        } else {
            ?>
            <div style="background-image: URL('/Remix/kuvat/valikko/rekisteroidy.png');" onclick="siirry('/Remix/rekisteroi.php')"></div>
            <div style="background-image: URL('/Remix/kuvat/valikko/kirjaudu.png');" onclick="siirry('/Remix/kirjaudu.php')"></div>
            <?php
        }
        ?>
    </div>
</div>
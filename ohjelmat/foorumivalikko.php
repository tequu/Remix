<div id="foorumivalikko">
    <div class="nappi" style="background-image: URL('/Remix/kuvat/valikko/keskustelut.png')" onclick="siirry('/Remix/foorumi/index.php')"></div>
    <div class="nappi" style="background-image: URL('/Remix/kuvat/valikko/tapahtumat.png')" onclick="siirry('/Remix/foorumi/tapahtumat.php')"></div>
    <div class="nappi" style="background-image: URL('/Remix/kuvat/valikko/seuransivuille.png')" onclick="siirry('/Remix/index.php')"></div>
    <div id="tunnus">
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
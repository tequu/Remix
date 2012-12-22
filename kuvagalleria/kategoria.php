<?php
$kategoriatid = mysql_real_escape_string($_GET['kategoriatid']);
$kuvanosoite = "/Remix/kuvat/kuvakategoriat/" . $kategoriatid . "/";
$kysely = kysely($yhteys, "SELECT nimi FROM kuvakategoriat WHERE joukkueetID='0' AND id='" . $kategoriatid . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <div class="nimi"><?php echo $tulos['nimi']; ?></div>
    <div class="kuvat">
    </div>
    <script id="kuvagalleria_kuva-template" type="text/html">
        <div id="isokuva-pohja">
            <div id="isokuva">
                <div id="yla"><span id="sulje">Sulje X</span></div>
                <div id="isokuva-content">
                    <div id="edellinen">
                        <div id="nuoli"></div>
                    </div>
                    <div id="kuva">
                    </div>
                    <div id="seuraava">
                        <div id="nuoli"></div>
                    </div>
                    <div id="kuvaus"></div>  
                </div>
            </div>
            <div id="poista-isokuva"></div>
        </div>
    </script>
    <script id="kuvagalleria_kategoria-template" type="text/html">
        {{#list}}
        <div class="kuva" data-id="{{id}}" style="background-image: URL('<?php echo $kuvanosoite; ?>{{src}}')"></div>
        {{/list}}
    </script>

    <script type="text/javascript" src="/Remix/js/mustache.js"></script>
    <script type="text/javascript" src="/Remix/js/underscore-min.js"></script>
    <script type="text/javascript" src="/Remix/js/backbone-min.js"></script>
    <script type="text/javascript" src="/Remix/js/kuvagalleria.js"></script>
    <?php
}
?>
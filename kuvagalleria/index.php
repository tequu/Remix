<script id="kuvagalleria_kategoriat-template" type="text/html">
    {{#kategoriat}}
    <div class="nimi" data-id="{{id}}">{{otsikko}}</div>
    <div class="kuvat" id="kategoria_{{id}}" data-id="{{id}}">
        {{#kuvat}}
        <div class="kuva" data-id="{{id}}" style="background-image: URL('{{kuva}}')"></div>
        {{/kuvat}}
        <div id="clear"></div>
    </div>
    {{/kategoriat}}
</script>
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
<script id="sivunumerot-template" type="text/html">
    {{#ensimmainen}}<div id="ensimmainen"></div>{{/ensimmainen}}
    {{#edellinen}}<div id="edellinen"></div>{{/edellinen}}
    {{#sivunumerot}}
    <div id="{{#valittu}}valittu{{/valittu}}sivunumero">{{sivunumero}}</div>
    {{/sivunumerot}}
    {{#seuraava}}<div id="seuraava"></div>{{/seuraava}}
    {{#viimeinen}}<div id="viimeinen"></div>{{/viimeinen}}
</script>
<script type="text/javascript" src="/Remix/js/mustache.js"></script>
<script type="text/javascript" src="/Remix/js/underscore-min.js"></script>
<script type="text/javascript" src="/Remix/js/backbone-min.js"></script>
<script type="text/javascript" src="/Remix/js/sivunumerot.js"></script>
<script type="text/javascript" src="/Remix/js/kuvagalleria.js"></script>
<hr />
<h4 class="ala_otsikko">K�ytt�jien haku</h4>
<input type="hidden" id="sivuid" value="<?php echo $okayttajat; ?>" />
<div id="hakukentta">
    <div class="vasen">Hae</div><div class="oikea"><input type="text" id="haku" /></div>
</div>
<div class="vasen">J�rjestys</div>
<div class="oikea">
    <input type="radio" name="jarjesta" id="jarjesta" class="jarjesta" value="0" CHECKED/>Etunimi
    <input type="radio" name="jarjesta" id="jarjesta" class="jarjesta" value="1" />Sukunimi
    <input type="radio" name="jarjesta" id="jarjesta" class="jarjesta" value="2" />Tunnus
    <input type="radio" name="jarjesta" id="jarjesta" class="jarjesta" value="3" />S�hk�posti
    <input type="radio" name="jarjesta" id="jarjesta" class="jarjesta" value="4" />Syntym�vuosi
</div>
<div class="clear"></div>
<hr />
<div id="tulokset"></div>

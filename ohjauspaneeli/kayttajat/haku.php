<hr />
<div class="ala_otsikko">K�ytt�jien haku</div>
Hae:<input type="text" id="haku" onkeyup="haeKayttajat(<?php echo $okayttajat; ?>, 'haku','jarjesta','tulokset')"/><br />
J�rjest�:<br />
<input type="radio" name="jarjesta" id="jarjesta" value="0" onchange="haeKayttajat(<?php echo $okayttajat; ?>, 'haku','jarjesta','tulokset')" CHECKED/>Etunimell�<br />
<input type="radio" name="jarjesta" id="jarjesta" value="1" onchange="haeKayttajat(<?php echo $okayttajat; ?>, 'haku','jarjesta','tulokset')" />Sukunimell�<br />
<input type="radio" name="jarjesta" id="jarjesta" value="2" onchange="haeKayttajat(<?php echo $okayttajat; ?>, 'haku','jarjesta','tulokset')" />Tunnuksella<br />
<input type="radio" name="jarjesta" id="jarjesta" value="3" onchange="haeKayttajat(<?php echo $okayttajat; ?>, 'haku','jarjesta','tulokset')" />S�hk�postilla<br />
<input type="radio" name="jarjesta" id="jarjesta" value="4" onchange="haeKayttajat(<?php echo $okayttajat; ?>, 'haku','jarjesta','tulokset')" />Syntym�vuodella<br />
<hr />
<div id="tulokset"></div><hr />

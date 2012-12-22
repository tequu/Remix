<hr />
<div class="ala_otsikko">Käyttäjien haku</div>
Hae:<input type="text" id="haku" onkeyup="haeKayttajat(<?php echo $okayttajat; ?>, 'haku','jarjesta','tulokset')"/><br />
Järjestä:<br />
<input type="radio" name="jarjesta" id="jarjesta" value="0" onchange="haeKayttajat(<?php echo $okayttajat; ?>, 'haku','jarjesta','tulokset')" CHECKED/>Etunimellä<br />
<input type="radio" name="jarjesta" id="jarjesta" value="1" onchange="haeKayttajat(<?php echo $okayttajat; ?>, 'haku','jarjesta','tulokset')" />Sukunimellä<br />
<input type="radio" name="jarjesta" id="jarjesta" value="2" onchange="haeKayttajat(<?php echo $okayttajat; ?>, 'haku','jarjesta','tulokset')" />Tunnuksella<br />
<input type="radio" name="jarjesta" id="jarjesta" value="3" onchange="haeKayttajat(<?php echo $okayttajat; ?>, 'haku','jarjesta','tulokset')" />Sähköpostilla<br />
<input type="radio" name="jarjesta" id="jarjesta" value="4" onchange="haeKayttajat(<?php echo $okayttajat; ?>, 'haku','jarjesta','tulokset')" />Syntymävuodella<br />
<hr />
<div id="tulokset"></div><hr />

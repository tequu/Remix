<div class="ala_otsikko">Luo johtokunnan jäsen</div>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?sivuid=" . $ojohtokunta; ?>" method="post">
    <input type="hidden" name="ohjaa" value="53" />
    <table>
        <tr>
            <td>Etunimi:</td>
            <td><input type="text" name="etunimi" value="<?php echo $_POST['etunimi']; ?>" /></td>
        </tr>
        <tr>
            <td>Sukunimi:</td>
            <td><input type="text" name="sukunimi" value="<?php echo $_POST['sukunimi']; ?>" /></td>
        </tr>
        <tr><td height="10px" colspan="2"></td></tr>
        <tr>
            <td>Arvonimi:</td>
            <td>
                <input type="radio" id="eiarvonimea" name="arvovalinta" value="ei" onclick="$('#arvonimit').hide()"<?php echo $_POST['arvovalinta'] == "ei" ? " CHECKED" : ""; ?>/>
                <label for="eiarvonimea">Ei arvonimeä</label><br />
                <input type="radio" name="arvovalinta" value="luettelo" onclick="$('#arvonimit').hide()"<?php echo $_POST['arvovalinta'] == "luettelu" ? " CHECKED" : ""; ?> />
                <select name="arvonimil">
                    <option<?php echo $_POST['arvovalinta'] == "luettelo" && $_POST['arvonimil'] == "Puheenjohtaja" ? " SELECTED" : ""; ?>>Puheenjohtaja</option>
                    <option<?php echo $_POST['arvovalinta'] == "luettelo" && $_POST['arvonimil'] == "Sihteeri" ? " SELECTED" : ""; ?>>Sihteeri</option>
                    <option<?php echo $_POST['arvovalinta'] == "luettelo" && $_POST['arvonimil'] == "Rahastonhoitaja" ? " SELECTED" : ""; ?>>Rahastonhoitaja</option>
                    <option<?php echo $_POST['arvovalinta'] == "luettelo" && $_POST['arvonimil'] == "Varustevastaava" ? " SELECTED" : ""; ?>>Varustevastaava</option>
                    <option<?php echo $_POST['arvovalinta'] == "luettelo" && $_POST['arvonimil'] == "Tiedottaja" ? " SELECTED" : ""; ?>>Tiedottaja</option>
                </select>
                <br />
                <input type="radio" id="muu" name="arvovalinta" value="muu" onclick="$('#arvonimit').show()"<?php echo $_POST['arvovalinta'] == "muu" ? " CHECKED" : ""; ?> />
                <label for="muu">Jokin muu, mikä?</label>
                <br />
                <div id="arvonimit" style="display:none">
                    <input type="text" name="arvonimit" value="<?php echo $_POST['arvonimit']; ?>" />
                </div>
            </td>
        </tr>
        <tr><td height="10px" colspan="2"></td></tr>
        <tr>
            <td>Sähköposti:</td>
            <td><input type="text" name="sahkoposti" value="<?php echo $_POST['sahkoposti']; ?>" /></td>
        </tr>
        <tr>
            <td>Puhelin:</td>
            <td><input type="text" name="puhelin" value="<?php echo $_POST['puhelin']; ?>" /></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Lisää" /></td>
        </tr>
    </table>
</form>

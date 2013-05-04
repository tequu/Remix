<div id="levea_content">
    <div id="ohjauspaneeli">
        <div class="otsikko">Ohjauspaneeli</div>
        <div id="ohjauspaneelin_valinta_valikko">
            Ohjauspaneelin sivu:
            <select name="sivuid" id="sivuid" onchange="vaihdaHallinta()">
                <?php
                $masteroikeudet = false;
                $adminoikeudet = false;
                $joukkueoikeudet = false;
                
                if (tarkistaAdminOikeudet($yhteys, "Masteradmin")) {
                    $masteroikeudet = true;
                } else {
                    if (tarkistaAdminOikeudet($yhteys, "Admin")) {
                        $adminoikeudet = true;
                    }
                    if (tarkistaHallintaOikeudetJoukkueeseen($yhteys, 0)) {
                        $joukkueoikeudet = true;
                    }
                }
                echo "<option value=\"" . $oomattiedot . "\"" . (!isset($_GET['sivuid']) || $_GET['sivuid'] == $oomattiedot ? " SELECTED" : "") . ">Omien tietojen hallinta</option>";
                if ($masteroikeudet) {
                    echo "<option value=\"" . $okeskustelualueryhma . "\"" . ($_GET['sivuid'] == $okeskustelualueryhma ? " SELECTED" : "") . ">Keskustelualueiden ryhmien hallinta</option>";
                }
                if ($masteroikeudet) {
                    echo "<option value=\"" . $okeskustelualue . "\"" . ($_GET['sivuid'] == $okeskustelualue ? " SELECTED" : "") . ">Keskustelualueiden hallinta</option>";
                    echo "<option value=\"" . $okayttajat . "\"" . ($_GET['sivuid'] == $okayttajat ? " SELECTED" : "") . ">Käyttäjien hallinta</option>";
                }
                if ($masteroikeudet || $joukkueoikeudet) {
                    echo "<option value=\"" . $ojoukkue . "\"" . ($_GET['sivuid'] == $ojoukkue ? " SELECTED" : "") . ">Joukkueiden hallinta</option>";
                }
                if ($masteroikeudet || $joukkueoikeudet) {
                    echo "<option value=\"" . $otiedotus . "\"" . ($_GET['sivuid'] == $otiedotus ? " SELECTED" : "") . ">Tiedotusten hallinta</option>";
                }
                if ($masteroikeudet || $joukkueoikeudet) {
                    echo "<option value=\"" . $opelaajat . "\"" . ($_GET['sivuid'] == $opelaajat ? " SELECTED" : "") . ">Pelaajien hallinta</option>";
                    echo "<option value=\"" . $oyhteyshenkilot . "\"" . ($_GET['sivuid'] == $oyhteyshenkilot ? " SELECTED" : "") . ">Yhteyshenkilöiden hallinta</option>";
                    echo "<option value=\"" . $osarjataulukko . "\"" . ($_GET['sivuid'] == $osarjataulukko ? " SELECTED" : "") . ">Sarjataulukkojen hallinta</option>";
                    echo "<option value=\"" . $opelit . "\"" . ($_GET['sivuid'] == $opelit ? " SELECTED" : "") . ">Pelien hallinta</option>";
                    echo "<option value=\"" . $otilastot . "\"" . ($_GET['sivuid'] == $otilastot ? " SELECTED" : "") . ">Tilastojen hallinta</option>";
                }
                if ($masteroikeudet || $adminoikeudet || $joukkueoikeudet) {
                    echo "<option value=\"" . $okuvat . "\"" . ($_GET['sivuid'] == $okuvat ? " SELECTED" : "") . ">Kuvien hallinta</option>";
                    echo "<option value=\"" . $ouutiset . "\"" . ($_GET['sivuid'] == $ouutiset ? " SELECTED" : "") . ">Uutisten hallinta</option>";
                }
                if ($masteroikeudet || $adminoikeudet) {
                    echo "<option value=\"" . $ojohtokunta . "\"" . ($_GET['sivuid'] == $ojohtokunta ? " SELECTED" : "") . ">Johtokunnan hallinta</option>";
                }
                ?>
            </select>
        </div>
        <hr />
        <?php
        $id = $_GET['sivuid'];
        if ($id == $okeskustelualueryhma)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/keskustelualueidenryhmienhallinta.php");
        else if ($id == $okeskustelualue)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/keskustelualuehallinta.php");
        else if ($id == $okayttajat)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kayttajienhallinta.php");
        else if ($id == $ojoukkue)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/joukkueidenhallinta.php");
        else if ($id == $otiedotus)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tiedotuksethallinta.php");
        else if ($id == $opelaajat)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelaajathallinta.php");
        else if ($id == $oyhteyshenkilot)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/yhteyshenkilothallinta.php");
        else if ($id == $osarjataulukko)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/sarjataulukothallinta.php");
        else if ($id == $opelit)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/pelithallinta.php");
        else if ($id == $otilastot)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/tilastothallinta.php");
        else if ($id == $okuvat)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/kuvathallinta.php");
        else if ($id == $ouutiset)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/uutisethallinta.php");
        else if ($id == $ojohtokunta)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/johtokuntahallinta.php");
        else
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/omattiedot.php");
        ?>
    </div>
</div>
<div id="levea_content">
    <div id="ohjauspaneeli">
        <h1 class="otsikko">Ohjauspaneeli</h1>
        <?php include("/home/fbcremix/public_html/Remix/ohjauspaneeli/valikko.php"); ?>
        <hr />
        <?php
        $id = $_GET['sivuid'];
        if ($id == $okeskustelualueryhma)
            include("/home/fbcremix/public_html/Remix/ohjauspaneeli/keskustelualueryhmathallinta.php");
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
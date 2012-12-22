<?php
if ($joukkue == 0) {
    $kysely = kysely($yhteys, "SELECT kk.id id, kk.nimi nimi FROM kuvakategoriat kk WHERE joukkueetID='0'");
} else {
    $kysely = kysely($yhteys, "SELECT kk.id id, kk.nimi nimi FROM kuvakategoriat kk, joukkueet j WHERE joukkueetID=j.id AND j.id='" . $joukkue . "' AND kausi='" . $kausi . "'");
}
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr />
    <form id="muokkaakuvia" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="sivuid" value="<?php echo $okuvat; ?>" />
        <input type="hidden" name="joukkue" value="<?php echo $joukkue; ?>" />
        <input type="hidden" name="mode" value="muokkaa" />
        Valitse kuvakategoria:
        <select name="kuvakategoriatid" onchange="laheta('muokkaakuvia', [], [])">
            <?php
            echo!isset($_GET['kuvakategoriatid']) ? "<option></option>" : "";
            do {
                echo "<option value=\"" . $tulos['id'] . "\"" . ($_GET['kuvakategoriatid'] == $tulos['id'] ? " SELECTED" : "") . ">" . $tulos['nimi'] . "</option>";
            } while ($tulos = mysql_fetch_array($kysely));
            ?>
        </select><br />
    </form>
    <?php
}
?>
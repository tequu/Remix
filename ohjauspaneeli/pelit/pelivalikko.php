<?php
$kysely = kysely($yhteys, "SELECT p.id id, vastustaja, UNIX_TIMESTAMP(aika) aika, koti FROM pelit p, peliryhmat pr, joukkueet j " .
        "WHERE peliryhmatID=pr.id AND joukkueetID=j.id AND j.id='" . $joukkue . "' AND pr.id='" . $peliryhmatid . "' AND kausi='" . $kausi . "'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr />
    <form id="muokkaapelia" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="sivuid" value="<?php echo $opelit; ?>" />
        <input type="hidden" name="joukkue" value="<?php echo $joukkue; ?>" />
        <input type="hidden" name="mode" value="muokkaapelia" />
        <input type="hidden" name="peliryhmatid" value="<?php echo $peliryhmatid; ?>" />
        Valitse peli:
        <select name="pelitid" onchange="laheta('muokkaapelia',[],[])">
            <?php
            echo!isset($_GET['pelitid']) ? "<option></option>" : "";
            do {
                echo "<option value=\"" . $tulos['id'] . "\"".($_GET['pelitid'] == $tulos['id']?" CHECKED":"").">" . 
                        date("d.m.Y H:i", $tulos['aika']) ." ". ($tulos['koti'] ? "FBC Remix - " . $tulos['vastustaja'] : $tulos['vastustaja'] . " - FBC Remix") .
                "</option>";
            } while ($tulos = mysql_fetch_array($kysely));
            ?>
        </select><br />
    </form>
    <?php
}
?>

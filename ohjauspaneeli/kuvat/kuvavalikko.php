<?php
$kysely = kysely($yhteys, "SELECT k.id id, kuva FROM kuvat k, kuvakategoriat kk, joukkueet j ".
        "WHERE kuvakategoriatID=kk.id AND j.id=joukkueetID AND j.id='" . $joukkue . "' AND kk.id='".$kuvakategoriatid."' AND kausi='".$kausi."'");
if ($tulos = mysql_fetch_array($kysely)) {
    ?>
    <hr />
    <form name="muokkaakuvaa" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" name="sivuid" value="<?php echo $okuvat; ?>" />
        <input type="hidden" name="joukkue" value="<?php echo $joukkue; ?>" />
        <input type="hidden" name="mode" value="muokkaakuvaa" />
        <input type="hidden" name="kuvakategoriatid" value="<?php echo $kuvakategoriatid; ?>" />
        Valitse kuvakategoria:<select name="kuvatid" onchange="laheta('muokkaakuvaa',[],[])">
            <?php
            echo!isset($_GET['kuvatid']) ? "<option></option>" : "";
            do {
                echo"<option value=\"" . $tulos['id'] . "\"" . ($_GET['kuvatid'] == $tulos['id'] ? " SELECTED" : "") . ">".$tulos['kuva']."</option>";
            } while ($tulos = mysql_fetch_array($kysely))
            ?>
        </select>
    </form>
    <?php
}
?>
<div class="otsikko" style="background-image:URL('/Remix/kuvat/kirjoitavieraskirjaan.png')"></div>
<div class="takaisin" onclick="siirry('/Remix/vieraskirja.php');"></div>
<div id="error"><?php echo $error; ?></div>
<form action="/Remix/vieraskirja.php?mode=kirjoita" name="vieraskirja" method="post">
    <input type="hidden" name="ohjaa" value="75" />
    <?php
    if (isset($_SESSION['id'])) {
        ?>
        Nimimerkki:<br /><input type="text" name="nimi" value="<?php echo $_SESSION['nimimerkki']; ?>" size="26" /><br />
        Seura: <br /> <input type="text" name="seura" value="<?php echo $_POST['seura']; ?>" size="26" /> <br />
        Joukkue: <br /> <input type="text" name="joukkue" value="<?php echo $_POST['joukkue']; ?>" size="26" /> <br />
        <?php
    } else {
        ?>
        Nimimerkki: <br /> <input type='text' name='nimi' value="<?php echo $_POST['nimi']; ?>" size="26" /> <br />
        Seura: <br /> <input type='text' name='seura' value="<?php echo $_POST['seura']; ?>" size="26" /> <br />
        Joukkue: <br /> <input type='text' name='joukkue' value="<?php echo $_POST['joukkue']; ?>" size="26" /> <br /> 
        Sähköposti: <br /> *Saat sähköpostiisi viestin josta sinun on varmistettava viestisi.<br /><input type='text' name='email' size="26" /> <br />
        <?php
    }
    ?>
    Viesti: <br /> <textarea name='viesti' rows='6' cols='70' ><?php echo $_POST['viesti']; ?></textarea> <br /> <br /> 
    <div class="laheta" onclick="laheta('vieraskirja', [], []);"></div>
</form>
<?php
include("/home/fbcremix/public_html/Remix/yla.php");
$osat = explode("/", $_SERVER['HTTP_REFERER']);
if ($osat[count($osat) - 1] != "kirjaudu.php")
    $_SESSION['kirjaudu'] = $osat[count($osat) - 1];
?>
<div id="levea_content">
    <div id="kirjaudu">
        <div class="otsikko" style="background-image:URL('/Remix/kuvat/kirjaudu.png')"></div>
        <div id="error"><?php echo $error; ?></div>
        <form id="kirjauduform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="ohjaa" value="1" />
            <div id="osa">
                Käyttäjätunnus:<br />
                Salasana:
            </div>
            <div id="osa">
                <input type="text" name="login" value="<?php echo $_POST['login']; ?>" />
                <input type="password" name="salasana" />
            </div>
            <div id="clear"></div>
            <div class="kirjaudusisaan" onclick="laheta('kirjauduform', [], [], []);" /></div>
            <a href="/Remix/unohtunutsalasana.php">Unohditko salasanasi?</a>
        </form>
    </div>
</div>
<?php
include("/home/fbcremix/public_html/Remix/ala.php");
?>
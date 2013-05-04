<div id="ilmoittaudu">
    <?php
    echo "<div class=\"";
    if ($kin) {
        echo "in_ilmoittautunut";
    } else {
        echo "in";
    }
    echo "\" data-id=\"" . $tapahtumatid. "\"></div>" .
    "<div class=\"";
    if ($kout) {
        echo "out_ilmoittautunut";
    } else {
        echo "out";
    }
    echo "\" data-id=\"" . $tapahtumatid . "\"></div>";
    ?>
</div>
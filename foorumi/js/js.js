$(document).ready(function() {
    $(".liiku").click(function() {
        parent.location = "/Remix/foorumi/" + $(this).data("url");
    });
    $(".laheta").click(function() {
        $("#"+$(this).data("form")).submit();
    });
});
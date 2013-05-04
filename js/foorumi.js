function haeKeskustelualueet(ryhmatid, kysely, kayttaja, success){
    var data = {
        ryhmatid: ryhmatid,
        kyselyid: kysely,
        kayttaja: kayttaja
    };
    $.ajax({
        type: "POST",
        url: "/Remix/json/haekeskustelualueet.php",
        data: data,
        dataType: "json",
        success: success
    });
}
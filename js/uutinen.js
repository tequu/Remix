function haeUutinen(id){
    id = parseInt(id);
    if(typeof id == "number"){
        $.ajax({
            type: "GET",
            url: "/Remix/haeuutinen.php",
            data: {
                id: id
            },
            dataType: "json",
            success: tulostaUutinen
        });
    }
}
function tulostaUutinen(data, textStatus, jqXHR){
    var kirjoitettava = "<div id=\"uutinen\">";
    if(data.kirjoitusaika != null){
        var aika = new Date(parseInt(data.kirjoitusaika*1000));
        var kuvanotsikko = "/Remix/kuvat/uutiset/uutinen_"+(aika.getDate()<10?"0":"")+aika.getDate()+(aika.getMonth()+1<10?"0":"")+(aika.getMonth()+1)+aika.getFullYear()+"/"+data.otsikko+"/"+data.kuva
        
        kuvanotsikko = kuvanotsikko.replace(/ä/gi, "a");
        kuvanotsikko = kuvanotsikko.replace(/ö/gi, "o");
        kuvanotsikko = kuvanotsikko.replace(/ /gi, "_");
        kuvanotsikko = kuvanotsikko.replace(/\?/gi, "");
        kuvanotsikko = kuvanotsikko.replace(/\!/gi, "");
        kirjoitettava += "<div id=\"otsikko\">"+data.otsikko+"</div>"+
        "<div id=\"kuvaus\">"+data.kuvaus+"</div>"+
        "<div id=\"tiedot\">"+aika.getDate()+"."+(aika.getMonth()+1)+"."+aika.getFullYear()+" "+(aika.getHours()<10?"0":"")+aika.getHours()+":"+(aika.getMinutes()<10?"0":"")+aika.getMinutes()+" "+data.kirjoittaja+"</div>";
        if(data.kuva != 'ei')
            kirjoitettava += "<div id=\""+(data.asettele=='v'?"kuvavasemmalle":"kuvakeskelle")+"\">"+"<img src=\""+kuvanotsikko+"\"/></div>";
        kirjoitettava += "<div id=\"teksti\">"+data.uutinen+"</div>";
    }
    else
        kirjoitettava += "<div class='otsikko'>Uutista ei löydy</div>";
    kirjoitettava += "</div>";
    $("#uutisetsivu_oikea").html(kirjoitettava);
    $("#uutisetsivu_oikea").slideDown("slow");
}
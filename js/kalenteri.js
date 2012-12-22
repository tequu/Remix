var kuukaudet = new Array("Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu", "Toukokuu", "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu");
var ensimmainenPaiva = 1;
var kalenteriKuukausi = 1;
var kalenteriVuosi = 1;
$(document).ready(function(){
    if($('#kalenteri_pohja').length){
        piirraKalenteri();
        var paiva = new Date();
        piirraKuukausi(paiva.getMonth(), paiva.getFullYear());
    }
});
function getKuukausi(kuukausi, vuosi, suunta){
    var paiva;
    if(suunta == "t"){
        if(kuukausi == 0){
            paiva = new Date(--vuosi, 11, 1);
        } else {
            paiva = new Date(vuosi, --kuukausi, 1);
        }
    } else {
        if(kuukausi == 11){
            paiva = new Date(++vuosi, 0, 1);
        } else {
            paiva = new Date(vuosi, ++kuukausi, 1);
        }
    }
    return paiva;
}
function kuukaudenPituus(kuukausi, vuosi){
    var pituus;
    if((kuukausi == 3) || (kuukausi == 5) || (kuukausi == 8) || (kuukausi == 10))
        pituus = 30;
    else if(kuukausi == 1){
        if (vuosi/4 - parseInt(vuosi/4) != 0)
            pituus = 28
        else
            pituus = 29
    }
    else{
        pituus = 31     
    }
    return pituus;
}
function piirraKalenteri(){
    var text = "";
    var tamapaiva = new Date();
    kuukausi = tamapaiva.getMonth();
    vuosi = tamapaiva.getFullYear();
    paiva = new Date(vuosi, kuukausi, 1);
    var paivat= new Array("ma", "ti", "ke", "to", "pe", "la", "su");
    text = "<div id=\"kalenteri\">"+
    "<div class=\"valikko\">"+
    "<div class=\"edellinen\"><</div>"+
    "<div class=\"kuukausi_vuosi\"></div>"+
    "<div class=\"seuraava\">></div>"+
    "</div>"+
    "<div class=\"kalenteri_content\">"+
    "<div class=\"viikonpaivat\">";
    for(var i = 0;i<paivat.length;i++){
        text += "<div>"+paivat[i]+"</div>";
    }
    text += "</div>";
    var x = 1;
    for(var i = 0;i<6;i++){
        text += "<div class=\"viikko\">";
        for(var u = 0;u<7;u++){
            text+= "<div class=\"paiva"+x+"\"></div>";
            x++;
        }
        text += "</div>";
    }
    text += "</div></div>";
    $("#kalenteri_pohja").html(text);
}
function piirraKuukausi(kuukausi, vuosi){
    kalenteriKuukausi = kuukausi;
    kalenteriVuosi = vuosi;
    var tamakuukausi = new Date(vuosi, kuukausi, 1);
    var edellinenKuukausi = getKuukausi(tamakuukausi.getMonth(), tamakuukausi.getFullYear(), 't');
    var seuraavaKuukausi = getKuukausi(tamakuukausi.getMonth(), tamakuukausi.getFullYear(), 'e');
    var edellisenKuukaudenPituus = kuukaudenPituus(edellinenKuukausi.getMonth(), edellinenKuukausi.getFullYear());
    var viikonpaiva = (tamakuukausi.getDay() == 0) ? 7:tamakuukausi.getDay();
    var laskuri = 1;
    for(var i = (edellisenKuukaudenPituus+2)-viikonpaiva; i <= edellisenKuukaudenPituus; i++){
        $("#kalenteri .paiva"+laskuri).removeAttr("id").unbind("click").attr("id", "harmaa").html(i);
        laskuri++;
    }
    ensimmainenPaiva = laskuri;
    for(var i = 1; i <= kuukaudenPituus(tamakuukausi.getMonth(), tamakuukausi.getFullYear()); i++){
        $("#kalenteri .paiva"+laskuri).removeAttr("id").unbind("click").html(i);
        laskuri++;
    }
    var i = 1;
    while(laskuri<=42){
        $("#kalenteri .paiva"+laskuri).removeAttr("id").unbind("click").attr("id", "harmaa").html(i);
        i++;
        laskuri++;
    }
    var tanaan = new Date();
    $("#kalenteri .paiva"+(ensimmainenPaiva-1+tanaan.getDate())).removeAttr("id");
    if(kuukausi == tanaan.getMonth() && vuosi == tanaan.getFullYear()){
        $("#kalenteri .paiva"+(ensimmainenPaiva-1+tanaan.getDate())).attr("id", "tamapaiva");
    }
    $("#kalenteri .kuukausi_vuosi").html(kuukaudet[tamakuukausi.getMonth()]+" "+tamakuukausi.getFullYear());
    $("#kalenteri .edellinen").unbind("click").bind("click", function(){
        uusiKuukausi(edellinenKuukausi.getMonth(), edellinenKuukausi.getFullYear());
    });
    $("#kalenteri .seuraava").unbind("click").bind("click", function(){
        uusiKuukausi(seuraavaKuukausi.getMonth(), seuraavaKuukausi.getFullYear())
    });
    haePelit(tamakuukausi);
}
function uusiKuukausi(kuukausi, vuosi){
    piirraKuukausi(kuukausi, vuosi);
}
function haePelit(aloitus){
    var lopetus = (getKuukausi(aloitus.getMonth(), aloitus.getFullYear(), 'e').getTime()-(24*60*60*1000))/1000;
    aloitus = aloitus.getTime()/1000;
    $.ajax({
        type: "GET",
        url: "/Remix/haepelit.php",
        data: {
            alku: aloitus,
            loppu: lopetus,
            joukkueid: joukkueid
        },
        dataType: "json",
        success: piirraPelit
    });
}

function piirraPelit(data, textStatus, jqXHR){
    var i = 0;
    if(typeof data[i] != "undefined"){
        var tanaan = new Date();
        while(data[i] != null){
            var paiva = new Date(data[i].aika);
            if(data[i].joukkueet != "undefined"){
                var u = 0;
                var title = "";
                while(data[i].joukkueet[u] != null){
                    title += data[i].joukkueet[u]+", ";
                    u++;
                }
                title = title.substring(0, title.length-2);
                $("#kalenteri .viikko .paiva"+(ensimmainenPaiva-1+paiva.getDate())).attr("title", title);
            }
            if(data[i].kotiturnaus == 1 && paiva.getDate() == tanaan.getDate()){
                $("#kalenteri .viikko .paiva"+(ensimmainenPaiva-1+paiva.getDate())).attr("id", "kotiturnaustanaan").bind("click", function(){
                    var kuukaudenPaiva = $(this).html();
                    var hakupaiva = new Date(paiva.getFullYear(), paiva.getMonth(), kuukaudenPaiva, 0, 0, 0, 0);
                    siirry("/Remix/pelit.php?aika="+hakupaiva.getTime());
                });
            } else if(paiva.getDate() == tanaan.getDate()){
                $("#kalenteri .viikko .paiva"+(ensimmainenPaiva-1+paiva.getDate())).attr("id", "pelitanaan").bind("click", function(){
                    var kuukaudenPaiva = $(this).html();
                    var hakupaiva = new Date(paiva.getFullYear(), paiva.getMonth(), kuukaudenPaiva, 0, 0, 0, 0);
                    siirry("/Remix/pelit.php?aika="+hakupaiva.getTime());
                });
            } else if(data[i].kotiturnaus == 1){
                $("#kalenteri .viikko .paiva"+(ensimmainenPaiva-1+paiva.getDate())).attr("id", "kotiturnaus").bind("click", function(){
                    var kuukaudenPaiva = $(this).html();
                    var hakupaiva = new Date(paiva.getFullYear(), paiva.getMonth(), kuukaudenPaiva, 0, 0, 0, 0);
                    siirry("/Remix/pelit.php?aika="+hakupaiva.getTime());
                });
            } else {
                $("#kalenteri .viikko .paiva"+(ensimmainenPaiva-1+paiva.getDate())).attr("id", "peli").bind("click", function(){
                    var kuukaudenPaiva = $(this).html();
                    var hakupaiva = new Date(paiva.getFullYear(), paiva.getMonth(), kuukaudenPaiva, 0, 0, 0, 0);
                    siirry("/Remix/pelit.php?aika="+hakupaiva.getTime());
                });
            }
            i++;
        }
    }
}
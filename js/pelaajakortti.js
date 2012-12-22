var pelaajakorttinykyinen = 0;
$(document).ready(function(){
    $("#pelaajat #pelaaja").click(function(){
        if(!$("#pelaajakortti-pohja").is(":visible")){
            alustaPelaajakortti();
            pelaajakorttinykyinen = $(this).attr("title");
            haeJaTulostaPelaajakortti();
        }
    });
});

function haeJaTulostaPelaajakortti(){
    $("#pelaajakortti #kuva").empty().css("background-image", "URL(\"/Remix/kuvat/loader.gif\")");
    $("#pelaajakortti #tiedot").empty().css("background-image", "URL(\"/Remix/kuvat/loader.gif\")");
    $.ajax({
        type: "GET",
        url: "/Remix/haepelaajantiedot.php",
        data: {
            id: pelaajakorttinykyinen, 
            joukkueid: joukkueid
        },
        dataType: "json",
        success: function (data, textStatus, jqXHR){
            setTimeout(function (){
                paivitaPelaajakortti(data, textStatus, jqXHR);
            }, 500);
        }
    });
}
function alustaPelaajakortti(){
    var pelaajakortti = "<div id=\"pelaajakortti-pohja\"><div id=\"pelaajakortti\">"+
    "<div id=\"yla\"><span id=\"sulje\">Sulje X</span></div>"+
    "<div id=\"pelaajakortti-content\">"+
    "<div id=\"edellinen\"></div>"+
    "<div id=\"kuva\">"+
    "</div><div id=\"tiedot\">"+
    "</div>"+
    "<div id=\"seuraava\"></div>"+
    "</div>"+
    "</div><div class=\"poistapelaajakortti\"></div>"+
    "</div>";
    $("body").append(pelaajakortti);
    $("#pelaajakortti-pohja").slideDown("slow");
    $("#pelaajakortti-pohja #sulje").bind("click", function (){
        suljePelaajakortti();
    });
    $(".poistapelaajakortti").bind("click", function(){
        suljePelaajakortti();
    })
}
function suljePelaajakortti(){
    $("#pelaajakortti-pohja #sulje").unbind("click");
    $(".poistapelaajakortti").unbind("click");
    $("#pelaajakortti-pohja").fadeOut(function(){
        $(this).remove();
    });
}
function paivitaPelaajakortti(data, textStatus, jqXHR){
    var kuva = data.kuva;
    var nimi = data.sukunimi + " " + data.etunimi;
    var numero = data.numero;
    var syntymavuosi = data.syntymavuosi;
    if(kuva != null && kuva != ""){
        $("#pelaajakortti #kuva").css("background-image", "URL(\"/Remix/kuvat/pelaajakortit/"+joukkueid+"/pelaajat/"+kuva+"\")");
    } else{
        $("#pelaajakortti #kuva").css("background-image", "");
    }
    if(data.kapteeni == 1 && data.maalivahti == 1){
        $("#pelaajakortti #kuva").append("<div id=\"kapteenijamaalivahti\"></div>");
    }
    else if(data.kapteeni == 1){
        $("#pelaajakortti #kuva").append("<div id=\"kapteeni\"></div>");
    }
    else if(data.maalivahti == 1){
        $("#pelaajakortti #kuva").append("<div id=\"maalivahti\"></div>");
    }
    var tiedot = "<div id=\"nimi\">"+nimi+" #"+numero+"</div>"+
    "<div id=\"tieto\">"+
    "<div id=\"tiedonnimi\">Syntymävuosi</div>"+
    "<div id=\"tiedontieto\">"+syntymavuosi+"</div>"+
    "</div>";
    var i = 0;
    if(typeof data.lisatieto != "undefined"){
        while(data.lisatieto[i] != null){
            tiedot += "<div id=\"tieto\">"+
            "<div id=\"tiedonnimi\">"+data.lisatieto[i].nimi+"</div>"+
            "<div id=\"tiedontieto\">"+data.lisatieto[i].tieto+"</div>"+
            "</div>";
            i++;
        }  
    }
    $("#pelaajakortti #tiedot").css("background-image", "").append(tiedot);
    var edellinen = "", seuraava = "";
    var loytyi = false;
    $("#pelaajat").find("#pelaaja").each(function (){
        if($(this).attr("title") == pelaajakorttinykyinen){
            loytyi = true;
            return true;
        }
        if(loytyi){
            seuraava = $(this).attr("title");
            return false;
        }
        edellinen = $(this).attr("title");
        return true;
    });
    $("#pelaajakortti #edellinen").attr("title", edellinen).unbind("click").bind("click", function(){
        if($(this).attr("title") != ""){
            pelaajakorttinykyinen = $(this).attr("title");
            haeJaTulostaPelaajakortti();
        }
    });
    $("#pelaajakortti #seuraava").attr("title", seuraava).unbind("click").bind("click", function(){
        if($(this).attr("title") != ""){
            pelaajakorttinykyinen = $(this).attr("title");
            haeJaTulostaPelaajakortti();
        }
    });
}
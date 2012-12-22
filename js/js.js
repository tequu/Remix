//Pelaajakortin tiedot kent‰n lis‰‰miseen
$(document).ready(function(){
    $(".lisaalisatieto").click(function(){
        for(var i=0;i<8;i++){
            if(!lisatiedot[i]){
                if(i>0){
                    var teksti = "<div id='lisatieto_kentta_"+i+"'>"+(i+1)+". Lis‰tieto:<br />Nimi:<input type='text' name='pelaajakortti_kentta_"+i+"' id='pelaajakortti_kentta_"+i+"' /> Tieto:<input type='text' name='pelaajakortti_kentta_arvo_"+i+"' id='pelaajakortti_kentta_arvo_"+i+"' /><input type='button' value='Siirr‰ ylˆs' onclick='lisatietosiirraylos("+i+")' />";
                    if(i!=7)
                        teksti += "<input type='button' value='Siirr‰ alas' onclick='lisatietosiirraalas("+i+")' />";
                    teksti += "<input type='button' value='Poista' onclick='poistalisatieto("+i+")' /></div>"
                    $("#lisatieto_kentta_"+(i-1)).after(teksti);
                }
                else
                    $("#lisatieto").append("<div id='lisatieto_kentta_"+i+"'>"+(i+1)+". Lis‰tieto:<br />Nimi:<input type='text' name='pelaajakortti_kentta_"+i+"' id='pelaajakortti_kentta_"+i+"' /> Tieto:<input type='text' name='pelaajakortti_kentta_arvo_"+i+"' id='pelaajakortti_kentta_arvo_"+i+"' /><input type='button' value='Siirr‰ alas' onclick='lisatietosiirraalas("+i+")' /><input type='button' value='Poista' onclick='poistalisatieto("+i+")' /></div>");
                lisatiedot[i] = true;
                break;
            }
        }
    });
});
function poistalisatieto(kentta){
    if(kentta>=0&&kentta<8){
        var i=kentta;
        for(;i<7;i++){
            if(!lisatiedot[i+1])
                break;
            document.getElementById("pelaajakortti_kentta_"+i).value=document.getElementById("pelaajakortti_kentta_"+(i+1)).value;
            document.getElementById("pelaajakortti_kentta_arvo_"+i).value=document.getElementById("pelaajakortti_kentta_arvo_"+(i+1)).value;
        }
        $("#lisatieto_kentta_"+(i)).remove();
        lisatiedot[i] = false;
    }
}
function lisatietosiirraylos(i){
    var tieto = document.getElementById("pelaajakortti_kentta_"+(i-1)).value;
    var arvo = document.getElementById("pelaajakortti_kentta_arvo_"+(i-1)).value;
    document.getElementById("pelaajakortti_kentta_"+(i-1)).value = document.getElementById("pelaajakortti_kentta_"+(i)).value;
    document.getElementById("pelaajakortti_kentta_arvo_"+(i-1)).value = document.getElementById("pelaajakortti_kentta_arvo_"+(i)).value;
    document.getElementById("pelaajakortti_kentta_"+i).value = tieto;
    document.getElementById("pelaajakortti_kentta_arvo_"+i).value = arvo;
    
}
function lisatietosiirraalas(i){
    if(i+1 < 8 && i+1 >= 0 && document.getElementById("pelaajakortti_kentta_"+(i+1)) && document.getElementById("pelaajakortti_kentta_arvo_"+(i+1))){
        var tieto = document.getElementById("pelaajakortti_kentta_"+(i+1)).value;
        var arvo = document.getElementById("pelaajakortti_kentta_arvo_"+(i+1)).value;
        document.getElementById("pelaajakortti_kentta_"+(i+1)).value = document.getElementById("pelaajakortti_kentta_"+(i)).value;
        document.getElementById("pelaajakortti_kentta_arvo_"+(i+1)).value = document.getElementById("pelaajakortti_kentta_arvo_"+(i)).value;
        document.getElementById("pelaajakortti_kentta_"+i).value = tieto;
        document.getElementById("pelaajakortti_kentta_arvo_"+i).value = arvo;  
    }
}
//Ilmoittaa k‰ytt‰j‰n l‰sn‰ tai poissaolevaksi tapahtumaan, k‰ytt‰j‰n valinnan mukaan
function ilmoittauduTapahtumaan(tapahtuma, lasna){
    alustaPyynto();
    Pyynto.onreadystatechange = function(){
        if(Pyynto.readyState == 4 && Pyynto.status == 200){
            var vastaus = Pyynto.responseText;
            $("#tila_"+tapahtuma).empty();
            //N‰ytet‰‰n vihre‰ hyv‰ksymis teksti jos ilmoittautuminen onnistui ja piilotetaan se 400ms p‰‰st‰ hitaasti
            $("#tila_"+tapahtuma).css("color", "green");
            $("#tila_"+tapahtuma).append(vastaus);
            $("#tila_"+tapahtuma).hide().toggle().delay(400).fadeOut("slow");
        }
    }
    Pyynto.open("POST", "/Foorumit/ilmoittaudutapahtumaan.php", true);
    Pyynto.overrideMimeType('text/plain; charset=windows-1252');
    Pyynto.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    Pyynto.send("tapahtuma="+tapahtuma+"&lasna="+lasna);
}
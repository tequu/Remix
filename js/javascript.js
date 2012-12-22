Array.prototype.in_array = function (obj) {
    var len = this.length;
    for (var x=0;x<len;x++) {
        if ( this[x] == obj ) return true;
    }
    return false;
}
Array.prototype.in_array2 = function (obj, kohta){
    var len = this.length;
    for(var i=0;i<len;i++){
        if(this[i][kohta] == obj) return true;
    }
    return false;
}
Array.prototype.in_array3 = function(obj1){
    var len = this.length;
    for(var i=0;i<len;i++){
        if(this[i][0]==obj1){
            if(this[i][1]==1){
                return true;
            }
            else
                return false;
        }
    }
}
function alustaPyynto()
{
    if(window.ActiveXObject)
    {
        // Jos k�ytet��n Internet Exploreria:
        Pyynto = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else
    {
        // Jos k�ytet��n muita selaimia:
        Pyynto = new XMLHttpRequest();
    }
}
//Siirtyy halutulle sivulle
function siirry(linkki){
    parent.location=linkki;
}

//L�hett�� parametrin� annetun lomakkeen. Lis�� ennen l�hetyst� nimet-muuttujia vastaaviin ID kenttiin formissa arvot muuttujan arvot
function laheta(form, nimet, arvot){
    for(var i=0;i<nimet.length;i++){
        var nimi = document.forms[form].elements[nimet[i]];
        nimi.value=arvot[i];
    }
//    $("#error").append(document.forms[form]);
    document.forms[form].submit();
}
function poista(i, form){
    var poistettavaid = document.getElementById('poistettavaid');
    poistettavaid.value = i;
    document.forms[form].submit();
}
function siirra(i, form, a){
    var siirrettavaid = document.getElementById('siirrettavaid');
    var suunta = document.getElementById('suunta');
    siirrettavaid.value = i;
    suunta.value = a;
    document.forms[form].submit();
}
Array.prototype.in_array = function(obj) {
    var len = this.length;
    for (var x = 0; x < len; x++) {
        if (this[x] == obj)
            return true;
    }
    return false;
}
Array.prototype.in_array2 = function(obj, kohta) {
    var len = this.length;
    for (var i = 0; i < len; i++) {
        if (this[i][kohta] == obj)
            return true;
    }
    return false;
}
Array.prototype.in_array3 = function(obj1) {
    var len = this.length;
    for (var i = 0; i < len; i++) {
        if (this[i][0] == obj1) {
            if (this[i][1] == 1) {
                return true;
            }
            else
                return false;
        }
    }
}
function alustaPyynto()
{
    if (window.ActiveXObject)
    {
        // Jos käytetään Internet Exploreria:
        Pyynto = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else
    {
        // Jos käytetään muita selaimia:
        Pyynto = new XMLHttpRequest();
    }
}
//Siirtyy halutulle sivulle
function siirry(linkki) {
    parent.location = linkki;
}

//Lähettää parametrinä annetun lomakkeen. Lisää ennen lähetystä nimet-muuttujia vastaaviin ID kenttiin formissa arvot muuttujan arvot
function laheta(form, nimet, arvot, additional_inputs) {
    $.each(nimet, function(index, value) {
        $("#" + value).val(arvot[index]);
    });
    $.each(additional_inputs, function(index, value) {
        $("#form_" + value.val($("#" + value).valu()));
    });
    $("#" + form).submit();
}
function vahvista_ja_laheta(form, nimet, arvot, additional_inputs, viesti) {
    if (confirm(viesti)) {
        laheta(form, nimet, arvot, additional_inputs);
    }
}
function poista(i, form) {
    var poistettavaid = document.getElementById('poistettavaid');
    poistettavaid.value = i;
    document.forms[form].submit();
}
function siirra(i, form, a) {
    var siirrettavaid = document.getElementById('siirrettavaid');
    var suunta = document.getElementById('suunta');
    siirrettavaid.value = i;
    suunta.value = a;
    document.forms[form].submit();
}
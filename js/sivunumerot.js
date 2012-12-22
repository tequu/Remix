"use strict";
var sivunumerot = (function(){
    
    var asetukset = {
        template: $("#sivunumerot-template").html()
    };
    
    function Sivunumerot(minne, sivuja, aloitussivu, sivujanakyvissa, clicked){
        this.model = {
            minne: minne,
            sivuja: sivuja,
            nykyinensivu: aloitussivu,
            sivujanakyvissa: sivujanakyvissa,
            clicked: clicked
        }
        this.nakyma = new Nakyma({
            model: this.model
        });
    }
    Sivunumerot.prototype.getNykyinenSivu = function(){
        return this.model.nykyinensivu;
    }
    
    
    
    var Nakyma = Backbone.View.extend({
        
        el: $("body"),
        
        initialize: function(){
            this.paivita();
        },
       
        paivita: function(){
            var _this = this;
            $("#"+this.model.minne).unbind("click");
            if(this.model.sivuja < 2){
                return;
            }
            var ensimmainen = false, edellinen = false, sivunumerot = new Array(), seuraava = false, viimeinen = false;
            if(this.model.nykyinensivu != 0){
                ensimmainen = true;
                edellinen = true;
            }
            var aloitus = Math.floor(this.model.nykyinensivu-this.model.sivujanakyvissa/2);
            if(aloitus < 0){
                aloitus = 0;
            }
            var lopetus = aloitus+this.model.sivujanakyvissa;
            if(aloitus > 1){
                sivunumerot.push({
                    sivunumero: "...",
                    valittu: false
                });
            } else if(aloitus == 1){
                sivunumerot.push({
                    sivunumero: 1,
                    valittu: false
                });
            }
            while(aloitus <= lopetus){
                sivunumerot.push({
                    sivunumero: aloitus+1,
                    valittu: aloitus==this.model.nykyinensivu?true:false
                });
                aloitus++;
                if(aloitus >= this.model.sivuja){
                    break;
                }
            }
            if(aloitus < this.model.sivuja-1){
                sivunumerot.push({
                    sivunumero: "...",
                    valittu: false
                });
            } else if(aloitus == this.model.sivuja-1){
                sivunumerot.push({
                    sivunumero: this.model.sivuja,
                    valittu: aloitus==this.model.nykyinensivu?true:false
                });
            }
            if(this.model.nykyinensivu != this.model.sivuja-1){
                seuraava = true,
                viimeinen = true
            }
            var data = {
                ensimmainen: ensimmainen,
                edellinen: edellinen,
                sivunumerot: sivunumerot,
                seuraava: seuraava,
                viimeinen: viimeinen
            }
            $("#"+this.model.minne).html(Mustache.render(asetukset.template, data));
            $("#"+this.model.minne+" #sivunumero").bind("click", function(e) {
                _this.clicked(e);
            });
            $("#"+this.model.minne+" #ensimmainen").bind("click", function(e) {
                _this.ensimmainen();
            });
            $("#"+this.model.minne+" #edellinen").bind("click", function(e) {
                _this.edellinen();
            });
            $("#"+this.model.minne+" #seuraava").bind("click", function(e) {
                _this.seuraava(e);
            });
            $("#"+this.model.minne+" #viimeinen").bind("click", function(e) {
                _this.viimeinen(e);
            });
        },
        clicked: function(e){
            var sivu = Number($(e.target).html());
            if(isNaN(sivu)){
                return;
            }
            this.model.nykyinensivu = sivu-1;
            this.model.clicked();
            this.paivita();
        },
        seuraava: function(){
            this.model.nykyinensivu = Number(this.model.nykyinensivu)+1;
            this.model.clicked();
            this.paivita();
        },
        edellinen: function(){
            this.model.nykyinensivu = Number(this.model.nykyinensivu)-1;
            this.model.clicked();
            this.paivita();
        },
        ensimmainen: function(){
            this.model.nykyinensivu = 0;
            this.model.clicked();
            this.paivita();
        },
        viimeinen: function(){
            this.model.nykyinensivu = this.model.sivuja-1;
            this.model.clicked();
            this.paivita();
        }
    });
    
    return Sivunumerot;
})();
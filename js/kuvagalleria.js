"use strict";
(function(Sivunumerot) {
    var kuvagalleria = {
        model: {},
        view: {}
    };
    
    kuvagalleria.asetukset = {
        kuvaTemplate: $("#kuvagalleria_kuva-template").html(),
        kategoriaTemplate: $("#kuvagalleria_kategoria-template").html(),
        kategoriatTemplate: $("#kuvagalleria_kategoriat-template").html(),
        url: "http://fbcremix.com/Remix/json/kuvagalleria.php",
        kuvatUrl: "/Remix/kuvat/kuvakategoriat/",
        loaderUrl: "/Remix/kuvat/loader.gif",
        riveja: 2,
        rivilla: 1
    }
    kuvagalleria.model.image = Backbone.Model.extend({
        initialize: function(image){
            this.set({
                src: image.src,
                kuvaus: image.kuvaus
            });
        }
    });
    kuvagalleria.model.kuvatLista = Backbone.Collection.extend({
        model: kuvagalleria.model.image
    });
    kuvagalleria.model.kategoria = Backbone.Model.extend({
        initialize: function(kategoria){
            this.set({
                id: kategoria.id,
                otsikko: kategoria.otsikko,
                kuvia: kategoria.kuvia,
                kuvat: kategoria.kuvat,
                sivunumerot: undefined
            })
        }
    });
    kuvagalleria.model.kategoriat = Backbone.Model.extend({
        initialize: function(tiedot){
            this.set({
                kategoriat: tiedot.kategoriat,
                sivunumerot: undefined
            });
        }
    });
    
    kuvagalleria.model.isoKuva = Backbone.Model.extend({
        initialize: function(kategoria){
            this.set({
                id: kategoria.id,
                kuvia: kategoria.kuvia,
                kuvat: new kuvagalleria.model.kuvatLista(kategoria.kuvat.toJSON()),
                valittu: kategoria.valittu,
                sivu: 0
            })
        }
    });
    kuvagalleria.model.kategoriaLista = Backbone.Collection.extend({
        model: kuvagalleria.model.kategoria
    });


    kuvagalleria.view.KategoriatView = Backbone.View.extend({
        el: $(".kuvagalleria"),
        initialize: function(){
            this.$el.unbind("click");
            this.lataaKategoriat();
        },
        events: {
            "click .kaikki_kuvat .nimi": "naytaKategoria",
            "click .kaikki_kuvat .kuva": "naytaIsokuva"
        },
        lataaKategoriat: function(){
            this.model.get("kategoriat").reset();
            var _this = this;
            $.ajax({
                url: kuvagalleria.asetukset.url,
                dataType: 'json',
                success: function(data){
                    _this.tallennaKategoriat(data);
                    var sivuja = _this.model.get("kategoriat").length/kuvagalleria.asetukset.riveja;
                    _this.model.set("sivunumerot", new Sivunumerot("sivunumerot", sivuja, 0, 3, function(){
                        _this.uusiSivu();
                    }));
                    naytaKategoriat(_this.getSivunKategoriat(), kuvagalleria.asetukset.rivilla);
                    _this.lataaKaikkiKuvatJson();
                }
            });
        },
        getSivunKategoriat: function(){
            var maara = 0;
            var kategoriat = [];
            var model = this.model.get("kategoriat").toJSON();
            var alkaen = this.model.get("sivunumerot").getNykyinenSivu()*kuvagalleria.asetukset.riveja;
            for(var i=alkaen; i<model.length;i++){
                kategoriat.push(model[i]);
                maara++;
                if(maara == kuvagalleria.asetukset.riveja){
                    break;
                }
            };
            return kategoriat;
        },
        tallennaKategoriat: function(data){
            var kategoriat = this.model.get("kategoriat");
            $(data).each(function(index, kategoria){
                var data = {
                    id: kategoria.id,
                    otsikko: kategoria.otsikko,
                    kuvia: kategoria.kuvia,
                    kuvat: new kuvagalleria.model.kuvatLista(),
                    valittu: -1
                }
                try{
                    kategoriat.add(data);
                } catch(err){
                    console.log("Virhe kategorian latauksessa: "+ err);
                    return;
                }
            }); 
        },
        lataaKaikkiKuvatJson: function(){
            var _this = this;
            $(this.getSivunKategoriat()).each(function(index, kategoria){
                lataaKuvatJson(kategoria.id, 0, kuvagalleria.asetukset.rivilla, function(data){
                    tallennaKuvat(kategoria.kuvat, data);
                    _this.lataaKuvat(kategoria.id);
                });
            });
        },
        lataaKuvat: function(index){
            var kategoria = this.model.get("kategoriat").get(index);
            var url = kuvagalleria.asetukset.kuvatUrl+kategoria.get("id")+"/";
            lataaKuvat(kategoria, url, function(i, src){
                $(".kuvagalleria #kategoria_"+kategoria.get("id")+" .kuva:nth("+i+")").css("background-image", "url("+src+")");
            });
        },
        naytaKategoria: function(e){
            var naytettavaKategoria = this.model.get("kategoriat").get($(e.target).data("id"));   
            new kuvagalleria.view.KategoriaView({
                model: naytettavaKategoria
            });
            //            history.pushState({
            //                mode: "kategoriat",
            //                id: naytettavaKategoria.get("id")
            //            }, "", "http://fbcremix.com/Remix/kuvagalleria.php?kategoria="+naytettavaKategoria.get("id"));
            e.preventDefault();
        },
        naytaIsokuva: function(e){
            var kategoriaid = $(e.target).parent().data("id");
            var kuvaid = $(e.target).data("id");
            var kategoria = this.model.get("kategoriat").get(kategoriaid).toJSON();
            naytaIsokuva(kategoria, 0, kuvaid);
            //            history.pushState({
            //                mode: "kategoriat"
            //            }, "", "http://fbcremix.com/Remix/kuvagalleria.php?kuva="+kuvaid);
            e.preventDefault();
        },
        uusiSivu: function(){
            naytaKategoriat(this.getSivunKategoriat(), kuvagalleria.asetukset.rivilla);
            this.lataaKaikkiKuvatJson();
        }
    });
    
    
    kuvagalleria.view.KategoriaView = Backbone.View.extend({
        el: $(".kuvagalleria"),
        initialize: function(){
            this.$el.unbind("click");
            $(".kuvagalleria #takaisin").show();
            var sivuja = this.model.get("kuvia")/(kuvagalleria.asetukset.riveja*kuvagalleria.asetukset.rivilla);
            this.model.set("sivunumerot", new Sivunumerot("sivunumerot", sivuja, 0, 3, function(){
                _this.uusiSivu();
            }));
            this.lataaKuvat();   
        },
        events: {
            "click .kuva": "naytaIsokuva",
            "click #takaisin": "takaisin"
        },
        lataaKuvat: function(){
            var _this = this;
            var montako = kuvagalleria.asetukset.riveja*kuvagalleria.asetukset.rivilla;
            var alkaen = this.model.get("sivunumerot").getNykyinenSivu()*montako;
            lataaKuvatJson(this.model.get("id"), alkaen, montako, function(data) {
                tallennaKuvat(_this.model.get("kuvat"), data);
                _this.naytaKategoria();
                _this.naytaKuvat();
            });
        },
        naytaKategoria: function(){
            var data = new Array();
            data.push(this.model.toJSON());
            naytaKategoriat(data, kuvagalleria.asetukset.riveja*kuvagalleria.asetukset.rivilla);
        },
        naytaKuvat: function() {
            var _this = this;
            var url = kuvagalleria.asetukset.kuvatUrl+this.model.get("id")+"/";
            lataaKuvat(this.model, url, function(i, src){
                $(".kuvagalleria #kategoria_"+_this.model.get("id")+" .kuva:nth("+i+")").css("background-image", "url("+src+")");
            });
        },
        naytaIsokuva: function(e){
            var kuvaid =  $(e.target).data("id");
            naytaIsokuva(this.model.toJSON(), this.model.get("sivunumerot").getNykyinenSivu(), kuvaid);
            //            history.pushState({
            //                mode: "kategoria"
            //            }, "", "http://fbcremix.com/Remix/kuvagalleria.php?kategoria="+this.model.get("id")+"&kuva="+kuvaid);
            e.preventDefault();
        },
        uusiSivu: function(){
            this.lataaKuvat();
        },
        takaisin: function(){
            $(".kuvagalleria #takaisin").hide();
            var lista = new kuvagalleria.model.kategoriaLista();
            var kategoriat = new kuvagalleria.model.kategoriat({
                kategoriat: lista 
            });
            new kuvagalleria.view.KategoriatView({
                model: kategoriat
            });
        }
    });
    
    
    kuvagalleria.view.IsokuvaView = Backbone.View.extend({
        el: $("body"),
        initialize: function(){
            this.$el.unbind("click");
            this.alusta();
            this.naytaKuva();
        },
        events: {
            "click #sulje": "sulje",
            "click #poista-isokuva": "sulje",
            "click #edellinen #nuoli": "edellinen",
            "click #seuraava #nuoli": "seuraava"
        },
        alusta: function() {
            $("body").append(kuvagalleria.asetukset.kuvaTemplate);
            $("#isokuva-pohja").slideDown("slow");
        },
        sulje: function(){
            $("#isokuva-pohja").fadeOut(function(){
                $(this).remove();
            });
        //            history.back();
        },
        naytaKuva: function(){
            $("#isokuva #kuva").css("background-image", "url("+kuvagalleria.asetukset.loaderUrl+")");
            if($("#isokuva #kuva img")){
                $(" #isokuva #kuva img").remove();
            }
            $("#isokuva #kuvaus").hide();
            var img = new Image();
            var _this = this;
            var nykyinen = this.model.get("kuvat").toJSON()[this.model.get("valittu")];
            $(img).load(function() {
                $(this).hide();
                $("#isokuva #kuva").append(this);
                _this.animoi(img.width, img.height);
                $("#isokuva #kuvaus").html(nykyinen.kuvaus);
            }).error(function() {
                console.log("Virhe kuvaa ladattaessa");
            }).attr("src", kuvagalleria.asetukset.kuvatUrl+this.model.get("id")+"/"+nykyinen.src);  
        },
        animoi: function(width, height){
            var $isokuva, $content, $kuva, $nuoli, $se;
            var containerwidth = width+76;
            var containerheight = height+78, contentheight = height+48, arrowheight = height/2-35;
            $isokuva = $("#isokuva-pohja #isokuva");
            $content = $("#isokuva-pohja #isokuva #isokuva-content");
            $kuva = $("#isokuva-pohja #isokuva #isokuva-content #kuva");
            $nuoli = $("#isokuva-pohja #isokuva #nuoli");
            $se = $("#isokuva-pohja #seuraava, #isokuva-pohja #edellinen");
            $isokuva.animate({
                "width":containerwidth+"px", 
                "height":containerheight+"px"
            }, 700);
            $content.animate({
                "width":containerwidth+"px", 
                "height":contentheight+"px"
            }, 700);
            $kuva.animate({
                "width":width+"px", 
                "height":height+"px"
            }, 700);
            $nuoli.animate({
                "top":arrowheight
            }, 700);
            $se.animate({
                "height": height+"px"
            }, 700);
            setTimeout(function() {
                $("#isokuva #kuva").css("background-image", "");
                $("#isokuva #kuva img").show();
                $("#isokuva #kuvaus").show();
            }, 700);
        },
        lataa: function(){
            var _this = this;
            lataaKuvatJson(this.model.get("id"), this.model.get("sivu"), kuvagalleria.asetukset.riveja*kuvagalleria.asetukset.rivilla, function(data){
                tallennaKuvat(_this.model.get("kuvat"), data);
                _this.naytaKuva();
            });
        },
        seuraava: function() {
            var nykyinen = Number(this.model.get("valittu"));
            if(nykyinen + 1 < this.model.get("kuvat").length){
                this.model.set("valittu", nykyinen+1);
                this.naytaKuva();
                return;
            }
            var montako = kuvagalleria.asetukset.riveja*kuvagalleria.asetukset.rivilla;
            if(montako*this.model.get("sivu") + nykyinen + 1 < this.model.get("kuvia")){
                this.model.set("sivu", Number(this.model.get("sivu")) + 1);
                this.model.set("valittu", 0);
                this.lataa();
            }
        },
        edellinen: function() {
            var nykyinen = Number(this.model.get("valittu"));
            if(nykyinen - 1 >= 0){
                this.model.set("valittu", nykyinen-1);
                this.naytaKuva();
                return;
            }
            var montako = kuvagalleria.asetukset.riveja*kuvagalleria.asetukset.rivilla;
            var sivu = Number(this.model.get("sivu"));
            if(sivu > 0){
                this.model.set("sivu", sivu-1);
                this.model.set("valittu", montako-1);
                this.lataa();
            }
        }
    });

    function lataaKuvatJson(kategoriaid, alkaen, montako, callback){
        $.ajax({
            url: kuvagalleria.asetukset.url+"?kategoriatid="+kategoriaid+"&alkaen="+alkaen+"&maara="+montako,
            dataType: 'json',
            success: function(data){
                callback(data);
            }
        });
    }
    
    function tallennaKuvat(kuvat, data){
        kuvat.reset();
        $(data).each(function(index, kuva){
            try{
                kuvat.add(kuva);
            } catch(err){
                console.log("Virhe kuvan latauksessa: " + err);
            }
        });
    }
        
    function naytaIsokuva(kategoria, sivu, kuvaid){
        var model = new kuvagalleria.model.isoKuva(kategoria);
        model.set("valittu", kuvaid);
        model.set("sivu", sivu);
        new kuvagalleria.view.IsokuvaView({
            model: model
        })
    }
    
    function lataaKuvat(kategoria, url, loaded){
        $(kategoria.get("kuvat").toJSON()).each(function(index, kuva){
            lataaKuva(url+kuva.src, function(src){
                loaded(index, src);
            });
        });
    }
    
    function lataaKuva(src, loaded){
        var img = new Image();
        $(img).load(function() {
            loaded(src);
        }).error(function() {
            console.log("Virhe kuvaa ladattaessa");
        }).attr("src", src);
    }
    function naytaKategoriat(kategoriat, montako){
        var lista = [];
        $(kategoriat).each(function(index, kategoria){
            var loading = [];
            var stop = kategoria.kuvia>montako?montako:kategoria.kuvia;
            for(var i=0;i<stop;i++){
                loading.push({
                    id: i,
                    kuva: kuvagalleria.asetukset.loaderUrl
                });
            }
            lista.push({
                otsikko: kategoria.otsikko,
                id: kategoria.id,
                kuvat: loading
            });
        });
        var data = {
            kategoriat: lista
        }
        $(".kuvagalleria .kaikki_kuvat").html(Mustache.render(kuvagalleria.asetukset.kategoriatTemplate, data));
    }
    $(document).ready(function(){
        var lista = new kuvagalleria.model.kategoriaLista();
        var kategoriat = new kuvagalleria.model.kategoriat({
            kategoriat: lista 
        });
        new kuvagalleria.view.KategoriatView({
            model: kategoriat
        });
    //        window.addEventListener('popstate', function(event){
    //            
    //            if(event.state == null){
    //                return;
    //            }
    //            console.log(event.state.mode);
    //            if(event.state.mode == "kategoriat"){
    //                if($("#isokuva-pohja").length != 0){
    //                    $("#isokuva-pohja").fadeOut(function(){
    //                        $(this).remove();
    //                    });
    //                } else {
    //                    var lista = new kuvagalleria.model.kategoriaLista();
    //                    var kategoriat = new kuvagalleria.model.kategoriat({
    //                        kategoriat: lista 
    //                    });
    //                    new kuvagalleria.view.KategoriatView({
    //                        model: kategoriat
    //                    });
    //                }
    //            } else if(event.state.mode == "kategoria"){
    //                
    //            }
    //        });
    //        history.replaceState({
    //            mode: "kategoriat"
    //        }, document.title, document.location.href);
    });
})(sivunumerot);
                    
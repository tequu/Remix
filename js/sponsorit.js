function slideit(){
    if(sponsorimaara>1){
        if(!document.images){
            return;
        }
        document.images.sponsorit.src=eval("sponsoriimage"+sponsoristep+".src");
        if(sponsoristep<sponsorimaara-1)
            sponsoristep++;
        else
            sponsoristep=0;
        setTimeout("slideit()",2500);
    }
}
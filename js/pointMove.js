// JavaScript Document
var ceilingFixed = function(oDiv,oPosition,aA,objArry,classOn,oTop){
        var oScrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        if(oScrollTop >= oTop){
            oDiv.style.position = 'fixed';
            oDiv.style.top = '0';
            oDiv.style.left = '0';
            oPosition.style.display = 'block';

            for(var i=0; i<objArry.length;i++){
                if(oScrollTop >= objArry[i].offsetTop-70){
                    aA.eq(i).addClass(classOn).siblings().removeClass(classOn);
                }else{
                    continue;
                }
            }

        }else{
            oDiv.style.position = '';
            aA.eq(0).addClass(classOn).siblings().removeClass(classOn);
            oPosition.style.display = 'none';
        }
    };
var getPos = function(obj){
    var l= 0,t=0;
    while(obj){
        l+=obj.offsetLeft;
        t+=obj.offsetTop;
        obj=obj.offsetParent;
    }
    return {left: l, top: t};
};

$(function(){
	$('#btnclick2').click(function(){
		var h =document.body.scrollHeight;
		var w = window.innerWidth;
		var t=document.body.scrollTop;
		$('.ttop2').css('height',t);
		$('.divShow2').css('width',w).css('height',h).addClass('show1');
		$('.ttop2').css('height',t);
		$('.dis2').show();
	})
	$('.divShow2').click(function(){
		$(this).hide();
		$('.dis2').hide();
	});
});
//nober

$(function(){
	$('#btnclick1').click(function(){
		var h =document.body.scrollHeight;
		var w = window.innerWidth;
		$('.divShow1').css('width',w).css('height',h).addClass('show1');
		$('.dis1').show();
		$("#cabutt").show();
	})
	$('.divShow1').click(function(){
		$(this).hide();
		$('.dis1').hide();
	});
});
//搜索
$(function(){
	$('#btnclick').click(function(){
		var h =document.body.scrollHeight;
		var w = window.innerWidth;
		var t=document.body.scrollTop;
		var t=document.body.scrollTop;
		// $('.ttop').css('height',t);
		var o=window.screen.height;
		$('.objg1').css('height',o*0.2);
		$('.divShow').css('width',w).css('height',h).addClass('show');
		$('.dis').show();
		//$('.ttop').css('',w).css('top',t).css('width',w);
	})
	$('.divShow').click(function(){
		$(this).hide();
		$('.dis').hide();
	});
});
//排名

$(function(){
	$('#btnclick3').click(function(){
		var h =document.body.scrollHeight;
		var w = window.innerWidth;
		var o=window.screen.availHeight;
		$('.obj').css('height',o*0.7);
		$('.divShow3').css('width',w).css('height',h).addClass('show3');
		$('.dis3').show();
		$('.divShow3').show();
	})
	$('#cabutt').click(function(){
		$(this).hide();
		$('.dis3').hide();
	});
});
//萌宝详情
$(function(){
	$('#btnclick4').click(function(){
		var h =document.body.scrollHeight;
		var w = window.innerWidth;
		var t=document.body.scrollTop;
		var t=document.body.scrollTop;
		// $('.ttop').css('height',t);
		var o=window.screen.height;
		$('.objg1').css('height',o*0.2);
		$('.divShow4').css('width',w).css('height',h).addClass('show');
		$('.dis4').show();
		//$('.ttop').css('',w).css('top',t).css('width',w);
	})
	$('.divShow4').click(function(){
		$(this).hide();
		$('.dis4').hide();
	});
});
//拉票

$(function() {
$('a.jqlightbox').lightBox({
	overlayBgColor: '#000000',
	overlayOpacity: 0.8,
	containerBorderSize: 10,
	containerResizeSpeed: 400,
	fixedNavigation: false        
	//点击弹出框  投票成功
	});
});
function monuseoverout1(obj){ 
var description_title=document.getElementById("description_title");
var shipping_title=document.getElementById("shipping_title");

var detail=document.getElementById("detail");
var product_rz=document.getElementById("product_rz");
var ProductReviews=document.getElementById("ProductReviews");
product_rz.style.display="none";
if(obj=="description_title"){
description_title.setAttribute("class","tab-menu hover");
shipping_title.setAttribute("class","tab-menu");
detail.style.display="block";
product_rz.style.display="none";

}else if(obj=="shipping_title"){
description_title.setAttribute("class","tab-menu");
shipping_title.setAttribute("class","tab-menu hover");

detail.style.display="none";
product_rz.style.display="block";

} 

} //切换div

var lastFaqClick=null;
window.onload=function(){
var faq=document.getElementById("faq");
var dls=faq.getElementsByTagName("dl");

for (var i=0,dl;dl=dls[i];i++){
var dt=dl.getElementsByTagName("dt")[0];//取得标题
dt.id = "faq_dt_"+(Math.random()*100);
dt.onclick=function(){
var p=this.parentNode;//取得父节点
if (lastFaqClick!=null&&lastFaqClick.id!=this.id){
var dds=lastFaqClick.parentNode.getElementsByTagName("dd");
var dds=lastFaqClick.parentNode.getElementsByTagName("dd");
var dts=lastFaqClick.parentNode.getElementsByTagName("dt");
for (var i=0,dd;dd=dds[i];i++)
dd.style.display='none';

}
lastFaqClick=this;
var dds=p.getElementsByTagName("dd");//取得对应子节点，也就是说明部分
var tmpDisplay='none';
if (gs(dds[0],'display')=='none')
tmpDisplay='block';
for (var i=0;i<dds.length;i++)
dds[i].style.display=tmpDisplay;
}
}

function gs(d,a){
if (d.currentStyle){
var curVal=d.currentStyle[a]
}else{
var curVal=document.defaultView.getComputedStyle(d, null)[a]
}
return curVal;
}
//以上是js展开收缩效果
}
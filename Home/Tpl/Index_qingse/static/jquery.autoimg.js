/*
 * jQuery插件：autoimg
 * 	版本：v1.1
 *  时间：2010-8-31
 *  作者：飞飞
 *  QQ：276230416
 *  网址：http://www.ffasp.com
 *  Download by http://www.codefans.net
 +--------------------------------------------------------------------
 *使用说明：
 *1.本插件支持一个页面多次使用
 *2.html模板如下
	<div class="autoimg">
	  <div class="parentdiv"><img src="" /> </div>
	  <ul class="imglist">
		<li><img src="image/1/0.jpg" /></li>
		<li><img src="image/1/1.jpg" /></li>
		<li><img src="image/1/2.jpg" /></li>
	  </ul>
	  <div class="clearboth"></div>
	</div>
 *3.参考样式[见示例：index.html]	
	<style type="text/css">
	.parentdiv {
		position:relative;
		overflow:hidden;
		height:300px;
		width:300px;
		border:1px solid #000;
		float:left;
	}
	.imglist {
		width:270px;
		float:left
	}
	.imglist li {
		width:80px;
		height:80px;
		overflow:hidden;
		float:left;
		margin:5px;
	}
	.imglist li img {
		width:80px
	}
	.clearboth {
		clear:both
	}
	</style>
 +--------------------------------------------------------------------
 */
jQuery.fn.autoimg = function(G){
	
	var interval = new Array();
	var ord = new Array();
	var D = {
		delay:5,//幻灯片切换延时
		loadingImgSrc:"loading.gif"
	};
	$.extend(D,G)

	//初始化函数
	$.init = function(el,i){
	var framediv,frameimg,firstimg,i,f;
		framediv = $(el).children("div").eq(0);
		//frameimg = framediv.children("img");
		frameimg = $("<img />");
		frameimg.attr("src",D.loadingImgSrc);
		firstimg = $(el).children("ul").find("img").eq(0);
		imgwrap =  $(el).children("ul").children("li");
		//+--------------------------------------------------		
		imgMargins = $.imgCenter({"w": framediv.width() ,"h":framediv.height()},{"w":64,"h":64});
		frameimg.css({width:64,height:64,marginLeft:imgMargins.l,marginTop:imgMargins.t})
		framediv.html(frameimg);
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		framediv.loadthumb({parentDiv:framediv,frameimg:frameimg,img:firstimg});
		$.imgSlide(el,i);
	} 
 	//自动播放切换图片列表
	$.imgSlide = function(el,i){
		ord[i] = 0;
		interval[i] = setInterval(function(){$.interval(el,i,ord[i])},D.delay*1000);
	}

	$.interval = function(el,i,j){
		var framediv,frameimg,firstimg;
		var imgSrc,imgLength,i;
	
		framediv = $(el).children("div");
		frameimg = framediv.children("img");
		firstimg = $(el).children("ul").find("img").eq(0);
		imgwrap =  $(el).children("ul").children("li");
	
		imgLength = imgwrap.size();
		if(j>=imgLength){j=0};
		if(j < imgLength){
			framediv.loadthumb({parentDiv:framediv,frameimg:frameimg,img:imgwrap.find("img").eq(j)});
		}
		if(j<imgLength){
			ord[i]=j+1;
		}else{ord[i]=0;};
	}
	//图片预加载
	jQuery.fn.loadthumb = function(D) {
		var imgTmp,imgDem,imgMargins;
		D = $.extend({
			 parentDiv : {},
			 img:{},
			 frameimg : {}
		},D);
		D.frameimg.hide();
		imgTmp = new Image();
		$(imgTmp).load(function(){
			imgDem = {};
			imgDem.w  = imgTmp.width;
			imgDem.h  = imgTmp.height;
			imgDem = $.imgResize({"w":D.parentDiv.width() ,"h": D.parentDiv.height()},{"w":imgDem.w,"h":imgDem.h});
			imgMargins = $.imgCenter({"w": D.parentDiv.width() ,"h": D.parentDiv.height()},{"w":imgDem.w,"h":imgDem.h});
			D.frameimg.css({width:imgDem.w,height:imgDem.h,marginLeft:imgMargins.l,marginTop:imgMargins.t})
					  .attr("src",D.img.attr("src"))
					  .fadeIn("slow");
		}).attr("src",D.img.attr("src"));//.attr("src",options.src)要放在load后面，
		
	}
	
	//重置图片宽度，高度插件 ( parentDem是父元素，imgDem是图片 )
	jQuery.imgResize = function(parentDem,imgDem){
		if(imgDem.w>0 && imgDem.h>0){
			var rate = (parentDem.w/imgDem.w < parentDem.h/imgDem.h)?parentDem.w/imgDem.w:parentDem.h/imgDem.h;
			//如果 指定高度/图片高度  小于  指定宽度/图片宽度 ，  那么，我们的比例数 取 指定高度/图片高度。
			//如果 指定高度/图片高度  大于  指定宽度/图片宽度 ，  那么，我们的比例数 取 指定宽度/图片宽度。
			if(rate <= 1){   
				imgDem.w = imgDem.w*rate; //图片新的宽度 = 宽度 * 比例数
			}else{//  如果比例数大于1，则新的宽度等于以前的宽度。
				imgDem.w = imgDem.w;
			}
			if(rate <= 1){   
				imgDem.h = imgDem.h*rate;
			}
			else{
				imgDem.h = imgDem.h;
			}
		}	
		return imgDem;
	}
	//使图片在父元素内水平，垂直居中，( parentDem是父元素，imgDem是图片 )
	jQuery.imgCenter = function(parentDem,imgDem){
		var left = (parentDem.w - imgDem.w)*0.5;
		var top = (parentDem.h - imgDem.h)*0.5;
		return { "l": left , "t": top};
	}
	//图片列表鼠标悬停效果
	jQuery.imgHover = function(el,i){
		var framediv,frameimg,firstimg;
		var imgSrc,imgLength,i;
	
		framediv = $(el).children("div");
		frameimg = framediv.children("img");
		firstimg = $(el).children("ul").find("img").eq(0);
		imgwrap =  $(el).children("ul").children("li");
	
		$(el).children("ul").children("li").hover(
			function(){
				framediv.loadthumb({parentDiv:framediv,frameimg:frameimg,img:$(this).find("img")})	;
				clearInterval(interval[i]);
			},function(){
				ord[i] = $(el).children("ul").children("li").index($(this))+1;
				interval[i] = setInterval(function(){$.interval(el,i,ord[i])},D.delay*1000);				
			});
	}

	$(this).each(function(i){
		$.init(this,i);
		$.imgHover(this,i);
	});
};
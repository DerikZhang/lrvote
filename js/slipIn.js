/**
 * jQuery Plugin to obtain touch gestures from iPhone, iPod Touch and iPad, should also work with Android mobile phones (not tested yet!)
 * Common usage: wipe images (left and right to show the previous or next image)
 * 
 * @author Andreas Waltl, netCU Internetagentur (http://www.netcu.de)
 * @version 1.1.1 (9th December 2010) - fix bug (older IE's had problems)
 * @version 1.1 (1st September 2010) - support wipe up and wipe down
 * @version 1.0 (15th July 2010)
 */
(function($){$.fn.touchwipe=function(settings){var config={min_move_x:20,min_move_y:20,wipeLeft:function(){},wipeRight:function(){},wipeUp:function(){},wipeDown:function(){},preventDefaultEvents:true};if(settings)$.extend(config,settings);this.each(function(){var startX;var startY;var isMoving=false;function cancelTouch(){this.removeEventListener('touchmove',onTouchMove);startX=null;isMoving=false}function onTouchMove(e){if(config.preventDefaultEvents){e.preventDefault()}if(isMoving){var x=e.touches[0].pageX;var y=e.touches[0].pageY;var dx=startX-x;var dy=startY-y;if(Math.abs(dx)>=config.min_move_x){cancelTouch();if(dx>0){config.wipeLeft()}else{config.wipeRight()}}else if(Math.abs(dy)>=config.min_move_y){cancelTouch();if(dy>0){config.wipeDown()}else{config.wipeUp()}}}}function onTouchStart(e){if(e.touches.length==1){startX=e.touches[0].pageX;startY=e.touches[0].pageY;isMoving=true;this.addEventListener('touchmove',onTouchMove,false)}}if('ontouchstart'in document.documentElement){this.addEventListener('touchstart',onTouchStart,false)}});return this}})(jQuery);

/*! Copyright (c) 2013 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.1.3
 *
 * Requires: 1.2.2+
 */
(function(factory){if(typeof define==="function"&&define.amd){define(["jquery"],factory)}else{if(typeof exports==="object"){module.exports=factory}else{factory(jQuery)}}}(function($){var toFix=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"];var toBind="onwheel" in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"];var lowestDelta,lowestDeltaXY;if($.event.fixHooks){for(var i=toFix.length;i;){$.event.fixHooks[toFix[--i]]=$.event.mouseHooks}}$.event.special.mousewheel={setup:function(){if(this.addEventListener){for(var i=toBind.length;i;){this.addEventListener(toBind[--i],handler,false)}}else{this.onmousewheel=handler}},teardown:function(){if(this.removeEventListener){for(var i=toBind.length;i;){this.removeEventListener(toBind[--i],handler,false)}}else{this.onmousewheel=null}}};$.fn.extend({mousewheel:function(fn){return fn?this.bind("mousewheel",fn):this.trigger("mousewheel")},unmousewheel:function(fn){return this.unbind("mousewheel",fn)}});function handler(event){var orgEvent=event||window.event,args=[].slice.call(arguments,1),delta=0,deltaX=0,deltaY=0,absDelta=0,absDeltaXY=0,fn;event=$.event.fix(orgEvent);event.type="mousewheel";if(orgEvent.wheelDelta){delta=orgEvent.wheelDelta}if(orgEvent.detail){delta=orgEvent.detail*-1}if(orgEvent.deltaY){deltaY=orgEvent.deltaY*-1;delta=deltaY}if(orgEvent.deltaX){deltaX=orgEvent.deltaX;delta=deltaX*-1}if(orgEvent.wheelDeltaY!==undefined){deltaY=orgEvent.wheelDeltaY}if(orgEvent.wheelDeltaX!==undefined){deltaX=orgEvent.wheelDeltaX*-1}absDelta=Math.abs(delta);if(!lowestDelta||absDelta<lowestDelta){lowestDelta=absDelta}absDeltaXY=Math.max(Math.abs(deltaY),Math.abs(deltaX));if(!lowestDeltaXY||absDeltaXY<lowestDeltaXY){lowestDeltaXY=absDeltaXY}fn=delta>0?"floor":"ceil";delta=Math[fn](delta/lowestDelta);deltaX=Math[fn](deltaX/lowestDeltaXY);deltaY=Math[fn](deltaY/lowestDeltaXY);args.unshift(event,delta,deltaX,deltaY);return($.event.dispatch||$.event.handle).apply(this,args)}}));

/*!
 * jQuery imagesLoaded plugin v1.0.4
 * http://github.com/desandro/imagesloaded
 *
 * MIT License. by Paul Irish et al.
 */

(function(a,b){a.fn.imagesLoaded=function(i){var g=this,e=g.find("img").add(g.filter("img")),c=e.length,h="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";function f(){i.call(g,e)}function d(j){if(--c<=0&&j.target.src!==h){setTimeout(f);e.unbind("load error",d)}}if(!c){f()}e.bind("load error",d).each(function(){if(this.complete||this.complete===b){var j=this.src;this.src=h;this.src=j}});return g}})(jQuery);


/**
* hoverIntent r6 // 2011.02.26 // jQuery 1.5.1+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* 
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @author    Brian Cherne brian(at)cherne(dot)net
*/
(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type=="mouseenter"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.bind('mouseenter',handleHover).bind('mouseleave',handleHover)}})(jQuery);


/*
 *  Sharrre.com - Make your sharing widget!
 *  Version: beta 1.3.3 
 *  Author: Julien Hany
 *  License: MIT http://en.wikipedia.org/wiki/MIT_License or GPLv2 http://en.wikipedia.org/wiki/GNU_General_Public_License
 */
(function(g,i,j,b){var h="sharrre",f={className:"sharrre",share:{googlePlus:false,facebook:false,twitter:false,digg:false,delicious:false,stumbleupon:false,linkedin:false,pinterest:false},shareTotal:0,template:"",title:"",url:j.location.href,text:j.title,urlCurl:"sharrre.php",count:{},total:0,shorterTotal:true,enableHover:true,enableCounter:true,enableTracking:false,hover:function(){},hide:function(){},click:function(){},render:function(){},buttons:{googlePlus:{url:"",urlCount:false,size:"medium",lang:"en-US",annotation:""},facebook:{url:"",urlCount:false,action:"like",layout:"button_count",width:"",send:"false",faces:"false",colorscheme:"",font:"",lang:"en_US"},twitter:{url:"",urlCount:false,count:"horizontal",hashtags:"",via:"",related:"",lang:"en"},digg:{url:"",urlCount:false,type:"DiggCompact"},delicious:{url:"",urlCount:false,size:"medium"},stumbleupon:{url:"",urlCount:false,layout:"1"},linkedin:{url:"",urlCount:false,counter:""},pinterest:{url:"",media:"",description:"",layout:"horizontal"}}},c={googlePlus:"",facebook:"https://graph.facebook.com/fql?q=SELECT%20url,%20normalized_url,%20share_count,%20like_count,%20comment_count,%20total_count,commentsbox_count,%20comments_fbid,%20click_count%20FROM%20link_stat%20WHERE%20url=%27{url}%27&callback=?",twitter:"http://cdn.api.twitter.com/1/urls/count.json?url={url}&callback=?",digg:"http://services.digg.com/2.0/story.getInfo?links={url}&type=javascript&callback=?",delicious:"http://feeds.delicious.com/v2/json/urlinfo/data?url={url}&callback=?",stumbleupon:"",linkedin:"http://www.linkedin.com/countserv/count/share?format=jsonp&url={url}&callback=?",pinterest:""},l={googlePlus:function(m){var n=m.options.buttons.googlePlus;g(m.element).find(".buttons").append('<div class="button googleplus"><div class="g-plusone" data-size="'+n.size+'" data-href="'+(n.url!==""?n.url:m.options.url)+'" data-annotation="'+n.annotation+'"></div></div>');i.___gcfg={lang:m.options.buttons.googlePlus.lang};var o=0;if(typeof gapi==="undefined"&&o==0){o=1;(function(){var p=j.createElement("script");p.type="text/javascript";p.async=true;p.src="//apis.google.com/js/plusone.js";var q=j.getElementsByTagName("script")[0];q.parentNode.insertBefore(p,q)})()}else{gapi.plusone.go()}},facebook:function(m){var n=m.options.buttons.facebook;g(m.element).find(".buttons").append('<div class="button facebook"><div id="fb-root"></div><div class="fb-like" data-href="'+(n.url!==""?n.url:m.options.url)+'" data-send="'+n.send+'" data-layout="'+n.layout+'" data-width="'+n.width+'" data-show-faces="'+n.faces+'" data-action="'+n.action+'" data-colorscheme="'+n.colorscheme+'" data-font="'+n.font+'" data-via="'+n.via+'"></div></div>');var o=0;if(typeof FB==="undefined"&&o==0){o=1;(function(t,p,u){var r,q=t.getElementsByTagName(p)[0];if(t.getElementById(u)){return}r=t.createElement(p);r.id=u;r.src="//connect.facebook.net/"+n.lang+"/all.js#xfbml=1";q.parentNode.insertBefore(r,q)}(j,"script","facebook-jssdk"))}else{FB.XFBML.parse()}},twitter:function(m){var n=m.options.buttons.twitter;g(m.element).find(".buttons").append('<div class="button twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-url="'+(n.url!==""?n.url:m.options.url)+'" data-count="'+n.count+'" data-text="'+m.options.text+'" data-via="'+n.via+'" data-hashtags="'+n.hashtags+'" data-related="'+n.related+'" data-lang="'+n.lang+'">Tweet</a></div>');var o=0;if(typeof twttr==="undefined"&&o==0){o=1;(function(){var q=j.createElement("script");q.type="text/javascript";q.async=true;q.src="//platform.twitter.com/widgets.js";var p=j.getElementsByTagName("script")[0];p.parentNode.insertBefore(q,p)})()}else{g.ajax({url:"//platform.twitter.com/widgets.js",dataType:"script",cache:true})}},digg:function(m){var n=m.options.buttons.digg;g(m.element).find(".buttons").append('<div class="button digg"><a class="DiggThisButton '+n.type+'" rel="nofollow external" href="http://digg.com/submit?url='+encodeURIComponent((n.url!==""?n.url:m.options.url))+'"></a></div>');var o=0;if(typeof __DBW==="undefined"&&o==0){o=1;(function(){var q=j.createElement("SCRIPT"),p=j.getElementsByTagName("SCRIPT")[0];q.type="text/javascript";q.async=true;q.src="//widgets.digg.com/buttons.js";p.parentNode.insertBefore(q,p)})()}},delicious:function(o){if(o.options.buttons.delicious.size=="tall"){var p="width:50px;",n="height:35px;width:50px;font-size:15px;line-height:35px;",m="height:18px;line-height:18px;margin-top:3px;"}else{var p="width:93px;",n="float:right;padding:0 3px;height:20px;width:26px;line-height:20px;",m="float:left;height:20px;line-height:20px;"}var q=o.shorterTotal(o.options.count.delicious);if(typeof q==="undefined"){q=0}g(o.element).find(".buttons").append('<div class="button delicious"><div style="'+p+'font:12px Arial,Helvetica,sans-serif;cursor:pointer;color:#666666;display:inline-block;float:none;height:20px;line-height:normal;margin:0;padding:0;text-indent:0;vertical-align:baseline;"><div style="'+n+'background-color:#fff;margin-bottom:5px;overflow:hidden;text-align:center;border:1px solid #ccc;border-radius:3px;">'+q+'</div><div style="'+m+'display:block;padding:0;text-align:center;text-decoration:none;width:50px;background-color:#7EACEE;border:1px solid #40679C;border-radius:3px;color:#fff;"><img src="http://www.delicious.com/static/img/delicious.small.gif" height="10" width="10" alt="Delicious" /> Add</div></div></div>');g(o.element).find(".delicious").on("click",function(){o.openPopup("delicious")})},stumbleupon:function(m){var n=m.options.buttons.stumbleupon;g(m.element).find(".buttons").append('<div class="button stumbleupon"><su:badge layout="'+n.layout+'" location="'+(n.url!==""?n.url:m.options.url)+'"></su:badge></div>');var o=0;if(typeof STMBLPN==="undefined"&&o==0){o=1;(function(){var p=j.createElement("script");p.type="text/javascript";p.async=true;p.src="//platform.stumbleupon.com/1/widgets.js";var q=j.getElementsByTagName("script")[0];q.parentNode.insertBefore(p,q)})();s=i.setTimeout(function(){if(typeof STMBLPN!=="undefined"){STMBLPN.processWidgets();clearInterval(s)}},500)}else{STMBLPN.processWidgets()}},linkedin:function(m){var n=m.options.buttons.linkedin;g(m.element).find(".buttons").append('<div class="button linkedin"><script type="in/share" data-url="'+(n.url!==""?n.url:m.options.url)+'" data-counter="'+n.counter+'"><\/script></div>');var o=0;if(typeof i.IN==="undefined"&&o==0){o=1;(function(){var p=j.createElement("script");p.type="text/javascript";p.async=true;p.src="//platform.linkedin.com/in.js";var q=j.getElementsByTagName("script")[0];q.parentNode.insertBefore(p,q)})()}else{i.IN.init()}},pinterest:function(m){var n=m.options.buttons.pinterest;g(m.element).find(".buttons").append('<div class="button pinterest"><a href="http://pinterest.com/pin/create/button/?url='+(n.url!==""?n.url:m.options.url)+"&media="+n.media+"&description="+n.description+'" class="pin-it-button" count-layout="'+n.layout+'">Pin It</a></div>');(function(){var o=j.createElement("script");o.type="text/javascript";o.async=true;o.src="//assets.pinterest.com/js/pinit.js";var p=j.getElementsByTagName("script")[0];p.parentNode.insertBefore(o,p)})()}},d={googlePlus:function(){},facebook:function(){fb=i.setInterval(function(){if(typeof FB!=="undefined"){FB.Event.subscribe("edge.create",function(m){_gaq.push(["_trackSocial","facebook","like",m])});FB.Event.subscribe("edge.remove",function(m){_gaq.push(["_trackSocial","facebook","unlike",m])});FB.Event.subscribe("message.send",function(m){_gaq.push(["_trackSocial","facebook","send",m])});clearInterval(fb)}},1000)},twitter:function(){tw=i.setInterval(function(){if(typeof twttr!=="undefined"){twttr.events.bind("tweet",function(m){if(m){_gaq.push(["_trackSocial","twitter","tweet"])}});clearInterval(tw)}},1000)},digg:function(){},delicious:function(){},stumbleupon:function(){},linkedin:function(){function m(){_gaq.push(["_trackSocial","linkedin","share"])}},pinterest:function(){}},a={googlePlus:function(m){i.open("https://plus.google.com/share?hl="+m.buttons.googlePlus.lang+"&url="+encodeURIComponent((m.buttons.googlePlus.url!==""?m.buttons.googlePlus.url:m.url)),"","toolbar=0, status=0, width=900, height=500")},facebook:function(m){i.open("http://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent((m.buttons.facebook.url!==""?m.buttons.facebook.url:m.url))+"&t="+m.text+"","","toolbar=0, status=0, width=900, height=500")},twitter:function(m){i.open("https://twitter.com/intent/tweet?text="+encodeURIComponent(m.text)+"&url="+encodeURIComponent((m.buttons.twitter.url!==""?m.buttons.twitter.url:m.url))+(m.buttons.twitter.via!==""?"&via="+m.buttons.twitter.via:""),"","toolbar=0, status=0, width=650, height=360")},digg:function(m){i.open("http://digg.com/tools/diggthis/submit?url="+encodeURIComponent((m.buttons.digg.url!==""?m.buttons.digg.url:m.url))+"&title="+m.text+"&related=true&style=true","","toolbar=0, status=0, width=650, height=360")},delicious:function(m){i.open("http://www.delicious.com/save?v=5&noui&jump=close&url="+encodeURIComponent((m.buttons.delicious.url!==""?m.buttons.delicious.url:m.url))+"&title="+m.text,"delicious","toolbar=no,width=550,height=550")},stumbleupon:function(m){i.open("http://www.stumbleupon.com/badge/?url="+encodeURIComponent((m.buttons.delicious.url!==""?m.buttons.delicious.url:m.url)),"stumbleupon","toolbar=no,width=550,height=550")},linkedin:function(m){i.open("https://www.linkedin.com/cws/share?url="+encodeURIComponent((m.buttons.delicious.url!==""?m.buttons.delicious.url:m.url))+"&token=&isFramed=true","linkedin","toolbar=no,width=550,height=550")},pinterest:function(m){i.open("http://pinterest.com/pin/create/button/?url="+encodeURIComponent((m.buttons.pinterest.url!==""?m.buttons.pinterest.url:m.url))+"&media="+encodeURIComponent(m.buttons.pinterest.media)+"&description="+m.buttons.pinterest.description,"pinterest","toolbar=no,width=700,height=300")}};function k(n,m){this.element=n;this.options=g.extend(true,{},f,m);this.options.share=m.share;this._defaults=f;this._name=h;this.init()}k.prototype.init=function(){var m=this;if(this.options.urlCurl!==""){c.googlePlus=this.options.urlCurl+"?url={url}&type=googlePlus";c.stumbleupon=this.options.urlCurl+"?url={url}&type=stumbleupon";c.pinterest=this.options.urlCurl+"?url={url}&type=pinterest"}g(this.element).addClass(this.options.className);if(typeof g(this.element).data("title")!=="undefined"){this.options.title=g(this.element).attr("data-title")}if(typeof g(this.element).data("url")!=="undefined"){this.options.url=g(this.element).data("url")}if(typeof g(this.element).data("text")!=="undefined"){this.options.text=g(this.element).data("text")}g.each(this.options.share,function(n,o){if(o===true){m.options.shareTotal++}});if(m.options.enableCounter===true){g.each(this.options.share,function(n,p){if(p===true){try{m.getSocialJson(n)}catch(o){}}})}else{if(m.options.template!==""){this.options.render(this,this.options)}else{this.loadButtons()}}g(this.element).hover(function(){if(g(this).find(".buttons").length===0&&m.options.enableHover===true){m.loadButtons()}m.options.hover(m,m.options)},function(){m.options.hide(m,m.options)});g(this.element).click(function(){m.options.click(m,m.options);return false})};k.prototype.loadButtons=function(){var m=this;g(this.element).append('<div class="buttons"></div>');g.each(m.options.share,function(n,o){if(o==true){l[n](m);if(m.options.enableTracking===true){d[n]()}}})};k.prototype.getSocialJson=function(o){var m=this,p=0,n=c[o].replace("{url}",encodeURIComponent(this.options.url));if(this.options.buttons[o].urlCount===true&&this.options.buttons[o].url!==""){n=c[o].replace("{url}",this.options.buttons[o].url)}if(n!=""&&m.options.urlCurl!==""){g.getJSON(n,function(r){if(typeof r.count!=="undefined"){var q=r.count+"";q=q.replace("\u00c2\u00a0","");p+=parseInt(q,10)}else{if(r.data&&r.data.length>0&&typeof r.data[0].total_count!=="undefined"){p+=parseInt(r.data[0].total_count,10)}else{if(typeof r.shares!=="undefined"){p+=parseInt(r.shares,10)}else{if(typeof r[0]!=="undefined"){p+=parseInt(r[0].total_posts,10)}else{if(typeof r[0]!=="undefined"){}}}}}m.options.count[o]=p;m.options.total+=p;m.renderer();m.rendererPerso()}).error(function(){m.options.count[o]=0;m.rendererPerso()})}else{m.renderer();m.options.count[o]=0;m.rendererPerso()}};k.prototype.rendererPerso=function(){var m=0;for(e in this.options.count){m++}if(m===this.options.shareTotal){this.options.render(this,this.options)}};k.prototype.renderer=function(){var n=this.options.total,m=this.options.template;if(this.options.shorterTotal===true){n=this.shorterTotal(n)}if(m!==""){m=m.replace("{total}",n);g(this.element).html(m)}else{g(this.element).html('<div class="box"><a class="count" href="#">'+n+"</a>"+(this.options.title!==""?'<a class="share" href="#">'+this.options.title+"</a>":"")+"</div>")}};k.prototype.shorterTotal=function(m){if(m>=1000000){m=(m/1000000).toFixed(2)+"M"}else{if(m>=1000){m=(m/1000).toFixed(1)+"k"}}return m};k.prototype.openPopup=function(m){a[m](this.options);if(this.options.enableTracking===true){var n={googlePlus:{site:"Google",action:"+1"},facebook:{site:"facebook",action:"like"},twitter:{site:"twitter",action:"tweet"},digg:{site:"digg",action:"add"},delicious:{site:"delicious",action:"add"},stumbleupon:{site:"stumbleupon",action:"add"},linkedin:{site:"linkedin",action:"share"},pinterest:{site:"pinterest",action:"pin"}};_gaq.push(["_trackSocial",n[m].site,n[m].action])}};k.prototype.simulateClick=function(){var m=g(this.element).html();g(this.element).html(m.replace(this.options.total,this.options.total+1))};k.prototype.update=function(m,n){if(m!==""){this.options.url=m}if(n!==""){this.options.text=n}};g.fn[h]=function(n){var m=arguments;if(n===b||typeof n==="object"){return this.each(function(){if(!g.data(this,"plugin_"+h)){g.data(this,"plugin_"+h,new k(this,n))}})}else{if(typeof n==="string"&&n[0]!=="_"&&n!=="init"){return this.each(function(){var o=g.data(this,"plugin_"+h);if(o instanceof k&&typeof o[n]==="function"){o[n].apply(o,Array.prototype.slice.call(m,1))}})}}}})(jQuery,window,document);


/**
 * Copyright (c) 2007-2012 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * @author Ariel Flesler
 * @version 1.4.3.1
 */
(function($){var h=$.scrollTo=function(a,b,c){$(window).scrollTo(a,b,c)};h.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};h.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(e,f,g){if(typeof f=='object'){g=f;f=0}if(typeof g=='function')g={onAfter:g};if(e=='max')e=9e9;g=$.extend({},h.defaults,g);f=f||g.duration;g.queue=g.queue&&g.axis.length>1;if(g.queue)f/=2;g.offset=both(g.offset);g.over=both(g.over);return this._scrollable().each(function(){if(e==null)return;var d=this,$elem=$(d),targ=e,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}$.each(g.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=h.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(g.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=g.offset[pos]||0;if(g.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*g.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(g.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&g.queue){if(old!=attr[key])animate(g.onAfterFirst);delete attr[key]}});animate(g.onAfter);function animate(a){$elem.animate(attr,f,g.easing,a&&function(){a.call(this,e,g)})}}).end()};h.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);


/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright 漏 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];
jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(x,t,b,c,d){return jQuery.easing[jQuery.easing.def](x,t,b,c,d)},easeInQuad:function(x,t,b,c,d){return c*(t/=d)*t+b},easeOutQuad:function(x,t,b,c,d){return -c*(t/=d)*(t-2)+b},easeInOutQuad:function(x,t,b,c,d){if((t/=d/2)<1){return c/2*t*t+b}return -c/2*((--t)*(t-2)-1)+b},easeInCubic:function(x,t,b,c,d){return c*(t/=d)*t*t+b},easeOutCubic:function(x,t,b,c,d){return c*((t=t/d-1)*t*t+1)+b},easeInOutCubic:function(x,t,b,c,d){if((t/=d/2)<1){return c/2*t*t*t+b}return c/2*((t-=2)*t*t+2)+b},easeInQuart:function(x,t,b,c,d){return c*(t/=d)*t*t*t+b},easeOutQuart:function(x,t,b,c,d){return -c*((t=t/d-1)*t*t*t-1)+b},easeInOutQuart:function(x,t,b,c,d){if((t/=d/2)<1){return c/2*t*t*t*t+b}return -c/2*((t-=2)*t*t*t-2)+b},easeInQuint:function(x,t,b,c,d){return c*(t/=d)*t*t*t*t+b},easeOutQuint:function(x,t,b,c,d){return c*((t=t/d-1)*t*t*t*t+1)+b},easeInOutQuint:function(x,t,b,c,d){if((t/=d/2)<1){return c/2*t*t*t*t*t+b}return c/2*((t-=2)*t*t*t*t+2)+b},easeInSine:function(x,t,b,c,d){return -c*Math.cos(t/d*(Math.PI/2))+c+b},easeOutSine:function(x,t,b,c,d){return c*Math.sin(t/d*(Math.PI/2))+b},easeInOutSine:function(x,t,b,c,d){return -c/2*(Math.cos(Math.PI*t/d)-1)+b},easeInExpo:function(x,t,b,c,d){return(t==0)?b:c*Math.pow(2,10*(t/d-1))+b},easeOutExpo:function(x,t,b,c,d){return(t==d)?b+c:c*(-Math.pow(2,-10*t/d)+1)+b},easeInOutExpo:function(x,t,b,c,d){if(t==0){return b}if(t==d){return b+c}if((t/=d/2)<1){return c/2*Math.pow(2,10*(t-1))+b}return c/2*(-Math.pow(2,-10*--t)+2)+b},easeInCirc:function(x,t,b,c,d){return -c*(Math.sqrt(1-(t/=d)*t)-1)+b},easeOutCirc:function(x,t,b,c,d){return c*Math.sqrt(1-(t=t/d-1)*t)+b},easeInOutCirc:function(x,t,b,c,d){if((t/=d/2)<1){return -c/2*(Math.sqrt(1-t*t)-1)+b}return c/2*(Math.sqrt(1-(t-=2)*t)+1)+b},easeInElastic:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0){return b}if((t/=d)==1){return b+c}if(!p){p=d*0.3}if(a<Math.abs(c)){a=c;var s=p/4}else{var s=p/(2*Math.PI)*Math.asin(c/a)}return -(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b},easeOutElastic:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0){return b}if((t/=d)==1){return b+c}if(!p){p=d*0.3}if(a<Math.abs(c)){a=c;var s=p/4}else{var s=p/(2*Math.PI)*Math.asin(c/a)}return a*Math.pow(2,-10*t)*Math.sin((t*d-s)*(2*Math.PI)/p)+c+b},easeInOutElastic:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0){return b}if((t/=d/2)==2){return b+c}if(!p){p=d*(0.3*1.5)}if(a<Math.abs(c)){a=c;var s=p/4}else{var s=p/(2*Math.PI)*Math.asin(c/a)}if(t<1){return -0.5*(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b}return a*Math.pow(2,-10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p)*0.5+c+b},easeInBack:function(x,t,b,c,d,s){if(s==undefined){s=1.70158}return c*(t/=d)*t*((s+1)*t-s)+b},easeOutBack:function(x,t,b,c,d,s){if(s==undefined){s=1.70158}return c*((t=t/d-1)*t*((s+1)*t+s)+1)+b},easeInOutBack:function(x,t,b,c,d,s){if(s==undefined){s=1.70158}if((t/=d/2)<1){return c/2*(t*t*(((s*=(1.525))+1)*t-s))+b}return c/2*((t-=2)*t*(((s*=(1.525))+1)*t+s)+2)+b},easeInBounce:function(x,t,b,c,d){return c-jQuery.easing.easeOutBounce(x,d-t,0,c,d)+b},easeOutBounce:function(x,t,b,c,d){if((t/=d)<(1/2.75)){return c*(7.5625*t*t)+b}else{if(t<(2/2.75)){return c*(7.5625*(t-=(1.5/2.75))*t+0.75)+b}else{if(t<(2.5/2.75)){return c*(7.5625*(t-=(2.25/2.75))*t+0.9375)+b}else{return c*(7.5625*(t-=(2.625/2.75))*t+0.984375)+b}}}},easeInOutBounce:function(x,t,b,c,d){if(t<d/2){return jQuery.easing.easeInBounce(x,t*2,0,c,d)*0.5+b}return jQuery.easing.easeOutBounce(x,t*2-d,0,c,d)*0.5+c*0.5+b}});

/*
 *
 * TERMS OF USE - EASING EQUATIONS
 * 
 * Open source under the BSD License. 
 * 
 * Copyright 漏 2001 Robert Penner
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *



/* ------------------------------------------------------------------------
Class: prettyPhoto
Use: Lightbox clone for jQuery
Author: Stephane Caron (http://www.no-margin-for-errors.com)
Version: 3.1.3
------------------------------------------------------------------------- */
(function($){$.prettyPhoto={version:"3.1.4"};$.fn.prettyPhoto=function(pp_settings){pp_settings=jQuery.extend({hook:"rel",animation_speed:"fast",ajaxcallback:function(){},slideshow:5000,autoplay_slideshow:false,opacity:0.8,show_title:true,allow_resize:true,allow_expand:true,default_width:500,default_height:344,counter_separator_label:"/",theme:"pp_default",horizontal_padding:20,hideflash:false,wmode:"opaque",autoplay:true,modal:false,deeplinking:true,overlay_gallery:true,overlay_gallery_max:30,keyboard_shortcuts:true,changepicturecallback:function(){},callback:function(){},ie6_fallback:true,markup:'<div class="pp_pic_holder"> 						<div class="ppt">&nbsp;</div> 						<div class="pp_top"> 							<div class="pp_left"></div> 							<div class="pp_middle"></div> 							<div class="pp_right"></div> 						</div> 						<div class="pp_content_container"> 							<div class="pp_left"> 							<div class="pp_right"> 								<div class="pp_content"> 									<div class="pp_loaderIcon"></div> 									<div class="pp_fade"> 										<a href="#" class="pp_expand" title="Expand the image">Expand</a> 										<div class="pp_hoverContainer"> 											<a class="pp_next" href="#">next</a> 											<a class="pp_previous" href="#">previous</a> 										</div> 										<div id="pp_full_res"></div> 										<div class="pp_details"> 											<div class="pp_nav"> 												<a href="#" class="pp_arrow_previous">Previous</a> 												<p class="currentTextHolder">0/0</p> 												<a href="#" class="pp_arrow_next">Next</a> 											</div> 											<p class="pp_description"></p> 											<div class="pp_social">{pp_social}</div> 											<a class="pp_close" href="#">Close</a> 										</div> 									</div> 								</div> 							</div> 							</div> 						</div> 						<div class="pp_bottom"> 							<div class="pp_left"></div> 							<div class="pp_middle"></div> 							<div class="pp_right"></div> 						</div> 					</div> 					<div class="pp_overlay"></div>',gallery_markup:'<div class="pp_gallery"> 								<a href="#" class="pp_arrow_previous">Previous</a> 								<div> 									<ul> 										{gallery} 									</ul> 								</div> 								<a href="#" class="pp_arrow_next">Next</a> 							</div>',image_markup:'<img id="fullResImage" src="{path}" />',flash_markup:'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',quicktime_markup:'<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',iframe_markup:'<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',inline_markup:'<div class="pp_inline">{content}</div>',custom_markup:"",social_tools:'<div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"><\/script></div><div class="facebook"><iframe src="//www.facebook.com/plugins/like.php?locale=en_US&href={location_href}&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:23px;" allowTransparency="true"></iframe></div>'},pp_settings);var matchedObjects=this,percentBased=false,pp_dimensions,pp_open,pp_contentHeight,pp_contentWidth,pp_containerHeight,pp_containerWidth,windowHeight=$(window).height(),windowWidth=$(window).width(),pp_slideshow;doresize=true,scroll_pos=_get_scroll();$(window).unbind("resize.prettyphoto").bind("resize.prettyphoto",function(){_center_overlay();_resize_overlay()});if(pp_settings.keyboard_shortcuts){$(document).unbind("keydown.prettyphoto").bind("keydown.prettyphoto",function(e){if(typeof $pp_pic_holder!="undefined"){if($pp_pic_holder.is(":visible")){switch(e.keyCode){case 37:$.prettyPhoto.changePage("previous");e.preventDefault();break;case 39:$.prettyPhoto.changePage("next");e.preventDefault();break;case 27:if(!settings.modal){$.prettyPhoto.close()}e.preventDefault();break}}}})}$.prettyPhoto.initialize=function(){settings=pp_settings;if(settings.theme=="pp_default"){settings.horizontal_padding=16}if(settings.ie6_fallback&&PEXETO.getBrowser().msie&&parseInt(PEXETO.getBrowser().version)==6){settings.theme="light_square"}theRel=$(this).attr(settings.hook);galleryRegExp=/\[(?:.*)\]/;isSet=(galleryRegExp.exec(theRel))?true:false;pp_images=(isSet)?jQuery.map(matchedObjects,function(n,i){if($(n).attr(settings.hook).indexOf(theRel)!=-1){return $(n).attr("href")}}):$.makeArray($(this).attr("href"));pp_titles=(isSet)?jQuery.map(matchedObjects,function(n,i){if($(n).attr(settings.hook).indexOf(theRel)!=-1){return($(n).find("img").attr("alt"))?$(n).find("img").attr("alt"):""}}):$.makeArray($(this).find("img").attr("alt"));pp_descriptions=(isSet)?jQuery.map(matchedObjects,function(n,i){if($(n).attr(settings.hook).indexOf(theRel)!=-1){return($(n).attr("title"))?$(n).attr("title"):""}}):$.makeArray($(this).attr("title"));if(pp_images.length>settings.overlay_gallery_max){settings.overlay_gallery=false}set_position=jQuery.inArray($(this).attr("href"),pp_images);rel_index=(isSet)?set_position:$("a["+settings.hook+"^='"+theRel+"']").index($(this));_build_overlay(this);if(settings.allow_resize){$(window).bind("scroll.prettyphoto",function(){_center_overlay()})}$.prettyPhoto.open();return false};$.prettyPhoto.open=function(event){if(typeof settings=="undefined"){settings=pp_settings;if(PEXETO.getBrowser().msie&&PEXETO.getBrowser().version==6){settings.theme="light_square"}pp_images=$.makeArray(arguments[0]);pp_titles=(arguments[1])?$.makeArray(arguments[1]):$.makeArray("");pp_descriptions=(arguments[2])?$.makeArray(arguments[2]):$.makeArray("");isSet=(pp_images.length>1)?true:false;set_position=(arguments[3])?arguments[3]:0;_build_overlay(event.target)}if(PEXETO.getBrowser().msie&&PEXETO.getBrowser().version==6){$("select").css("visibility","hidden")}if(settings.hideflash){$("object,embed,iframe[src*=youtube],iframe[src*=vimeo]").css("visibility","hidden")}_checkPosition($(pp_images).size());$(".pp_loaderIcon").show();if(settings.deeplinking){setHashtag()}if(settings.social_tools){facebook_like_link=settings.social_tools.replace("{location_href}",encodeURIComponent(location.href));$pp_pic_holder.find(".pp_social").html(facebook_like_link)}if($ppt.is(":hidden")){$ppt.css("opacity",0).show()}$pp_overlay.show().fadeTo(settings.animation_speed,settings.opacity);$pp_pic_holder.find(".currentTextHolder").text((set_position+1)+settings.counter_separator_label+$(pp_images).size());if(typeof pp_descriptions[set_position]!="undefined"&&pp_descriptions[set_position]!=""){$pp_pic_holder.find(".pp_description").show().html(unescape(pp_descriptions[set_position]))}else{$pp_pic_holder.find(".pp_description").hide()}movie_width=(parseFloat(getParam("width",pp_images[set_position])))?getParam("width",pp_images[set_position]):settings.default_width.toString();movie_height=(parseFloat(getParam("height",pp_images[set_position])))?getParam("height",pp_images[set_position]):settings.default_height.toString();percentBased=false;if(movie_height.indexOf("%")!=-1){movie_height=parseFloat(($(window).height()*parseFloat(movie_height)/100)-150);percentBased=true}if(movie_width.indexOf("%")!=-1){movie_width=parseFloat(($(window).width()*parseFloat(movie_width)/100)-150);percentBased=true}$pp_pic_holder.fadeIn(function(){(settings.show_title&&pp_titles[set_position]!=""&&typeof pp_titles[set_position]!="undefined")?$ppt.html(unescape(pp_titles[set_position])):$ppt.html("&nbsp;");imgPreloader="";skipInjection=false;switch(_getFileType(pp_images[set_position])){case"image":imgPreloader=new Image();nextImage=new Image();if(isSet&&set_position<$(pp_images).size()-1){nextImage.src=pp_images[set_position+1]}prevImage=new Image();if(isSet&&pp_images[set_position-1]){prevImage.src=pp_images[set_position-1]}$pp_pic_holder.find("#pp_full_res")[0].innerHTML=settings.image_markup.replace(/{path}/g,pp_images[set_position]);imgPreloader.onload=function(){pp_dimensions=_fitToViewport(imgPreloader.width,imgPreloader.height);_showContent()};imgPreloader.onerror=function(){alert("Image cannot be loaded. Make sure the path is correct and image exist.");$.prettyPhoto.close()};imgPreloader.src=pp_images[set_position];break;case"youtube":pp_dimensions=_fitToViewport(movie_width,movie_height);movie_id=getParam("v",pp_images[set_position]);if(movie_id==""){movie_id=pp_images[set_position].split("youtu.be/");movie_id=movie_id[1];if(movie_id.indexOf("?")>0){movie_id=movie_id.substr(0,movie_id.indexOf("?"))}if(movie_id.indexOf("&")>0){movie_id=movie_id.substr(0,movie_id.indexOf("&"))}}movie="http://www.youtube.com/embed/"+movie_id;(getParam("rel",pp_images[set_position]))?movie+="?rel="+getParam("rel",pp_images[set_position]):movie+="?rel=1";if(settings.autoplay){movie+="&autoplay=1"}toInject=settings.iframe_markup.replace(/{width}/g,pp_dimensions.width).replace(/{height}/g,pp_dimensions.height).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,movie);break;case"vimeo":pp_dimensions=_fitToViewport(movie_width,movie_height);movie_id=pp_images[set_position];var regExp=/http:\/\/(www\.)?vimeo.com\/(\d+)/;var match=movie_id.match(regExp);movie="http://player.vimeo.com/video/"+match[2]+"?title=0&amp;byline=0&amp;portrait=0";if(settings.autoplay){movie+="&autoplay=1;"}vimeo_width=pp_dimensions.width+"/embed/?moog_width="+pp_dimensions.width;toInject=settings.iframe_markup.replace(/{width}/g,vimeo_width).replace(/{height}/g,pp_dimensions.height).replace(/{path}/g,movie);break;case"quicktime":pp_dimensions=_fitToViewport(movie_width,movie_height);pp_dimensions.height+=15;pp_dimensions.contentHeight+=15;pp_dimensions.containerHeight+=15;toInject=settings.quicktime_markup.replace(/{width}/g,pp_dimensions.width).replace(/{height}/g,pp_dimensions.height).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,pp_images[set_position]).replace(/{autoplay}/g,settings.autoplay);break;case"flash":pp_dimensions=_fitToViewport(movie_width,movie_height);flash_vars=pp_images[set_position];flash_vars=flash_vars.substring(pp_images[set_position].indexOf("flashvars")+10,pp_images[set_position].length);filename=pp_images[set_position];filename=filename.substring(0,filename.indexOf("?"));toInject=settings.flash_markup.replace(/{width}/g,pp_dimensions.width).replace(/{height}/g,pp_dimensions.height).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,filename+"?"+flash_vars);break;case"iframe":pp_dimensions=_fitToViewport(movie_width,movie_height);frame_url=pp_images[set_position];frame_url=frame_url.substr(0,frame_url.indexOf("iframe")-1);toInject=settings.iframe_markup.replace(/{width}/g,pp_dimensions.width).replace(/{height}/g,pp_dimensions.height).replace(/{path}/g,frame_url);break;case"ajax":doresize=false;pp_dimensions=_fitToViewport(movie_width,movie_height);doresize=true;skipInjection=true;$.get(pp_images[set_position],function(responseHTML){toInject=settings.inline_markup.replace(/{content}/g,responseHTML);$pp_pic_holder.find("#pp_full_res")[0].innerHTML=toInject;_showContent()});break;case"custom":pp_dimensions=_fitToViewport(movie_width,movie_height);toInject=settings.custom_markup;break;case"inline":myClone=$(pp_images[set_position]).clone().append('<br clear="all" />').css({width:settings.default_width}).wrapInner('<div id="pp_full_res"><div class="pp_inline"></div></div>').appendTo($("body")).show();doresize=false;pp_dimensions=_fitToViewport($(myClone).width(),$(myClone).height());doresize=true;$(myClone).remove();toInject=settings.inline_markup.replace(/{content}/g,$(pp_images[set_position]).html());break}if(!imgPreloader&&!skipInjection){$pp_pic_holder.find("#pp_full_res")[0].innerHTML=toInject;_showContent()}});return false};$.prettyPhoto.changePage=function(direction){currentGalleryPage=0;if(direction=="previous"){set_position--;if(set_position<0){set_position=$(pp_images).size()-1}}else{if(direction=="next"){set_position++;if(set_position>$(pp_images).size()-1){set_position=0}}else{set_position=direction}}rel_index=set_position;if(!doresize){doresize=true}if(settings.allow_expand){$(".pp_contract").removeClass("pp_contract").addClass("pp_expand")}_hideContent(function(){$.prettyPhoto.open()})};$.prettyPhoto.changeGalleryPage=function(direction){if(direction=="next"){currentGalleryPage++;if(currentGalleryPage>totalPage){currentGalleryPage=0}}else{if(direction=="previous"){currentGalleryPage--;if(currentGalleryPage<0){currentGalleryPage=totalPage}}else{currentGalleryPage=direction}}slide_speed=(direction=="next"||direction=="previous")?settings.animation_speed:0;slide_to=currentGalleryPage*(itemsPerPage*itemWidth);$pp_gallery.find("ul").animate({left:-slide_to},slide_speed)};$.prettyPhoto.startSlideshow=function(){if(typeof pp_slideshow=="undefined"){$pp_pic_holder.find(".pp_play").unbind("click").removeClass("pp_play").addClass("pp_pause").click(function(){$.prettyPhoto.stopSlideshow();return false});pp_slideshow=setInterval($.prettyPhoto.startSlideshow,settings.slideshow)}else{$.prettyPhoto.changePage("next")}};$.prettyPhoto.stopSlideshow=function(){$pp_pic_holder.find(".pp_pause").unbind("click").removeClass("pp_pause").addClass("pp_play").click(function(){$.prettyPhoto.startSlideshow();return false});clearInterval(pp_slideshow);pp_slideshow=undefined};$.prettyPhoto.close=function(){if($pp_overlay.is(":animated")){return}$.prettyPhoto.stopSlideshow();$pp_pic_holder.stop().find("object,embed").css("visibility","hidden");$("div.pp_pic_holder,div.ppt,.pp_fade").fadeOut(settings.animation_speed,function(){$(this).remove()});$pp_overlay.fadeOut(settings.animation_speed,function(){if(PEXETO.getBrowser().msie&&PEXETO.getBrowser().version==6){$("select").css("visibility","visible")}if(settings.hideflash){$("object,embed,iframe[src*=youtube],iframe[src*=vimeo]").css("visibility","visible")}$(this).remove();$(window).unbind("scroll.prettyphoto");clearHashtag();settings.callback();doresize=true;pp_open=false;delete settings})};function _showContent(){$(".pp_loaderIcon").hide();projectedTop=scroll_pos.scrollTop+((windowHeight/2)-(pp_dimensions.containerHeight/2));if(projectedTop<0){projectedTop=0}$ppt.fadeTo(settings.animation_speed,1);$pp_pic_holder.find(".pp_content").animate({height:pp_dimensions.contentHeight,width:pp_dimensions.contentWidth},settings.animation_speed);$pp_pic_holder.animate({top:projectedTop,left:((windowWidth/2)-(pp_dimensions.containerWidth/2)<0)?0:(windowWidth/2)-(pp_dimensions.containerWidth/2),width:pp_dimensions.containerWidth},settings.animation_speed,function(){$pp_pic_holder.find(".pp_hoverContainer,#fullResImage").height(pp_dimensions.height).width(pp_dimensions.width);$pp_pic_holder.find(".pp_fade").fadeIn(settings.animation_speed);if(isSet&&_getFileType(pp_images[set_position])=="image"){$pp_pic_holder.find(".pp_hoverContainer").show()}else{$pp_pic_holder.find(".pp_hoverContainer").hide()}if(settings.allow_expand){if(pp_dimensions.resized){$("a.pp_expand,a.pp_contract").show()}else{$("a.pp_expand").hide()}}if(settings.autoplay_slideshow&&!pp_slideshow&&!pp_open){$.prettyPhoto.startSlideshow()}settings.changepicturecallback();pp_open=true});_insert_gallery();pp_settings.ajaxcallback()}function _hideContent(callback){$pp_pic_holder.find("#pp_full_res object,#pp_full_res embed").css("visibility","hidden");$pp_pic_holder.find(".pp_fade").fadeOut(settings.animation_speed,function(){$(".pp_loaderIcon").show();callback()})}function _checkPosition(setCount){(setCount>1)?$(".pp_nav").show():$(".pp_nav").hide()}function _fitToViewport(width,height){resized=false;var isMobile=PEXETO.utils.checkIfMobile(),spacing=isMobile?40:200;_getDimensions(width,height);imageWidth=width,imageHeight=height;if(((pp_containerWidth>windowWidth)||(pp_containerHeight>windowHeight))&&doresize&&settings.allow_resize&&!percentBased){resized=true,fitting=false;while(!fitting){if((pp_containerWidth>windowWidth)){imageWidth=(windowWidth-spacing);imageHeight=(height/width)*imageWidth}else{if((pp_containerHeight>windowHeight)){imageHeight=(windowHeight-spacing);imageWidth=(width/height)*imageHeight}else{fitting=true}}pp_containerHeight=imageHeight,pp_containerWidth=imageWidth}_getDimensions(imageWidth,imageHeight);if(!isMobile){if((pp_containerWidth>windowWidth)||(pp_containerHeight>windowHeight)){_fitToViewport(pp_containerWidth,pp_containerHeight)}}}return{width:Math.floor(imageWidth),height:Math.floor(imageHeight),containerHeight:Math.floor(pp_containerHeight),containerWidth:Math.floor(pp_containerWidth)+(settings.horizontal_padding*2),contentHeight:Math.floor(pp_contentHeight),contentWidth:Math.floor(pp_contentWidth),resized:resized}}function _getDimensions(width,height){width=parseFloat(width);height=parseFloat(height);$pp_details=$pp_pic_holder.find(".pp_details");$pp_details.width(width);detailsHeight=parseFloat($pp_details.css("marginTop"))+parseFloat($pp_details.css("marginBottom"));$pp_details=$pp_details.clone().addClass(settings.theme).width(width).appendTo($("body")).css({position:"absolute",top:-10000});detailsHeight+=$pp_details.height();detailsHeight=(detailsHeight<=34)?36:detailsHeight;if(PEXETO.getBrowser().msie&&PEXETO.getBrowser().version==7){detailsHeight+=8}$pp_details.remove();$pp_title=$pp_pic_holder.find(".ppt");$pp_title.width(width);titleHeight=parseFloat($pp_title.css("marginTop"))+parseFloat($pp_title.css("marginBottom"));$pp_title=$pp_title.clone().appendTo($("body")).css({position:"absolute",top:-10000});titleHeight+=$pp_title.height();$pp_title.remove();pp_contentHeight=height+detailsHeight;pp_contentWidth=width;pp_containerHeight=pp_contentHeight+titleHeight+$pp_pic_holder.find(".pp_top").height()+$pp_pic_holder.find(".pp_bottom").height();pp_containerWidth=width}function _getFileType(itemSrc){if(itemSrc.match(/youtube\.com\/watch/i)||itemSrc.match(/youtu\.be/i)){return"youtube"}else{if(itemSrc.match(/vimeo\.com/i)){return"vimeo"}else{if(itemSrc.match(/\b.mov\b/i)){return"quicktime"}else{if(itemSrc.match(/\b.swf\b/i)){return"flash"}else{if(itemSrc.match(/\biframe=true\b/i)){return"iframe"}else{if(itemSrc.match(/\bajax=true\b/i)){return"ajax"}else{if(itemSrc.match(/\bcustom=true\b/i)){return"custom"}else{if(itemSrc.substr(0,1)=="#"){return"inline"}else{return"image"}}}}}}}}}function _center_overlay(){if(doresize&&typeof $pp_pic_holder!="undefined"){scroll_pos=_get_scroll();contentHeight=$pp_pic_holder.height(),contentwidth=$pp_pic_holder.width();projectedTop=(windowHeight/2)+scroll_pos.scrollTop-(contentHeight/2);if(projectedTop<0){projectedTop=0}if(contentHeight>windowHeight){return}$pp_pic_holder.css({top:projectedTop,left:(windowWidth/2)+scroll_pos.scrollLeft-(contentwidth/2)})}}function _get_scroll(){if(self.pageYOffset){return{scrollTop:self.pageYOffset,scrollLeft:self.pageXOffset}}else{if(document.documentElement&&document.documentElement.scrollTop){return{scrollTop:document.documentElement.scrollTop,scrollLeft:document.documentElement.scrollLeft}}else{if(document.body){return{scrollTop:document.body.scrollTop,scrollLeft:document.body.scrollLeft}}}}}function _resize_overlay(){windowHeight=$(window).height(),windowWidth=$(window).width();if(typeof $pp_overlay!="undefined"){$pp_overlay.height($(document).height()).width(windowWidth)}}function _insert_gallery(){if(isSet&&settings.overlay_gallery&&_getFileType(pp_images[set_position])=="image"&&(settings.ie6_fallback&&!(PEXETO.getBrowser().msie&&parseInt(PEXETO.getBrowser().version)==6))){itemWidth=52+5;navWidth=(settings.theme=="facebook"||settings.theme=="pp_default")?50:30;itemsPerPage=Math.floor((pp_dimensions.containerWidth-100-navWidth)/itemWidth);itemsPerPage=(itemsPerPage<pp_images.length)?itemsPerPage:pp_images.length;totalPage=Math.ceil(pp_images.length/itemsPerPage)-1;if(totalPage==0){navWidth=0;$pp_gallery.find(".pp_arrow_next,.pp_arrow_previous").hide()}else{$pp_gallery.find(".pp_arrow_next,.pp_arrow_previous").show()}galleryWidth=itemsPerPage*itemWidth;fullGalleryWidth=pp_images.length*itemWidth;$pp_gallery.css("margin-left",-((galleryWidth/2)+(navWidth/2))).find("div:first").width(galleryWidth+5).find("ul").width(fullGalleryWidth).find("li.selected").removeClass("selected");goToPage=(Math.floor(set_position/itemsPerPage)<totalPage)?Math.floor(set_position/itemsPerPage):totalPage;$.prettyPhoto.changeGalleryPage(goToPage);$pp_gallery_li.filter(":eq("+set_position+")").addClass("selected")}else{$pp_pic_holder.find(".pp_content").unbind("mouseenter mouseleave")}}function _build_overlay(caller){if(settings.social_tools){facebook_like_link=settings.social_tools.replace("{location_href}",encodeURIComponent(location.href))}settings.markup=settings.markup.replace("{pp_social}","");$("body").append(settings.markup);$pp_pic_holder=$(".pp_pic_holder"),$ppt=$(".ppt"),$pp_overlay=$("div.pp_overlay");if(isSet&&settings.overlay_gallery){currentGalleryPage=0;toInject="";for(var i=0;i<pp_images.length;i++){if(!pp_images[i].match(/\b(jpg|jpeg|png|gif)\b/gi)){classname="default";img_src=""}else{classname="";img_src=pp_images[i]}toInject+="<li class='"+classname+"'><a href='#'><img src='"+img_src+"' width='50' alt='' /></a></li>"}toInject=settings.gallery_markup.replace(/{gallery}/g,toInject);$pp_pic_holder.find("#pp_full_res").after(toInject);$pp_gallery=$(".pp_pic_holder .pp_gallery"),$pp_gallery_li=$pp_gallery.find("li");$pp_gallery.find(".pp_arrow_next").click(function(){$.prettyPhoto.changeGalleryPage("next");$.prettyPhoto.stopSlideshow();return false});$pp_gallery.find(".pp_arrow_previous").click(function(){$.prettyPhoto.changeGalleryPage("previous");$.prettyPhoto.stopSlideshow();return false});$pp_pic_holder.find(".pp_content").hover(function(){$pp_pic_holder.find(".pp_gallery:not(.disabled)").fadeIn()},function(){$pp_pic_holder.find(".pp_gallery:not(.disabled)").fadeOut()});itemWidth=52+5;$pp_gallery_li.each(function(i){$(this).find("a").click(function(){$.prettyPhoto.changePage(i);$.prettyPhoto.stopSlideshow();return false})})}if(settings.slideshow){$pp_pic_holder.find(".pp_nav").prepend('<a href="#" class="pp_play">Play</a>');$pp_pic_holder.find(".pp_nav .pp_play").click(function(){$.prettyPhoto.startSlideshow();return false})}$pp_pic_holder.attr("class","pp_pic_holder "+settings.theme);$pp_overlay.css({opacity:0,height:$(document).height(),width:$(window).width()}).bind("click",function(){if(!settings.modal){$.prettyPhoto.close()}});$("a.pp_close").bind("click",function(){$.prettyPhoto.close();return false});if(settings.allow_expand){$("a.pp_expand").bind("click",function(e){if($(this).hasClass("pp_expand")){$(this).removeClass("pp_expand").addClass("pp_contract");doresize=false}else{$(this).removeClass("pp_contract").addClass("pp_expand");doresize=true}_hideContent(function(){$.prettyPhoto.open()});return false})}$pp_pic_holder.find(".pp_previous, .pp_nav .pp_arrow_previous").bind("click",function(){$.prettyPhoto.changePage("previous");$.prettyPhoto.stopSlideshow();return false});$pp_pic_holder.find(".pp_next, .pp_nav .pp_arrow_next").bind("click",function(){$.prettyPhoto.changePage("next");$.prettyPhoto.stopSlideshow();return false});_center_overlay()}if(!pp_alreadyInitialized&&getHashtag()){pp_alreadyInitialized=true;hashIndex=getHashtag();hashRel=hashIndex;hashIndex=hashIndex.substring(hashIndex.indexOf("/")+1,hashIndex.length-1);hashRel=hashRel.substring(0,hashRel.indexOf("/"));setTimeout(function(){$("a["+pp_settings.hook+"^='"+hashRel+"']:eq("+hashIndex+")").trigger("click")},50)}return this.unbind("click.prettyphoto").bind("click.prettyphoto",$.prettyPhoto.initialize)};function getHashtag(){url=location.href;hashtag=(url.indexOf("#prettyPhoto")!==-1)?decodeURI(url.substring(url.indexOf("#prettyPhoto")+1,url.length)):false;return hashtag}function setHashtag(){if(typeof theRel=="undefined"){return}location.hash=theRel+"/"+rel_index+"/"}function clearHashtag(){if(location.href.indexOf("#prettyPhoto")!==-1){location.hash="prettyPhoto"}}function getParam(name,url){name=name.replace(/[\[]/,"\\[").replace(/[\]]/,"\\]");var regexS="[\\?&]"+name+"=([^&#]*)";var regex=new RegExp(regexS);var results=regex.exec(url);return(results==null)?"":results[1]}})(jQuery);var pp_alreadyInitialized=false;



// Generated by CoffeeScript 1.4.0
/*
jQuery Waypoints - v2.0.2
Copyright (c) 2011-2013 Caleb Troughton
Dual licensed under the MIT license and GPL license.
https://github.com/imakewebthings/jquery-waypoints/blob/master/licenses.txt
*/
(function(){var t=[].indexOf||function(t){for(var e=0,n=this.length;e<n;e++){if(e in this&&this[e]===t)return e}return-1},e=[].slice;(function(t,e){if(typeof define==="function"&&define.amd){return define("waypoints",["jquery"],function(n){return e(n,t)})}else{return e(t.jQuery,t)}})(this,function(n,r){var i,o,l,s,f,u,a,c,h,d,p,y,v,w,g,m;i=n(r);c=t.call(r,"ontouchstart")>=0;s={horizontal:{},vertical:{}};f=1;a={};u="waypoints-context-id";p="resize.waypoints";y="scroll.waypoints";v=1;w="waypoints-waypoint-ids";g="waypoint";m="waypoints";o=function(){function t(t){var e=this;this.$element=t;this.element=t[0];this.didResize=false;this.didScroll=false;this.id="context"+f++;this.oldScroll={x:t.scrollLeft(),y:t.scrollTop()};this.waypoints={horizontal:{},vertical:{}};t.data(u,this.id);a[this.id]=this;t.bind(y,function(){var t;if(!(e.didScroll||c)){e.didScroll=true;t=function(){e.doScroll();return e.didScroll=false};return r.setTimeout(t,n[m].settings.scrollThrottle)}});t.bind(p,function(){var t;if(!e.didResize){e.didResize=true;t=function(){n[m]("refresh");return e.didResize=false};return r.setTimeout(t,n[m].settings.resizeThrottle)}})}t.prototype.doScroll=function(){var t,e=this;t={horizontal:{newScroll:this.$element.scrollLeft(),oldScroll:this.oldScroll.x,forward:"right",backward:"left"},vertical:{newScroll:this.$element.scrollTop(),oldScroll:this.oldScroll.y,forward:"down",backward:"up"}};if(c&&(!t.vertical.oldScroll||!t.vertical.newScroll)){n[m]("refresh")}n.each(t,function(t,r){var i,o,l;l=[];o=r.newScroll>r.oldScroll;i=o?r.forward:r.backward;n.each(e.waypoints[t],function(t,e){var n,i;if(r.oldScroll<(n=e.offset)&&n<=r.newScroll){return l.push(e)}else if(r.newScroll<(i=e.offset)&&i<=r.oldScroll){return l.push(e)}});l.sort(function(t,e){return t.offset-e.offset});if(!o){l.reverse()}return n.each(l,function(t,e){if(e.options.continuous||t===l.length-1){return e.trigger([i])}})});return this.oldScroll={x:t.horizontal.newScroll,y:t.vertical.newScroll}};t.prototype.refresh=function(){var t,e,r,i=this;r=n.isWindow(this.element);e=this.$element.offset();this.doScroll();t={horizontal:{contextOffset:r?0:e.left,contextScroll:r?0:this.oldScroll.x,contextDimension:this.$element.width(),oldScroll:this.oldScroll.x,forward:"right",backward:"left",offsetProp:"left"},vertical:{contextOffset:r?0:e.top,contextScroll:r?0:this.oldScroll.y,contextDimension:r?n[m]("viewportHeight"):this.$element.height(),oldScroll:this.oldScroll.y,forward:"down",backward:"up",offsetProp:"top"}};return n.each(t,function(t,e){return n.each(i.waypoints[t],function(t,r){var i,o,l,s,f;i=r.options.offset;l=r.offset;o=n.isWindow(r.element)?0:r.$element.offset()[e.offsetProp];if(n.isFunction(i)){i=i.apply(r.element)}else if(typeof i==="string"){i=parseFloat(i);if(r.options.offset.indexOf("%")>-1){i=Math.ceil(e.contextDimension*i/100)}}r.offset=o-e.contextOffset+e.contextScroll-i;if(r.options.onlyOnScroll&&l!=null||!r.enabled){return}if(l!==null&&l<(s=e.oldScroll)&&s<=r.offset){return r.trigger([e.backward])}else if(l!==null&&l>(f=e.oldScroll)&&f>=r.offset){return r.trigger([e.forward])}else if(l===null&&e.oldScroll>=r.offset){return r.trigger([e.forward])}})})};t.prototype.checkEmpty=function(){if(n.isEmptyObject(this.waypoints.horizontal)&&n.isEmptyObject(this.waypoints.vertical)){this.$element.unbind([p,y].join(" "));return delete a[this.id]}};return t}();l=function(){function t(t,e,r){var i,o;r=n.extend({},n.fn[g].defaults,r);if(r.offset==="bottom-in-view"){r.offset=function(){var t;t=n[m]("viewportHeight");if(!n.isWindow(e.element)){t=e.$element.height()}return t-n(this).outerHeight()}}this.$element=t;this.element=t[0];this.axis=r.horizontal?"horizontal":"vertical";this.callback=r.handler;this.context=e;this.enabled=r.enabled;this.id="waypoints"+v++;this.offset=null;this.options=r;e.waypoints[this.axis][this.id]=this;s[this.axis][this.id]=this;i=(o=t.data(w))!=null?o:[];i.push(this.id);t.data(w,i)}t.prototype.trigger=function(t){if(!this.enabled){return}if(this.callback!=null){this.callback.apply(this.element,t)}if(this.options.triggerOnce){return this.destroy()}};t.prototype.disable=function(){return this.enabled=false};t.prototype.enable=function(){this.context.refresh();return this.enabled=true};t.prototype.destroy=function(){delete s[this.axis][this.id];delete this.context.waypoints[this.axis][this.id];return this.context.checkEmpty()};t.getWaypointsByElement=function(t){var e,r;r=n(t).data(w);if(!r){return[]}e=n.extend({},s.horizontal,s.vertical);return n.map(r,function(t){return e[t]})};return t}();d={init:function(t,e){var r;if(e==null){e={}}if((r=e.handler)==null){e.handler=t}this.each(function(){var t,r,i,s;t=n(this);i=(s=e.context)!=null?s:n.fn[g].defaults.context;if(!n.isWindow(i)){i=t.closest(i)}i=n(i);r=a[i.data(u)];if(!r){r=new o(i)}return new l(t,r,e)});n[m]("refresh");return this},disable:function(){return d._invoke(this,"disable")},enable:function(){return d._invoke(this,"enable")},destroy:function(){return d._invoke(this,"destroy")},prev:function(t,e){return d._traverse.call(this,t,e,function(t,e,n){if(e>0){return t.push(n[e-1])}})},next:function(t,e){return d._traverse.call(this,t,e,function(t,e,n){if(e<n.length-1){return t.push(n[e+1])}})},_traverse:function(t,e,i){var o,l;if(t==null){t="vertical"}if(e==null){e=r}l=h.aggregate(e);o=[];this.each(function(){var e;e=n.inArray(this,l[t]);return i(o,e,l[t])});return this.pushStack(o)},_invoke:function(t,e){t.each(function(){var t;t=l.getWaypointsByElement(this);return n.each(t,function(t,n){n[e]();return true})});return this}};n.fn[g]=function(){var t,r;r=arguments[0],t=2<=arguments.length?e.call(arguments,1):[];if(d[r]){return d[r].apply(this,t)}else if(n.isFunction(r)){return d.init.apply(this,arguments)}else if(n.isPlainObject(r)){return d.init.apply(this,[null,r])}else if(!r){return n.error("jQuery Waypoints needs a callback function or handler option.")}else{return n.error("The "+r+" method does not exist in jQuery Waypoints.")}};n.fn[g].defaults={context:r,continuous:true,enabled:true,horizontal:false,offset:0,triggerOnce:false};h={refresh:function(){return n.each(a,function(t,e){return e.refresh()})},viewportHeight:function(){var t;return(t=r.innerHeight)!=null?t:i.height()},aggregate:function(t){var e,r,i;e=s;if(t){e=(i=a[n(t).data(u)])!=null?i.waypoints:void 0}if(!e){return[]}r={horizontal:[],vertical:[]};n.each(r,function(t,i){n.each(e[t],function(t,e){return i.push(e)});i.sort(function(t,e){return t.offset-e.offset});r[t]=n.map(i,function(t){return t.element});return r[t]=n.unique(r[t])});return r},above:function(t){if(t==null){t=r}return h._filter(t,"vertical",function(t,e){return e.offset<=t.oldScroll.y})},below:function(t){if(t==null){t=r}return h._filter(t,"vertical",function(t,e){return e.offset>t.oldScroll.y})},left:function(t){if(t==null){t=r}return h._filter(t,"horizontal",function(t,e){return e.offset<=t.oldScroll.x})},right:function(t){if(t==null){t=r}return h._filter(t,"horizontal",function(t,e){return e.offset>t.oldScroll.x})},enable:function(){return h._invoke("enable")},disable:function(){return h._invoke("disable")},destroy:function(){return h._invoke("destroy")},extendFn:function(t,e){return d[t]=e},_invoke:function(t){var e;e=n.extend({},s.vertical,s.horizontal);return n.each(e,function(e,n){n[t]();return true})},_filter:function(t,e,r){var i,o;i=a[n(t).data(u)];if(!i){return[]}o=[];n.each(i.waypoints[e],function(t,e){if(r(i,e)){return o.push(e)}});o.sort(function(t,e){return t.offset-e.offset});return n.map(o,function(t){return t.element})}};n[m]=function(){var t,n;n=arguments[0],t=2<=arguments.length?e.call(arguments,1):[];if(h[n]){return h[n].apply(null,t)}else{return h.aggregate.call(null,n)}};n[m].settings={resizeThrottle:100,scrollThrottle:30};return i.load(function(){return n[m]("refresh")})})}).call(this);



/**
 * Portfolio item slider (carousel) - displays a set of images, separated by pages.
 * The pages can be changed by clicking on arrows with an animation.
 * @author Pexeto
 * http://pexetothemes.com
 */
(function($) {

	var carouselId = 0;

	$.fn.pexetoCarousel = function(options) {
		carouselId++;

			var defaults        = {
				//set the default options (can be overwritten from the calling function)
				minItemWidth        : 290,
				namespace           : 'carousel' + carouselId,
				itemMargin          : 6,
				shadowWidth         : 0,
				selfDisplay         : true, //if set to true, the carousel will get displayed 
				//as soon as it is loaded. Otherwise, the calling code would be
				//responsible to display the carousel (set its opacity to 1)
				
				//selectors and classes
				holderSelector      : '.pc-holder',
				pageWrapperSelector : '.pc-page-wrapper',
				itemSelector        : '.pc-item',
				titleSelector       : '.portfolio-project-title',
				hoverClass          : 'portfolio-hover',
				headerSelector      : '.pc-header'
			};


		var o            = $.extend(defaults, options),
			//define some variables that will be used globally within the script
			$container   = $(this),
			$root        = $container.find(o.holderSelector).eq(0),
			$items       = $root.find(o.itemSelector),
			$header      = $container.find(o.headerSelector),
			pageNumber   = 0,
			itemsNumber  = $items.length,
			currentPage  = 0,
			inAnimation  = false,
			pageWidth    = $root.find(o.pageWrapperSelector).eq(0).width(),
			itemsPerPage = 0,
			columns      = 0,
			$prevArrow   = null,
			$nextArrow   = null;


		/**
		 * Inits the main functionality.
		 */

		function init() {

			var defWidth = parseInt($items.eq(0).data('defwidth'), 10);

			if(defWidth && defWidth>100){
				o.minItemWidth = defWidth - 20;
			}

			pageNumber = $root.find(o.pageWrapperSelector).length;

			if(pageNumber > 1) {
				//show the arrows and add the animation functionality if there are 
				//more than one pages
				buildNavigation();
			}

			setImageSize();

			bindEventHandlers();

			if(o.selfDisplay) {
				$container.animate({
					opacity: 1
				});
			}

			itemsPerPage = $root.find(o.pageWrapperSelector + ':first' + ' ' + o.itemSelector).length;

		}

		/**
		 * Sets the image size according to the current wrapper width.
		 */
		function setImageSize() {
			var itemWidth, rootWidth;

			columns = Math.floor(($container.width() - o.itemMargin) / (o.minItemWidth + o.itemMargin));

			if(columns <= 1) {
				columns = 2;
			}
			itemWidth = Math.floor(($container.width() + o.itemMargin - 2 * o.shadowWidth) / columns) - o.itemMargin;

			$items.width(itemWidth);

			pageWidth = $root.find(o.pageWrapperSelector).eq(0).width();

			rootWidth = pageNumber * pageWidth + 1000;
			$root.css({
				width: rootWidth
			});

			setNavigationVisibility();

		}

		/**
		 * Binds a change slide event handler to the root, so that it can be animated
		 * when any of the navigation buttons has been clicked.
		 */

		function bindEventHandlers() {
			if(pageNumber > 1) {

				//mobile device finger slide events
				$root.touchwipe({
					wipeLeft: doOnNextSlide,
					wipeRight: doOnPreviousSlide,
					preventDefaultEvents: false
				});

				$(window).on('resize.' + o.namespace, doOnWindowResize);
			}

			$root.on('destroy' + o.namespace, doOnDestroy);
		}

		/**
		 * Changes the current slide of items, to another one.
		 * @param  {int} index the index of the new slide to show
		 */
		function changeSlide(index) {
			if(!inAnimation) {
				inAnimation = true;
				var margin = getPageMarginPosition(index);
				$root.animate({
					marginLeft: [margin, 'easeOutExpo']
				}, 800, function() {
					inAnimation = false;
					currentPage = index;
				});
			}
		}

		/**
		 * Calculates the position offset (margin) of the current slide 
		 * according to the current wrapper width.
		 * @param  {int} index the inex of the current slide
		 * @return {int}       the calculated margin
		 */
		function getPageMarginPosition(index) {
			setSizes();
			return -index * (pageWidth) - o.itemMargin / 2 + o.shadowWidth;
		}

		function setSizes(){
			setImageSize();
			pageWidth = $root.find(o.pageWrapperSelector).eq(0).width();
		}

		/**
		 * On window resize event handler - resizes the wrapper and then the
		 * inner images according to the current window size.
		 */
		function doOnWindowResize() {
			setSizes();
			$root.css({
				marginLeft: getPageMarginPosition(currentPage)
			});
		}

		/**
		 * On next slide event handler - shows the next slide if there is one.
		 */
		function doOnNextSlide() {
			if(!inAnimation){
				if(!isLastPageVisible()) {
					var index = currentPage < pageNumber - 1 ? currentPage + 1 : 0;
					changeSlide(index);
				} else {
					animateLastPage(true);
				}
			}
		}

		/**
		 * On previous slide event handler - shows the previous slide if there
		 * is one.
		 */
		function doOnPreviousSlide() {
			if(!inAnimation){
				if(currentPage > 0) {
					changeSlide(currentPage - 1);
				} else {
					animateLastPage(false);
				}
			}
		}

		/**
		 * Animates the carousel when there are no more slides left and the
		 * user still tries to open the previous/next slide - animates it in a
		 * way to show that there are no more slides.
		 * @param  {boolean} last setting whether this is the last slide (when
		 * set to true) or the first slide (when set to false)
		 */
		function animateLastPage(last) {
			var i = last ? -1 : 1;
			$root.stop().animate({
				left: i * 10
			}, 100, function() {
				$(this).stop().animate({
					left: 0
				}, 300);
			});
		}

		/**
		 * Checks if the last slide/page is visible on the carousel.
		 * @return {boolean} true if it is visible and false if it is not
		 */
		function isLastPageVisible() {
			if((itemsNumber - (currentPage) * itemsPerPage) <= columns) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Checks if all of the slides/pages are visible on the carousel.
		 * @return {boolean} true if they are visible and false if they are not
		 */
		function areAllPagesVisible() {
			return(itemsNumber <= columns && currentPage == 0) ? true : false;
		}

		/**
		 * Builds the navigation (arrows) to change the slides.
		 */
		function buildNavigation() {

			//next items arrow
			$prevArrow = $('<div />', {
				'class': 'pc-next hover'
			}).on('click.' + o.namespace, doOnNextSlide).appendTo($header);

			//previous items arrow
			$nextArrow = $('<div />', {
				'class': 'pc-prev hover'
			}).on('click.' + o.namespace, doOnPreviousSlide).appendTo($header);
		}

		/**
		 * Shows the navigation arrows when there are some slides that are not
		 * visible and hides them when all of the slides are visible.
		 */
		function setNavigationVisibility() {
			if(areAllPagesVisible()) {
				if($prevArrow){
					$prevArrow.hide();
				}
				if($nextArrow){
					$nextArrow.hide();
				}
			} else {
				if($prevArrow){
					$prevArrow.show();
				}
				if($nextArrow){
					$nextArrow.show();
				}
			}

		}

		/**
		 * On destroy event handler- removes all the registered event listeners.
		 */
		function doOnDestroy() {
			$(window).off('.' + o.namespace);
			$root.off('.' + o.namespace);
			$prevArrow.off('.' + o.namespace);
			$nextArrow.off('.' + o.namespace);
		}


		if($root.length) {
			init();
		}

	};
}(jQuery));

(function($) {

	//CSS 3 transition support detection - code from: https://gist.github.com/jonraasch/373874
	var thisBody = document.body || document.documentElement,
	thisStyle = thisBody.style,
	supportTransition = thisStyle.transition !== undefined || thisStyle.WebkitTransition !== undefined || thisStyle.MozTransition !== undefined || thisStyle.MsTransition !== undefined || thisStyle.OTransition !== undefined;

	$.fn.pexetoTransit = function(){
		var properties={},
			callback = null,
			namespace = 'pexetoTransit',
			callbackCalled = false;

		if(!arguments.length || typeof arguments[0]!=='object'){
			return $(this);
		}

		if(supportTransition){
			properties = arguments[0];

			if(arguments[1]){
				callback = arguments[1];
				$(this).on('transitionend.'+namespace+' webkitTransitionEnd.'+namespace+' oTransitionEnd.'+namespace+' MSTransitionEnd.'+namespace, function(e){
					if(!callbackCalled){
						callback.call();
						$(this).off(namespace);
						callbackCalled = true;
					}
					
				});
			}
			$(this).css(properties)
		}else{
			$.fn.animate.apply($(this), arguments);
		}

		return $(this);
	};

}(jQuery));


/**
 * Pexeto Contact Form - contains all the contact form functionality.
 * @author Pexeto
 * http://pexetothemes.com
 */
(function($) {
	$.fn.pexetoContactForm = function(options) {
		var defaults = {
			//set the default options (CAN BE OVERWRITTEN BY THE INITIALIZATION CODE)
			ajaxurl             : '',
			invalidClass        : 'invalid',
			afterValidClass     : 'after-validation',
			captcha             : false,
			
			//selectors
			submitSel           : '.send-button',
			errorSel            : '.error-message',
			statusSel           : '.contact-status',
			sentSel             : '.sent-message',
			loaderSel           : '.contact-loader',
			checkSel            : '.check',
			failSel             : '.fail-message',
			inputWrapperSel     : '.contact-input-wrapper',
			
			//texts
			wrongCaptchaText    : 'The text you have entered did not match the text on the image. Please try again.',
			failText            : 'An error occurred. Message not sent',
			validationErrorText : 'Please fill in all the fields correctly',
			messageSentText     : 'Message sent'
		};


		var o = $.extend(defaults, options);
		o.ajaxurl = $(this).attr('action');

		//define some variables that will be used globally within the script
		var $root           = $(this),
			$requiredFields = $root.find('input.required, textarea.required, #recaptcha_response_field'),
			$fields         = $root.find('input, textarea'),
			$errorBox       = $root.find(o.errorSel),
			$sentBox        = $root.find(o.sentSel),
			$loader         = $root.find(o.loaderSel),
			$check          = $root.find(o.checkSel);

		/**
		 * Inits the main functionality.
		 */

		function init() {
			$fields.on('focus', doOnFieldsFocus);
			$root.find(o.submitSel).eq(0).on('click', doOnSendClicked);
		}

		/**
		 * On send button click event handler. Sends an AJAX request to send the message if the
		 * entered input data is valid.
		 * @param  {object} e the event object
		 */

		function doOnSendClicked(e) {
			//set the send button click handler functionality
			e.preventDefault();
			var isValid = validateForm();

			if(isValid) {
				//the form is valid, send the email
				$loader.css({
					visibility: 'visible'
				});
				//hide all the message boxes
				$errorBox.slideUp();

				var data = $root.serialize() + '&action=pexeto_send_email';
				//send the AJAX request
				sendAjaxRequest(data);
			}
		}

		/**
		 * Sends the AJAX request to send the message.
		 * @param  {object} data the data needed for the request
		 */
		function sendAjaxRequest(data) {
			$.ajax({
				url: PEXETO.ajaxurl,
				data: data,
				dataType: 'json',
				type: 'post'
			}).done(function(res) {
				//reset the form
				$loader.css({
					visibility: 'hidden'
				});
				if(res.success) {
					//the message was sent successfully
					$root.get(0).reset();
					hideAfterValidationErrors();
					//show the confirmation check icon
					$check.css({
						visibility: 'visible'
					}, 200);


					$sentBox.html(o.messageSentText).slideDown();

					setTimeout(function() {
						//hide the confirmation boxes
						$sentBox.slideUp();
						$check.css({
							visibility: 'hidden'
						}, 200);
					}, 3000);
				} else {
					//the message was not sent successfully, show an error
					if(o.captcha && res.captcha_failed) {
						//captcha did not validate
						Recaptcha.reload();
						showErrorMessage(o.wrongCaptchaText);
					} else {
						//another error occurred, show general error message
						showErrorMessage(o.failText);
					}
				}
			}).fail(function() {
				//the message was not sent successfully, show an error
				$loader.css({
					visibility: 'hidden'
				});
				showErrorMessage(o.failText);
			});
		}


		/**
		 * Validates the form input.
		 * @return {boolean} true if the form is valid.
		 */

		function validateForm() {
			var isValid = true;

			hideValidationErrors();
			$requiredFields.each(function() {
				var $elem = $(this);
				if(!$.trim($elem.val()) || ($elem.hasClass('email') && !isValidEmail($elem.val()))) {
					//this field value is not valid display an error
					showError($elem);
					isValid = false;
				}
			});

			if(!isValid) {
				//show an error box
				showErrorMessage(o.validationErrorText);
			}
			return isValid;
		}

		/**
		 * Hides all the validation errors from the required fields.
		 */

		function hideValidationErrors() {
			$requiredFields.removeClass(o.invalidClass).removeClass(o.afterValidClass);
		}

		/**
		 * Hides the after validation errors and styles from the required fields. After validation
		 * means when there was a previous validation failure and the user after that clicks on a
		 * failed field, which gets a new after validation style.
		 */

		function hideAfterValidationErrors() {
			$requiredFields.each(function() {
				var $wrapper = $(this).parents(o.inputWrapperSel).eq(0),
					$errorElem = $wrapper.length ? $wrapper : $(this);
				$errorElem.removeClass(o.afterValidClass);
			});
		}

		/**
		 * Adds an error message to an element.
		 * @param  {object} $elem jQuery object element (input element) to which to add the message
		 */

		function showError($elem) {
			var $wrapper = $elem.parents(o.inputWrapperSel).eq(0),
				$errorElem = $wrapper.length ? $wrapper : $elem;
			$errorElem.addClass(o.invalidClass);
		}

		/**
		 * Displays a fail to send message error.
		 */

		function showErrorMessage(message) {
			$errorBox.html(message).slideDown();
		}

		/**
		 * On field focus in event handler. If the field is required and failed a validation,
		 * another after validation class is added to it when it gains focus.
		 */

		function doOnFieldsFocus() {
			var $wrapper = $(this).parents(o.inputWrapperSel).eq(0),
				$errorElem = $wrapper.length ? $wrapper : $(this);
			if($errorElem.hasClass(o.invalidClass)) {
				$errorElem.addClass(o.afterValidClass);
			}
			$errorElem.removeClass(o.invalidClass);
		}


		/**
		 * Checks if an email address is a valid one.
		 *
		 * @param {string} email the email address to validate
		 * @return {boolean} true if the address is a valid one
		 */

		function isValidEmail(email) {
			var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
			return pattern.test(email);
		}

		if($root.length) {
			init();
		}

	};
}(jQuery));

/**
 * Define a placeholder check to jQuery.support
 * code adapted from: http://diveintohtml5.org/detect.html#input-placeholder
 */
jQuery.support.placeholder = (function() {
	var i = document.createElement('input');
	return 'placeholder' in i;
})();



(function($) {

	/**
	 * Calls a callback function when all the images from a collection have been loaded.
	 * A "callback" parameter should be added - the function to be called when all the
	 * images are loaded.
	 * @param  {object} options object literal containing the initialization options
	 *
	 * Dependencies: jQuery (http://jquery.com/)
	 *
	 * Example usage: $('.test img').pexetoOnImgLoaded({callback:showImages});
	 *
	 * @author Pexeto
	 * http://pexetothemes.com
	 */
	$.fn.pexetoOnImgLoaded = function(options) {
		var defaults     = {},
			o            = $.extend(defaults, options),
			$images      = $(this),
			ie           = PEXETO.getBrowser().msie;


		/**
		 * Contains the main plugin functionality - once all the images are loaded, calls the
		 * callback function.
		 */

		function init() {
			var imagesNum = $images.length,
				imgLoaded = 0;

			if(!imagesNum) {
				o.callback.call(this);
				return;
			}

			$images.each(function() {

				$(this).on('load', function(e) {
					e.stopPropagation();
					imgLoaded++;
					if(imgLoaded === imagesNum) {
						//all the images are loaded, call the callback function
						o.callback.call(this);
						$(this).off('load');
					}
				}).on('error', function(){
					$(this).trigger('load');
				});

				if(this.complete || (ie && this.width)) {
					$(this).trigger('load');
				}
			});
		}

		init();
	};
}(jQuery));


(function($) {

	/**
	 * Pexeto Tabs Widget.
	 * Dependencies : jQuery
	 *
	 * @author Pexeto
 	 * http://pexetothemes.com
	 */
	$.fn.pexetoTabs = function(options) {
		var defaults = {
			//selectors and classes
			tabSel       : '.tabs li',
			paneSel      : '.panes>div',
			currentClass : 'current'
		},
		o       = $.extend(defaults, options),
		$root   = $(this),
		$tabs   = $root.find(o.tabSel),
		$panes  = $root.find(o.paneSel),
		current = 0;


		/**
		 * Inits the tabs - sets a click event handler to the tabs.
		 */
		function init() {
			showSelected(0);

			$root.on('click', o.tabSel, function(e) {
				e.preventDefault();

				var index = $tabs.index($(this));

				if(index !== current) {
					hideTab(current);
					showSelected(index);
				}

			});
		}

		/**
		 * Displays the selected tab.
		 * @param  {int} index the index of the selected tab
		 */
		function showSelected(index) {
			$panes.eq(index).fadeIn();
			$tabs.eq(index).addClass(o.currentClass);
			current = index;
		}

		/**
		 * Hides a tab when a new one has been selected.
		 * @param  {index} index the index of the tab to hide
		 */
		function hideTab(index) {
			$panes.eq(index).hide();
			$tabs.eq(index).removeClass(o.currentClass);
		}

		init();
	};
}(jQuery));



(function($) {

	/**
	 * Pexeto Accordion Widget.
	 * Dependencies : jQuery
	 *
	 * @author Pexeto
 	 * http://pexetothemes.com
	 */
	$.fn.pexetoAccordion = function(options) {
		var defaults = {

			//selectors and classes
			tabSel       : '.accordion-title',
			paneSel      : '.pane',
			currentClass : 'current'
		},
		o       = $.extend(defaults, options),
		$root   = $(this),
		$tabs   = $root.find(o.tabSel),
		$panes  = $root.find(o.paneSel),
		current = 0;


		/**
		 * Inits the main functionality - registers a click event handler
		 * to the accordion tabs.
		 */
		function init() {
			//display the first pane
			showSelected(0);

			$root.on('click', o.tabSel, function(e) {
				e.preventDefault();

				var index = $tabs.index($(this));

				if(index !== current) {
					hideTab(current).done(function() {
						showSelected(index);
					});
				}

			});
		}

		/**
		 * Displays the selected accordion tab.
		 * @param  {int} index the index of the selected tab
		 */
		function showSelected(index) {
			$panes.eq(index).animate({
				height: 'show',
				opacity: 1
			});
			$tabs.eq(index).addClass(o.currentClass);
			current = index;
		}

		/**
		 * Hides a tab when a new one has been selected.
		 * @param  {index} index the index of the tab to hide
		 */
		function hideTab(index) {
			var def = new $.Deferred();
			$panes.eq(index).animate({
				height: 'hide',
				opacity: 0
			}, function() {
				def.resolve();
			});
			$tabs.eq(index).removeClass(o.currentClass);
			return def.promise();
		}

		init();
	};
}(jQuery));



/**
 * PEXETO contains the functionality for initializing all the main scripts in the
 * site and also some helper functions.
 *
 * @type {Object}
 * @author Pexeto
 */
var PEXETO = PEXETO || {};

(function($) {
	$.extend(PEXETO, {
		ajaxurl       : '',
		lightboxStyle : 'light_rounded',
		masonryClass  : 'page-masonry'
	});

	/**
	 * Retrieves the current browser info.
	 * Code from jQuery Migrate: http://code.jquery.com/jquery-migrate-1.2.0.js
	 * @return an object containing the browser info, for example for IE version 7
	 * it would return:
	 * {msie:true, version:7}
	 */
	PEXETO.getBrowser = function(){
		var browser = {},
			ua,
			match,
			matched;

		if(PEXETO.browser){
			return PEXETO.browser;
		}

		ua = navigator.userAgent.toLowerCase();

		match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
			/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
			/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
			/(msie) ([\w.]+)/.exec( ua ) ||
			ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
			[];

		matched = {
			browser: match[ 1 ] || "",
			version: match[ 2 ] || "0"
		};

		if ( matched.browser ) {
			browser[ matched.browser ] = true;
			browser.version = matched.version;
		}

		// Chrome is Webkit, but Webkit is also Safari.
		if ( browser.chrome ) {
			browser.webkit = true;
		} else if ( browser.webkit ) {
			browser.safari = true;
		}

		PEXETO.browser = browser;

		return browser;
	}


	/**
	 * Contains the init functionality.
	 * @type {Object}
	 */
	PEXETO.init = {

		/**
		 * Inits all the main functionality. Calls all the init functions.
		 */
		initSite: function() {

			var init = this,
				clickMsg = '';

			//initialize the lightbox
			init.lightbox(null, {});

			init.lightbox($("a[rel^='pglightbox']:not(#portfolio-slider a, #portfolio-gallery a)"), {});

			init.tabs();
			init.placeholder($('.placehoder'));
			init.loadableImg($('img.loadable, .blog-post-img img'));
			init.dropDown();
			init.quickGallery();
			init.carousel();

			if(PEXETO.disableRightClick) {
				PEXETO.utils.disableRightClick();
			}

			PEXETO.utils.checkIfMobile();


			//wrap the sidebar categories widget number of posts text node within a span
			var catSpans = $('li.cat-item, .widget_archive li').contents().filter(function() {
				return this.nodeType == 3;
			});
			if(catSpans.length) {
				catSpans.wrap($('<span />', {
					'class': 'cat-number'
				}));
			}

			//init social sharing
			var $share = $('.social-share');
			$share.each(function() {
				PEXETO.init.share($(this));
			});

			init.parallax();

			init.bgCoverFallback();

			if(!PEXETO.utils.checkIfMobile()){
				init.setScrollToTop();
			}

			init.ieClass();

			if(PEXETO.stickyHeader){
				new PEXETO.utils.stickyHeader($('#header'), {}).init();
				init.ieIframeFix();
			}



		},


		/**
		 * Fixes the IE ignoring of z-index on iframes.
		 */
		ieIframeFix : function(){
			if(PEXETO.getBrowser().msie){

				$('iframe').each(function() {
					var url = $(this).attr("src"),
						newUrl = PEXETO.url.addUrlParameter(url, 'wmode=transparent');

					$(this).attr({
						"src" : newUrl,
						"wmode" : "Opaque"
					});
				});
			}
		},

		/**
		 * Adds an ie10 class to Internet Explorer
		 * @return {[type]} [description]
		 */
		ieClass : function(){
			var browser = PEXETO.getBrowser();
			if(browser.msie && parseInt(browser.version,10)==10){
				$('body').addClass('ie10');
			}
		},

		/**
		 * Inits the scroll to top button functionality.
		 */
		setScrollToTop : function(){
			var $scrollBtn = $('.scroll-to-top'),
				btnDisplayed = false;

			if($scrollBtn.length){
				/**
				 * Shows or hides the scroll to top button depending on the current
				 * document scroll position.
				 */
				var setButtonVisibility = function(){
					var scrollPos = $(document).scrollTop(),
				   		winHeight = $(window).height();

				   		if(!btnDisplayed && scrollPos > winHeight){
				   			//display scroll button
				   			$scrollBtn.pexetoTransit({opacity:1, marginBottom:0});
				   			btnDisplayed = true;
				   		}else if(btnDisplayed && scrollPos < winHeight){
				   			$scrollBtn.pexetoTransit({opacity:0, marginBottom:-30});
				   			btnDisplayed = false;
				   		}
				};
				$(window).on('mousewheel', setButtonVisibility);
				setButtonVisibility();

				$scrollBtn.on('click', function(){
					$.scrollTo($('#main-container'), {
						duration: 1000,
						easing: 'easeOutSine',
						offset: {
							top: 0
						},
						onAfter:function(){
							setButtonVisibility();
							$(window).trigger('pexetoscroll');
						}
					});
				});
			}
			
		},

		/**
		 * Inits the fallback functionality for the CSS background-size:cover
		 */
		bgCoverFallback : function(){
			if(PEXETO.getBrowser().msie && PEXETO.getBrowser().version<=8){
				$('.full-bg-image').each(function(){
					new PEXETO.utils.bgCoverFallback($(this)).init();
				});
			}
		},

		/**
		 * Inits the parallax animation effect for some elements,
		 */
		parallax : function(){
			//init the full-width backfround image parallax
			if(!PEXETO.utils.checkIfMobile()){
				$('.parallax-scroll .full-bg-image').each(function(){
					new PEXETO.parallax(
						$(this),
						'background',
						{}
						).init();
				});
			}

			//init the services boxes parallax
			$('.pexeto-parallax .sl-icons').each(function(){
				new PEXETO.parallax(
					$(this),
					'list',
					{
						children:$(this).find('li'),
						initProp : {opacity:0, marginLeft:50},
						endProp: {opacity:1, marginLeft:0}
					}
					).init();
			});

			//init the services boxes list parallax
			$('.services-wrapper.pexeto-parallax').each(function(){
				new PEXETO.parallax(
					$(this),
					'list',
					{
						children:$(this).find('.services-box'),
						initProp : {opacity:0, top:50, position:'relative'},
						endProp: {opacity:1, top:0}
					}
					).init();
			});

			//init the testimonial parallax
			$('.testimonial-container.pexeto-parallax').each(function(){
				new PEXETO.parallax(
					$(this),
					'list',
					{
						children : $(this).find('blockquote, img.testimonial-img, .testimonials-details'),
						initProp : {opacity:0, top:50, position:'relative'},	
						endProp:{opacity:1, top:0}
					}
					).init();
			});

		},

		/**
		 * Inits the PrettyPhoto plugin lightbox.
		 * @param  {object} $el element or set of elements to which the lightbox will be loaded
		 */
		lightbox: function($el, add_options) {
			$el = $el || $("a[rel^='lightbox'],a[rel^='lightbox[group]']");
			var defaults = {
				animation_speed: 'normal',
				theme: PEXETO.lightboxStyle,
				overlay_gallery: false,
				slideshow: false,
				social_tools: ''
			},
				options = $.extend(defaults, PEXETO.lightboxOptions);

			if(!$.isEmptyObject(add_options)){
				options = $.extend(options, add_options);
			}

			$el.prettyPhoto(options);
		},

		/**
		 * Inits a placeholder functionality for browsers that don't support placeholder.
		 * @param  {object} $el element or set of elements to which this functionality
		 * will be initialized.
		 */
		placeholder: function($el) {
			if(!$.support.placeholder) {
				$el.each(function() {
					$(this).attr('value', $(this).attr('placeholder'));
				}).on('focusin', function() {
					$(this).attr('value', $(this).attr('placeholder'));
				}).on('focusout', function() {
					$(this).attr('value', '');
				});
			}
		},

		/**
		 * Inits the tabs and accordion functionality.
		 */
		tabs: function() {
			//set the tabs functionality
			$('.tabs-container').each(function(){
				$(this).pexetoTabs();
			});

			//set the accordion functionality
			$('.accordion-container').each(function(){
				$(this).pexetoAccordion();
			});
		},

		/**
		 * Inits the portfolio items carousel
		 */
		carousel: function() {
			if(!$('body').hasClass('single-portfolio')) {
				$('.portfolio-carousel').each(function() {
					$(this).pexetoCarousel();
				});
			}

		},

		/**
		 * Makes an image get displayed once it is loaded in a fade in animation.
		 * @param  {object} $el jQuery object or list of objects that contains 
		 * the loadable images. Each image must have a div parent from the 
		 * "img-loading" class which has a min-width and min-height set to it.
		 */
		loadableImg: function($el) {
			if($el.length) {
				$el.each(function() {
					$(this).pexetoOnImgLoaded({
						callback: function() {
							$(this).animate({
								opacity: 1
							}).parents('div.img-loading:first').css({
								minWidth: 0,
								minHeight: 0
							});
						}
					});
				})
			}
		},

		/**
		 * Inits the quick gallery functionality. Loads the masonry script if
		 * the masonry option has been enabled.
		 */
		quickGallery: function() {
			$('.quick-gallery').each(function() {
				var $gallery = $(this),
					masonry = $gallery.hasClass(PEXETO.masonryClass);

				new PEXETO.utils.resizableImageGallery('.qg-img', 
						{
							masonry:masonry,
							parent:$gallery
						}).init();
			});
		},

		/**
		 * Loads the Nivo slider.
		 * @param  {object} $el     jQuery element which will contain the 
		 * slider images
		 * @param  {object} options object literal containing the slider 
		 * initialization options
		 */
		nivoSlider: function($el, options) {

			// loads the Nivo slider	
			var loadSlider = function() {
					$el.nivoSlider({
						effect: options.animation,
						// Specify sets like:
						// 'fold,fade,sliceDown'
						slices: options.slices,
						//For slice animations
						boxCols: options.columns,
						// For box animations
						boxRows: options.rows,
						// For box animations
						animSpeed: options.speed,
						pauseTime: options.interval,
						startSlide: 0,
						// Set starting Slide (0 index)
						directionNav: options.arrows,
						// Next & Prev
						directionNavHide: false,
						// Only show on hover
						controlNav: options.buttons,
						// 1,2,3...
						controlNavThumbs: false,
						// Use thumbnails for
						// Control
						// Nav
						controlNavThumbsFromRel: false,
						// Use image rel for
						// thumbs
						keyboardNav: true,
						// Use left & right arrows
						pauseOnHover: options.pauseOnHover,
						// Stop animation while hovering
						manualAdvance: !options.autoplay,
						// Force manual transitions
						captionOpacity: 0.8,
						// Universal caption opacity
						beforeChange: function() {},
						afterChange: function() {},
						slideshowEnd: function() {} // Triggers after all slides have been shown
					}).css({
						minHeight: 0
					});

					// remove numbers from navigation
					$('.nivo-controlNav a').html('');
					$('.nivo-directionNav a').html('');
				};

			if(!PEXETO.getBrowser().msie) {
				//load the slider once the images get loaded
				$el.find('img').pexetoOnImgLoaded({
					callback: loadSlider
				});
			} else {
				loadSlider();
			}
		},

		/**
		 * Inits the sharing functionality. Uses the Sharrre script for the 
		 * sharing functionality.
		 * @param  {object} $wrapper a jQuery object wrapper that wraps the
		 * sharing buttons
		 */
		share: function($wrapper) {

			if(!$wrapper.length) {
				return;
			}

			$wrapper.find('.share-item').each(function() {
				var $el = $(this),
					type = $el.data('type'),
					title = $el.data('title'),
					url = $el.data('url'),
					args = {
						url: url,
						title: title,
						share: {},
						template: '<div></div>',
						enableHover: false,
						enableTracking: false,
						urlCurl: '',
						buttons: {},
						click: function(api, options) {
							api.simulateClick();
							api.openPopup(type);
						}
					};

				args.share[type] = true;

				if(type === 'googlePlus') {
					//set the language attribute for Google+
					args.buttons.googlePlus = {
						lang: $el.data('lang')
					};
				} else if(type === 'pinterest') {
					//set an image URL and a description to share on Pinterest
					args.buttons.pinterest = {
						media: $el.data('media'),
						description: title
					};

				}

				$el.sharrre(args);
			});
		},


		/**
		 * Inits the drop-down menu functionality.
		 */
		dropDown: function() {
			var $menu = $('#menu'),
				initMenu = function(){
				var logoHeight = $('#logo-container a').height(),
					br = PEXETO.getBrowser(),
					brPadding = br.mozilla || br.msie ? 22 : 23,
					btStickyPadding = br.mozilla || br.msie ? 9 : 10,
					padding = logoHeight > 50 ? 1 : brPadding,
					defPadding = padding,
					//default menu top offset
					top = $menu.find('li:first').outerHeight(),
					onMenuMouseover = function() {
						var $el = $(this),
							$ul = $el.find('ul:first'),
							left = $el.width(),
							parentUlNum = $ul.parents('ul').length,
							elWidth = $el.width(),
							ulWidth = $ul.width(),
							winWidth = $(window).width(),
							elOffset = $el.offset().left;


						$el.addClass('hovered');
						if(parentUlNum > 1 && (elWidth + ulWidth + elOffset > winWidth)) {
							//if the drop down ul goes beyound the screen, move it on the left side
							$ul.css({
								left: -elWidth
							});
						} else if(parentUlNum === 1) {
							if(ulWidth + elOffset > winWidth) {
								$ul.css({
									left: (winWidth - 3 - (ulWidth + elOffset))
								});
							} else {
								$ul.css({
									left: 0
								});
							}
						}

						if(parentUlNum === 1) {
							$ul.data('padding', padding);
						}


						$ul.stop().css({
							height: 'auto',
							paddingTop: 0,
							top: ($ul.data('padding') + $ul.data('top'))
						}).slideToggle({duration:300, easing:'easeOutExpo', complete: function() {
							$ul.css({
								overflow: 'visible',
								visibility: 'visible',
								paddingTop: $ul.data('padding'),
								top: $ul.data('top')
							});
						}
					});
					},
					onMenuMouseout = function() {
						var $el = $(this),
							$ul = $el.find('ul:first');
						$el.removeClass('hovered');

						$ul.stop().css({
							paddingTop: 0,
							top: ($ul.data('padding') + $ul.data('top'))
						}).slideToggle({duration:300, easing:'easeOutExpo', complete: function() {
							$ul.css({
								overflow: 'hidden',
								display: 'none'
							});
						}
					});
					};

				if(PEXETO.getBrowser().webkit){
					padding+=1;
				}

				$menu.find('ul ul').data('padding', padding).data('top', top);
				$menu.find('ul ul ul').data('padding', 0).data('top', 0);
					
				var setPadding = function(){
					padding = $('body').hasClass('fixed-header-scroll') ? btStickyPadding : defPadding;
				};

				setPadding();
				$(window).on('pexetostickychange', setPadding);
				
				$menu.find('ul li').has('ul').each(function() {
					var $el = $(this);
					$el.hoverIntent({
						over:onMenuMouseover, 
						out:onMenuMouseout,
						interval:'20'}).append('<span class="drop-arrow"></span>');
				});

			}

			//SET THE SMALL RESOLUTION MENU
			this.smallMenu();

			

			if($menu.is(':visible')){
				initMenu();
			}else{
				$(window).on('resize.pexetodropdown', function(){
					if($menu.is(':visible')){
						initMenu();
						$(window).off('.pexetodropdown');
					}
				});
			}

			

		},


		/**
		 * Inits the drop-down menu for small screen devices.
		 */
		smallMenu: function() {
			var menuClass = 'mob-nav-menu',
				precedingElSel = '.section-header',
				menuSel = '#menu',
				$menuBtn = $('.mob-nav-btn'),
				opened = false,
				inAnimation = false,
				$menu = $('<div />', {
							'class': menuClass,
							html: $(menuSel).html()
						});

			$menuBtn.on('click', function() {
				if(!inAnimation) {
					if(!opened) {
						inAnimation = true;
						$menu.insertAfter(precedingElSel).animate({
							height: 'show'
						}, function() {
							opened = true;
							inAnimation = false;
						});
					} else {
						inAnimation = true;
						$menu.animate({
							height: 'hide'
						}, function() {
							$(this).detach();
							opened = false;
							inAnimation = false;
						});
					}
				}
			});

			$(window).on('resize', function(){
				if(!$menuBtn.is(':visible') && ($menu && opened)){
					$menu.hide().detach();
					opened = false;
				}
			});
		},

		blogMasonry : function(cols){
			var spacing = 30,
				$parent = $('.'+PEXETO.masonryClass),
				setColumnWidth = function(){
					var curCols = cols,
						containerWidth = $parent.width();

					if(containerWidth <= 600){
						curCols = 1;
					}else if(containerWidth>600 && containerWidth<=800){
						curCols = 2;
					}

					var width = Math.floor((containerWidth - (curCols-1)*spacing) / curCols) -1;

					$parent.find('.post').width(width);

					return width;
				};

			setColumnWidth();

			$parent.masonry({
				itemSelector:'.post',
				gutter: spacing,
				transitionDuration : 0
			});

			$parent.find('img').each(function() {
				$(this).imagesLoaded(function() {
					$parent.masonry('layout');
				});
			});

			$(window).on('resize', function(){
				setColumnWidth();
				$parent.masonry('layout');
			});

		}

	};


	/**
	 * Parallax class - contains methods to apply various parallax animations
	 * to an element or set of elemements.
	 * @param  {object} $el     the element to apply the animation to
	 * @param  {string} type    the type of animation - available options:
	 * - background : animates a background image, changes its position on scroll
	 * - list : animates a list of items, one after another
	 * - single : animates a single item
	 * @param  {object} options options for the animation. Properties:
	 * - children : a list of elements to animate when the type of animation
	 * is set to list
	 * - initProp : object containing a set of CSS properties that are applied
	 * to each element before the animation starts
	 * - endProp : object containing a set of CSS properties that are applied
	 * to each element to be animated
	 */
	PEXETO.parallax = function($el, type, options){
		this.$el = $el;
		this.type = type;
		this.options = options;
	};

	/**
	 * Inits the parallax functionality. Calls the corresponding animation
	 * method depending on the type of effect selected.
	 */
	PEXETO.parallax.prototype.init = function(){
		var self = this,
			funcToExec = {
			'background' : 'setBackground',
			'list' : 'setList',
			'single' : 'setSingleElement'
		};

		if(self.type in funcToExec){
			PEXETO.parallax.prototype[funcToExec[self.type]].call(this);
		}
	};

	/**
	 * Sets a parallax background image functionality. Moves the image position
	 * on mouse scroll.
	 */
	PEXETO.parallax.prototype.setBackground = function(){
		var self = this,
			$el = self.$el,
			$parent = $el.parent(),
			waypoints = {},
			maxTop = 60,
			numSteps = 100,
			topStep = maxTop/numSteps,
			initWaypoint = 90,
			endWaypoint = 120,
			waypointStep = Math.floor((initWaypoint+endWaypoint) / numSteps);

			//generate an array containing waypoints and the corresponding data
			for(var i=0; i<numSteps; i++){
				waypoints[initWaypoint-i*waypointStep] = '-'+((i+1)*topStep)+'%';
			}

			_.each(waypoints, function(top, waypoint){

				$parent.waypoint(function(direction){
					$el.stop().pexetoTransit({top:top});
				}, {offset:waypoint+'%'});
			});

	};

	/**
	 * Registers a single element parallax animation. The "initProp" and
	 * "endProp" properties should be set to the constructor's options object.
	 */
	PEXETO.parallax.prototype.setSingleElement = function(){
		var self = this,
			$el = self.$el;

		$el.css(self.options.initProp)
			.waypoint(function(){
				$el.addClass('animated-element')
					.pexetoTransit(self.options.endProp)
					.waypoint('destroy');
			}, {'offset':'90%'});
	};


	/**
	 * Registers a list of elements parallax animation. The "initProp" and
	 * "endProp" properties should be set to the constructor's options object
	 * to set the animation properties. Also a "children" property should be
	 * added to the options object containing the children elements to be loaded.
	 */
	PEXETO.parallax.prototype.setList = function(){
		var self = this,
			$el = self.$el,
			$children = self.options.children
				.css(self.options.initProp);

			$el.waypoint(function(direction){

				$children.each(function(i){
					var $element = $(this);
					setTimeout(function(){
						$element.addClass('animated-element').pexetoTransit(self.options.endProp);
					}, i * 300);
				});

				$el.waypoint('destroy');

			}, {'offset':'90%'});


	};




	/**
	 * Contains some general helper functions.
	 * @type {Object}
	 */
	PEXETO.utils = {

		/**
		 * Disables right click which opens the context menu.
		 * @param  {string} message a message that will be displayed on right click. Use empty
		 * string if you don't need to display a message
		 */
		disableRightClick: function() {
			$(document).bind('contextmenu', function(e) {
				return false;
			});
		},

		/**
		 * JavaScript templating function :
		 * http://mir.aculo.us/2011/03/09/little-helpers-a-tweet-sized-javascript-templating-engine/
		 * @param  {string} s the string template
		 * @param  {object} d object literal containing the values that will be replaced in the string
		 * @return {string}   the replaced string with the data set
		 */
		template: function(s, d) {
			for(var p in d)
			s = s.replace(new RegExp('{' + p + '}', 'g'), d[p]);
			return s;
		},

		/**
		 * Checks if the current device is a mobile device. If it is a mobile device, and it is within
		 * the recognized devices, adds its specific class to the body.
		 * @return {boolean} setting if the device is a mobile device or not
		 */
		checkIfMobile: function() {
			if(typeof PEXETO.isMobile !== 'undefined') {
				return PEXETO.isMobile;
			}
			var userAgent = navigator.userAgent.toLowerCase(),
				devices = [{
					'class': 'iphone',
					regex: /iphone/
				}, {
					'class': 'ipad',
					regex: /ipad/
				}, {
					'class': 'ipod',
					regex: /ipod/
				}, {
					'class': 'android',
					regex: /android/
				}, {
					'class': 'bb',
					regex: /blackberry/
				}, {
					'class': 'iemobile',
					regex: /iemobile/
				}],
				i, len;

			PEXETO.isMobile = false;
			for(i = 0, len = devices.length; i < len; i += 1) {
				if(devices[i].regex.test(userAgent)) {
					$('body').addClass(devices[i]['class'] + ' mobile');
					PEXETO.isMobile = true;
					PEXETO.mobileType = devices[i]['class'];
					return true;
				}
			}

			return false;
		},

		/**
		 * Fades an element in.
		 * @param {object} $elem the element to be faded
		 */
		elemFadeIn: function($elem) {
			$elem.stop().animate({
				opacity: 1
			}, function() {
				$elem.animate({
					opacity: 1
				}, 0);
			});
		},

		/**
		 * Fades an elemen out to a selected opacity.
		 * @param {object} $elem the element to be faded
		 * @param {number} opacity the opacity to be faded to (number between 0 and 1)
		 */
		elemFadeOut: function($elem, opacity) {
			$elem.stop().animate({
				opacity: opacity
			}, function() {
				$elem.animate({
					opacity: opacity
				}, 0);
			});
		},

		getNaturalImgSize: function($img){
			var img = $img.get(0);
			if(img.naturalWidth && img.naturalHeight){
				return {width:img.naturalWidth, height:img.naturalHeight};
			}else{
				return {width:$img.width(), height:$img.height()};
			}
		}
	};


	/**
	 * Contains some URL helper functions.
	 * @type {Object}
	 */
	PEXETO.url = {

		/**
		 * Retrieves the URL parameters/
		 * @return {object} containing the parameters and values in key-value pairs
		 */
		getUrlParameters: function() {
			var vars = {};
			var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
				vars[key] = value;
			});
			return vars;
		},

		getCustomUrlParameters : function(url){
			var vars = {};
			var parts = url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
				vars[key] = value;
			});
			return vars;
		},

		/**
		 * Adds a parameter to the URL.
		 * @param {string} url   the URL to which to add the parameters to
		 * @param {string} param the parameter in a string format including its value, example:
		 * "param=value"
		 * @return {string} the URL with the added parameter
		 */
		addUrlParameter: function(url, param) {
			url += (url.split('?')[1] ? '&' : '?') + param;
			return url;
		}

		

	};


	/***************************************************************************
	 * STICKY HEADER
	 **************************************************************************/


	/**
	 * Sticky header functionality - displays the hader always on the top of the
	 * screen.
	 * @param  {object} $element jQuery element - the header element
	 * @param  {object} options  the options settings
	 */
	PEXETO.utils.stickyHeader = function($element, options){
		this.$el = $element;
		this.$body = $('body');
		this.$win = $(window);

		var defaults = {
			scrollHeight : 64,
			scrollClass : 'fixed-header-scroll'
		};

		this.o = $.extend(defaults, options);
	};


	/**
	 * Inits the sticky header functionality.
	 */
	PEXETO.utils.stickyHeader.prototype.init = function(){
		var self = this;


		self.$parent = this.$el.parent();
		self.setPositions();

		$('#logo-container img').pexetoOnImgLoaded({callback:function(){
			//refresh the default header height when the logo image has been loaded
			if(!self.scrolled){
				self.defaultHeight = self.$el.outerHeight();
				self.setPositions();
			}
		}});

		$(window).on('mousewheel pexetoscroll scroll', function(){
			self.setPositions();
		});
	};

	/**
	 * Checks whether the current window is scrolled.
	 * @return {boolean} true if it is scrolled and false if it is not scrolled
	 */
	PEXETO.utils.stickyHeader.prototype.isScrolled = function(){
		return $(document).scrollTop() ? true : false;
	};


	/**
	 * Positions the depending elements of the sticky header depending
	 * on the current header position.
	 */
	PEXETO.utils.stickyHeader.prototype.setPositions = function(){
		var self = this,
			currentScrolled = self.isScrolled();
			
		if(!self.defaultHeight){
			self.defaultHeight = self.$el.outerHeight();
		}

		if(currentScrolled && !self.scrolled){
			self.scrolled = true;
			self.$body.addClass(self.o.scrollClass);
			self.$parent.css({paddingTop:self.o.scrollHeight}); 
			self.$win.trigger('pexetostickychange');
		}else if(!currentScrolled && (self.scrolled || self.scrolled===undefined)){
			self.scrolled = false;
			self.$body.removeClass(self.o.scrollClass);
			self.$parent.css({paddingTop:self.defaultHeight}); 
			self.$win.trigger('pexetostickychange');
		}
	};


	/***************************************************************************
	 * RESIZABLE IMAGE GALLERY
	 **************************************************************************/

	/**
	 * Resizable gallery functionality. Resizes the images in a gallery so they
	 * so that they can always fill the full parent container without any gaps.
	 * Also provides a masonry functionality that uses the jQuery Masonry script.
	 * @param  {string} selector the items selector
	 * @param  {object} options  an options object, supported parameters:
	 * - parent : jQuery object, the parent container of the items
	 * - masonry : boolean setting whether to enable masonry or not
	 */
	PEXETO.utils.resizableImageGallery = function(selector, options){
		this.selector = selector;
		this.options = options;
		this.$parent = options.parent || $('.'+PEXETO.masonryClass);
		this.$items = this.$parent.find(selector);
		this.masonry = options.masonry;
	};

	/**
	 * Inits the resizable functionality.
	 * @return {object} the resizableImageGallery object
	 */
	PEXETO.utils.resizableImageGallery.prototype.init = function(){
		var self = this;

		self.setImageSize();

		if(self.masonry){
			self.initMasonry();
		}
		
		self.loadImages();

		$(window).on('resize', $.proxy(self.refresh, self));

		return self;
	};

	/**
	 * Inits the Masonry script.
	 */
	PEXETO.utils.resizableImageGallery.prototype.initMasonry = function(){
		var self = this;
		self.$parent.masonry({
			itemSelector : self.selector,
			transitionDuration: 0
		});
	};


	/**
	 * Adds an onload event handler to each of the images.
	 */
	PEXETO.utils.resizableImageGallery.prototype.loadImages = function(){
		var self = this;

		self.$parent.find('img').each(function() {
			$(this).pexetoOnImgLoaded({callback:function() {
				if(self.masonry){
					//refresh masonry
					self.$parent.masonry('layout');
				}
				$(this).css({
					 opacity: 1
				})
				.trigger('imgmasonryloaded');
			}});
		});
	};

	/**
	 * Calculates the image width depending on the default image width and
	 * the width of the parent container div.
	 * @return {int} the width of the image including the margins of the image
	 */
	PEXETO.utils.resizableImageGallery.prototype.setImageSize = function(){
		var self = this,
			$firstItem = self.$items.eq(0),
			space = parseInt($firstItem.css('marginRight'), 10) + parseInt($firstItem.css('marginLeft'), 10),
			defaultWidth = $firstItem.data('defwidth') ? $firstItem.data('defwidth') : $firstItem.width(),
			numColumns = 0,
			spaceLeft = 0,
			ratio = 0,
			containerWidth = self.$parent.width(),
			newImgW, i, len, col, row, w, h;

			containerWidth = Math.floor(containerWidth-1);

			numColumns = Math.floor((containerWidth) / (defaultWidth + space));
			if(numColumns<=0){
				numColumns = 1;
			}

			spaceLeft = containerWidth - numColumns * (defaultWidth + space);

			if(spaceLeft > defaultWidth / 2) {
				numColumns += 1;
			}

			newImgW = numColumns === 1 ? containerWidth - space 
				: Math.floor((containerWidth) / numColumns) - space;

			self.$items.css({
				width: newImgW,
				height: 'auto'
			});

			return newImgW + space;
	};

	/**
	 * Refreshes the gallery - recalculates the image dimensions and refreshes
	 * the masonry script if masonry is enabled.
	 */
	PEXETO.utils.resizableImageGallery.prototype.refresh = function(){
		var self = this;

		if(!self.paused){
			self.setImageSize();

			if(self.masonry){
				self.$parent.masonry('layout');
			}
		}
		
	};

	/**
	 * Destroys the masonry script if it is enabled.
	 */
	PEXETO.utils.resizableImageGallery.prototype.destroy = function(){
		var self = this;

		if(self.masonry){
			self.$parent.masonry('destroy');
		}
	};

	PEXETO.utils.resizableImageGallery.prototype.pause = function(){
		this.paused = true;
	};

	PEXETO.utils.resizableImageGallery.prototype.resume = function(){
		this.paused = false;
	};




	/***************************************************************************
	 * BACKGROUND IMAGE COVER FALLBACK
	 **************************************************************************/

	/**
	 * CSS background-size:cover fallback. Main constructior.
	 */
	PEXETO.utils.bgCoverFallback = function($el){
		this.$el = $el;
	};


	/**
	 * Inits the fallback functionality - sets the background image as an image
	 * element that is positioned main div element.
	 */
	PEXETO.utils.bgCoverFallback.prototype.init = function(){
		var self = this,
			src='',
			img,
			$img;

		src = self.$el.css('backgroundImage');
		self.$el.css({'backgroundImage':''});
		src = src.replace('url("','').replace('")','');

		img = new Image();
		img.src = src;

		$img = $(img).appendTo(self.$el);
		self.$img = $img;

		new PEXETO.utils.fullBgImage($img).init();
	};


	PEXETO.utils.fullBgImage = function($img){
		this.$img = $img;
		this.$parent = $img.parent();
		var naturalSize = PEXETO.utils.getNaturalImgSize($img);
		this.imgWidth = naturalSize.width;
		this.imgHeight = naturalSize.height;

	};

	PEXETO.utils.fullBgImage.prototype.init = function(){
		var self = this;
		self.positionImage();

		$(window).on('resize', function(){
			self.positionImage();
		});
	};

	PEXETO.utils.fullBgImage.prototype.positionImage = function(){
		var self = this,
			parentWidth = self.$parent.width(),
			parentHeight = self.$parent.height(),
			naturalSize = PEXETO.utils.getNaturalImgSize(self.$img),
			imgWidth = self.imgWidth,
			imgHeight = self.imgHeight,
			displayHeight = Math.round(parentWidth * imgHeight / imgWidth),
			args = {};

			if(parentWidth/parentHeight > imgWidth/imgHeight){
				args = {
					width:'100%',
					height:'auto',
					left:0
				};

				self.$img.css(args);

				var curImgHeight = self.$img.height(),
					top = curImgHeight > parentHeight ? - (curImgHeight - parentHeight) / 2 : 0;
				
				self.$img.css({top:top});

			}else{
			
				args = {
					width:'auto',
					height:'100%',
					top:0
				};

				self.$img.css(args);

				var curImgWidth = self.$img.width(),
					left = curImgWidth > parentWidth ? - (curImgWidth - parentWidth) / 2 : 0;

				self.$img.css({left:left});
			}
	};




	PEXETO.utils.supportsTransition = function(){
		if(PEXETO.supportsTransition !== undefined){
			return PEXETO.supportsTransition;
		}

		var b = document.body || document.documentElement,
        s = b.style,
        support = s.transition !== undefined || s.WebkitTransition !== undefined || s.MozTransition !== undefined || s.MsTransition !== undefined || s.OTransition !== undefined;
   		PEXETO.supportsTransition = support;
   		return support;
	};

}(jQuery));

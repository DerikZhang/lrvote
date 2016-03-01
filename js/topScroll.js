// JavaScript Document
function topScroll(oHeadDiv,oNavBox){
	var timer = null;
	var pos_timer = null;
	var nHeight = oNavBox.offsetHeight;
	window.onscroll = function(){
		var oInfoDiv = document.getElementById('inform');
		clearInterval(timer);
		clearTimeout(pos_timer);
		if( getScrollTop() >= 70){
			oHeadDiv.style.position = 'fixed';
			posChange(oNavBox,78);
			if(oHeadDiv.className.indexOf('opacity') == -1){
			addClass(oHeadDiv,'opacity');
		}
		}else{
			oHeadDiv.style.position = 'relative';
			posChange(oNavBox,119);
		}
		if(oInfoDiv){
			oInfoDiv.getElementsByTagName('div')[0].style.display = 'none';
			oInfoDiv.style.border = 'solid 1px transparent';
		}
		timer = setInterval(bgChange,200);
	}	
	function posChange(ele,nTarget){
		var curHeight = ele.offsetHeight;
		if( curHeight > nTarget){
			curHeight --;
			ele.style.height = curHeight+'px';
			pos_timer = setTimeout(function(){posChange(ele,nTarget);},5);
		}else if(curHeight <nTarget){
			curHeight ++;
			ele.style.height = curHeight+'px';
			pos_timer = setTimeout(function(){posChange(ele,nTarget);},5);
		}else{
			clearTimeout(pos_timer);
		}
	}
	function bgChange(){
		removeClass(oHeadDiv,'opacity');
	}
	oNavBox.onmouseover = function(){
		if(oHeadDiv.style.position == 'fixed'){
			clearTimeout(pos_timer);
			posChange(oNavBox,119);
		}
	}
	oNavBox.onmouseout = function(){
		if(oHeadDiv.style.position == 'fixed'){
			clearTimeout(pos_timer);
			posChange(oNavBox,78);
			oNavBox.style.overflow = 'hidden';
		}	
	}
	function getScrollTop() {
		if ('pageYOffset' in window) {
			return window.pageYOffset;
		} else if (document.compatMode === "BackCompat") {
			return document.body.scrollTop;
		} else {
			return document.documentElement.scrollTop;
		}
	}
	function addClass(ele,className){
		var reg = new RegExp('\\b'+className+'\\b','g');
		if(!reg.test(ele.className)){
			ele.className += ' '+className;
		}
	}
	/*function getCss(ele,attr){
		return ele.currentStyle ? ele.currentStyle[attr] : getcomputedStyle(ele,false)[attr];
	}*/
	function removeClass(ele,className){
		var reg = new RegExp('\\b'+className+'\\b','g');
		if(reg.test(ele.className)){
			ele.className = ele.className.replace(reg,'');
		}
	}
}

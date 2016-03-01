/**
 * @author luoyifan
 * @description 
 * 调用方法 new一个Alert对象
 * new YAlert({
*			title : '提示内容',
*			content : '测试',
*			show : true, //是否默认显示对话框
*			close : {
*						text : '切换省份',
*						click : function(){
*									alert("close");
*								}
*						
*					},//是否展现close按钮，没有此属性时不展现
*			cancel : {
*						 text : '取消',
*						 click : function(){
*								alert("cancel");
*						 }
*					}//是否展现cancel按钮，没有此属性时不展现
*	});
 * @returns
 */
YAlert =  window.YAlert || (function(){
	return function(){
			this.initialize.apply(this, arguments);
		};
})();
(function(){
	Object.extend = function(destination, source){
		for (var property in source) {
			destination[property] = source[property];
		}
		return destination;
	};
	bindEvent = function(obj, type, fn ){
		if (obj.attachEvent) {
			obj['e'+type+fn] = fn;
			obj[type+fn] = function(){obj['e'+type+fn]( window.event );};
			obj.attachEvent('on'+type, obj[type+fn] );
		} else{
			obj.addEventListener( type, fn, false );
		}
	};
	unbindEvent = function( obj, type, fn ) {
		if (obj.detachEvent) {
			obj.detachEvent('on'+type, obj[type+fn] );
			obj[type+fn] = null;
		} else {
			obj.removeEventListener( type, fn, false );
		}
	};
	YAlert.prototype = {
		initialize : function(params){
			if(params){
				this.frameid = 'yhd_alert_tip';
				this.userClose = params.close;
				this.userCancel = params.cancel;
				this.titletext = params.title;
				this.contenttext = params.content;
				this.showFlag = params.show;
				this.oDiv = document.getElementById('yhd_alert_tip');
				if(!this.oDiv){
					this.create();
				}else{
					this.oClose = document.getElementById('yhd_alert_titleclose');
					this.oCancelBtn = document.getElementById('yhd_alert_cancel');
					this.oCloseBtn = document.getElementById('yhd_alert_close');
					if(this.showFlag) this.show();
				}
				this.initContent();
			}
		},
		show : function(){
			this.oDiv.style.display = 'block';
		},
		close : function(){
			this.oDiv.style.display = 'none';
		},
		create : function(){
			this.oDiv = document.createElement('div');
			this.oAlert_backg = document.createElement('div');
			this.oAlert_frame = document.createElement('div');
			this.oTitle = document.createElement('div');
			this.oText = document.createElement('div');
			this.oBtnBox = document.createElement('div');
			
			this.oDiv.id = this.frameid;
			this.oAlert_backg.className = 'm_popup_wrap_mask';
			this.oAlert_frame.className = 'm_popup_wrap';
			this.oTitle.className = 'title';
			this.oText.className = 'text';
			this.oBtnBox.className = 'btn_box';
			
			document.body.appendChild(this.oDiv);
			this.oDiv.appendChild(this.oAlert_frame);
			this.oDiv.appendChild(this.oAlert_backg);
			this.oAlert_frame.appendChild(this.oTitle);
			this.oAlert_frame.appendChild(this.oText);
			this.oAlert_frame.appendChild(this.oBtnBox);
			
			this.oTitleText = document.createElement('span');
			this.oClose = document.createElement('b');
			
			this.oTitleText.id = 'yhd_alert_titletext';
			this.oClose.id = 'yhd_alert_titleclose';
			this.oClose.className = 'close';
			
			this.oTitle.appendChild(this.oTitleText);
			this.oTitle.appendChild(this.oClose);
			
			this.oContentText = document.createElement('p');
			this.oContentText.id = 'yhd_alert_contenttext';
			this.oText.appendChild(this.oContentText);
			
			this.oCancelBtn = document.createElement('a');
			this.oCancelBtn.id = 'yhd_alert_cancel';
			this.oCancelBtn.className = 'btn_public btn_general';
			this.oCancelBtn.innerHTML = '取消';
			this.oBtnBox.appendChild(this.oCancelBtn);
			
			this.oCloseBtn = document.createElement('a');
			this.oCloseBtn.id = 'yhd_alert_close';
			this.oCloseBtn.className = 'btn_public btn_highlight';
			this.oCloseBtn.innerHTML = '关闭';
			this.oBtnBox.appendChild(this.oCloseBtn);
			
			this.bindClose();
			
			if(this.showFlag){
				this.show();
			}else{
				this.close();
			}
		},
		oEve: function(onEnd){
			this.onEnd = {};
			Object.extend(this.onEnd, onEnd || {});
		},
		initContent : function(){
			var list = ['titletext','contenttext'];
			for(var num in list){
				if(document.getElementById('yhd_alert_'+list[num])){
					document.getElementById('yhd_alert_'+list[num]).innerHTML = this[list[num]];
				}
			}
			this.bindClick();
		},
		bindClick : function(){
			if(this.userClose){
				if(this.userClose.text) this.oCloseBtn.innerHTML = this.userClose.text;
				if(this.userClose.click) this.oCloseBtn.onclick = this.userClose.click;
			}else{
				this.oCloseBtn.style.display = 'none';
			}
			if(this.userCancel){
				if(this.userCancel.text) this.oCancelBtn.innerHTML = this.userCancel.text;
				if(this.userCancel.click) this.oCancelBtn.onclick = this.userCancel.click;
			}else{
				this.oCancelBtn.style.display = 'none';
			}
		},
		bindClose : function(){
			var _this = this;
			var closeAlertEvent = function(){
					_this.oDiv.style.display = 'none';
			};
			bindEvent(_this.oClose,'click',closeAlertEvent);
			bindEvent(_this.oCancelBtn,'click',closeAlertEvent);
			bindEvent(_this.oCloseBtn,'click',closeAlertEvent);
		}
	};
})();
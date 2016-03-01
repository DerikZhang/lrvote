
var PEXETO_SS = PEXETO_SS || {};


(function($){

	var ss, cs, ps;

	PEXETO_SS.styleSwitcher = function(options){
		this.options = options;
	};

	ss = PEXETO_SS.styleSwitcher.prototype;

	ss.init = function(){
		var self = this;

		if(typeof(Storage)==="undefined" || self.checkIfMobile()){
			//disable the switcher for old browsers
			return;
		}

		self.blockSize = {};
		if(self.options.switcher_block_width){
			self.blockSize.width = self.options.switcher_block_width;
		}
		if(self.options.switcher_block_height){
			self.blockSize.height = self.options.switcher_block_height;
		}

		self.buildSwitcher();
		self.bindEventHandlers();

	};

	ss.buildSwitcher = function(){
		var self = this,
			//$parent = self.options.appendTo ? $(self.options.appendTo) : $('body'),
			$parent = $(self.options.appendTo);
			opened = sessionStorage.pexeto_ss_opened === undefined ? self.options.switcher_opened :
				JSON.parse(sessionStorage.pexeto_ss_opened),
			$blocks = null;

		self.$el = $('<div />', {'class':'pexeto-style-switcher'})
			.prependTo($parent);

		if(self.options.switcher_width){
			self.$el.width(self.options.switcher_width);
		}
		

		self.addColorOptions();
		self.addPatternOptions();


		self.$toggleBtn = $('<div />', {'class':'ss-toggle-btn'}).appendTo(self.$el);
		self.$el.show();
		self.width = self.$el.outerWidth();

		self.toggleSwitcher(opened, false);

		if(self.options.switcher_add_text){
			self.$el.append('<p>'+self.options.switcher_add_text+'</p>');
		}
	};

	ss.bindEventHandlers = function(){
		var self = this;

		self.$toggleBtn.on('click', function(){
			self.toggleSwitcher(!self.opened, true);
		});
	};

	ss.toggleSwitcher = function(open, animate){
		var self = this,
			args = {},
			$el = self.$el,
			doOnComplete = function(){
				self.inAnimation = false;
				self.opened = open;
				sessionStorage.pexeto_ss_opened = open;
			};


		if(!self.inAnimation){
			if(open){
				//show the switcher
				$el.removeClass('ss-closed');
				args.marginLeft = 0;
			}else{
				//hide the switcher
				$el.addClass('ss-closed');
				args.marginLeft = -self.width;
			}

			if(animate){
				self.inAnimation = true;
				$el.animate(args, doOnComplete);
			}else{
				$el.css(args);
				doOnComplete();
			}
		}
	};

	ss.addColorOptions = function(){
		var i, color, id,
			self = this,
			len = self.options.colors.length;


		if(len){
			for(i=0; i<len; i++){
				color = self.options.colors[i];
				id = color['id'];

				new PEXETO_SS.colorSetter(id, color, self.$el, self.blockSize).init();
			}
		}
	};	

	ss.addPatternOptions = function(){
		var i, pattern, id,
			self = this,
			len = self.options.patterns.length;


		if(len){
			for(i=0; i<len; i++){
				pattern = self.options.patterns[i];
				id = pattern['id'];

				new PEXETO_SS.patternSetter(id, pattern, self.$el, self.blockSize).init();
			}
		}
	};

	ss.checkIfMobile = function() {
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

		for(i = 0, len = devices.length; i < len; i += 1) {
			if(devices[i].regex.test(userAgent)) {
				$('body').addClass(devices[i]['class'] + ' mobile');
				return true;
			}
		}

		return false;
	};


	PEXETO_SS.patternSetter = function(id, options, $parent, size){
		this.itemId = id;
		this.options = options;
		this.$parent = $parent;
		this.elSize = size;
	};
	ps = PEXETO_SS.patternSetter.prototype;

	ps.init = function(){
		var self = this,
			savedPattern;
		self.buildElement();
		self.bindEventHandlers();
		self.$applyElements = $(self.options.pattern_elements);

		savedPattern = sessionStorage[self.itemId];
		if(savedPattern){
			self.applyPattern(savedPattern);
		}

	};

	ps.buildElement = function(){
		var i,
			self = this,
			patternImg = self.options['pattern_img'],
			patternNum = parseInt(self.options['pattern_number'], 10),
			html = '<div class="ss-wrapper">',
			fileName,
			buttonWidth = self.elSize.width || 20,
			position;

		if(self.options.pattern_title){
			html+='<label>'+self.options.pattern_title+'</label>';
		}


		if(patternNum){
			for(i=0; i<patternNum; i++){
				fileName = self.options.pattern_image_format.replace('{i}', i);
				html+='<a rel="'+fileName+'" class="ss-pattern" href=""'+
				' style="background-image:url('+patternImg+'); background-position:-'+(i*buttonWidth)+'px 0;"></a>';
			}
		}

		html+='</div>';

		self.$el = $(html).appendTo(self.$parent);

		if(self.elSize.width || self.elSize.heihgt){
			self.$el.find('.ss-pattern').css(self.elSize);
		}
	};


	ps.bindEventHandlers = function(){
		var self = this;

		self.$el.on('click', 'a', function(e){
			var pattern = $(this).attr('rel');
			e.preventDefault();
			self.applyPattern(pattern);
			sessionStorage[self.itemId] = pattern;
		});
	};

	ps.saveValue = function(pattern){
		var self = this;
		sessionStorage[self.itemId] = pattern;
	};

	ps.applyPattern = function(pattern){
		var self = this;

		self.$applyElements.css({backgroundImage:'url('+self.options.pattern_path+pattern+')'});
	};





	PEXETO_SS.colorSetter = function(id, options, $parent, size){
		this.itemId = id;
		this.options = options;
		this.$parent = $parent;
		this.elSize = size;


	};
	cs = PEXETO_SS.colorSetter.prototype;

	cs.init = function(){
		var self = this,
			savedColor;
		self.keys = ['colors_bg', 'colors_text', 'colors_border', 'colors_bg_rgba'];
		self.setDependentElements();
		self.buildElement();
		self.bindEventHandlers();

		if(self.options.colorpicker==='on'){
			self.initColorpicker();
		}

		savedColor = sessionStorage[self.itemId];
		if(savedColor){
			self.applyColor(savedColor);
		}

		

	};

	cs.setDependentElements = function(){
		var self = this,
			keys = self.keys,
			i = keys.length;

		self.elements = [];

		while(i--){
			if(self.options[keys[i]]){
				self.elements[keys[i]] = self.options[keys[i]];
			}
		}

	};

	cs.buildElement = function(){
		var i,
			self = this,
			colors = self.options.colors.split(','),
			len = colors.length,
			html = '<div class="ss-wrapper">';

		if(self.options.colors_title){
			html+='<label>'+self.options.colors_title+'</label>';
		}

		if(len){
			for(i=0; i<len; i++){
				html+='<a rel="'+colors[i]+'" class="ss-color" href="" style="background-color:#'+colors[i]+';"></a>';
			}
		}

		html+='</div>';

		self.$el = $(html).appendTo(self.$parent);
		if(self.elSize.width || self.elSize.heihgt){
			self.$el.find('.ss-color').css(self.elSize);
		}
	};

	cs.bindEventHandlers = function(){
		var self = this;

		self.$el.on('click', 'a', function(e){
			var color = $(this).attr('rel');
			e.preventDefault();
			self.applyColor(color);
			sessionStorage[self.itemId] = color;
		});
	};


	cs.applyColor = function(color){
		var self = this,
			elements = self.elements,
			$style,
			style = '<style type="text/css">',
			rgb, selectors;

		self.currentColor = color;


		if(elements.colors_bg){
			//apply background color
			style+=elements.colors_bg+'{background-color:#'+color+';}';
		}

		if(elements.colors_text){
			//apply text color
			style+=' '+elements.colors_text+'{color:#'+color+';}';
		}

		if(elements.colors_border){
			//apply border color
			style+=' '+elements.colors_border+'{border-color:#'+color+';}';
		}


		if(elements.colors_bg_rgba){
			rgb = self.hexToRgb(color);
			selectors = elements.colors_bg_rgba.split(',');
			console.log('SELECTORS', selectors);

			$.each(selectors, function(index, sel){
				var $curEl = $(sel),
					elColor = $curEl.css('background-color'),
					colors,
					alpha = 0.5,
					rgbaString;

				if(elColor && elColor.indexOf('rgba')!=='-1'){
				    elColor = elColor.replace(/rgba|\(|\)|\s/g, '');
				    colors = elColor.split(',');
				    if(colors[3]){
						alpha = parseFloat(colors[3]);
				    }

				   rgbaString = 'rgba('+rgb.r+','+rgb.g+','+rgb.b+','+alpha+')';
				   style+=' '+sel+'{background-color:'+rgbaString+';}';
				}
			});
		}

		if(self.$pickerPreview){
			self.$pickerPreview.css({background:'#'+color});
		}
		

		style+='</style>';

		$style = $(style);

		$style.appendTo($('head'));

		if(self.$style){
			self.$style.remove();
		}

		self.$style = $style;

	};

	cs.initColorpicker = function(){
		var self = this;
		self.$pickerBtn = $('<div />', {'class':'ss-picker-btn', 'html':'<span>Pick a color</span>'})
			.appendTo(self.$el);

		self.$pickerPreview = $('<div />', {'class':'ss-picker-preview'})
			.appendTo(self.$pickerBtn);

		self.$pickerBtn.ColorPicker({
			onSubmit: function(hsb, hex, rgb){
				self.applyColor(hex);
				sessionStorage[self.itemId] = hex;
				self.$pickerPreview.css({background:'#'+hex});
			},
			onBeforeShow : function(){
				if(self.currentColor){
					self.$pickerBtn.ColorPickerSetColor(self.currentColor);
				}
			}
		});
	};

	//code from http://css-tricks.com/snippets/php/convert-hex-to-rgb/
	cs.hexToRgb = function( hex ) {
	   var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	    return result ? {
	        r: parseInt(result[1], 16),
	        g: parseInt(result[2], 16),
	        b: parseInt(result[3], 16)
	    } : null;
	};

})(jQuery);

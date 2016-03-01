/**
 * 系统公用js
 */
$(function() {
	jQuery.validator
			.addMethod(
					"isMobile",
					function(value, element) {
						var length = value.length;
						var mobile = /^(((13[0-9]{1})|(15[0-9]{1})|(14[1-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
						return this.optional(element)
								|| (length == 11 && mobile.test(value));
					}, "请正确填写手机号码");
	jQuery.validator
			.addMethod(
					"TelorMobile",
					function(value, element) {
						var length = value.length;
						var mobile = /^(((13[0-9]{1})|(15[0-9]{1})|(14[1-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
						var tel = /^0+\d{10,11}$/;
						// var tel = /^\d{3,4}-?\d{7,9}$/;
						return this.optional(element)
								|| (tel.test(value) || mobile.test(value));
					}, "请正确填写您的固话或手机号码");
	jQuery.validator.addMethod("notEqualTo", function(value, element, param) {
		return value != $(param).val();
	}, $.validator.format("两次输入不能相同!"));
	jQuery.validator.addMethod("onlyLetterAndDigit", function(value, element,
			params) {
		var regex = new RegExp('^[A-Za-z0-9]+$');
		return regex.test(value);
	}, "只能输入字母或数字");
	jQuery.validator.addMethod("onlyLetterAndChinese", function(value, element,
			params) {
		var regex = new RegExp('^[\\u4E00-\\u9FA5\\uF900-\\uFA2D\\w]');
		return regex.test(value);
	}, "只能输入字母或汉字");
	jQuery.validator.addMethod("isContainDig", function(value, element){
		var pattern = new RegExp("[`~!@%#$^&*()=|{}':;',　\\[\\]<>/? \\.；：%……+￥（）【】‘”“'。，、？]"); 
		return this.optional(element)||!pattern.test(value) ;
	}, "不允许包含特殊符号!");
	jQuery.validator.addMethod("mustContainChinese", function(value, element){
		return /[\u4e00-\u9fa5]/g.test(value);
	}, "必须至少有一个汉字!");
	jQuery.validator.addMethod("stringCheck", function(value, element) {
		return this.optional(element) || /^[\u0391-\uFFE5\w]+$/.test(value);
	}, "只能包括中文字、英文字母、数字和下划线");
	// 电话号码验证
	jQuery.validator.addMethod("isTel", function(value, element) {
		var tel = /^\d{3,4}-?\d{7,9}$/; // 电话号码格式010-12345678
		return this.optional(element) || (tel.test(value)); 
	}, "请正确填写您的电话号码");
	jQuery.validator.addMethod("onlyDigit", function(value, element, params) {
		var regex = new RegExp('^[0-9]+$');
		return regex.test(value);
	}, "只能输入数字");
	jQuery.validator
			.addMethod(
					"MobileOrTel",
					function(value, element) {
						var length = value.length;
						var mobile = /^(((13[0-9]{1})|(15[0-9]{1})|(14[1-9]{1})|(17[1-9]{1})|(18[0-9]{1}))+\d{8})$/;
						// var tel = /^0+\d{10,11}$/;
						var tel = /^\d{3,4}-?\d{7,9}$/;
						return this.optional(element)
								|| (tel.test(value) || mobile.test(value));
					}, "请正确填写您的固话或手机号码");
	jQuery.validator.addMethod("byteRangeLength", function(value, element,
			param) {
		var length = value.length;
		for ( var i = 0; i < value.length; i++) {
			if (value.charCodeAt(i) > 127) {
				length++;
			}
		}
		return this.optional(element)
				|| (length >= param[0] && length <= param[1]);
	}, jQuery.validator.format("请确保输入的值在{0}-{1}个汉字之间(一个中文字算2个字节)"));
	// 邮政编码验证
	jQuery.validator.addMethod("isZipCode", function(value, element) {
		var tel = /^[0-9]{6}$/;
		return this.optional(element) || (tel.test(value));
	}, "请正确填写您的邮政编码");
	jQuery.validator.addMethod("isMoney", function(value, element) {
		// var money=/^\d+(\.\d{1,2})$|^\d*$/;
		var money = /^(([1-9]\d{0,9})|0)(\.\d{1,2})?$/;
		return this.optional(element) || money.test(value);
	}, "请输入正确的金额");
	// 输入是否含有中文
	jQuery.validator.addMethod("isNotChinese",
			function(value, element, params) {
				var patrn = /[\u4E00-\u9FA5]|[\uFE30-\uFFA0]/gi;
				if (!patrn.exec(value)) {
					return true;
				} else {
					return false;
				}

			}, "输入不能含有汉字");
	jQuery.validator.addMethod("validNickName",
			function(value, element, params) {
				// var regex = new RegExp('^[A-Za-z0-9_@]+$');
				var regex = new RegExp(
						'^[\u4e00-\u9fa5]{2,6}$|^[a-zA-Z]+[A-Za-z0-9-_@]+$');
				return regex.test(value);
			}, "只能输入字母、数字、_和@");

	// 手机或邮箱可以登录
	jQuery.validator
			.addMethod(
					"isEmailOrMobel",
					function(value, element, params) {
						var mobile = /^(((13[0-9]{1})|(15[0-9]{1})|(14[1-9]{1})|(17[1-9]{1})|(18[0-9]{1}))+\d{8})$/;
						var email = /^([a-zA-Z0-9_\.\-])+@([a-zA-Z0-9_\.\-])+\.[a-zA-Z]{2,3}$/;
						return (email.test(value) || mobile.test(value));
					}, "输入错误");
	// 后台验证邮箱的有效性
	jQuery.validator.addMethod("checkValidEmail", function(value, element,
			params) {
		var result = false;
		$.ajax({
			url : ctx + "/user/reg/checkValidEmail",
			async : false,
			type : "POST",
			data : {
				email : value
			},
			dataType : "json",
			success : function(data) {
				result = data;
			}
		});
		return result;
	});
});

String.prototype.trim = function() {
	return this.replace(/(^\s*)|(\s*$)/g, "");
};
String.prototype.ltrim = function() {
	return this.replace(/(^\s*)/g, "");
};
String.prototype.rtrim = function() {
	return this.replace(/(\s*$)/g, "");
};

function closeMe() {
	// $("#cboxClose").click();
	$.fn.colorbox.close();
}

function closeMeWithinIframe() {
	parent.$.fn.colorbox.close();
}

function IsURL(str_url) {
	var strRegex = "^((https|http|ftp|rtsp|mms)://)"
	// + "(([0-9a-zA-Z_!~*'().&=+$%-]+: )?[0-9a-zA-Z_!~*'().&=+$%-]+@)?"
	// //ftp的user@
	+ "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184
			+ "|" // 允许IP和DOMAIN（域名）
			+ "([0-9a-zA-Z_!~*'()-]+\.)*" // 域名- www.
			+ "([0-9a-zA-Z][0-9a-zA-Z-]{0,61})?[0-9a-zA-Z]\." // 二级域名
			+ "[a-zA-Z]{2,6})" // first level domain- .com or .museum
			+ "(:[0-9]{1,4})?" // 端口- :80
			+ "((/?))";
	var re = new RegExp(strRegex);
	// re.test()
	if (re.test(str_url)) {
		return (true);
	} else {
		return (false);
	}
}
function isTelOrMobel(number) {
	var mobile = /^(((13[0-9]{1})|(15[0-9]{1})|(14[1-9]{1})|(17[1-9]{1})|(18[0-9]{1}))+\d{8})$/;
	var tel = /^0+\d{10,11}$/;
	return (tel.test(number) || mobile.test(number));
}

/**
 * 判断是否包含全角字符
 * 
 * @param str
 * @returns {Boolean}
 */
function chkHalf(str) {
	for ( var i = 0; i < str.length; i++) {
		strCode = str.charCodeAt(i);
		if ((strCode > 65248) || (strCode == 12288)) {
			/*
			 * alert("有全角字符"); break;
			 */
			return false;
		}
	}
	return true;
}

// 全角转换为半角函数
function toCDB(str) {
	var tmp = "";
	for ( var i = 0; i < str.length; i++) {
		if ("，。！".indexOf(str.charCodeAt(i))<0&&str.charCodeAt(i) > 65248 && str.charCodeAt(i) < 65375) {
			tmp += String.fromCharCode(str.charCodeAt(i) - 65248);
		} else {
			tmp += String.fromCharCode(str.charCodeAt(i));
		}
	}
	return tmp;
}

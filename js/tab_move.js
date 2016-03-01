/*轮播图*/
//TODO 整个方法
function homeNav(){
	var PicTotal = $('#banner_list').find('li').length; // 总共几张
	var CurrentIndex = 0;                               // 当前要显示的索引
	var ToDisplayPicNumber = 1;                         // 定时器下一次执行用到的索引
	var timer = null;                                   // 定时器对象
	var hideIndex = 0;                                  // 要隐藏的索引
	var iTime = 5000;                                   // 间隔时间
	
	//移上移下显示隐藏
	$(".banner").hover(function(){
		clearTimeout(timer);
		$('.next_btn, .prev_btn').show();
	},function(){
		$('.next_btn, .prev_btn').hide();
		timer = setTimeout(PicNumClick, iTime);
		//离开从新赋值
		ToDisplayPicNumber = (CurrentIndex + 1 == PicTotal ? 0 : CurrentIndex + 1);
	});
	//下一个
	$('.next_btn').bind('click',function(){
		CurrentIndex = (CurrentIndex + 1 == PicTotal ? 0 : CurrentIndex + 1); 
		DisplayPic();
	});
	//上一个
	$('.prev_btn').bind('click',function(){
		CurrentIndex = (CurrentIndex - 1 < 0 ? PicTotal-1 : CurrentIndex -1); 
		DisplayPic();
	});
	//中间按钮
	$('#circle_btns').find('a').bind('click',function(){
		CurrentIndex = $(this).index();
		DisplayPic();
	});
	//图片切换效果
	function DisplayPic() {
		clearTimeout(timer);
		if(CurrentIndex == hideIndex){return;}
		$('#banner_list').find('li').eq(CurrentIndex).css({'opacity':1,'z-index':2});
		$('#banner_list').find('li').eq(hideIndex).css({'z-index':5}).stop(true,true).animate({'opacity':0}, 300 , function(){
			hideIndex = CurrentIndex;
			$(this).css({'z-index':1});
		});
		$('.circle_btns').find('a').eq(CurrentIndex).addClass('cur').siblings().removeClass('cur');
	}
	//循环调用自身
	function PicNumClick() {
		$("#circle_btns a").eq(ToDisplayPicNumber).trigger("click");
		ToDisplayPicNumber = (ToDisplayPicNumber + 1) % PicTotal;
		timer = setTimeout(PicNumClick,iTime);
	}
	setTimeout(PicNumClick, iTime);
}
/*短信语音验证码左侧菜单栏固定*/
function scrollT(){
	var scrollT = $(document).scrollTop();
	var top = $('.list_bar').offset().top;
	if(scrollT>747){
		$('.list_bar').css({'position':'fixed','top':'2px'});
	}else{
		$('.list_bar').css({'position':'static'});	
	}
}
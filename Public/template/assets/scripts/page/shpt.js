/**
 * 加载生活配套页面
 */
define(function(require){
var app=require("modules/app/main");
//如果存在show_page 参数，则加载 包含参数指定的css的页面，默认加载 page-index
//shpt.html?show_page=order_p3
var show_page_name = app.getQueryString('show_page');
if(show_page_name){
	app.showPage("."+show_page_name);
}else{
	app.showPage(".page-index");
}
require("modules/index/main").init(),
require("modules/teletext/main").init(),
require("modules/link/main").init(),
//require("modules/video/main").init(),
//require("modules/map/main").init(),
//require("modules/form/main").init(),
console.log("\n运行成功！"),
$(".app-footer").after($("input[data-weixin-callback]"))
});
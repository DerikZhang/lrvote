/**
 * Created with JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-5-27
 * Time: 上午12:11
 * To change this template use File | Settings | File Templates.
 */

$(function(){
    $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayBtn: true,
        startDate: "2013-02-14",
        minuteStep: 10
    });
});

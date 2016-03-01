<?php


include_once '../../dbconfig.php';
$cfg['tb_pre'] = $dbconfig['DB_PREFIX'];
$cfg['db_charset'] = 'utf8'; 
$cfg['sqlerr'] = '1';
$cfg['errlog'] = '1';
$cfg['timediff'] = '0'; 
$fr_time = time();
define('FR_ROOT', str_replace("\\", '/', dirname(__FILE__)));
define('CACHE_ROOT', $cfg['cache_dir'] ? $cfg['cache_dir'] : FR_ROOT.'/cache');
define('DATA_ROOT', FR_ROOT.'/data');
include('../mysql.class.php');
$db = new db_mysql();
$db->halt = $cfg['sqlerr'];
$db->connect($dbconfig['DB_HOST'], $dbconfig['DB_USER'], $dbconfig['DB_PWD'], $dbconfig['DB_NAME'],0);


$rs = $db->get_one("select id,wx_appid,wx_appsecret from {$cfg['tb_pre']}user WHERE id='".$_GET['id']."'"); 

//var_dump($rs);

require_once "jssdk.php";

$jssdk = new JSSDK($rs['wx_appid'], $_GET['wx_appsecret']);
$signPackage = $jssdk->GetSignPackage($_GET['url']);
?>

wx.config({
appId: '<?php echo $signPackage["appId"];?>',
timestamp: <?php echo $signPackage["timestamp"];?>,
nonceStr: '<?php echo $signPackage["nonceStr"];?>',
signature: '<?php echo $signPackage["signature"];?>',
jsApiList: [
        'checkJsApi',
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'hideMenuItems',
        'showMenuItems',
        'hideAllNonBaseMenuItem',
        'showAllNonBaseMenuItem',
        'translateVoice',
        'startRecord',
        'stopRecord',
        'onRecordEnd',
        'playVoice',
        'pauseVoice',
        'stopVoice',
        'uploadVoice',
        'downloadVoice',
        'chooseImage',
        'previewImage',
        'uploadImage',
        'downloadImage',
        'getNetworkType',
        'openLocation',
        'getLocation',
        'hideOptionMenu',
        'showOptionMenu',
        'closeWindow',
        'scanQRCode',
        'chooseWXPay',
        'openProductSpecificView',
        'addCard',
        'chooseCard',
        'openCard'
      ]
});






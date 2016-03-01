<?php

$dbconfig = require_once 'mysqlconf.php';


$cfg['tb_pre'] = $dbconfig['DB_PREFIX'];
$cfg['db_charset'] = 'utf8'; 
$cfg['sqlerr'] = '1';
$cfg['errlog'] = '1';
$cfg['timediff'] = '0'; 
$fr_time = time();
define('FR_ROOT', str_replace("\\", '/', dirname(__FILE__)));
define('CACHE_ROOT', $cfg['cache_dir'] ? $cfg['cache_dir'] : FR_ROOT.'/cache');
define('DATA_ROOT', FR_ROOT.'/data');
include('Public/mysql.class.php');
$db = new db_mysql();
$db->halt = $cfg['sqlerr'];
$db->connect($dbconfig['DB_HOST'], $dbconfig['DB_USER'], $dbconfig['DB_PWD'], $dbconfig['DB_NAME'],0);


$state = explode( 'A',$_GET['state']);
$id = $state[0];
$mid = $state[1];
$rs = $db->get_one("select * from {$cfg['tb_pre']}member where m_id = '".$mid."' ");
if($rs){
	$appid = $rs['m_appid'];
	$secret = $rs['m_appsecret'];
	$token = $rs['m_wxid'];
	$wxname = $rs['m_wxname'];
}else{
	echo 'error';exit;
}	


if (isset($_GET['code'])){
	$code = $_GET['code'];

	$res = json_decode(httpGet("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code"),true);

	$access_token = $res['access_token'];
	$openid = $res['openid'];

	$res1 = json_decode(httpGet("https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid),true);//用户信息
	if(isset($res1['errcode'])){
		echo $res1['errcode'];
		exit;
	}


	$rs = $db->get_one("select id from {$cfg['tb_pre']}userinfo where wecha_id = '".$openid."' AND mid=".$mid);
	if(!$rs){
		$db->query("INSERT INTO {$cfg['tb_pre']}userinfo(mid,token,wecha_id,wechaname,sex,wechahead,addtime) VALUES('".$mid."','".$token."','".$openid."','".$res1['nickname']."','".$res1['sex']."','".$res1['headimgurl']."',".time().")");
	}else{
		$db->query("UPDATE  {$cfg['tb_pre']}userinfo set wechaname='".$res1['nickname']."',sex='".$res1['sex']."',wechahead='".$res1['headimgurl']."',addtime=".time()." WHERE mid=".$mid." AND wecha_id='".$openid."'");
	}


	$res2 = json_decode(file_get_contents("Home/access_token-".$appid.".json"),true);
	if(empty($res2)){  //微信JS接口安全域名一定要加
		$res2 = json_decode(httpGet("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret),true);
	}
	$access_token2 = $res2['access_token'];

	$res3 = json_decode(httpGet("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token2."&openid=".$openid),true);

	//var_dump($res3);exit;

	if(isset($res3['errcode'])){
		//请关注
	}

	if($res3['subscribe'] == "0"){
		//请关注
	}

	if($res3['subscribe'] == "1" ){  //已经关注了
		$tmp = "&token=".$token."&wecha_id=".$openid;
	}

	header('Location: /Home/index.php?g=Home&m=Index&a=index&id='.$id.'&auth=1'.$tmp);
	exit;

}else{
	echo "NO CODE";
	exit;
}

function httpGet($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_URL, $url);

	$res = curl_exec($curl);
	curl_close($curl);

	return $res;
}



?>

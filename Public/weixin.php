<?php
/**
* wechat php test
*/
/*error_reporting(0); */
$dbconfig = require_once dirname(__FILE__).'/../mysqlconf.php';
$cfg['tb_pre'] = $dbconfig['DB_PREFIX'];
$cfg['db_charset'] = 'utf8';
$cfg['sqlerr'] = '1';
$cfg['errlog'] = '1';
$cfg['timediff'] = '0';
$fr_time = time();
define('FR_ROOT', str_replace("\\", '/', dirname(__FILE__)));
define('CACHE_ROOT', $cfg['cache_dir'] ? $cfg['cache_dir'] : FR_ROOT.'/cache');
define('DATA_ROOT', FR_ROOT.'/data');
include('mysql.class.php');
$db = new db_mysql();
$db->halt = $cfg['sqlerr'];
$db->connect($dbconfig['DB_HOST'], $dbconfig['DB_USER'], $dbconfig['DB_PWD'], $dbconfig['DB_NAME'],0);
$tmp = $_SERVER['PHP_SELF'];
$tmp = str_replace("weixin.php", "", $tmp);

$rs = $db->get_one("select m_id,m_isSubscribe,m_isConnent,m_isReply from {$cfg['tb_pre']}member where m_wxid = '".$_GET['token']."' ");
if($rs['m_id']){
	define("MID", $rs['m_id']);
	define("ISREPLY", $rs['m_isReply']);
	define("ISCONNENT", $rs['m_isConnent']);
}else{
	echo 'error';exit;
}

//define your token
define("TOKEN", $_GET['token']);
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();
$wechatObj->responseMsg();
class wechatCallbackapiTest
{
	public function valid()
	{
		$echoStr = $_GET["echostr"];
//valid signature , option
		if($this->checkSignature()){
			echo $echoStr;
	//exit;
		}
	}
	public function responseMsg()
	{
		global $db,$cfg,$tmp,$dbconfig;


		$domain = $_SERVER['SERVER_NAME'];
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
	//extract post data
		if (!empty($postStr)){

			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj->FromUserName;
			$toUsername = $postObj->ToUserName;
			$keyword = trim($postObj->Content);
			$type=$postObj->MsgType;
			$subscribe=$postObj->Event;
			$eventKey=$postObj->EventKey; //菜单点击值
			$time = time();
			$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>0</FuncFlag>
			</xml>";
//关注回复
			$eventTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[event]]></MsgType>
			<Event><![CDATA[subscribe]]></Event>
			</xml>";


         if($subscribe=='unsubscribe' && $rs['m_isSubscribe']=='1'){
            $rs = $db->get_one("select id from {$cfg['tb_pre']}userinfo where wecha_id = '".$fromUsername."' ");
            if($rs){
                $db->query("DELETE FROM {$cfg['tb_pre']}userinfo WHERE token='".TOKEN."' and wecha_id = '".$fromUsername."'" );
                //减少票
                $query = $db->query("SELECT form_id FROM {$cfg['tb_pre']}vote_record WHERE token='".TOKEN."' and wecha_id = '".$fromUsername."'" );
                while ( $rs = $db->fetch_array( $query  )  ){
                   $db->query("UPDATE {$cfg['tb_pre']}form SET ticket=ticket-1 WHERE id=".$rs['form_id'] );
                }
                $db->query("DELETE FROM {$cfg['tb_pre']}vote_record WHERE token='".TOKEN."' and wecha_id = '".$fromUsername."'" );
            }
         }


			switch ($type){

					case "event";   //关注回复
						//insert wxuser
					$rs = $db->get_one("select id from {$cfg['tb_pre']}userinfo where wecha_id = '".$fromUsername."' ");
					if(!$rs){
						$db->query("INSERT INTO {$cfg['tb_pre']}userinfo(token,wecha_id) VALUES('".TOKEN."','".$fromUsername."')");
					}
					$_SESSION['token'] = TOKEN;
					$_SESSION['wecha_id'] = $fromUsername;
					$msgType = "text";
					$num = 0;
					$tmp2 = '';

					$rs1 = $db->get_one("select id,typeid,title,picurl,jumpUrl,description from {$cfg['tb_pre']}reply where mid = '".MID."' and keyword='' ");

					$tmp3 = '';
					if($eventKey!=''){ //菜单点击值
						$rs1 = '';
						$tmp3 = " and keyword='".$eventKey."'";
					}	
					
					if($rs1){
						if($rs1['typeid'] == "1"){
						   	  $content = $rs1['description'];
						}elseif ($rs1['typeid'] == "2") {
						   	    $num++;
								$tmp2 .="<item>
								<Title><![CDATA[".$rs1['title']."]]></Title>
								<Description><![CDATA[".strip_tags($rs1['description'])."]]></Description>
								<PicUrl><![CDATA[".$rs1['picurl']."]]></PicUrl>
								<Url><![CDATA[".$rs1['jumpUrl']."]]></Url>
								</item>";
						}
					}else{
							$sql_a = "select id,title,picurl,info,wx_info from {$cfg['tb_pre']}vote WHERE status=1 AND statdate<".$time." AND enddate>".$time." AND mid=".MID." {$tmp3} ORDER BY id DESC LIMIT 0,6 ";
							$query = $db->query( $sql_a );
							while ( $rs = $db->fetch_array( $query ) ){
								$num++;
								if(empty($rs['picurl'])){
									$rs['picurl'] = "http://".$domain.'/Public/images/weixin.jpg';
								}
								$url = "http://".$domain."/Home/index.php?g=Home&m=Index&a=index&id=".$rs['id']."&wecha_id=".$fromUsername."&token=".TOKEN;
								$tmp2 .="<item>
								<Title><![CDATA[".$rs['title']."]]></Title>
								<Description><![CDATA[".strip_tags($rs['wx_info'])."]]></Description>
								<PicUrl><![CDATA[".$rs['picurl']."]]></PicUrl>
								<Url><![CDATA[".$url."]]></Url>
								</item>";

							}
					}


					$newsTpl="<xml>
					<ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
					<FromUserName><![CDATA[".$toUsername."]]></FromUserName>
					<CreateTime>".$time."</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>".$num."</ArticleCount>
					<Articles>
					".$tmp2."
					</Articles>
					<FuncFlag>0</FuncFlag>
					</xml>";
					if($num == 0){
						$content = empty($content)?"谢谢关注!":$content;
					}else{
						echo $newsTpl;
						exit;
					}

					$contentStr = $content;
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);

					break;

					case "text";


					if(!empty($keyword)){
						$tmp2 = $content = '';
						$rs = $db->get_one("select id from {$cfg['tb_pre']}userinfo where wecha_id = '".$fromUsername."' ");
						if(!$rs){
							$db->query("INSERT INTO {$cfg['tb_pre']}userinfo(token,wecha_id) VALUES('".TOKEN."','".$fromUsername."')");
						}
						$_SESSION['token'] = TOKEN;
						$_SESSION['wecha_id'] = $fromUsername;
						$msgType = "text";

						//关键字投票
						if( strstr($keyword, '号') && ISREPLY == "1" && ISCONNENT == "1"){
							$token = TOKEN;
							$wecha_id = $fromUsername;
							$arr1 = explode("号", $keyword);
							$a_num = $arr1[0];
							$a_username = $arr1[1];
							$sql_a = "select id,vid,ticket from {$cfg['tb_pre']}form WHERE status=1 AND num=".$a_num." AND username='".$a_username."' AND mid=".MID."  ORDER BY id DESC LIMIT 0,1 ";
							$rs3 = $db->get_one($sql_a);
							if($rs3){
								$form_id = $rs3['id'];
								$vid = $rs3['vid'];
								$sql_a = "select wx_url,prevent,cknums,mid,jump,hour,allperson,nativeplace,v_startdate,v_enddate,isVerify from {$cfg['tb_pre']}vote WHERE status=1 AND statdate<".$time." AND enddate>".$time." and id='".$vid."' AND mid=".MID."  ORDER BY id DESC LIMIT 0,1 ";
								$vote = $db->get_one($sql_a);
								if($vote){
									$ip = '编号姓名投票';
									$dd = 3600*intval($vote['hour']);
									$sql_a = "select count(*) as a from {$cfg['tb_pre']}vote_record WHERE vid=".$vid.' and token=\''.$token.'\' and wecha_id=\''.$wecha_id.'\' and addtime+'.$dd.' >'.time();
									$tmp = $db->get_one($sql_a);
									$tmp = $tmp['a'];

									if($vote['prevent']){
										//24点来计算
										date_default_timezone_set('PRC');
										$dd = strtotime(date("Y-m-d"));
										$dd2 = $dd+86399;
										$sql_a = "select count(*) as a from {$cfg['tb_pre']}vote_record WHERE vid=".$vid.' and token=\''.$token.'\' and wecha_id=\''.$wecha_id.'\' and addtime>'.$dd.' and addtime<'.$dd2;
										$tmp4 = $db->get_one($sql_a);
										$tmp4 = $tmp4['a'];	
										if($tmp4 >= 1 ){
											$content = '投票失败，每天只能投一次！';
										}									
									}else{
										if($vote['allperson']){
											$sql_a = "select count(*) as a from {$cfg['tb_pre']}vote_record WHERE form_id=".$form_id.' and token=\''.$token.'\' and wecha_id=\''.$wecha_id.'\' and addtime+'.$dd.' >'.time();
											$tmp5 = $db->get_one($sql_a);	
											$tmp5 = $tmp5['a'];
											if($tmp5>0){
												$content = '投票失败，在规定时间内不能重复投给同一个人';
												echo sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $content);	
												exit;
											}
										}

										if($tmp >= $vote['cknums']){
											$content = '投票失败，您已超过每天投票次数';
										}else{
												$db->query("UPDATE {$cfg['tb_pre']}form set ticket=ticket+1 where status=1 and id=".$form_id);

												$db->query("INSERT INTO {$cfg['tb_pre']}vote_record(form_id,vid,token,wecha_id,addtime,ip,mid) VALUES('".$form_id."','".$vid."','".$token."','".$wecha_id."','".$time."','".$ip."','".MID."')");
												$ticket = intval($rs3['ticket'])+1;
											    $arr = array();
												$tmp6 = 0;
												$sql_a = "select id from {$cfg['tb_pre']}form WHERE status=1 AND vid={$vid} ORDER BY ticket DESC";
												$i=0;
												$query = $db->query( $sql_a );
												while ( $v = $db->fetch_array( $query ) ){
													$arr[] = $v;
													$i++;
													if($v['id'] == $form_id){
														$tmp6 = $i;
													}
													if($tmp6 != 0 ){
														break;
													}
												}

												$content = '投票成功！'.$username.'当前为:'.$ticket.'票,排名第'.$tmp6;
										}


									}

								}else{
									$content = '投票不存在，或者过期！';
								}
							}else{
								$content = '未找到选手';
							}
							echo sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $content);	
							exit;	
						}
						//END




						$sql_a = "select id,title,picurl,info,wx_info from {$cfg['tb_pre']}vote WHERE status=1 AND statdate<".$time." AND enddate>".$time." and keyword='".$keyword."' AND mid=".MID."  ORDER BY id DESC LIMIT 0,1 ";
						$num = 0;
						$query = $db->query( $sql_a );
						while ( $rs = $db->fetch_array( $query ) ){
							$num++;
							if(empty($rs['picurl'])){
								$rs['picurl'] = "http://".$domain.'/Public/images/weixin.jpg';
							}
							$url = "http://".$domain."/Home/index.php?g=Home&m=Index&a=index&id=".$rs['id']."&wecha_id=".$fromUsername."&token=".TOKEN;
							$tmp2 .="<item>
							<Title><![CDATA[".$rs['title']."]]></Title>
							<Description><![CDATA[".strip_tags($rs['wx_info'])."]]></Description>
							<PicUrl><![CDATA[".$rs['picurl']."]]></PicUrl>
							<Url><![CDATA[".$url."]]></Url>
							</item>";
						}

						if($num == 0){
						    //自定义关键词
							$rs1 = $db->get_one("select id,typeid,title,picurl,jumpUrl,description from {$cfg['tb_pre']}reply where mid = '".MID."' and keyword='".$keyword."' ");
							if($rs1){

							}else{
								//不匹配
								$rs1 = $db->get_one("select id,typeid,title,picurl,jumpUrl,description from {$cfg['tb_pre']}reply where mid = '".MID."' and keyword='' ");
							}
							if($rs1){
								   if($rs1['typeid'] == "1"){
								   	  $content = $rs1['description'];
								   }elseif ($rs1['typeid'] == "2") {
								   	    $num++;
										$tmp2 .="<item>
										<Title><![CDATA[".$rs1['title']."]]></Title>
										<Description><![CDATA[".strip_tags($rs1['description'])."]]></Description>
										<PicUrl><![CDATA[".$rs1['picurl']."]]></PicUrl>
										<Url><![CDATA[".$rs1['jumpUrl']."]]></Url>
										</item>";
								    }
							}else{
								$content = "谢谢关注！";
							}

						}

						if(!empty($tmp2)){
							$newsTpl="<xml>
							<ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
							<FromUserName><![CDATA[".$toUsername."]]></FromUserName>
							<CreateTime>".$time."</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<ArticleCount>".$num."</ArticleCount>
							<Articles>
							".$tmp2."
							</Articles>
							<FuncFlag>0</FuncFlag>
							</xml>";
							echo $newsTpl;
							exit;
						}

					}else{
						$content = "找不到内容!";
					}

						$contentStr = $content;
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						break;
					}
					echo $resultStr;
				}else {
					echo "";
					exit;
				}
			}

			private function checkSignature()
			{
				$signature = $_GET["signature"];
				$timestamp = $_GET["timestamp"];
				$nonce = $_GET["nonce"];

				$token = TOKEN;
				$tmpArr = array($token, $timestamp, $nonce);
				sort($tmpArr, SORT_STRING);
				$tmpStr = implode( $tmpArr );
				$tmpStr = sha1( $tmpStr );

				if( $tmpStr == $signature ){
					return true;
				}else{
					return false;
				}
			}


		}
		?>

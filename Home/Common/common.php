<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

//公共函数
function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$format = str_replace ( '#', ':', $format );
	return date ($format, $time );
}

// 缓存文件
function cmssavecache($name = '', $fields = '') {
	$Model = D ( $name );
	$list = $Model->select ();
	$data = array ();
	foreach ( $list as $key => $val ) {
		if (empty ( $fields )) {
			$data [$val [$Model->getPk ()]] = $val;
		} else {
			// 获取需要的字段
			if (is_string ( $fields )) {
				$fields = explode ( ',', $fields );
			}
			if (count ( $fields ) == 1) {
				$data [$val [$Model->getPk ()]] = $val [$fields [0]];
			} else {
				foreach ( $fields as $field ) {
					$data [$val [$Model->getPk ()]] [] = $val [$field];
				}
			}
		}
	}
	$savefile = cmsgetcache ( $name );
	// 所有参数统一为大写
	$content = "<?php\nreturn " . var_export ( array_change_key_case ( $data, CASE_UPPER ), true ) . ";\n?>";
	file_put_contents ( $savefile, $content );
}

function cmsgetcache($name = '') {
	return DATA_PATH . '~' . strtolower ( $name ) . '.php';
}
function getStatus($status, $imageShow = true) {
	switch ($status) {
		case 0 :
			$showText = '禁用';
			$showImg = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
			break;
		case 2 :
			$showText = '待审';
			$showImg = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/prected.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">';
			break;
		case - 1 :
			$showText = '删除';
			$showImg = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
			break;
		case 1 :
		default :
			$showText = '正常';
			$showImg = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';

	}
	return ($imageShow === true) ?  $showImg  : $showText;

}
function getDefaultStyle($style) {
	if (empty ( $style )) {
		return 'blue';
	} else {
		return $style;
	}

}
function IP($ip = '', $file = 'UTFWry.dat') {
	$_ip = array ();
	if (isset ( $_ip [$ip] )) {
		return $_ip [$ip];
	} else {
		import ( "ORG.Net.IpLocation" );
		$iplocation = new IpLocation ( $file );
		$location = $iplocation->getlocation ( $ip );
		$_ip [$ip] = $location ['country'] . $location ['area'];
	}
	return $_ip [$ip];
}

function getNodeName($id) {
	if (Session::is_set ( 'nodeNameList' )) {
		$name = Session::get ( 'nodeNameList' );
		return $name [$id];
	}
	$Group = D ( "Node" );
	$list = $Group->getField ( 'id,name' );
	$name = $list [$id];
	Session::set ( 'nodeNameList', $list );
	return $name;
}

function get_pawn($pawn) {
	if ($pawn == 0)
		return "<span style='color:green'>没有</span>";
	else
		return "<span style='color:red'>有</span>";
}
function get_patent($patent) {
	if ($patent == 0)
		return "<span style='color:green'>没有</span>";
	else
		return "<span style='color:red'>有</span>";
}


function getNodeGroupName($id) {
	if (empty ( $id )) {
		return '未分组';
	}
	if (isset ( $_SESSION ['nodeGroupList'] )) {
		return $_SESSION ['nodeGroupList'] [$id];
	}
	$Group = D ( "Group" );
	$list = $Group->getField ( 'id,title' );
	$_SESSION ['nodeGroupList'] = $list;
	$name = $list [$id];
	return $name;
}

function getCardStatus($status) {
	switch ($status) {
		case 0 :
			$show = '未启用';
			break;
		case 1 :
			$show = '已启用';
			break;
		case 2 :
			$show = '使用中';
			break;
		case 3 :
			$show = '已禁用';
			break;
		case 4 :
			$show = '已作废';
			break;
	}
	return $show;

}

// zhanghuihua@msn.com
function showStatus($status, $a_id, $callback="") {
	switch ($status) {
		case 0 :
			$info = '<a href="__URL__/resume/a_id/' . $a_id . '/navTabId/__MODULE__" target="ajaxTodo" callback="'.$callback.'">恢复</a>';
			break;
		case 2 :
			$info = '<a href="__URL__/pass/a_id/' . $a_id . '/navTabId/__MODULE__" target="ajaxTodo" callback="'.$callback.'">批准</a>';
			break;
		case 1 :
			$info = '<a href="__URL__/forbid/a_id/' . $a_id . '/navTabId/__MODULE__" target="ajaxTodo" callback="'.$callback.'">禁用</a>';
			break;
		case - 1 :
			$info = '<a href="__URL__/recycle/a_id/' . $a_id . '/navTabId/__MODULE__" target="ajaxTodo" callback="'.$callback.'">还原</a>';
			break;
	}
	return $info;
}

/**
 +----------------------------------------------------------
 * 获取登录验证码 默认为4位数字
 +----------------------------------------------------------
 * @param string $fmode 文件名
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function build_verify($length = 4, $mode = 1) {
	return rand_string ( $length, $mode );
}


function getGroupName($id) {
	if ($id == 0) {
		return '无上级组';
	}
	if ($list = F ( 'groupName' )) {
		return $list [$id];
	}
	$dao = D ( "Role" );
	$list = $dao->select ( array ('field' => 'id,name' ) );
	foreach ( $list as $vo ) {
		$nameList [$vo ['id']] = $vo ['name'];
	}
	$name = $nameList [$id];
	F ( 'groupName', $nameList );
	return $name;
}
function sort_by($array, $keyname = null, $sortby = 'asc') {
	$myarray = $inarray = array ();
	# First store the keyvalues in a seperate array
	foreach ( $array as $i => $befree ) {
		$myarray [$i] = $array [$i] [$keyname];
	}
	# Sort the new array by
	switch ($sortby) {
		case 'asc' :
			# Sort an array and maintain index association...
			asort ( $myarray );
			break;
		case 'desc' :
		case 'arsort' :
			# Sort an array in reverse order and maintain index association
			arsort ( $myarray );
			break;
		case 'natcasesor' :
			# Sort an array using a case insensitive "natural order" algorithm
			natcasesort ( $myarray );
			break;
	}
	# Rebuild the old array
	foreach ( $myarray as $key => $befree ) {
		$inarray [] = $array [$key];
	}
	return $inarray;
}

/**
	 +----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码
 * 默认长度6位 字母和数字混合 支持中文
	 +----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
	 +----------------------------------------------------------
 * @return string
	 +----------------------------------------------------------
 */
function rand_string($len = 6, $type = '', $addChars = '') {
	$str = '';
	switch ($type) {
		case 0 :
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		case 1 :
			$chars = str_repeat ( '0123456789', 3 );
			break;
		case 2 :
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
			break;
		case 3 :
			$chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		default :
			// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
			$chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
			break;
	}
	if ($len > 10) { //位数过长重复字符串一定次数
		$chars = $type == 1 ? str_repeat ( $chars, $len ) : str_repeat ( $chars, 5 );
	}
	if ($type != 4) {
		$chars = str_shuffle ( $chars );
		$str = substr ( $chars, 0, $len );
	} else {
		// 中文随机字
		for($i = 0; $i < $len; $i ++) {
			$str .= msubstr ( $chars, floor ( mt_rand ( 0, mb_strlen ( $chars, 'utf-8' ) - 1 ) ), 1 );
		}
	}
	return $str;
}
function pwdHash($password, $type = 'md5') {
	return hash ( $type, $password );
}

/* zhanghuihua */
function percent_format($number, $decimals=0) {
	return number_format($number*100, $decimals).'%';
}
/**
 * 动态获取数据库信息
 * @param $tname 表名
 * @param $where 搜索条件
 * @param $order 排序条件 如："id desc";
 * @param $count 取前几条数据 
 */
function findList($tname,$where="", $order, $count){
	$m = M($tname);
	if(!empty($where)){
		$m->where($where);
	}
	if(!empty($order)){
		$m->order($order);
	}
	if($count>0){
		$m->limit($count);
	}
	return $m->select();
}
function findById($name,$id){
	$m = M($name);
	return $m->find($id);
}

function attrById($name, $attr, $id){
	$m = M($name);
	$a = $m->where('id='.$id)->getField($attr);
	return $a;
}


function findBy($name,$where,$field=''){
	$m = M($name);
	if($field=""){
		return $m->where($where)->find();
	}
	else{
		return $m->where($where)->field($field)->find();
	}
}


function ShowProduct($id){	$m = M('P');  $data = $m->where(array("p_id"=>$id))->find();	return $data;}

function ShowMPProduct($id){  
    $m = M('Member_p'); 
    $data = $m->where(array("p_id"=>$id))->find(); 
    return $data;
}

function ShowDataTu($id,$zid){
	$m = M('Data_tu');
	$data = $m->where("uid=".$id." and z_id=".$zid)->find();
	return $data;
}


function ShowUser($id){

	$m = M('User');

	$data = $m->where("id='".$id."'")->find();

	return $data;

}

//商家
function ShowMemberUser($id){

	$m = M('Member');

	$data = $m->where("id='".$id."'")->find();

	return $data;

}


//战略
function ShowZMember($id){
	$m = M('Z_member');
	$data = $m->where("id='".$id."'")->find();
	return $data;
}


function showMemberGroup($a){

	if($a==0){

	 return "普通创富客";	

	}else if($a==1){

	 return "<span style='color:#ff0000'>VIP创富客</span>";		

	}else if($a==2){

	 return "<span style='color:#ff0000'>战略伙伴</span>";		

	}else if($a==3){

	 return "预留权限";		

	}else{

	return "未定创富客";	

	}

}

function getTotalY($uid=0){
	$model = M( "Subscribe_record" );
	if($uid){
		return $count = $model->where("s_userid=".$uid." and s_flag<>'' ")->sum('s_y_price');
	}else{
		return $count = $model->where("s_flag<>'' ")->sum('s_y_price');
	}
}


function getTotalYB($uid=0){
	$model = M( "Subscribe_record" );
	if($uid){
		return $count = $model->where("s_userid=".$uid." and s_flag<>'' and s_refund=1")->sum('s_y_price');
	}else{
		return $count = $model->where("s_flag<>'' and s_refund=1 ")->sum('s_y_price');
	}
}



function getSubParent($id){
	$model = M( "Subscribe" );
	return $model->where("s_id=".$id." or s_parentid=".$id)->select();
}


function getMPSubParent($id){
	$model = M( "Member_c" );
	return $model->where("c_id=".$id." or c_parentid=".$id)->select();
}


//短信发送
//返回1为发送成功！
function sms_sending($mobile,$content){
	$content=preg_replace("/\s/","",$content);
	
	
    $content = iconv("UTF-8","gb2312",$content);
    $url="http://sms.webchinese.cn/web_api/?Uid=wanqili&Key=511cdc13bb2a43b24e7d&smsMob={$mobile}&smsText={$content}";
    if(function_exists('file_get_contents'))
    {
        $file_contents = file_get_contents($url);
    }
    else
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
    }
    //echo $url;
    //echo $file_contents;
    return $file_contents;
}



function productPercent($id){
    //项目成交率
	//$model = M ("Subscribe");
	//$total_count = $model->where("s_parentid=0  and s_pid=".$id)->count();
	//$c_count = $model->where("s_parentid=0 and s_flag<>'' and  s_pid=".$id)->count();
	//return  round($c_count/$total_count,4)*100 .'%';

    //项目成交率  与商家一致
	$model = M ("Member_c");
    $total_count = $model->where("c_parentid=0  and c_pid=".$id)->count();
    $c_count = $model->where("c_parentid=0 and c_success<>'' and c_pid=".$id)->count();
    if($total_count == 0 || $c_count==0 ) return "新项目上线";
    return  round($c_count/$total_count,4)*100 .'%';

}


function strLength($str,$charset='utf-8'){
    if($charset=='utf-8') $str = iconv('utf-8','gb2312',$str);
    $num = strlen($str);
    $cnNum = 0;
    for($i=0;$i<$num;$i++){
        if(ord(substr($str,$i+1,1))>127){
            $cnNum++;
            $i++;
        }
    }
    $enNum = $num-($cnNum*2);
    $number = ($enNum/2)+$cnNum;
    return ceil($number);
}

        //群发短信发送
        function groupSend($id) {   //sms id
            //不设置时间限制
            set_time_limit(0);
            $sms = M('Sms');
	        $smsRecord = M("Sms_record");            
            $mapsms = array();
            $mapsms['typeid'] = array('eq',0);
            $mapsms['flag'] = array('eq',2);
            //$mapsms['status'] = array('eq',1);

            $mapsms['id'] = $id;

            $smsList = $sms->where($mapsms)->select();
            foreach($smsList as $v){
                $customer = M('Member_c');
                $map = array();
                $map['c_pid'] = array('eq',$v['c_pid']);
                $map['c_parentid'] = array('eq',0);                
                if(!empty($v['c_category'])){ //为空时代表所有分类
                    $map['c_category'] = array('eq',$v['c_category']);
                }

                $member = M('Member');
                //判断费用
                $user = $member->where("username='".$v['username']."'")->field("smsNum,money")->find();
                if(!$user){
                    continue;
                }

                //if($user['money'] < C("SMS_PRICE") ){ //余额不足
                   // continue;
                //}

                if($user['smsNum'] <=0  ){ //短信不足
                    continue;
                }                

                $customerList = $customer->where($map)->select();
                //以客户注册时间判断
                foreach($customerList as $v1) {

                	$vo = $smsRecord->where("s_sid=".$v['id']." and s_cid=".$v1['c_id']." and s_pid=".$v['c_pid'])->field("s_id")->find();
                	if($vo){
                        continue; //已经发送过了
                	}

                    $tmp = $v1['c_addtime']; //客户注册时间
                    
                    if( time() > $tmp ){ 
                        if(!empty($v1['c_mobile'])) {
                            //替换
                            $tmp3 = str_replace ( '{$姓名}', $v1['c_customer'], $v['content'] ); 

                            //判断字数
                            $aa = 1;
                            $bb = C("SMS_PRICE");
                            $cc = ceil(mb_strlen($tmp3, 'UTF-8')/C("SMS_LENGTH"));                            
                            $aa = $aa*$cc;
                            $bb = $bb*$cc;  

                            $result = sms_sending($v1['c_mobile'],$tmp3);

                            //发送成功后，修改该短信列状态
                            if(empty($v1['c_order_sms'])){
                                $tmp2 = $v['id'];
                            }else{
                                $tmp2 = $v1['c_order_sms'] . ',' . $v['id'];
                            }
                            $customer->where('c_id =' . $v1['c_id'])->save(array('c_order_sms' => $tmp2));

                            //条数,扣费 状态：正在发送
                            $Model = new Model();
                            $Model->execute("update ".C("DB_PREFIX")."sms set money=money+".$bb." ,status=1 where id=".$v['id']);

                            //扣费 member
                            $Model->execute("update ".C("DB_PREFIX")."member set smsNum=smsNum-".$aa." ,money=money-".$bb." where username='".$v['username']."'");

                            //记录发送
                            $data = array(
                            	's_sid'=>$v['id'],
                            	's_cid'=>$v1['c_id'],
                                's_mid'=>$v1['c_mid'],                            	
                            	's_mobile'=>$v1['c_mobile'],
                            	's_pid'=>$v['c_pid'],
                            	's_typeid'=>0,
                            	's_content'=>$v['content'],
                                's_num'=>$aa,                            	
                            	's_addtime'=>time(),
                            );
                            $smsRecord->add($data);

                        }       
                    }
                }

                //状态：已发送
                $Model = new Model();
                $Model->execute("update ".C("DB_PREFIX")."sms set status=2 where id=".$v['id']);
            }



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


/**
** 截取中文字符串
**/
 function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
    if(function_exists("mb_substr")){
        $slice= mb_substr($str, $start, $length, $charset);
    }elseif(function_exists('iconv_substr')) {
        $slice= iconv_substr($str,$start,$length,$charset);
    }else{
        $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
        $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
        $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
        $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }    
        $fix='';
        if(strlen($slice) < strlen($str)){
            $fix='...';
        }
        return $suffix ? $slice.$fix : $slice;
 }


 function utf8_str_split($str, $split_len = 1)
{
    if (!preg_match('/^[0-9]+$/', $split_len) || $split_len < 1)
        return FALSE;
 
    $len = mb_strlen($str, 'UTF-8');
    if ($len <= $split_len)
        return array($str);
 
    preg_match_all('/.{'.$split_len.'}|[^\x00]{1,'.$split_len.'}$/us', $str, $ar);
 
    return $ar[0];
}







?>

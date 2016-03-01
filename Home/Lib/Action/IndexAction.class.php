<?php







class IndexAction extends Action {

    function _initialize() {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
             echo '此功能只能在微信浏览器中使用';exit;
        }
        import('@.ORG.Util.Cookie');
	}
	// 框架首页

	public function index() {

		if($this->_param('token') && $this->_param('token')){
			Cookie::set('token_'.$this->_param('id'),$this->_param('token'),C("cookie_time"));
			Cookie::set('wecha_id_'.$this->_param('id'),$this->_param('wecha_id'),C("cookie_time"));
		}


		$time = time();

		$model = M ( "Vote");

		$vo = $model->where ("id = ". $this->_param('id') ." and status=1 and statdate<".$time." and enddate>".$time  )->find();



		if(!$vo){

			$this->error('投票不存在，或者过期！');

			exit();

		}


		//member expried
		$member = M("Member")->where('m_id='.$vo['mid'])->find();
		$m_start = strtotime($member['m_startdate']);
		$m_end = strtotime($member['m_enddate']);
		if($m_start > time() || $m_end < time()){
			$this->error('账号过期或未开放，请联系管理员！');
			exit();
		}
		if($member['m_status'] == 0){
			$this->error('账号已停用，请联系管理员！');
			exit();
		}
		$this->assign ( 'member', $member );


		if($member['m_web_auth'] == 1){
			$token = Cookie::get('token_'.$this->_param('id'));
			$wecha_id = Cookie::get('wecha_id_'.$this->_param('id'));
			if(empty($token) && empty($wecha_id)){
				if(empty($_GET['auth'])){ //web网页授权
					header('Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$member['m_appid'].'&redirect_uri='.C("site_url").'/oauth2.php&response_type=code&scope=snsapi_userinfo&state='.$this->_param('id').'A'.$member['m_id'].'#wechat_redirect');
					exit;
				}
			}
		}


		$vo['uptime'] = $vo['enddate'] * 1000;

		$count = M ( "Form")->where("vid=".$this->_param('id'))->count();

		$vo['person']  = $count;

		$vo['tickets'] = 0;



		$Model = new Model();

		$vo1 = $Model->query("select sum(ticket) as a  from ".C("DB_PREFIX")."form  where vid=".$this->_param('id'));

		 if($vo1){

			   $vo['tickets'] = $vo1[0]['a'];

		 }





		$this->assign ( 'vo', $vo );



		//add click

		$Model = new Model();

		$Model->execute("update ".C("DB_PREFIX")."vote  set count=count+1 where id=".$this->_param('id'));



		//ad

		$ad = M("Ad")->where("vid=".$this->_param('id').' and display=1')->limit("0,10")->select();

		$this->assign ( 'ad', $ad );



		$ad1 = M("Ad")->where("vid=".$this->_param('id').' and display=1')->find();

		$this->assign ( 'ad1', $ad1 );







		//列表过滤器，生成查询Map对象

				$map = array();

				$map['vid'] = array('eq',$this->_param("id"));

				$map['status'] = array('eq',1);



				//读取数据库模块列表生成菜单项

				$model = M ("Form" );



				//排序字段 默认为主键名

				if (!empty ( $_REQUEST ['_order'] )) {

					$order = $_REQUEST ['_order'];

				} else {

					$order = ! empty ( $sortBy ) ? $sortBy : $model->getPk ();

				}


				//排序方式默认按照倒序排列

				//接受 sost参数 0 表示倒序 非0都 表示正序

				if (isset ( $_REQUEST ['_sort'] )) {

		//			$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';

					$sort = $_REQUEST ['_sort'] == 'asc' ? 'asc' : 'desc'; //zhanghuihua@msn.com

				} else {

					$sort = $asc ? 'asc' : 'desc';

				}

				//取得满足条件的记录数

				$count = $model->where ( $map )->count ();

				if ($count > 0) {

					import ( "@.ORG.Util.Page" );
					//创建分页对象
					if (! empty ( $_REQUEST ['listRows'] )) {
						$listRows = $_REQUEST ['listRows'];
					} else {
						$listRows = '';
					}

					$p = new Page ( $count, $listRows );

					//分页查询数据

					$voList = $model->where($map)->order( "`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->select ( );

					$list = array();
					$tmp = array();
					foreach($voList as $val){
						$tmp[$val[id]] = $val[id];
						$list[] = $val;
					}
					$tmp2 = $this->_param('s');
					if(!empty($tmp2)){
						if(!array_search($tmp2,$tmp)){
							$tmp3 = M ("Form" )->where('id='.$tmp2)->find();
							$list[] = $tmp3;
						}
					}



					//echo $model->getlastsql();

					//分页跳转的时候保证查询条件

					foreach ( $map as $key => $val ) {

						if (! is_array ( $val )) {

							$p->parameter .= "$key=" . urlencode ( $val ) . "&";

						}

					}

					//分页显示

					$page = $p->show ();

					//列表排序显示

					$sortImg = $sort; //排序图标

					$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示

					$sort = $sort == 'desc' ? 1 : 0; //排序方式



					//模板赋值显示

					$this->assign ( 'list', $list );

					$this->assign ( 'sort', $sort );

					$this->assign ( 'order', $order );

					$this->assign ( 'sortImg', $sortImg );

					$this->assign ( 'sortType', $sortAlt );

					$this->assign ( "page", $page );

				}





				//zhanghuihua@msn.com

				$this->assign ( 'totalCount', $count );

				$this->assign ( 'totalPages', $p->totalPages );

				$this->assign ( 'numPerPage', $p->listRows );

				$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);


				$subscribe = 0;
				//判断是否关注
				$wecha_id =  Cookie::get('wecha_id_'.$this->_param('id'));
				if($member['m_appid'] && $member['m_appsecret'] && $wecha_id && $member['m_isConnent']){
				   $res = json_decode(file_get_contents("access_token-".$member['m_appid'].".json"),true);

				  if(empty($res)){
				      require_once "../Public/weixin/jssdk.php";
				      $jssdk = new JSSDK($member['m_appid'], $member['m_appsecret']);
				      $signPackage = $jssdk->GetSignPackage();
					  $res = json_decode(file_get_contents("access_token-".$member['m_appid'].".json"),true);
				   }

					//var_dump($res);
					if(!isset($res['errcode'])){
						$access_token = $res['access_token'];
						$res1 = json_decode(httpGet("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$wecha_id),true);
					//var_dump($res1);
					if(!isset($res1['errcode'])){
							//Cookie::set('token_'.$this->_param('id'),'',-3600);
							//Cookie::set('wecha_id_'.$this->_param('id'),'',-3600);
							$subscribe = $res1['subscribe'];
						}elseif($res1['errcode'] =="48001"){  //微信 未认证
							$subscribe = 1;
						}elseif($res1['errcode'] =="40001" || $res1['errcode'] =="42001"){
						    //access_token is invalid or not latest hint
						    //delete  access file
							unlink('access_token-'.$member['m_appid'].".json");
							unlink('jsapi_ticket-'.$member['m_appid'].".json");
							require_once "../Public/weixin/jssdk.php";
							$jssdk = new JSSDK($member['m_appid'], $member['m_appsecret']);
							$signPackage = $jssdk->GetSignPackage();
							$res = json_decode(file_get_contents("access_token-".$member['m_appid'].".json"),true);
							$access_token = $res['access_token'];
							$res1 = json_decode(httpGet("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$wecha_id),true);
							if(!isset($res1['errcode'])){
								$subscribe = $res1['subscribe'];
							}
						}
					}
				}
			   $this->assign ( 'subscribe', $subscribe);

				$template = M("Template")->where("status=1 and id=".$vo['template'])->find();
			    $this->assign ( 'template', $template);
				$this->display($template['folder'].'/index');

	}



public function search() {



		$time = time();

		$model = M ( "Vote");

		$vo = $model->where ("id = ". $this->_param('id') ." and status=1 and statdate<".$time." and enddate>".$time  )->find();



		if(!$vo){

			echo "[]";

		}



		$list = M("Form")->where("vid=".$this->_param('id')." and status=1 and username like '%".$this->_param('term')."%'")->limit("0,10")->select();



		$str="";

		foreach($list as $v){

			$str.= json_encode($v['num']."号 ".$v['username']).",";

		}

		echo "[".trim($str,",")."]";





	}





public function search2() {

		$time = time();

		$model = M ( "Vote");

		$vo = $model->where ("id = ". $this->_param('id') ." and status=1 and statdate<".$time." and enddate>".$time  )->find();

		if(!$vo){

			echo "bb";

		}

		$name = $this->_param('name');
		$id = intval($name);
		$vo = M("Form")->where("vid=".$this->_param('id')." and status=1 and num = '".$id."'")->find();
		if($vo){
			echo $vo['id']."|1";
		}else{
			echo "bb";
		}
	}





	public function rank() {



		$time = time();

		$model = M ( "Vote");

		$vo = $model->where ("id = ". $this->_param('id') ." and status=1 "  )->find();



		if(!$vo){

			$this->error('投票不存在！');

			exit();

		}



		$vo['uptime'] = $vo['enddate'] * 1000;



		$count = M ( "Form")->where("vid=".$this->_param('id'))->count();

		$vo['person']  = $count;

		$vo['tickets'] = 0;



		$Model = new Model();

		$vo1 = $Model->query("select sum(ticket) as a  from ".C("DB_PREFIX")."form  where vid=".$this->_param('id'));

		 if($vo1){

			   $vo['tickets'] = $vo1[0]['a'];

		 }



		$this->assign ( 'vo', $vo );





		$list = M("Form")->where("vid=".$this->_param('id').' and status=1')->order("ticket desc")->limit("0,20")->select();

		$this->assign ( 'list', $list );






		$template = M("Template")->where("status=1 and id=".$vo['template'])->find();
	    $this->assign ( 'template', $template);
		$this->display($template['folder'].'/rank');

	}







	public function pageData() {



				//列表过滤器，生成查询Map对象

				$map = array();

				$map['vid'] = array('eq',$this->_param("id"));

				$map['status'] = array('eq',1);


				$time = time();
				$model = M ( "Vote");
				$vo = $model->where ("id = ". $this->_param('id') ." and status=1 and statdate<".$time." and enddate>".$time  )->find();
				$this->assign ( 'vo', $vo );


				//member expried
				$member = M("Member")->where('m_id='.$vo['mid'])->find();
				$m_start = strtotime($member['m_startdate']);
				$m_end = strtotime($member['m_enddate']);
				if($m_start > time() || $m_end < time()){
					$this->error('账号过期或未开放，请联系管理员！');
					exit();
				}
				if($member['m_status'] == 0){
					$this->error('账号已停用，请联系管理员！');
					exit();
				}
				$this->assign ( 'member', $member );



				//读取数据库模块列表生成菜单项

				$model = M ("Form" );



				//排序字段 默认为主键名

				if (!empty ( $_REQUEST ['_order'] )) {

					$order = $_REQUEST ['_order'];

				} else {

					$order = ! empty ( $sortBy ) ? $sortBy : $model->getPk ();

				}







				//排序方式默认按照倒序排列

				//接受 sost参数 0 表示倒序 非0都 表示正序

				if (isset ( $_REQUEST ['_sort'] )) {

		//			$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';

					$sort = $_REQUEST ['_sort'] == 'asc' ? 'asc' : 'desc'; //zhanghuihua@msn.com

				} else {

					$sort = $asc ? 'asc' : 'desc';

				}

				//取得满足条件的记录数

				$count = $model->where ( $map )->count ();

				if ($count > 0) {

					import ( "@.ORG.Util.Page" );

					//创建分页对象
					if (! empty ( $_REQUEST ['listRows'] )) {

						$listRows = $_REQUEST ['listRows'];

					} else {

						$listRows = '';

					}

					$p = new Page ( $count, $listRows );

					//分页查询数据

					$voList = $model->where($map)->order( "`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->select ( );



					//echo $model->getlastsql();

					//分页跳转的时候保证查询条件

					foreach ( $map as $key => $val ) {

						if (! is_array ( $val )) {

							$p->parameter .= "$key=" . urlencode ( $val ) . "&";

						}

					}

					//分页显示

					$page = $p->show ();

					//列表排序显示

					$sortImg = $sort; //排序图标

					$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示

					$sort = $sort == 'desc' ? 1 : 0; //排序方式



					//模板赋值显示

					$this->assign ( 'list', $voList );

					$this->assign ( 'sort', $sort );

					$this->assign ( 'order', $order );

					$this->assign ( 'sortImg', $sortImg );

					$this->assign ( 'sortType', $sortAlt );

					$this->assign ( "page", $page );

				}





				//zhanghuihua@msn.com

				$this->assign ( 'totalCount', $count );

				$this->assign ( 'totalPages', $p->totalPages );

				$this->assign ( 'numPerPage', $p->listRows );

				$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);


				$this->assign ( "subscribe", $_GET['subscribe']);
				$template = M("Template")->where("status=1 and id=".$vo['template'])->find();
			    $this->assign ( 'template', $template);
				$this->display($template['folder'].'/pageData');

	}




	public function content() {



		$time = time();

		$model = M ( "Vote");

		$vo = $model->where ("id = ". $this->_param('id') ." and status=1 and statdate<".$time." and enddate>".$time  )->find();



		if(!$vo){

			$this->error('投票不存在，或者过期！');

			exit();

		}

		$count = M ( "Form")->where("vid=".$this->_param('id'))->count();

		$vo['person']  = $count;

		$vo['tickets'] = 0;

		$Model = new Model();

		$vo1 = $Model->query("select sum(ticket) as a  from ".C("DB_PREFIX")."form  where vid=".$this->_param('id'));

		 if($vo1){

			   $vo['tickets'] = $vo1[0]['a'];

		 }

		//member expried
		$member = M("Member")->where('m_id='.$vo['mid'])->find();
		$m_start = strtotime($member['m_startdate']);
		$m_end = strtotime($member['m_enddate']);
		if($m_start > time() || $m_end < time()){
			$this->error('账号过期或未开放，请联系管理员！');
			exit();
		}
		if($member['m_status'] == 0){
			$this->error('账号已停用，请联系管理员！');
			exit();
		}
		$this->assign ( 'member', $member );


				$subscribe = 0;
				//判断是否关注
				$wecha_id =  Cookie::get('wecha_id_'.$this->_param('id'));
				if($member['m_appid'] && $member['m_appsecret'] && $wecha_id && $member['m_isConnent']){
				   $res = json_decode(file_get_contents("access_token-".$member['m_appid'].".json"),true);

				  if(empty($res)){
				      require_once "../Public/weixin/jssdk.php";
				      $jssdk = new JSSDK($member['m_appid'], $member['m_appsecret']);
				      $signPackage = $jssdk->GetSignPackage();
					  $res = json_decode(file_get_contents("access_token-".$member['m_appid'].".json"),true);
				   }

					//var_dump($res);
					if(!isset($res['errcode'])){
						$access_token = $res['access_token'];
						$res1 = json_decode(httpGet("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$wecha_id),true);
					//var_dump($res1);
					if(!isset($res1['errcode'])){
							//Cookie::set('token_'.$this->_param('id'),'',-3600);
							//Cookie::set('wecha_id_'.$this->_param('id'),'',-3600);
							$subscribe = $res1['subscribe'];
						}elseif($res1['errcode'] =="48001"){  //微信 未认证
							$subscribe = 1;
						}elseif($res1['errcode'] =="40001" || $res1['errcode'] =="42001"){
						    //access_token is invalid or not latest hint
						    //delete  access file
							unlink('access_token-'.$member['m_appid'].".json");
							unlink('jsapi_ticket-'.$member['m_appid'].".json");
							require_once "../Public/weixin/jssdk.php";
							$jssdk = new JSSDK($member['m_appid'], $member['m_appsecret']);
							$signPackage = $jssdk->GetSignPackage();
							$res = json_decode(file_get_contents("access_token-".$member['m_appid'].".json"),true);
							$access_token = $res['access_token'];
							$res1 = json_decode(httpGet("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$wecha_id),true);
							if(!isset($res1['errcode'])){
								$subscribe = $res1['subscribe'];
							}
						}
					}
				}
			   $this->assign ( 'subscribe', $subscribe);

		if($member['m_isConnent'] === '0'){
			$this->assign ( 'subscribe', 1 );
		}

		if(!empty($_GET['keyword'])){
		   $where = "vid=".$this->_param('id')." and (num = '".trim($this->_param("keyword"))."' or username='".trim($this->_param("keyword"))."')";
		}else{
		   $where = "id=".$this->_param('fid');
		}
		$form = M ( "Form")->where($where)->find();
		
		//form中增加对用户的排名数据
		$vid = $this->_param('id');
		$fid = $this->_param('fid');
		$table_name = M('form')->getTableName();
		$query_sql = '# 获取ID排名、前一位排名票数差
			select lpm,F.ticket-E.ticket as cha from (
			    #获取ID当前排名及票数
			    select M.pm as lpm , M.ticket from (
			        #排序，获取队列ID 票数 排名
			        select A.ticket,A.id,@rank:=@rank+1 as pm
			        from (
			            select id,vid,ticket from '.$table_name.'  where vid='.$vid.' order by ticket DESC,id
			        ) A , (select @rank:=0) B 
			    ) M where M.id='.$fid.'
			)E,(
			    #排序 获取队列票数及排名
			    select C.ticket,@rankb:=@rankb+1 as pm
			        from (
			            select vid,ticket from lr_form where vid='.$vid.' order by ticket DESC
			        ) C , (select @rankb:=0) D  
			)F where F.pm = E.lpm-1;
			#select timestampdiff(microsecond,@d,now());';
		//cha = array('lpm'=>LPM,'cha'=>CHA); LPM为fid当前在vid中的排名，CHA为fid距离上一名排名票数
		$chk = M()->query($query_sql);
		$log = new Log();
		$log->write('chk :'.json_encode($chk));
		$log->write('query sql :'.$query_sql);
		if ($chk==null){
			$cha = array('lpm'=>'1','cha'=>'0');
		}else{
			$cha = $chk[0];
		}
		$this->assign('cha',$cha);
		
		$this->assign ( 'form', $form );

		//ad

		$ad = M("Ad")->where("vid=".$this->_param('id').' and display=1')->limit("0,10")->select();

		$this->assign ( 'ad', $ad );



		$this->assign ( 'vo', $vo );



		$ad1 = M("Ad")->where("vid=".$this->_param('id').' and display=1')->find();

		$this->assign ( 'ad1', $ad1 );



		$template = M("Template")->where("status=1 and id=".$vo['template'])->find();
	    $this->assign ( 'template', $template);
		$this->display($template['folder'].'/content');



	}









	public function signup() {



		$time = time();

		$model = M ( "Vote");

		$vo = $model->where ("id = ". $this->_param('id') ." and status=1 and statdate<".$time." and enddate>".$time  )->find();



		if(!$vo){

			$this->error('投票不存在，或者过期！');

			exit();

		}


		//member expried
		$member = M("Member")->where('m_id='.$vo['mid'])->find();
		$m_start = strtotime($member['m_startdate']);
		$m_end = strtotime($member['m_enddate']);
		if($m_start > time() || $m_end < time()){
			$this->error('账号过期或未开放，请联系管理员！');
			exit();
		}
		if($member['m_status'] == 0){
			$this->error('账号已停用，请联系管理员！');
			exit();
		}
		$this->assign ( 'member', $member );



		$tmp = M("Userinfo")->where("token='".$this->_param('token')."' and wecha_id='".$this->_param('wecha_id')."'")->find();

		if($tmp){
			Cookie::set('token_'.$this->_param('id'),$this->_param('token'),C("cookie_time"));
			Cookie::set('wecha_id_'.$this->_param('id'),$this->_param('wecha_id'),C("cookie_time"));
		}


		$this->assign ( 'subscribe', $_GET['subscribe'] );
		if($member['m_isConnent'] === '0'){
			$this->assign ( 'subscribe', 1 );
		}









		$count = M ( "Form")->where("vid=".$this->_param('id'))->count();

		$vo['count']  = $count;





		//ad

		$ad = M("Ad")->where("vid=".$this->_param('id').' and display=1')->limit("0,10")->select();

		$this->assign ( 'ad', $ad );



		$this->assign ( 'vo', $vo );



		$ad1 = M("Ad")->where("vid=".$this->_param('id').' and display=1')->find();

		$this->assign ( 'ad1', $ad1 );



		$template = M("Template")->where("status=1 and id=".$vo['template'])->find();
	    $this->assign ( 'template', $template);
		$this->display($template['folder'].'/signup');



	}





	public function record() {



		if(!$this->_param('sid')){

			header('Location: /User/');

			exit;

		}



		$model = M ( "Soli");

		$soli = $model->where ( "s_id=".$this->_param('sid') )->find();

		$this->assign ( 'soli', $soli );



		$this->display ();

	}











public function vote(){

		$time = time();
		$vote = M("Vote")->where ("id = ". $this->_param('id') ." and status=1 and statdate<".$time." and enddate>".$time)->field('wx_url,prevent,cknums,mid,jump,hour,allperson,nativeplace,v_startdate,v_enddate,isVerify')->find();
		if(!$vote){
			$this->error('投票不存在，或者过期！');
			exit();
		}


		$tmp3 = time();
		if($vote['v_startdate'] > 0 && $vote['v_enddate'] > 0){
			if($vote['v_startdate']< $tmp3 && $vote['v_enddate'] > $tmp3){

			}else{
				$this->error('不能投票，投票时间未开放!');
				exit();
			}
		}

		if($vote['isVerify']){
			$verify = $_GET['verify'];
			if(md5($verify) != $_SESSION['lrVerify']){
				$this->error('投票失败，验证码错误!');
				exit();
			}
		}


		$token = Cookie::get('token_'.$this->_param('id'));
		$wecha_id = Cookie::get('wecha_id_'.$this->_param('id'));
		$member = M("Member")->where('m_id='.$vote['mid'])->field('m_isConnent,m_appid,m_appsecret,m_wxname')->find();
		if( ($token && $wecha_id) || $member['m_isConnent'] === '0' ){
			$ip = get_client_ip();

			$dd = 3600*intval($vote['hour']);

			if($member['m_isConnent'] === '0'){ //不关注微信号，按ip判断

				if($vote['allperson']){
					$tmp1 =  M("Vote_record")->where('form_id='.$this->_param('vid').' and ip=\''.$ip.'\' and addtime+'.$dd.' >'.time())->count();
					if($tmp1>0){
						C('sitename',$member['m_wxname']);
						$this->error('投票失败，在规定时间内不能重复投给同一个人','__URL__/index/id/'.$this->_param('id').'.html');
					}
				}

				$tmp =  M("Vote_record")->where('vid='.$this->_param('id').' and ip=\''.$ip.'\'  and addtime+'.$dd.' >'.time())->count();
				if($tmp > $vote['cknums'] ){
					C('sitename',$member['m_wxname']);
					$this->error('投票失败，您已超过每天投票次数','__URL__/index/id/'.$this->_param('id').'.html');
				}


			}else{
				$tmp =  M("Vote_record")->where('vid='.$this->_param('id').' and token=\''.$token.'\' and wecha_id=\''.$wecha_id.'\' and addtime+'.$dd.' >'.time())->count();

			if($vote['prevent']){
				//24点来计算
				date_default_timezone_set('PRC');
				$dd = strtotime(date("Y-m-d"));
				$dd2 = $dd+86399;
				$tmp =  M("Vote_record")->where('vid='.$this->_param('id').' and token=\''.$token.'\' and wecha_id=\''.$wecha_id.'\' and addtime>'.$dd.' and addtime<'.$dd2)->count();
				if($tmp >= 1 ){
					C('sitename',$member['m_wxname']);
					$this->error('投票失败，每天只能投一次！','__URL__/index/id/'.$this->_param('id').'.html');
				}

			}else{

				if($vote['allperson']){
					$tmp1 =  M("Vote_record")->where('form_id='.$this->_param('vid').' and token=\''.$token.'\' and wecha_id=\''.$wecha_id.'\' and addtime+'.$dd.' >'.time())->count();
					if($tmp1>0){
						C('sitename',$member['m_wxname']);
						$this->error('投票失败，在规定时间内不能重复投给同一个人','__URL__/index/id/'.$this->_param('id').'.html');
					}
				}

				if($tmp >= $vote['cknums']){
					C('sitename',$member['m_wxname']);
					$this->error('投票失败，您已超过每天投票次数','__URL__/index/id/'.$this->_param('id').'.html');
				}
			}

			}

			//投票地区判断
			if(!empty($vote['nativeplace'])){
				$currnet_ip = get_client_ip();
				$result = file_get_contents("http://api.map.baidu.com/location/ip?ip=".$currnet_ip."&coor=bd09ll&ak=MxEpomRtabSjLAEe1KDFjwo5");
				$result = json_decode($result,true);
				if($result['status'] ==0){
					$location = $result['content']['address_detail'];
					$nat = M("Sys_enum")->where("evalue='".$vote['nativeplace']."'")->field("ename")->find();
					if(!in_array($nat['ename'],$location)){
						C('sitename',$member['m_wxname']);
						$this->error('投票失败，所在地区不在投票范围内','__URL__/index/id/'.$this->_param('id').'.html');
					}
				}
			}


			//add click
			$Model = new Model();
			$result1 = $Model->execute("update ".C("DB_PREFIX")."form  set ticket=ticket+1 where status=1 and id=".$this->_param('vid'));

			//weixin data
			if($member['m_appid'] && $member['m_appsecret'] && $wecha_id && $member['m_isConnent'] ){
			    $res = json_decode(file_get_contents("access_token-".$member['m_appid'].".json"),true);
	            if ($res['expire_time'] < time()) { //access_token 缓存时间
					$res = json_decode(httpGet("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$member['m_appid']."&secret=".$member['m_appsecret']),true);
	            }
				if(!isset($res['errcode'])){
					$access_token = $res['access_token'];
					$res1 = json_decode(httpGet("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$wecha_id),true);
					if(!isset($res1['errcode'])){
						$userinfo = M("Userinfo")->where("wecha_id='".$wecha_id."'")->find();
						$us['token'] = $token;
						$us['wecha_id'] = $wecha_id;
						$us['mid'] = $vote['mid'];
						$us['wechaname'] = $res1['nickname'];
						$us['sex'] = $res1['sex'];
						$us['wechahead'] = $res1['headimgurl'];
						$us['city'] = $res1['city'];
						$us['province'] = $res1['province'];
						$us['country'] = $res1['country'];
						$us['subscribe_time'] = $res1['subscribe_time'];
						if($userinfo){
							$condition['id'] = $userinfo['id'];
							M("Userinfo")->where($condition)->data($us)->save();
						}else{
							M("Userinfo")->data($us)->add();
						}
					}
				}
			}

            if($result1){
                    $record = M("Vote_record");
                    $data['form_id'] = $this->_param('vid');
                    $data['vid'] = $this->_param('id');
                    $data['token'] = $token;
                    $data['wecha_id'] = $wecha_id;
                    $data['addtime'] = time();
                    $data['ip'] = $ip;
                    $data['mid'] = $vote['mid'];
                    $record->add($data);
                    if($vote['jump']){
                        C('sitename',$member['m_wxname']);
                        $this->success('投票成功！',$vote['jump']);
                    }else{
                        C('sitename',$member['m_wxname']);
                        $this->success('投票成功！','__URL__/index/id/'.$this->_param('id').'.html');
                    }
                }else{
                        C('sitename',$member['m_wxname']);
                        $this->error('投票失败！选手未审核','__URL__/index/id/'.$this->_param('id').'.html');
                    }
		}else{

			$this->error('请先关注微信公众号',$vote['wx_url']);

		}



}




public function addView(){
	$model = M ("Form");
	$model->where("id=".$this->_param("id"))->setInc('view');
}




public function insertForm(){



		$model = M ("Form");



		if(!$this->_param("username")){

			$this->error('报名失败！用户名不能为空！');

		}



        $tmp = $model->where("tel='".$this->_param("tel")."' and vid='".$this->_param("vid")."'")->find();
		if($tmp){

			$this->error('报名失败！该电话已存在！');

		}



		if(!$model->create()) {



			$this->error($model->getError());



		}else{

			$num = M("Form")->where("vid=".$this->_param("vid"))->max('num');
			if(!empty($num)){
				$num = intval($num) + 1;
				$model->num = $num;
			}else{
				$model->num = 1;
			}

			$vote = M("Vote")->where("id=".$this->_param("vid"))->field("isCheck")->find();
			$model->status = empty($vote['isCheck'])?1:0;

            $model->info = str_replace(array("\r\n", "\r", "\n"), '',$this->_param("info"));
			$model->addtime = time();

			if($result	 =	 $model->add()) {



				if($this->_param("is_ajax")){



					echo '{"message":"添加成功!","code":"1"}';



					exit;

				}



				if($this->_param("jumpUrl")){



					$jumpUrl = $this->_param("jumpUrl");



					$this->success('报名成功！',$jumpUrl);



				}else{



					$this->success('报名成功,等待审核！！');



				}







			}else{







				if($this->_param("is_ajax")){



					echo '{"message":"报名失败!","code":"0"}';



					exit;



				}







				$this->error('报名失败！');



			}



	    }



}















//统计



public function analyse(){







	echo '{result:1,msg:"请求成功"}';







}



















}







?>

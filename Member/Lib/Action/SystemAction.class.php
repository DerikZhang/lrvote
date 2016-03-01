<?php

class SystemAction extends CommonAction {



	function _filter(&$map){

	  

	}	

	

	// 框架首页

	public function index() {

		

		Cookie::set ( '_currentUrl_', __SELF__ );

		$this->display ();	



	}





	public function weixin() {

		$member = M("Member")->where("m_id=".$_SESSION[C('USER_AUTH_KEY')])->find();
	    $this->assign ( 'vo', $member );

		Cookie::set ( '_currentUrl_', __SELF__ );

		$this->display ();	

	}	

	








public function wxupdate(){

		$P = D("Member");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{

		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

			// 写入帐号数据

			if($result	 =	 $P->save()) {

				$this->success('修改成功！');

			}else{

				$this->error('修改失败！');

			}

	    }



}		






public function diyMenu() {



		        //列表过滤器，生成查询Map对象


				$map['pid'] = 0;
				$map['mid'] = $_SESSION[C('USER_AUTH_KEY')];

				//读取数据库模块列表生成菜单项

				$model = M ("Diymenu" );



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

					$p = new Page ( $count, 1000 );

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

				$this->assign ( 'numPerPage', $p->listRows );

				$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);



				Cookie::set ( '_currentUrl_', __SELF__ );

				$this->display ();	



	}





public function addMenu(){
	$list = M("Vote")->where("status=1 and mid=".$_SESSION[C('USER_AUTH_KEY')])->field("keyword,id")->select();
	$this->assign ( 'list', $list );

	$pp = M("Diymenu")->where("pid=0 and mid=".$_SESSION[C('USER_AUTH_KEY')])->field("id,title")->select();
	$this->assign ( 'pp', $pp );

	$this->display();
}



public function editMenu() {

	$list = M("Vote")->where("status=1 and mid=".$_SESSION[C('USER_AUTH_KEY')])->field("keyword,id")->select();
	$this->assign ( 'list', $list );

	$pp = M("Diymenu")->where("pid=0 and mid=".$_SESSION[C('USER_AUTH_KEY')])->field("id,title")->select();
	$this->assign ( 'pp', $pp );

	
	$model = M ( "Diymenu" );
	$id = $_REQUEST ["id"];
	$vo = $model->where("id = ".$id)->find();
	$this->assign ( 'vo', $vo );
	$this->display ();	
}



public function insertMenu(){
		$P = D("Diymenu");

		if(empty($_POST['pid'])){
			$tmp = $P->where("pid=0 and mid=".$_SESSION[C('USER_AUTH_KEY')])->count();
			if($tmp >= 3){
				$this->error('1级菜单最多只能开启3个！');
			}
		}

		if(!empty($_POST['pid'])){
			$tmp = $P->where("pid=".$_POST['pid']." and mid=".$_SESSION[C('USER_AUTH_KEY')])->count();
			if($tmp >= 5){
				$this->error('2级子菜单最多开启5个! ');
			}
		}

		if(!$P->create()) {
			$this->error($P->getError());
		}else{
			$P->mid = $_SESSION[C('USER_AUTH_KEY')];
		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			// 写入帐号数据
			if($result	 =	 $P->add()) {
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！');
			}
	    }
}	



public function updateMenu(){
		$P = D("Diymenu");
		if(!$P->create()) {
			$this->error($P->getError());
		}else{
		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			
			// 写入帐号数据
			if($result	 =	 $P->save()) {
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！');
			}
	    }
}

			
	public function deleteDiymenu() {
		$model = M ("Diymenu");
			$id = $_REQUEST ["id"];	
			if (isset ( $id )) {
				$list=$model->where ( "(id=$id or pid=$id) and mid=".$_SESSION[C('USER_AUTH_KEY')] )->delete();
				if ($list!==false) {
					$this->success ('删除成功！' );
				} else {
					$this->error ('删除失败！');
				}
			} 
	}


		
	public function greateMenu() {
		$str = array();
		$list = M ("Diymenu")->where("pid=0 and mid=".$_SESSION[C('USER_AUTH_KEY')])->select();
		foreach ($list as $value) {
			$list2 = M ("Diymenu")->where("pid=".$value['id'])->select();
			$tmp3 = array();
			foreach ($list2 as $value2) {
				$tmp2 = array();
				if($value2['typeid'] === "0"){
					$tmp2['type'] = "click";
					$tmp2['name'] = urlencode($value2['title']);
					$tmp2['key'] = urlencode($value2['keyword']);
				}
				if($value2['typeid'] === "1"){
					$tmp2['type'] = "view";
					$tmp2['name'] = urlencode($value2['title']);
					$tmp2['url'] = $value2['url'];
				}
				$tmp3[] = $tmp2;
			}
			$tmp[] = array("name"=>urlencode($value["title"]),"sub_button"=>$tmp3);
		}
		$str["button"] = $tmp;

		$content =  json_encode($str);
		$content = urldecode($content); 

		$member = M("Member")->where("m_id=".$_SESSION[C('USER_AUTH_KEY')])->field("m_appid,m_appsecret")->find();	
		if($member['m_appid'] && $member['m_appsecret'] ){
			$res = json_decode(httpGet("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$member['m_appid']."&secret=".$member['m_appsecret']),true);
			if(!isset($res['errcode'])){
				$access_token = $res['access_token'];

	            $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;

				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_URL, $url); 
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
				curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

				$res = curl_exec($ch);
				if (curl_errno($ch)) {
					echo 'Errno'.curl_error($ch);exit;
				}
				curl_close($ch);
				$res = json_decode($res,true);

				if(!empty($res['errcode'])){
					$this->error ('生成失败！'.$res['errcode']);
				}
				else{
					$this->success ('生成成功！' );
				}	
			}
			else{
				$this->error ('生成失败！'.$res['errcode']);
			}
		}
		else{
			$this->error ('生成失败！appid appsecret 为空！');
		}		
	}



public function reply() {



		        //列表过滤器，生成查询Map对象


				$map['mid'] = $_SESSION[C('USER_AUTH_KEY')];

				//读取数据库模块列表生成菜单项

				$model = M ("Reply" );



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

					$p = new Page ( $count, 1000 );

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

				$this->assign ( 'numPerPage', $p->listRows );

				$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);



				Cookie::set ( '_currentUrl_', __SELF__ );

				$this->display ();	



	}



public function replyInsert(){
		$P = D("Reply");

		if(empty($_POST['keyword'])){
			$tmp = $P->where("keyword='' and mid=".$_SESSION[C('USER_AUTH_KEY')])->count();
			if($tmp >= 1){
				$this->error('为空触发关键词,已存在！');
			}
		}
		if(!$P->create()) {
			$this->error($P->getError());
		}else{
			$P->mid = $_SESSION[C('USER_AUTH_KEY')];
			$P->addtime = time();
		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			// 写入帐号数据
			if($result	 =	 $P->add()) {
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！');
			}
	    }
}	

public function replyEdit() {
	$model = M ( "Reply" );
	$id = $_REQUEST ["id"];
	$vo = $model->where("id = ".$id)->find();
	$this->assign ( 'vo', $vo );
	$this->display ();	
}


public function replyUpdate(){
		$P = D("Reply");
		if(!$P->create()) {
			$this->error($P->getError());
		}else{
		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			
			// 写入帐号数据
			if($result	 =	 $P->save()) {
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！');
			}
	    }
}

			




	public function replyDelete() {
		$model = M ("Reply");
			$id = $_REQUEST ["id"];	
			if (isset ( $id )) {
				$condition = array ("mid"=>array("eq",$_SESSION[C('USER_AUTH_KEY')]),"id" => array ('in', explode ( ',', $id ) ) );
				$list=$model->where ( $condition )->delete();
				if ($list!==false) {
					$this->success ('删除成功！' );
				} else {
					$this->error ('删除失败！');
				}
			} 
	}



}

?>

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
		Cookie::set ( '_currentUrl_', __SELF__ );
		$this->display ();
	}

public function admin() {

		        //列表过滤器，生成查询Map对象
				$map = $this->_search ();
				if (method_exists ( $this, '_filter' )) {
					$this->_filter ( $map );
				}
				//读取数据库模块列表生成菜单项
				$model = M ("Administrator" );

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
				$this->assign ( 'numPerPage', $p->listRows );
				$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);

				Cookie::set ( '_currentUrl_', __SELF__ );
				$this->display ();

	}


public function update(){

		$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
	    require dirname(__FILE__).'/../../../infoconfig.php';

		$infoconfig['SITENAME'] = $this->_param('site_name');
		$infoconfig['site_url'] = $this->_param('site_url');
		$infoconfig['up_size'] = $this->_param('up_size');
		$infoconfig['up_exts'] = $this->_param('up_exts');
		$infoconfig['site_qq'] = $this->_param('site_qq');
		$infoconfig['site_tel'] = $this->_param('site_tel');
		$infoconfig['site_logo'] = $this->_param('site_logo');
		$infoconfig['EFFECTS'] = $this->_param('EFFECTS');
		$infoconfig['C_EFFECTS'] = $this->_param('C_EFFECTS');


		$infoconfig['site_name'] = $this->_param('site_name');
		$infoconfig['thumb'] = $this->_param('thumb');
		$infoconfig['thumb_width'] = $this->_param('thumb_width');
		$infoconfig['thumb_height'] = $this->_param('thumb_height');
		$infoconfig['site_title'] = $this->_param('site_title');
		$infoconfig['site_my'] = $this->_param('site_my');
		$infoconfig['server_topdomain'] = $this->_param('server_topdomain');
		$infoconfig['connectout'] = $this->_param('connectout');
		$infoconfig['site_mp'] = $this->_param('site_tel');
		$infoconfig['site_kfqq'] = $this->_param('site_qq');
		$infoconfig['site_email'] = $this->_param('site_email');
		$infoconfig['keyword'] = $this->_param('keyword');
		$infoconfig['content'] = $this->_param('content');
		$infoconfig['copyright'] = $this->_param('copyright');


		if (phpversion() > 5.0){
			file_put_contents(dirname(__FILE__).'/../../../infoconfig.php', ""."<?php\n \$infoconfig	= ".var_export($infoconfig,true).";\n?>");
		}

		//RUNTIME_FILE常量是入口文件中配置的runtimefile的路径及文件名；
		 if(file_exists(RUNTIME_FILE)){
			unlink(RUNTIME_FILE); //删除RUNTIME_FILE;
		 }
		$cachedir=RUNTIME_PATH."/Cache/";   //Cache文件的路径；
		 if ($dh = opendir($cachedir)) {     //打开Cache文件夹；
			while (($file = readdir($dh)) !== false) {    //遍历Cache目录，
					  unlink($cachedir.$file);                //删除遍历到的每一个文件；
			}
			closedir($dh);
		 }




		$this->success('修改成功！');
}




public function wxupdate(){

		$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
	    require dirname(__FILE__).'/../../../infoconfig.php';


		$infoconfig['wxname'] = $this->_param('wxname');
		$infoconfig['wxid'] = $this->_param('wxid');
		$infoconfig['weixin'] = $this->_param('weixin');
		$infoconfig['appid'] = $this->_param('appid');
		$infoconfig['appsecret'] = $this->_param('appsecret');
		$infoconfig['aeskey'] = $this->_param('aeskey');


		if (phpversion() > 5.0){
			file_put_contents(dirname(__FILE__).'/../../../infoconfig.php', ""."<?php\n \$infoconfig	= ".var_export($infoconfig,true).";\n?>");
		}

		//RUNTIME_FILE常量是入口文件中配置的runtimefile的路径及文件名；
		 if(file_exists(RUNTIME_FILE)){
			unlink(RUNTIME_FILE); //删除RUNTIME_FILE;
		 }
		$cachedir=RUNTIME_PATH."/Cache/";   //Cache文件的路径；
		 if ($dh = opendir($cachedir)) {     //打开Cache文件夹；
			while (($file = readdir($dh)) !== false) {    //遍历Cache目录，
					  unlink($cachedir.$file);                //删除遍历到的每一个文件；
			}
			closedir($dh);
		 }


		$this->success('修改成功！');
}




public function deleteCache(){
		DeleteDir(RUNTIME_PATH);
		DeleteDir('../Member/'.RUNTIME_PATH);
		DeleteDir('../Home/'.RUNTIME_PATH);

		//生成地区js
	    $jsCode = "<!--\r\n";
	    $jsCode .= "em_nativeplace=new Array();\r\n";
		$area = M("Sys_enum")->where("egroup='nativeplace'")->order("id")->select();
		foreach ($area as $value) {
            $jsCode .= "em_nativeplace[".$value['evalue']."]='".$value['ename']."';\r\n";
		}
	    $jsCode .= "-->";
	    $fp = fopen('../Public/nativeplace.js', 'w');
	    fwrite($fp, $jsCode);
	    fclose($fp);


		$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
		$this->success('删除成功！');
}




	public function insert(){
		$P = D("Administrator");
		if(!$P->create()) {
			$this->error($P->getError());
		}else{

			if($P->where("admin_name='".$_REQUEST['admin_name']."'")->find()){
				$this->error('添加失败！该用户已经存在');
			}

		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

			if(!empty($_REQUEST['password'])){
				$P->password = md5($_POST['password']);
			}

			if($result = $P->add()) {
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！');
			}
	    }
	}


	public function delete() {
		$model = M ("Administrator");
		if (! empty ( $model )) {
			$pk = $model->getPk ();
			$id = $_REQUEST [$pk];
			if (isset ( $id )) {
				$condition = array ($pk => array ('in', explode ( ',', $id ) ) );
				$list=$model->where ( $condition )->delete();;
				if ($list!==false) {
					$this->success ('删除成功！' );
				} else {
					$this->error ('删除失败！');
				}
			} else {
				$this->error ( '非法操作' );
			}
		}
	}







	// 框架首页
	public function article() {

				$model = M ("Article" );

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
				$count = $model->where ( $map )->count ( );
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
				$this->assign ( 'numPerPage', $p->listRows );
				$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);

				Cookie::set ( '_currentUrl_', __SELF__ );
				$this->display ();


	}



public function insertArticle(){
		$model = M ("Article");
	    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
		if(!$model->create()) {
			$this->error($model->getError());
		}else{
			$model->addtime = time();
			if($result	 =	 $model->add()) {
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！');
			}
	    }
}


public function updateArticle(){
		$model = M ("Article");
		if(!$model->create()) {
			$this->error($model->getError());
		}else{
		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			// 写入帐号数据
			if($result	 =	 $model->save($tmp)) {
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！');
			}
	    }
}



	public function editArticle() {
		$model = M ("Article");
		$id = $_REQUEST ["id"];
		$vo = $model->where("id = ".$id)->find();
		$this->assign ( 'vo', $vo );
		$this->display ();
	}


public function template() {



		        //列表过滤器，生成查询Map对象

				$map = $this->_search ();

				if (method_exists ( $this, '_filter' )) {

					$this->_filter ( $map );

				}

				//读取数据库模块列表生成菜单项

				$model = M ("Template" );



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

				$this->assign ( 'numPerPage', $p->listRows );

				$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);



				Cookie::set ( '_currentUrl_', __SELF__ );

				$this->display ();	



	}



	public function deleteTemplate() {

		$model = M ("Template");

		if (! empty ( $model )) {

			$pk = $model->getPk ();

			$id = $_REQUEST [$pk];	

			if (isset ( $id )) {

				$condition = array ($pk => array ('in', explode ( ',', $id ) ) );

				$list=$model->where ( $condition )->delete();;

				if ($list!==false) {

					$this->success ('删除成功！' );

				} else {

					$this->error ('删除失败！');

				}

			} else {

				$this->error ( '非法操作' );

			}

		}		

	}



	public function updateTemplate() {

		$P = D("Template");

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






	public function editTemplate() {

		$model = M ( "Template" );

		$id = $_REQUEST [$model->getPk ()];

		$vo = $model->getById ( $id );

		$this->assign ( 'vo', $vo );


		$this->display ();	

	}



	public function insertTemplate(){

		$P = D("Template");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{

		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			if($result = $P->add()) {

				$this->success('添加成功！');

			}else{

				$this->error('添加失败！');

			}

	    }

	}




}
?>

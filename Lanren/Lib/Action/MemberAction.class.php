<?php

class MemberAction extends CommonAction {



	function _filter(&$map){





	  $map['m_tel'] = array('like',"%".$_GET['m_tel']."%"); 


	  $map['m_username'] = array('like',"%".$_GET['m_username']."%");  

	

		

	}	

	

	// 框架首页

	public function index() {



		        //列表过滤器，生成查询Map对象

				$map = $this->_search ();

				if (method_exists ( $this, '_filter' )) {

					$this->_filter ( $map );

				}

				//读取数据库模块列表生成菜单项

				$model = M ("Member" );



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

	







	public function member() {



		        //列表过滤器，生成查询Map对象

			  $map['m_email'] = array('like',"%".$_GET['m_email']."%"); 

			  $map['m_mytel'] = array('like',"%".$_GET['m_mytel']."%");

			  $map['m_myqq'] = array('like',"%".$_GET['m_myqq']."%");  

			  $map['m_username'] = array('like',"%".$_GET['m_username']."%");  

			  $map['m_companyName'] = array('like',"%".$_GET['m_companyName']."%");  







				//读取数据库模块列表生成菜单项

				$model = M ("Dealer" );



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

	





	public function Userlogin() {





			  if(isset($_REQUEST['startdate']) && isset($_REQUEST['enddate'])){		  

				  $map['lasttime'] = array(array('egt',strtotime($_REQUEST['startdate'])),array('elt',strtotime($_REQUEST['enddate'])));

				}



				//读取数据库模块列表生成菜单项

				$model = M ("Users" );



				$order = "lasttime";	



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



	





	public function memberLogin() {





			  if(isset($_REQUEST['startdate']) && isset($_REQUEST['enddate'])){		  

				  $map['m_login_time'] = array(array('egt',strtotime($_REQUEST['startdate'])),array('elt',strtotime($_REQUEST['enddate'])));

				}



				//读取数据库模块列表生成菜单项

				$model = M ("Dealer" );



				$order = "m_login_time";	



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



	





	public function delete() {

		$model = M ("Member");

		if (! empty ( $model )) {

			$pk = $model->getPk ();

			$id = $_REQUEST ['id'];	

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





	public function memberEdit() {

		$model = M ( "dealer" );

		$id = $_REQUEST [$model->getPk ()];

		$vo = $model->where ( 'm_id='.$id )->find();

		$this->assign ( 'vo', $vo );



		//model

		$model = M ( "Model" );

		$list = $model->where("m_status=1")->select();

		$this->assign ( 'list', $list );



		$this->display ();	

	}








	public function memberAdd() {

		//model

		$model = M ( "Model" );

		$list = $model->where("m_status=1")->select();

		$this->assign ( 'list', $list );



		$this->display ();	

	}	





	public function edit() {

		$model = M ( "Member" );

		$vo = $model->where('m_id='.$this->_param('id'))->find();

		$this->assign ( 'vo', $vo );


		$this->display ();	

	}





	public function update() {

		$model = D ( "Member" );



		if(!empty($_REQUEST['m_password'])){
			$data['m_password'] = md5($_POST['m_password']);
		}

		

		$condition['m_id'] = $_POST['id'];

		$data['m_username'] = $_POST['m_username'];
		$data['m_num'] = $_POST['m_num'];
		$data['m_tel'] = $_POST['m_tel'];
		$data['m_status'] = $_POST['m_status'];
		$data['m_wxname'] = $_POST['m_wxname'];
		$data['m_wxid'] = $_POST['m_wxid'];
		$data['m_weixin'] = $_POST['m_weixin'];
		$data['m_appid'] = $_POST['m_appid'];
		$data['m_appsecret'] = $_POST['m_appsecret'];
		$data['m_aeskey'] = $_POST['m_aeskey'];
		$data['m_startdate'] = $_POST['m_startdate'];
		$data['m_enddate'] = $_POST['m_enddate'];
		$data['m_status'] = $_POST['m_status'];

		

		// 更新数据

		$list=$model->where($condition)->data($data)->save();



		if (false !== $list) {

			//成功提示

			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

			$this->success ('编辑成功!');

		} else {

			//错误提示

			$this->error ('编辑失败!');

		}



	}







	public function updateMember() {

		$P = D("Dealer");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{

		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

			// 写入帐号数据

			

		    unset($P->m_password); //delete  password property

			if(!empty($_REQUEST['m_password'])){

				$P->m_password = md5($_POST['m_password']);

			}



			$P->m_end_time = strtotime($_POST['m_end_time']);

			$tmp = $this->_param('aa');

			if(!empty($tmp)){

				$P->m_role = implode(',',$tmp); 

			}



			if($result	 =	 $P->save()) {

				$this->success('修改成功！');

			}else{

				$this->error('修改失败！');

			}

	    }



	}







public function Status(){

		$model = M ("Tx");

		$id = $_REQUEST ["id"];	

		if (isset ( $id )) {

		$condition = array ("tx_id" => array ('in', explode ( ',', $id ) ) );

		$data['tx_zt'] = $_REQUEST ["c"];	

		$list=$model->where ( $condition )->data($data)->save();

		if ($list!==false) {

			$this->success ('更新成功！' );

		} else {

			$this->error ('更新失败！');

		}

		}

}





public function mStatus(){

		$model = M ("User");

		$id = $_REQUEST ["id"];	

		if (isset ( $id )) {

		$condition = array ("id" => array ('in', explode ( ',', $id ) ) );

		$data['status'] = $_REQUEST ["c"];	

		$list=$model->where ( $condition )->data($data)->save();

		if ($list!==false) {

			$this->success ('更新成功！' );

		} else {

			$this->error ('更新失败！');

		}

		}

}





public function memberDelete(){

		$model = M ("Dealer");

		$id = $_REQUEST ["m_id"];	

		if (isset ( $id )) {

			$condition = array ("m_id" => array ('in', explode ( ',', $id ) ) );

			$list=$model->where ( $condition )->delete();

			if ($list!==false) {

				$this->success ('删除成功！' );

			} else {

				$this->error ('删除失败！');

			}

		}

}









	public function insert(){

		$P = D("Member");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{



			if($P->where("m_username='".$_REQUEST['m_username']."'")->find()){

				$this->error('添加失败！该用户已经存在');

			}	



		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
		    $P->m_addtime = time();
			if(!empty($_REQUEST['m_password'])){
				$P->m_password = md5($_POST['m_password']);
			} 



			if($result = $P->add()) {

				$this->success('添加成功！');

			}else{

				$this->error('添加失败！');

			}

	    }

	}





	public function insertMember(){

		$P = D("Dealer");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{



			if($P->where("m_username='".$_REQUEST['m_username']."'")->find()){

				$this->error('添加失败！该用户已经存在');

			}	



			if($P->where("m_companyName='".$_REQUEST['m_companyName']."'")->find()){

				$this->error('添加失败！该公司名已经存在');

			}	





		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

		    $P->m_reg_time = time();

		    unset($P->m_password); //delete  password property

			if(!empty($_REQUEST['m_password'])){

				$P->m_password = md5($_POST['m_password']);

			} 



    		$P->m_end_time = strtotime($_POST['m_end_time']);



			

			if($result = $P->add()) {

				$this->success('添加成功！');

			}else{

				$this->error('添加失败！');

			}

	    }

	}		





}

?>

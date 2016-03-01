<?php

class VoteAction extends CommonAction {



	function _filter(&$map){



	}	

	

	// 框架首页

	public function index() {



	        //列表过滤器，生成查询Map对象

				if(!empty($_GET["m_username"])){
		            $tmp = M("Member")->where("m_username='".$this->_param("m_username")."'")->field("m_id")->find();
		            $map['mid'] = array('eq',$tmp['m_id']);
				}

				//读取数据库模块列表生成菜单项

				$model = M ("Vote" );



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

	









	// 框架首页

	public function ad() {



		        //列表过滤器，生成查询Map对象

				$map = $this->_search ();

				if (method_exists ( $this, '_filter' )) {

					$this->_filter ( $map );

				}

				//读取数据库模块列表生成菜单项

				$model = M ("Ad" );



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

	





	// 框架首页

	public function form() {




			   $username = $this->_param('username');	
			   if(!empty($username)){
				   $map['username'] = array('eq',$username);
			   }				

			   $vid = $this->_param('vid');	
			   if(!empty($vid)){
				   $map['vid'] = array('eq',$vid);
			   }
		

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

				$this->assign ( 'numPerPage', $p->listRows );

				$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);


				$time = time();
				$list = M("Vote")->where ("status=1 and statdate<".$time." and enddate>".$time)->select();
				$this->assign ( 'voteList', $list );
				
				
				
				
				

				Cookie::set ( '_currentUrl_', __SELF__ );

				$this->display ();	



	}

	







	// 框架首页

	public function voteRecord() {



		        //列表过滤器，生成查询Map对象

				
			   $username = $this->_param('username');	
			   if(!empty($username)){
				   $form = M("Form")->where("username = '".$username."'")->find();
				   $map['form_id'] = array('eq',$form['id']);
			   }				

			   $vid = $this->_param('vid');	
			   if(!empty($vid)){
				   $map['vid'] = array('eq',$vid);
			   }
			   				
			   $ip = $this->_param('IP');	
			   if(!empty($ip)){
				   $map['ip'] = array('eq',$ip);
			   }				

				//读取数据库模块列表生成菜单项

				$model = M ("Vote_record" );



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


				$time = time();
				$list = M("Vote")->where ("status=1 and statdate<".$time." and enddate>".$time)->select();
				$this->assign ( 'voteList', $list );
				

				Cookie::set ( '_currentUrl_', __SELF__ );

				$this->display ();	



	}

	









	public function delete() {

		$model = M ("Vote");

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





	public function formDelete() {

		$model = M ("Form");

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







	public function voteRecordDelete() {

		$model = M ("Vote_record");

		if (! empty ( $model )) {

			$pk = $model->getPk ();

			$id = $_REQUEST [$pk];	

			if (isset ( $id )) {

				$condition = array ($pk => array ('in', explode ( ',', $id ) ) );



				$list=$model->where ( $condition )->field('form_id')->select();

				foreach($list as $v){

					$Model = new Model();

					$Model->execute("update ".C("DB_PREFIX")."form  set ticket=ticket-1 where id=".$v['form_id']);

				}

				$list=$model->where ( $condition )->delete();

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









	public function deleteAd() {

		$model = M ("Ad");

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





	public function addAd() {

		$time = time();

		$list = M("Vote")->where ("status=1 and statdate<".$time." and enddate>".$time  )->select();

		$this->assign ( 'vlist',$list);

		$this->display ();	



	}





	public function editAd() {

		$time = time();

		$list = M("Vote")->where ("status=1 and statdate<".$time." and enddate>".$time  )->select();

		$this->assign ( 'vlist',$list);



		$model = M ( "Ad" );

		$id = $_REQUEST [$model->getPk ()];

		$vo = $model->getById ( $id );

		$this->assign ( 'vo', $vo );



		$this->display ();	



	}



	public function edit() {

		$model = M ( "Vote" );

		$id = $_REQUEST [$model->getPk ()];

		$vo = $model->getById ( $id );

		$this->assign ( 'vo', $vo );



		$this->display ();	

	}







	public function formEdit() {

		$model = M ( "Form" );

		$id = $_REQUEST [$model->getPk ()];

		$vo = $model->getById ( $id );

		$this->assign ( 'vo', $vo );



		$this->display ();	

	}





	public function formUpdate() {

		$P = D("Form");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{

		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

			if($this->_param("num") != $this->_param("o_num")){
				$vid = $this->_param("vid");
				$num = $this->_param("num");
				$re = M('Form')->where("vid=".$vid." and num=".$num)->count();
				if($re > 0){
					$this->error('该投票号已存占用，请填写其它号');
					exit;
				}
			}



			// 写入帐号数据

			if($result	 =	 $P->save()) {

				$this->success('修改成功！');

			}else{

				$this->error('修改失败！');

			}

	    }



	}







	public function update() {

		$P = D("Vote");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{

		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

			// 写入帐号数据



			$P->statdate = strtotime($_POST['statdate']);

			$P->enddate = strtotime($_POST['enddate']);

			if($result	 =	 $P->save()) {

				$this->success('修改成功！');

			}else{

				$this->error('修改失败！');

			}

	    }



	}





	public function updateAd() {

		$P = D("Ad");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{

		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

			if($result	 =	 $P->save()) {

				$this->success('修改成功！');

			}else{

				$this->error('修改失败！');

			}

	    }



	}





	public function insert(){

		$P = D("Vote");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{



		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

			$P->statdate = strtotime($_POST['statdate']);

			$P->enddate = strtotime($_POST['enddate']);

			

			if($result = $P->add()) {

				$this->success('添加成功！');

			}else{

				$this->error('添加失败！');

			}

	    }

	}



	



	public function insertAd(){

		$P = D("Ad");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{



		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

			$P->addtime = time();

			

			if($result = $P->add()) {

				$this->success('添加成功！');

			}else{

				$this->error('添加失败！');

			}

	    }

	}





}

?>
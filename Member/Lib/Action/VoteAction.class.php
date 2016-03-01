<?php

class VoteAction extends CommonAction {



	function _filter(&$map){

		$map['mid'] = array('eq',$_SESSION[C('USER_AUTH_KEY')]);

	}



	// 框架首页

	public function index() {



		        //列表过滤器，生成查询Map对象

				$map = $this->_search ();

				if (method_exists ( $this, '_filter' )) {

					$this->_filter ( $map );

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

	public function fans() {

		        //列表过滤器，生成查询Map对象

				$map = $this->_search ();

				if (method_exists ( $this, '_filter' )) {

					$this->_filter ( $map );

				}

				//读取数据库模块列表生成菜单项

				$model = M ("Userinfo" );


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


			   $map['mid'] = array('eq',$_SESSION[C('USER_AUTH_KEY')]);

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
				$list = M("Vote")->where ("status=1 and statdate<".$time." and enddate>".$time.'  AND mid='.$_SESSION[C('USER_AUTH_KEY')])->select();
				$this->assign ( 'voteList', $list );






				Cookie::set ( '_currentUrl_', __SELF__ );

				$this->display ();



	}









	// 框架首页

	public function voteRecord() {

				$map['mid'] = array('eq',$_SESSION[C('USER_AUTH_KEY')]);

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
				$list = M("Vote")->where ("status=1 and statdate<".$time." and enddate>".$time.' AND mid='.$_SESSION[C('USER_AUTH_KEY')])->select();
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

				$condition = array ($pk => array ('in', explode ( ',', $id ) ) ,'mid'=>array('eq',$_SESSION[C('USER_AUTH_KEY')]));

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

				$condition = array ($pk => array ('in', explode ( ',', $id ) ),'mid'=>array('eq',$_SESSION[C('USER_AUTH_KEY')]) );

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

				$condition = array ($pk => array ('in', explode ( ',', $id ) ),'mid'=>array('eq',$_SESSION[C('USER_AUTH_KEY')]) );



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

				$condition = array ($pk => array ('in', explode ( ',', $id ) ),'mid'=>array('eq',$_SESSION[C('USER_AUTH_KEY')]) );

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

		$list = M("Vote")->where ("status=1 and statdate<".$time." and enddate>".$time .' and mid='.$_SESSION[C('USER_AUTH_KEY')] )->select();

		$this->assign ( 'vlist',$list);

		$this->display ();



	}





	public function editAd() {

		$time = time();

		$list = M("Vote")->where ("status=1 and statdate<".$time." and enddate>".$time .' and mid='.$_SESSION[C('USER_AUTH_KEY')] )->select();

		$this->assign ( 'vlist',$list);



		$model = M ( "Ad" );

		$id = $_REQUEST [$model->getPk ()];

		$vo = $model->getById ( $id );

		$this->assign ( 'vo', $vo );



		$this->display ();



	}



	public function edit() {
		$model = M ( "Vote" );
		$vo = $model->where('id='.$this->_param('id').' and mid='.$_SESSION[C('USER_AUTH_KEY')])->find();
		$this->assign ( 'vo', $vo );

		$list = M("Template")->where("status=1")->select();
		$this->assign ( 'list', $list );

		$this->display ();
	}



	public function add() {
		$model = M ( "Vote" );
		$count = $model->where('mid='.$_SESSION[C('USER_AUTH_KEY')])->count();
		$member = M("Member")->where('m_id='.$_SESSION[C('USER_AUTH_KEY')])->field("m_num")->find();
		if($member['m_num'] <= $count){
		   $this->error('添加失败！超过了已添加的投票条目');
		}

		$list = M("Template")->where("status=1")->select();
		$this->assign ( 'list', $list );

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
            $P->info = str_replace(array("\r\n", "\r", "\n"), '',$this->_param("info"));
			if($result	 =	 $P->save()) {

				$this->success('修改成功！');

			}else{

				$this->error('修改失败！');

			}

	    }



	}



	public function formInsert() {

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
			$P->addtime = time();
			if($result	 =	 $P->add()) {

				$this->success('添加成功！');

			}else{

				$this->error('添加失败！');

			}

	    }



	}






	public function update() {

		$P = D("Vote");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{

			if(empty($_POST['title'])){
				$this->error('投票标题不能为空！');
				exit;
			}

		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

			// 写入帐号数据


			$P->statdate = strtotime($_POST['statdate']);
			$P->enddate = strtotime($_POST['enddate']);
			$P->v_startdate = strtotime($_POST['v_startdate']);
			$P->v_enddate = strtotime($_POST['v_enddate']);

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


			if(empty($_POST['title'])){
				$this->error('投票标题不能为空！');
				exit;
			}


		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

			$P->statdate = strtotime($_POST['statdate']);
			$P->enddate = strtotime($_POST['enddate']);
			$P->v_startdate = strtotime($_POST['v_startdate']);
			$P->v_enddate = strtotime($_POST['v_enddate']);
			$P->mid = $_SESSION[C('USER_AUTH_KEY')];



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
			$P->mid = $_SESSION[C('USER_AUTH_KEY')];

			if($result = $P->add()) {

				$this->success('添加成功！');

			}else{

				$this->error('添加失败！');

			}

	    }

	}

		//数据导出
        public function exportData() {
            ini_set('memory_limit', '-1');
            $list[1]="编号";
            $list[2]="姓名";
            $list[3]="投票号";
            $list[4]="点击数";
            $list[5]="票数";
            $list[6]="手机";
            $list[7]="状态";
            $list[8]="姓别";
            $list[9]="地址";
            $list[10]="照片";
            $list[11]="介绍";
            $list[12]="报名时间";

			$Model = new Model();
			$voList = $Model->query("select id,username,num,view,ticket,tel,status,sex,address,picurl,info,addtime from ".C("DB_PREFIX")."form  where mid=".$_SESSION[C('USER_AUTH_KEY')]." order by addtime desc limit 0,2000");
            $data = array();
            $data[] = array("编号","姓名","投票号","点击数","票数","手机","状态","姓别","地址","照片","介绍","报名时间");
            $i=1;
            foreach ($voList as $value) {
                 $value['addtime']  = date("Y-m-d H:i:s",$value['addtime']);
                 $value['status']  = empty($value['status'])?"待审":"已审";
				 if($value['sex'] == "0"){
					$value['sex'] = '保密';
				 }elseif($value['sex'] == "1"){
					$value['sex'] = '男';
				 }elseif($value['sex'] == "2"){
					$value['sex'] = '女';
				 }
                 $data[] = array($i,$value['username'],$value['num'],$value['view'],$value['ticket'],$value['tel'],$value['status'],$value['sex'],$value['address'],$value['picurl'],$value['info'],$value['addtime']);
                 $i++;
            }

            require_once (dirname(__FILE__)."/../../../Public/excel/php-excel.class.php");
            $xls = new Excel_XML('UTF-8', false, 'data');
            $xls->addArray($data);
            $xls->generateXML("customer");


            //toexcel($list);

        }


        public function exportDataRecord() {
            ini_set('memory_limit', '-1');
	    $list[1]="编号";
            $list[2]="姓名";
            $list[3]="投票标题";
            $list[4]="投票IP";
            $list[5]="投票人微信ID";
            $list[6]="投票时间";


	    $form = M("Form")->where("status=1 and mid=".$_SESSION[C('USER_AUTH_KEY')])->field('id,username')->limit('0,2000')->select();
	    foreach($form as $v){
	    	$ff[$v['id']] = $v['username'];
	    }


	    $vote = M("Vote")->where("status=1 and mid=".$_SESSION[C('USER_AUTH_KEY')])->field('id,title')->select();
	    foreach($vote as $v){
	    	$vv[$v['id']] = $v['title'];
	    }

            $Model = new Model();
	    $voList = $Model->query("select id,form_id,vid,wecha_id,addtime,ip from ".C("DB_PREFIX")."vote_record  where mid=".$_SESSION[C('USER_AUTH_KEY')]." order by addtime desc limit 0,20000");
            $data = array();
            $data[] = array("编号","姓名","投票标题","投票IP","投票人微信ID","投票时间");
            $i=1;
            foreach ($voList as $value) {
	         $username = $ff[$value['form_id']];
	         $title = $vv[$value['vid']];
                 $value['addtime']  = date("Y-m-d H:i:s",$value['addtime']);
                 $data[] = array($i,$username,$title,$value['ip'],$value['wecha_id'],$value['addtime']);
                 $i++;
            }

            require_once (dirname(__FILE__)."/../../../Public/excel/php-excel.class.php");
            $xls = new Excel_XML('UTF-8', false, 'data');
            $xls->addArray($data);
            $xls->generateXML("record");


            //toexcel($list);

        }




	public function verify() {

		        //列表过滤器，生成查询Map对象

				$map = $this->_search ();

				if (method_exists ( $this, '_filter' )) {

					$this->_filter ( $map );

				}

				//读取数据库模块列表生成菜单项

				$model = M ("Verify" );



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




	public function insertVerify(){

		$P = D("Verify");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{

			if(!preg_match("/^[\x{4e00}-\x{9fa5}]{4}$/u",$_POST['title'])){
				$this->error('添加失败！必须四个字的中文');
			}

		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$P->mid = $_SESSION[C('USER_AUTH_KEY')];

			if($result = $P->add()) {

				$this->success('添加成功！');

			}else{

				$this->error('添加失败！');

			}

	    }

	}

	public function editVerify() {
		$model = M ( "Verify" );
		$id = $_REQUEST [$model->getPk ()];
		$vo = $model->getById ( $id );
		$this->assign ( 'vo', $vo );
		$this->display ();	
	}



	public function updateVerify() {

		$P = D("Verify");

		if(!$P->create()) {

			$this->error($P->getError());

		}else{

			if(!preg_match("/^[\x{4e00}-\x{9fa5}]{4}$/u",$_POST['title'])){
				$this->error('添加失败！必须四个字的中文');
			}

		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );

			if($result	 =	 $P->save()) {

				$this->success('修改成功！');

			}else{

				$this->error('修改失败！');

			}

	    }



	}


	public function deleteVerify() {

		$model = M ("Verify");

		if (! empty ( $model )) {

			$pk = $model->getPk ();

			$id = $_REQUEST [$pk];	

			if (isset ( $id )) {

				$condition = array ($pk => array ('in', explode ( ',', $id ) ),'mid'=>array('eq',$_SESSION[C('USER_AUTH_KEY')]) );

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










        


}

?>

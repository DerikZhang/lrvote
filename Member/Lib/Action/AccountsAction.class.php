<?php
class AccountsAction extends CommonAction {

	function _filter(&$map){
	 
	}

	
	public function Income() {
			
			   $map['a_type'] = array('eq',0);

			   if(!empty($_REQUEST['startdate']) && !empty($_REQUEST['enddate'])){		  
				  $map['a_arrival_time'] = array(array('egt',strtotime($_REQUEST['startdate'])),array('elt',strtotime($_REQUEST['enddate'])));
				}

			   if(!empty($_REQUEST['a_startdate']) && !empty($_REQUEST['a_enddate'])){		  
				  $map['a_endtime'] = array(array('egt',strtotime($_REQUEST['a_startdate'])),array('elt',strtotime($_REQUEST['a_enddate'])));
				}


				//读取数据库模块列表生成菜单项
				$model = M ("Accounts" );


				$_REQUEST ['_order'] = 	"a_id";
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


				//总计
				$model = M ("Accounts");
				$total_money = $model->where($map)->sum('a_price');
				$this->assign ( 'total_money',  $total_money?$total_money:0 );

				$this->display ();	
	}


	public function Outlay() {
			
			   $map['a_type'] = array('eq',1);

			   if(isset($_REQUEST['startdate']) && isset($_REQUEST['enddate'])){		  
				  $map['a_arrival_time'] = array(array('egt',strtotime($_REQUEST['startdate'])),array('elt',strtotime($_REQUEST['enddate'])));
				}

				//读取数据库模块列表生成菜单项
				$model = M ("Accounts" );

				$_REQUEST ['_order'] = 	"a_arrival_time";
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


				//总计
				$model = M ("Accounts");
				$total_money = $model->where($map)->sum('a_price');
				$this->assign ( 'total_money',  $total_money?$total_money:0 );

				$this->display ();	
	}

	

public function IncomeInsert(){
		$model = M ("Accounts");
	    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
		if(!$model->create()) {
			$this->error($model->getError());
		}else{
			$model->a_arrival_time = strtotime($_REQUEST['a_arrival_time']);
			$model->a_endtime = strtotime($_REQUEST['a_endtime']);
			$model->a_addtime = time();
			if($result	 =	 $model->add()) {
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！');
			}
	    }
}


public function incomeUpdate(){
		$model = M ("Accounts");
		if(!$model->create()) {
			$this->error($model->getError());
		}else{
		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$model->a_arrival_time = strtotime($_REQUEST['a_arrival_time']);
			$model->a_endtime = strtotime($_REQUEST['a_endtime']);			
			if($result	 =	 $model->save()) {
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！');
			}
	    }
}



	public function OutlayEdit() {
	   $model = M("Accounts");
	   $vo = $model->where("a_id=".$this->_param('id'))->find();
	   $this->assign('vo',$vo);	   
		$this->display();
	}


	public function IncomeEdit() {
	   $model = M("Accounts");
	   $vo = $model->where("a_id=".$this->_param('id'))->find();
	   $this->assign('vo',$vo);	   
		$this->display();
	}


	public function saveImg(){
	    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('gif','jpg','png');// 设置附件上传类型
        $upload->savePath = './Public/upload/';// 设置附件上传目录
		$upload->saveRule = time();	//指定文件名	
		 if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		 }else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			$info = $info[0];
			$savepath = str_replace('./', '', $info['savepath']).$info['savename'];
			$savepath = __ROOT__ .'/'. $savepath;
			$_SESSION['imgPath']	=	$savepath;
			$id = $this->_param("id");
			$a_type = $this->_param("a_type");
			if(empty($id)){
				if(empty($a_type)){
					$this->assign ( 'jumpUrl', __URL__.'/IncomeAdd/img/1' );
				}else{
					$this->assign ( 'jumpUrl', __URL__.'/OutlayAdd/img/1' );
				}
			}
			else{
				if(empty($a_type)){
					$this->assign ( 'jumpUrl', __URL__.'/incomeEdit/img/1/id/'.$id );
				}else{
					$this->assign ( 'jumpUrl', __URL__.'/OutlayEdit/img/1/id/'.$id);
				}
			}
			$this->success('上传成功！');			
		 }		
	}


public function delete() {
	$model = M ("Accounts");
	if (! empty ( $model )) {
		$id = $this->_param("id");
		if (isset ( $id )) {
			$condition = array ('a_id' => array ('in', explode ( ',', $id ) ) );
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
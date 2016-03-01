<?php
class ArctypeAction extends CommonAction {
	
	// 框架首页
	public function index() {

		        //列表过滤器，生成查询Map对象
				 $map['topid'] = array('eq',$this->_param("id"));

				//读取数据库模块列表生成菜单项
				$model = M ("Arctype" );

				//排序字段 默认为主键名
				if (!empty ( $_REQUEST ['_order'] )) {
					$order = $_REQUEST ['_order'];
				} else {
					$order = "sortrank";
				}
				//排序方式默认按照倒序排列
				//接受 sost参数 0 表示倒序 非0都 表示正序
				if (isset ( $_REQUEST ['_sort'] )) {
					$sort = $_REQUEST ['_sort'] == 'asc' ? 'asc' : 'desc'; //zhanghuihua@msn.com
				} else {
					$sort = 'asc';
				}
				//取得满足条件的记录数
				$count = $model->where ( $map )->count ( 'id' );
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
				$this->assign ( 't',  $this->_param('t') );
				$this->assign ( 'totalCount', $count );
				$this->assign ( 'numPerPage', $p->listRows );
				$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);

				Cookie::set ( '_currentUrl_', __SELF__ );
				$this->display ();	

	}
	

	public function delete() {
		$model = M ("Arctype");
			$id = $_REQUEST ["id"];	
			if (isset ( $id )) {
				$condition = array ("id" => array ('in', explode ( ',', $id ) ) );
				$list=$model->where ( $condition )->delete();
				if ($list!==false) {
					$this->success ('删除成功！' );
				} else {
					$this->error ('删除失败！');
				}
			} 
	}
	


	public function edit() {
		$model = M ( "Arctype" );
		$id = $_REQUEST ["id"];
		$vo = $model->where("id = ".$id)->find();
		$this->assign ( 'vo', $vo );
		$this->display ();	
	}




public function update(){
		$P = D("Arctype");
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


public function insert(){
		$P = D("Arctype");
		if(!$P->create()) {
			$this->error($P->getError());
		}else{
		    $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			// 写入帐号数据
			if($result	 =	 $P->add()) {
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！');
			}
	    }
}	



}
?>
<?php

class PersonAction extends CommonAction {

	

	// 框架首页

	public function index() {

		$model = M ( "Member" );

		$vo = $model->where("m_id = ".$_SESSION[C('USER_AUTH_KEY')])->find();

		$this->assign ( 'vo', $vo );

		Cookie::set ( '_currentUrl_', __SELF__ );

		$this->display ();



	}





	public function update() {

		$model = D ( "Member" );

		if(!empty($_REQUEST['m_password'])){

			$data['m_password'] = md5($_POST['m_password']);

		}



		$condition['m_id'] = $_SESSION[C('USER_AUTH_KEY')];

		

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





}

?>
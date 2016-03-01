<?php

class IndexAction extends CommonAction { 

	public function index() {

		$member = M("Member")->where("m_id=".$_SESSION[C('USER_AUTH_KEY')])->find();
	    $this->assign ( 'member', $member );
		$this->display ();

	}

}

?>
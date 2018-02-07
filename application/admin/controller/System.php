<?php
namespace app\admin\controller;
use app\admin\controller\Base;

use think\Session;

class System extends Base{

	//后台栏目列表
	public function systemList(){
		$category=db('category')->select();
		$tree=getTree($category);
		$count=count($category);
		$admin=Session::get('admin');
		$data=[
			'admin'=>$admin,
			'count'=>$count,
			'tree'=>$tree,
		];
		$this->assign($data);
		return $this->fetch();
	}

	//添加后台栏目
	public function systemCateAdd(){
		
	}

}
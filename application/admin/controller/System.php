<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\System as SystemModel;
use think\Validate;
use think\Session;

class System extends Base{
	//系统管理列表
	public function systemList(){
		$admin=Session::get('user');
		$category=db('category')->select();
		$tree=getTree($category);
		// dump($tree);die;
		$count=count($category);
		$this->categoryAssign();
		if($admin['role']==1){
			$adminRole='超级管理员';
		}else{
			$adminRole='普通管理员';
		}

		$data=[
			'title'=>'系统栏目',
			'adminRole'=>$adminRole,
			'count'=>$count,
			'admin'=>$admin,
			'tree'=>$tree,
		];
		$this->assign($data);
		return $this->fetch();

	}


	
}






?>
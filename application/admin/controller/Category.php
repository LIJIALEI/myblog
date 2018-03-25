<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Category as Model;

use think\Session;

class Category extends Base{

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
		if(request()->isAjax()){
			$data=input('post.');
			
			$validate=validate("Category");
			if(!($validate->scene('cateAdd')->check($data))){
				$status=0;
				$msg=$validate->getError();
			}else{
				$upd=[
					'cate_title'=>$data['cate_title'],
					'pid'=>$data['pid'],
					'cate_url'=>$data['cate_url'],
					'cate_icon'=>$data['cate_icon'],
				];
				$model=new Model();
				if($model->insert($upd)){
					$status=1;
					$msg="添加成功";
				}else{
					$status=0;
					$msg="连接超时";
				}
			}
			


			return [
				'status'=>$status,
				'message'=>$msg
			];
		}
		$model=new Model();
		$cate=$model->where('pid',0)->select();
		// $cate=db('category')->where('pid',0)->select();
		$this->assign('cate',$cate);
		return $this->fetch();


	}


	public function cateList(){
        $category = db('category')->select();//查询出所有分类
        $result = loop($category);
     	$data=[
           
            'category'=>$result,
        ];
        
        $this->assign($data);
		return $this-fetch();
	}



}
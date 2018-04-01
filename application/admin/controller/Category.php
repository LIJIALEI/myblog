<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Category as Model;

use think\Session;

class Category extends Base{
	//左侧目录
	// public function cateList(){
 //        $category = db('category')->select();
 //        $result = loop($category) ;

 //     	$data=[
 //            'category'=>$result,
 //        ];
        
 //        $this->assign($data);
	// 	return $this-fetch();
	// }


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
				if($data['pid']==0){
					$icon="&#xe658";
				}else{
					$icon=null;
				}
				$upd=[
					'cate_title'=>$data['cate_title'],
					'pid'=>$data['pid'],
					'cate_url'=>$data['cate_url'],
					'cate_icon'=>$icon,
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



	//删除栏目
	public function cateDel(){
		$cate_id=$_GET['category_id'];
		$model=new Model();
		$res=$model->where('id',$cate_id)->find();

		if($res['pid']==0){
			$arrCate=$model->where('pid',$cate_id)->select();
			// dump($arrCate);die;
			for($i=0;$i<sizeof($arrCate);$i++){
				if(!($model->where('id',$arrCate[$i]['id'])->delete())){
					$this->error('ID:'.$arrCate[$i]['id'].'栏目删除失败');
				}
			}
			if($model->where('id',$cate_id)->delete()){
				$this->success('栏目及其子栏目删除成功');
			}


		}else{
			if($model->where('id',$cate_id)->delete()){
				$this->success('删除栏目成功');
			}else{
				$this->error('删除失败');
			}
		}



		
	}

	//编辑栏目信息
	public function cateEdit(){
		if(request()->isAjax()){
			$id=$_GET['id'];
			$data['cate_role']=$_POST['cate_role'];
			$data['cate_title']=$_POST['cate_title'];
			if(!empty($_POST['cate_url'])){
				$data['cate_url']=$_POST['cate_url'];
			}
			

			$validate=validate("Category");
			if(!($validate->scene('cateAdd')->check($data))){
				$status=0;
				$msg=$validate->getError();
			}else{
				$model=new Model();
				if($model->where('id',$id)->setField($data)){
					$status=1;
					$msg='修改成功';
				}else{
					$status=0;
					$msg='连接数据库失败';
				}
			}

			return [
				'status'=>$status,
				'message'=>$msg
			];

		}


		$cate_id=$_GET['cate_id'];
		$model=new Model();
		$res=$model->where('id',$cate_id)->find();
		$this->assign('cate',$res);
		return $this->fetch();
	}







}
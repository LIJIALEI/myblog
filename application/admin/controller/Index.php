<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Session;

class Index extends Base
{
    //首页渲染
    public function index()
    {
    	$this->isLogin();
        $category = db('category')->order('id','asc')->select();//查询出所有分类
        $result = loop($category);
        // dump($result);die;
        $data=[
           
            'category'=>$result,
        ];
        
        $this->assign($data);
       	return $this->fetch();
        
    }


    public function welcome()
    {

    	return $this->fetch();
    }
}

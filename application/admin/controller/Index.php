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
        $category = db('category')->select();//查询出所有分类
        $result = loop($category);
        
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

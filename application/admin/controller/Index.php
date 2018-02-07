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

       	return $this->fetch();
        
    }


    public function welcome()
    {

    	return $this->fetch();
    }
}

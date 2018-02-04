<?php

namespace app\admin\controller;
use think\Controller;
use think\Session;

class Base extends Controller{
    
    //判断是否登录
     protected function isLogin(){
        if(!Session::has('user')){
            $this->error('用户未登录',url('user/login'));
        }
    }
    
    //判断是否已经登录
    protected function alreadyLogin(){
        if(Session::has('user')){
             $this->error('用户已经登录,请勿重复登录',url('index/index'));
        }
    }
    protected function categoryAssign(){
        $category = db('category')->select();//查询出所有分类
        $result = loop($category);
        $this->assign('category',$result);
    }
    
    
}

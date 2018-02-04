<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Session;

class Index extends Base
{
    public function index()
    {

        $user= Session::get('user');
        if($user['role']==1){
            $adminRole='超级管理员';
        }else{
            $adminRole='普通管理员';
        }

        //category递归

        $category = db('category')->select();//查询出所有分类
        $result = loop($category);
        // $lenth=count($category);
        // for ($i=0; $i < $lenth ; $i++) { 
        //      $category[$i]['cate_url'] = $category['cate_url'];
        // }
        

        //dump($result);die;
        // show_bug(json_encode($result,JSON_UNESCAPED_UNICODE));die;
        $data=[
            'title'=>'博客后台管理系统',
            'keywords'=>'blogAdmin',
            'description'=>'博客后台管理系统',
            'adminRole'=>$adminRole,
            'category'=>$result,
        ];
        
        $this->isLogin();
        $this->assign($data);
        return $this->fetch();
        
    }
}

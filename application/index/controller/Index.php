<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Session;

class Index extends Base{
    public function index (){
        $article1=db('article')->order('article_on_top desc')->limit(0,2)->select();
        $article2=db('article')->order('article_on_top desc')->limit(2,2)->select();
        
        
        $Photo=db('Photo')->order('photo_look_count desc')->limit(0,5)->select();
        $data=[
                'title'=>'欢迎来到我的博客',
                'Photo'=>$Photo,
                'article1'=>$article1,
                'article2'=>$article2,
            ]; 
        $this->assign($data);
        return $this->fetch(); 
    }
}
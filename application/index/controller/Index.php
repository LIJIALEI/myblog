<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Session;

class Index extends Base{
    public function index (){
        $article1=db('article')->order('look_count desc')->limit(0,2)->select();
        $article2=db('article')->order('look_count desc')->limit(2,2)->select();
//        dump($article1);echo'————';
//        dump($article2);die;
        $Photo=db('Photo')->select();
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
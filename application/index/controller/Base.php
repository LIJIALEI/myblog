<?php

namespace app\index\controller;
use think\Controller;
use think\Session;

class Base extends Controller{
    
    //检验是否登录，且判断是否有权利发表文章
    protected function vistCheckLogin(){
        if(!Session::get('vist')){
            $this->error('请先登录才可发表文章','vist/vistLogin');
        }else{
            $vist= Session::get('vist');
            $res=db('vist_role')->where('vist_id',$vist['vist_id'])->find();
            if($res['writeArticlt']!=1){
                $this->error('您无权发表文章','article/articleHome');
            }
        }
    }
    
    //检测用户权限—文章
    protected  function vistLookRole(){
        
    }
   
    
}

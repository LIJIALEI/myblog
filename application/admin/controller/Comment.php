<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Comment as CommentModel;
use think\Validate;
use think\Session;

class Comment extends Base{
    //评论主页
    public function commentList(){
        $res=db('comment')->select();
        $comment=db('comment')->alias('c')->join('article a','c.article_id = a.article_id')->order('comment_create_time desc')->paginate(10);
        $count=count($res);
//        foreach ($res as $key => $value) {
//            $arrCom[$key]=$value;
//        }
        $this->categoryAssign();
        $admin= Session::get('user');
         if($admin['role']==1){
            $adminRole='超级管理员';
        }else{
            $adminRole='普通管理员';
        }
        $data=[
            'title'=>'评论管理',
            'adminRole'=>$adminRole,
            'comment'=>$comment,
            'count'=>$count,
            
        ];
        $this->assign($data);
        return $this->fetch();
    }
    
    //删除评论
    public function commentDel(){
        $comment_id=$_GET['comment_id'];
        $admin= Session::get('user');
        $admin_role=db('user_role')->where('admin_id',$admin['id'])->find();
        if($admin_role['delComment']!=1){
            $this->error('您无权删除','comment/commentList');
        }else{
            if(db('comment')->where('comment_id',$comment_id)->delete()){
                $this->success('删除成功','comment/commentList');
            }else{
                $this->error('删除失败','comment/commentList');
            }
        }
        
        
    }
    
    //用户意见
    public function commentToUs(){
        $admin= Session::get('user');
        if($admin['role']==1){
            $adminRole='超级管理员';
        }else{
            $adminRole='普通管理员';
        }
        $res=db('suggestion')->select();
        $sugg=db('suggestion')->order('suggestion_create_time desc')->paginate(10);
        $count=count($res);
        $this->categoryAssign();
        $data=[
            'title'=>'游客的意见',
            'adminRole'=>$adminRole,
            'count'=>$count,
            'suggestion'=>$sugg,
            
        ];
        $this->assign($data);
        return $this->fetch();
    }
    
    //删除用户意见
    public function suggestionDel(){
        $suggestion_id=$_GET['suggestion_id'];
        if(db('suggestion')->where('suggestion_id',$suggestion_id)->delete()){
            $this->success('删除评论成功');
        } else {
            $this->error('连接超时');
        }
    }
    
}
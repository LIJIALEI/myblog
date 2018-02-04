<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Article as ArticleModel;
use think\Validate;
use think\Session;

class Article extends Base{
    //文章列表
    public function articleList(){
        $user= Session::get('user');
        if($user['role']==1){
            $adminRole='超级管理员';
        }else{
            $adminRole='普通管理员';
        }
        $admin=db('user_role')->where('admin_id',$user['id'])->find();
        //查询文章
        $res=db('article')->select();
        $article=db('article')->order('look_count desc')->paginate(10);
        $count=count($res);
        
        $this->categoryAssign();
        $data=[
            'adminRole'=>$adminRole,
            'title'=>'文章列表',
            'article'=>$article,
            'count'=>$count, 
            'admin'=>$admin,
            
        ];
        $this->assign($data);
        return $this->fetch();
    }
    
    //管理员删除文章
    public function articleDel(){
        $article_id=$_GET['article_id'];
        
      
            if(db('article')->where('article_id',$article_id)->delete()){
                $resCom=db('comment')->where('article_id',$article_id)->find();
                if(!empty($resCom)){
                    db('comment')->where('article_id',$article_id)->delete();
                    $this->success('文章删除成功，评论也一起删除了','article/articleList');
                }else{
                    $this->success('删除成功','article/articleList');
                }
            }else{
                $this->error('删除失败','article/articleList');
            }
        
        
    }
    
    //编辑文章
    public function articleEdit(){
        if(request()->isAjax()){
            $input=input('post.');
            $article_id=$_GET['article_id'];
            $res=db('article')->where('article_id',$article_id)->find();
            //adminRole=1才可以修改文章
            
            if(empty($input['article_content'])){
                $status=0;
                $message='还没修改';
            }else{
                    $time=time();
                    $upd=[
                        'article_content'=>$input['article_content'],
                        'article_update_time'=>$time,
                    ];
                    if(db('article')->where('article_id',$article_id)->setField($upd)){
                        $status=1;
                        $message='修改成功';
                    }else{
                        $status=0;
                        $message='插入失败';
                    }
                
            }
            return [
                'status'=>$status,
                'message'=>$message,
            ];
        }else{
            $article_id=$_GET['article_id'];
            $article=db('article')->where('article_id',$article_id)->find();
            $data=[
                'title'=>'编辑',
                'article'=>$article,
            ];
            $this->assign($data); 
            return $this->fetch();
        }
        
       
    }
    
    
    
}
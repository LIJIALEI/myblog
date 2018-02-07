<?php
namespace app\admin\controller;
use app\admin\controller\Base;

use think\Session;

class Article extends Base{

	public function articleList(){
		$count=db('article')->count();
        $article=db('article')->order('look_count desc')->paginate(10);
        $res=Session::get('admin');
        $admin=db('user_role')->where('admin_id',$res['id'])->find();

        $data=[
             
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
}
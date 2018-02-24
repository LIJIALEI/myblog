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

    //批量删除
    public function articleAllDel(){
    	$article=$_POST['article_id'];
    	// dump($article);die;
    	// $article_id=[];
    	// for($n=0;)

    	for($i=0;$i<sizeof($article);$i++){
    		if(db('article')->where('article_id',$article[$i])->delete()){
    			if(db('comment')->where('article_id',$article[$i])->find()){
    				db('comment')->where('article_id',$article[$i])->delete();
    				$status=1;
    				$msg="删除成功，文章连同评论";
    			}else{
    				$status=1;
    				$msg="文章删除成功";
    			}
    		}else{
    			$status=0;
				$msg="连接数据库失败";
    		}

    	}
    	return [
    		'status'=>$status,
    		'message'=>$msg,
    	];


    }



}
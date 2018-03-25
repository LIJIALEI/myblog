<?php
namespace app\admin\controller;
use app\admin\controller\Base;

use think\Session;

class Article extends Base{

	public function articleList(){
		$count=db('article')->count();
        $article=db('article')->order('article_on_top','desc')->paginate(10);
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

    //置顶
    public function setTop(){
        $article_id=$_GET['article_id'];
        $onTop=$_GET['onTop'];
        if($onTop==1){
            if(db('article')->where('article_on_top',1)->count()==4){
                $this->error('最多只能置顶4个');
            }
        }
        

        if(db('article')->where('article_id',$article_id)->setField('article_on_top',$onTop)){
            if($onTop==1){
                $this->success('置顶成功');
            }else{
                $this->success('取消置顶成功');
            }
        }else{
            $this->error('连接超时');
        }
         
    }


    //后台添加文章
    public function articleAdd(){
        if(request()->isAjax()){
            $admin=Session::get('admin');
            $input=input('post.');
            $validate=validate('Article');
            if(!($validate->scene('articleWrite')->check($input))){
                $status=0;
                $message=$validate->getError();
            }else{


                $time=time();
                $upd=[
                    'vist_author_id'=>$admin['id'],
                    'article_author'=>$admin['name'],
                    'article_title'=>$input['article_title'],
                    'article_content'=>$input['article_content'],
                    'article_create_time'=>$time,
                    'article_update_time'=>$time,
                ];
                
                if(db('article')->insert($upd)){
                    
                    $status=1;
                    $message='发表成功';
                }else{
                    $status=0;
                    $message='连接超时';
                }
            }

            return [
                'status'=>$status,
                'message'=>$message,
            ];
        }


        return $this->fetch();
    }


}
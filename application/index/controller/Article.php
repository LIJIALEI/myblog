<?php 
namespace app\index\controller;
use app\index\controller\Base;
use app\index\model\Article as ArticleModel;
use think\Validate;
use think\Session;

class Article extends Base{
    //日志主页
    public function articleHome(){
        
        $count= db('article')->count();
        if(!empty($_GET['List'])){
            $list=$_GET['List'];
        }else{
            $list=0;
        }
        $article=db('article');
        if($list==0){
            $article=$article->order('article_on_top desc')->order('article_create_time desc');
        }
        else if($list==1){
            $article=$article->order('look_count desc');
        }
        $article=$article->paginate(5,false,['query' => request()->param()]);
        $data=[
            'title'=>'日志页面',
            'count'=>$count,
            'article'=>$article, 
        ];
        $this->assign($data);
        return $this->fetch();
    }
    
    //发表文章
    public function articleWrite(){
        if(request()->isAjax()){
            $vist= Session::get('vist');
            $input=input('post.');
            $validate= validate('Article');
            if(!($validate->scene('articleWrite')->check($input))){
                $status=0;
                $message=$validate->getError();
            }else{
               

                $time=time();
                $upd=[
                    'vist_author_id'=>$vist['vist_id'],
                    'article_author'=>$vist['vist_name'],
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
                    $message='发表失败';
                }
            }
            return [
                'status'=>$status,
                'message'=>$message,
            ];
            
        }else{
             
            $this->vistCheckLogin();
            $data=[
                'title'=>'编写日志'
            ];
            $this->assign($data);
            return $this->fetch();
        }
        
        
       
    }
    
    //展示文章
    public function articleShow(){
        $vist= Session::get('vist');
        $resRole=db('vist_role')->where('vist_id',$vist['vist_id'])->find();
        $article_id=$_GET['article_id'];
        $comment=db('comment')->where('article_id',$article_id)->order('comment_create_time desc')->paginate(3,false,['query' => request()->param()]);
        
        if(empty($comment)){
            $comment="";
        }
        $res=db('article')->where('article_id',$article_id)->find();
        
        $upd=['look_count'=>$res['look_count']+1];
        db('article')->where('article_id',$article_id)->setField($upd);
        $data=[
            'title'=>'文章展示',
            'vist'=>$vist,
            'article'=>$res,
            'vistRole'=>$resRole,
            'comment'=>$comment,
        ];
        $this->assign($data);
        return $this->fetch();
    }
    
   
    
    
    
}



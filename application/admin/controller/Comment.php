<?php
namespace app\admin\controller;
use app\admin\controller\Base;

use think\Session;

class Comment extends Base{
	//评论列表
	public function commentList(){
		$count=db('comment')->count();
        $comment=db('comment')->alias('c')->join('article a','c.article_id = a.article_id')->order('comment_create_time desc')->paginate(10);
        $res=Session::get('admin');
        $admin=db('user_role')->where('admin_id',$res['id'])->find();
        $data=[
            'admin'=>$admin,
            'comment'=>$comment,
            'count'=>$count,
        ];
        $this->assign($data);
		return $this->fetch();
	}

	//删除评论
    public function commentDel(){
        $comment_id=$_GET['comment_id'];
        $article_id=$_GET['article_id'];
        $admin= Session::get('admin');
        $admin_role=db('user_role')->where('admin_id',$admin['id'])->find();
        $res=db('article')->where('article_id',$article_id)->find();
        $upd=['comment_count'=>$res['comment_count']-1];

        if($admin_role['delComment']!=1){
            $this->error('您无权删除','comment/commentList');
        }else{
            if((db('comment')->where('comment_id',$comment_id)->delete())&&(db('article')->where('article_id',$article_id)->setField($upd))){
                $this->success('删除成功','comment/commentList');
            }else{
                $this->error('删除失败','comment/commentList');
            }
        }       
    }

     //批量删除
    public function commentAllDel(){
        $comment=$_POST['comment_id'];
        // dump($comment);die;
        for($i=0;$i<sizeof($comment);$i++){
            $resCom=db('comment')->where('comment_id',$comment[$i])->find();
            $resArt=db('article')->where('article_id',$resCom['article_id'])->find();
            $upd=['comment_count'=>$resArt['comment_count']-1];
            if((db('comment')->where('comment_id',$comment[$i])->delete())&&(db('article')->where('article_id',$resCom['article_id'])->setField($upd))){
                $status=1;
                $msg='评论删除成功';
            }else{
                $status=0;
                $msg='连接超时';
            }

        }
        return [
            'status'=>$status,
            'message'=>$msg,
        ];


    }



    //意见反馈
    public function suggestionList(){
    	$count=db('suggestion')->count();
        $sugg=db('suggestion')->order('suggestion_create_time desc')->paginate(10);
        $data=[
            'count'=>$count,
            'suggestion'=>$sugg,
        ];
        $this->assign($data);
        return $this->fetch();
    }

    //删除意见
    public function suggestionDel(){
    	$suggestion_id=$_GET['suggestion_id'];
        if(db('suggestion')->where('suggestion_id',$suggestion_id)->delete()){
            $this->success('删除评论成功');
        } else {
            $this->error('连接超时');
        }
    }

     //批量删除
    public function suggAllDel(){
        $suggestion=$_POST['suggestion_id'];
        // dump($comment);die;
        for($i=0;$i<sizeof($suggestion);$i++){
            if(db('suggestion')->where('suggestion_id',$suggestion[$i])->delete()){
                $status=1;
                $msg='意见删除成功';
            } else {
                $status=0;
                $msg='连接超时';
            }

        }
        return [
            'status'=>$status,
            'message'=>$msg,
        ];


    }
}
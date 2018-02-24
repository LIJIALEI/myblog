<?php
namespace app\admin\controller;
use app\admin\controller\Base;

use think\Session;
class Photo extends Base{
	public function photoList(){
		$count=db('Photo')->count();
        $photo=db('Photo')->order('photo_upload_time desc')->paginate(5);
        $res=Session::get('admin');
  		$admin=db('user_role')->where('admin_id',$res['id'])->find();
        $data=[
           	'admin'=>$admin,
            'photo'=>$photo,
            'count'=>$count,

            
        ];
        $this->assign($data);
        return $this->fetch();
	}

    public function photoDel(){
        $photo_id=$_GET['photo_id'];
        if(db('Photo')->where('photo_id',$photo_id)->delete()){
            $this->success('删除成功','photo/photoList');
        }else{
            $this->error('删除失败','photo/photoList');
        }
    }

    public function photoAllDel(){
        $photo=$_POST['photo_id'];
        // dump($photo);die;
        // $photo_id=[];
        // for($n=0;)

        for($i=0;$i<sizeof($photo);$i++){
            if(db('photo')->where('photo_id',$photo[$i])->delete()){
                $status=1;
                $msg="删除照片成功";
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
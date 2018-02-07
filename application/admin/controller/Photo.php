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
}
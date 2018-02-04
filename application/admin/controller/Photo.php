<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Photo as PhotoModel;
use think\Validate;
use think\Session;

class Photo extends Base{
    //照片列表
    public function photoList(){
        $res=db('Photo')->select();
        $photo=db('Photo')->order('photo_upload_time desc')->paginate(5);
        $count= count($res);
        $admin= Session::get('user');
        $res=db('user_role')->where('admin_id',$admin['id'])->find();

       $this->categoryAssign();

        if($admin['role']==1){
            $adminRole='超级管理员';
        }else{
            $adminRole='普通管理员';
        }
        
        $data=[
            'title'=>'图片管理',
            'adminRole'=>$adminRole,
            'photo'=>$photo,
            'count'=>$count,
            'admin'=>$res,
            
        ];
        $this->assign($data);
        return $this->fetch();
    }
    
    //照片删除
    public function photoDel(){
        $photo_id=$_GET['photo_id'];
        if(db('Photo')->where('photo_id',$photo_id)->delete()){
            $this->success('删除成功','photo/photoList');
        }else{
            $this->error('删除失败','photo/photoList');
        }
      
    }


    //管理员上传照片
    // public function photoUpload(){

        
    //     return $this->fetch();
    // }
    

}

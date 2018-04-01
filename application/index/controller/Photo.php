<?php
namespace app\index\controller;
use app\index\controller\Base;
use app\index\model\Photo as PhotoModel;
use think\Validate;
use think\Session;

class Photo extends Base{
    //风景首页
    public function photoHome(){
        if(Session::has('vist')){
            $res=Session::get('vist');
            $vist=db('vist_role')->where('vist_id',$res['vist_id'])->find();
            if($vist['uploadPhoto']==1){
                $role=1;
            }else{
                $role=0;
            }
        }else{
            $role=2;
        }
        
        
        //判断排列条件
        if(!empty($_GET['List'])){
            $list=$_GET['List'];
        }else{
            $list=0;
        }

        $photo=db('Photo');
        if($list==0){
            $photo=$photo->order('photo_upload_time desc');
        }
        else if($list==1){
            $photo=$photo->order('photo_look_count desc');
        }

        $photo=$photo->paginate(3,false,['query' => request()->param()]);

        $data=[
            'title'=>'风景相册',
            'photo'=>$photo,
            'role'=>$role
        ];
        $this->assign($data);
        return $this->fetch();
    }
    


    //风景显示
    public function photoShow(){
        $photo_id=$_GET['photo_id'];
        $photo=db('photo')->where('photo_id',$photo_id)->find();
        $upd=[
            'photo_look_count'=>$photo['photo_look_count']+1
        ];
        db('photo')->where('photo_id',$photo_id)->setField($upd);
        $data=[
            'photo'=>$photo,
        ];
        $this->assign($data);
        return $this->fetch();
    }




    
    //图片上传
    public function photoUpload(){  
        return $this->fetch();
    }
    
    //图片上传2
    public function photoUploadFile(){
        $photo_title=$_POST['photo_title'];
        $time=time();
        
        $vist= Session::get('vist');
        $res=db('vist_role')->where('vist_id',$vist['vist_id'])->find();
        
        if(empty($photo_title)){
            $this->error('标题不能为空');
        } else {
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('image');
            if(!$file){
                $this->error('请选择文件');
            }
            // 移动到框架应用根目录__STATIC__/uploads/sight 目录下
            $url='uploads/sight';
            $info = $file->move(ROOT_PATH . 'public/static' . DS . $url);
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg  ——echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg ——echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg  ——echo $info->getFilename(); 
                
                
                $upd=[
                    'photo_name'=>$info->getFilename(),
                    'photo_title'=>$photo_title,
                    'photo_er'=>$vist['vist_name'],
                    'photo_src'=>$url.'/'.$info->getSaveName(),
                    'photo_upload_time'=>$time,
                ];
                
                
                if(db('Photo')->insert($upd)){
                    $this->success('上传成功');
                }else{
                    // 上传失败获取错误信息
                    $this->error('上传失败');
                }
                
            }else{
                // 上传失败获取错误信息
                $this->error('上传失败');
            }

        }
    }
    
    
    
}
<?php
namespace app\admin\controller;
use app\admin\controller\Base;

use think\Session;

class Admin extends Base
{
    //登录 
    public function login()
    {

       return $this->fetch();
        
    }

     //检查登录
    public function checkLogin(){
       if(request()->isAjax()){
           $data=input('post.');
           $validate= validate('Admin');
           //验证
           if(!($validate->scene('login')->check($data))){
               $status=0;
               $message=$validate->getError();
           }else{
               
               $res=db('user')->where('email',$data['loginEmail'])->find();
               if($res){
                   //判断是否有被禁用
                    if($res['status']==1){
                        //判断密码是否正确
                        if($res['password']==md5($data['loginPassword'])){
                            $status=1;
                            $message='欢迎登陆';

                            $time= time();
                            Session::set('admin',$res);

                            $upd=[
                                    'login_time'=>$time,
                                    'login_count'=>$res['login_count']+1,
                                ];
                            db('user')->where('name',$res['name'])->setField($upd);
                        }else{
                            $status=0;
                            $message='密码错误';
                        }
                    }else{
                        $status=0;
                            $message=$res['name'].'已经被禁用无法登陆';
                    }
                        
               }else{
                    $status=0;
                    $message='邮箱不存在';
               }
           }
           
        return [
            'status'=>$status,
            'message'=>$message,
        ];
       }
    }

       //登出
    public function logout(){
        Session::delete('admin');
        $this->success('退出成功', 'admin/login');
    }

        //admin列表
    public function adminList(){
      $admin=Session::get('admin');

      if($admin['role']==1){
        $count=db('user')->count();
        $res=db('user')->order('role desc')->select();
      }else{
        $res=db('user')->where('id',$admin['id'])->select();
        $count=count($res);
      }
      $data=[
        'admin'=>$res,
        'count'=>$count,
      ];
      $this->assign($data);

      return $this->fetch();
    }

     //admin启用
    public function adminStart(){
        $id=$_GET['id'];
        $time=time();
        $user= Session::get('admin');
        $res=db('user')->where('id',$id)->find();
        if($user['role']==1){
            if($res['role']!=1){
                    $upd=[
                        'status'=>1,
                        'update_time'=>$time,
                    ];
                    if(db('user')->where('id',$id)->setField($upd)){
                        $this->success($res['name'].'已经被启用','admin/adminList');
                    }
            }else{
                   $this->error($res['name'].'是超级管理员不存在被禁用','admin/adminList'); 
            }
        }else{
            $this->error('无权启用','admin/adminList');
        }
    }

    //admin停用
    public function adminStop(){
        $id=$_GET['id'];
        $time=time();
        $user= Session::get('admin');
        $res=db('user')->where('id',$id)->find();
        if($user['role']==1){
            if($res['role']!=1){
                    $upd=[
                        'status'=>0,
                        'update_time'=>$time,
                    ];
                    if(db('user')->where('id',$id)->setField($upd)){
                        $this->success($res['name'].'已经被禁用，请联系超级管理员','admin/adminList');
                    }
            }else{
                   $this->error($res['name'].'是超级管理员不能被禁用','admin/adminList'); 
            }
        }else{
            $this->error('无权禁用','admin/adminList');
        }
            
    }

    //admin编辑
    public function adminEdit(){
        $user= Session::get('admin');
        $res=db('user')->where('id',$user['id'])->find();
        $status=0;
        $message="";
        $time=time();
        $input=input('post.');
        if(request()->isAjax()){
            if(empty($input['prePassword'])){
                $status=0;
                $message="原始密码不能为空";
            }else{
                if($res['password']!=md5($input['prePassword'])){
                    $status=0;
                    $message="原始密码错误";
                }else{

                    $validate= validate('Admin');
                    if(!($validate->scene('adminEdit')->check($input))){
                        $status=0;
                        $message=$validate->getError();
                    }else{
                        if(isset($input['des'])){
                            $upd=[
                            'password'=>md5($input['newPassword']),  
                            'update_time'=>$time,
                            'des'=>$input['des'],
                            ];
                        }else{
                           $upd=[
                            'password'=>md5($input['newPassword']),  
                            'update_time'=>$time,
                            ]; 
                        }

                        if(db('user')->where('id',$user['id'])->setField($upd)){
                            $status=1;
                            $message='修改成功:)';
                        }else{
                            $status=0;
                            $message='修改失败';
                        }

                }

                   
                }
            }
             return [
                        'status'=>$status,
                        'message'=>$message,
                    ];
        }
        
        $data=[
         
            'user'=>$res,
        ];
        $this->assign($data);
        
        return $this->fetch();
    }

    //admin删除
    public function adminDel(){
        $id=$_GET['id'];
        //获取当前管理员级别
        $user= Session::get('admin');
        $res=db('user')->where('id',$id)->find();
        //1才可删除管理员
        if($user['role']==1){
            //若对象role=1则不可被删除
                if($res['role']==1){
                   $this->error($res['name'].'是超级管理员不能被删除','admin/adminList');
        } else {
                if((db('user')->where('id',$id)->delete())&&(db('user_role')->where('admin_id',$id)->delete())){
                    $this->success('管理员'.$res['name'].'已经被删除了','admin/adminList');
                }
            } 
        }else{
            $this->error($res['name'].'不是超级管理员无权删除','admin/adminList');
        }
       
    }

    //admin添加
    public function adminAdd(){
          if(request()->isAjax()){
            $status=0;
            $message="";
            $data=input('post.');
            
            $validate= validate('Admin');
            if(!($validate->scene('adminAdd')->check($data))){
                $status=0;
                $message=$validate->getError();
            } else {
                $time=time();
                $create_time=$time;
                $update_time=$time;
                $upd=[
                    'name'=>$data['name'],
                    'password'=>md5($data['password']),
                    'email'=>$data['email'],
                    'create_time'=>$create_time,
                    'update_time'=>$update_time,
                    'role'=>0,
                    'status'=>1,
                    'des'=>$data['des'],
                ];
                db('user')->insert($upd);
                
                $res=db('user')->where('name',$data['name'])->find();
                $upd_role=[
                    'admin_id'=>$res['id'],
                    'admin_name'=>$res['name'],
                ];
                db('user_role')->insert($upd_role);
                
                $status=1;
                $message='添加成功';
                
            } 
            return [
                'status'=>$status,
                'message'=>$message,
            ];
        }
        
         return $this->fetch();
    }


    //用户管理
    public function adminManageVist(){
        $admin= Session::get('user');
        $res=db('vist')->select();
        $vist=db('vist')->order('vist_id asc')->paginate(10);;
        $count= db('vist')->count();
        $this->categoryAssign();
        if($admin['role']==1){
            $adminRole='超级管理员';
        }else{
            $adminRole='普通管理员';
        }
        $data=[
           
            'count'=>$count,
            'vist'=>$vist,
            'admin'=>$admin,
            
            
        ];
        $this->assign($data);
        return $this->fetch();
    }

    //删除用户
    public function adminDelVist(){
        $admin= Session::get('admin');
        $id=$_GET['id'];
        $vist=db('vist')->where('vist_id',$id)->find();
        if($vist['vist_role']>$admin['role']){
            $this->error('抱歉，普通管理员无法删除会员用户。','admin/adminManageVist');
        }else{
            if((db('vist')->where('vist_id',$id)->delete())&&(db('vist_role')->where('vist_id',$id)->delete())){
                $this->success($vist['vist_name'].'用户删除成功','admin/adminManageVist');
            }else{
                $this->error('抱歉，删除失败。','admin/adminManageVist');
            }
        }
    }

    //停用用户
    public function adminStopVist(){
        $id=$_GET['id'];
        $vist=db('vist')->where('vist_id',$id)->find();
        
        $upd=[ 'vist_status'=>0 ];
            if(db('vist')->where('vist_id',$id)->setField($upd)){
                $this->success($vist['vist_name'].'用户已经停用','admin/adminManageVist');
            }else{
                $this->error($vist['vist_name'].'停用失败','admin/adminManageVist');
            }
    }
    

    //启用用户
    public function adminStartVist(){
        $id=$_GET['id'];
        $vist=db('vist')->where('vist_id',$id)->find();
        
        $upd=[ 'vist_status'=>1 ];
            if(db('vist')->where('vist_id',$id)->setField($upd)){
                $this->success($vist['vist_name'].'用户已经启用','admin/adminManageVist');
            }else{
                $this->error($vist['vist_name'].'启用失败','admin/adminManageVist');
            }
    }
    

    //修改用户权限
    public function adminChangeVist(){

        
        $vist=db('vist_role')->order('vist_role desc')->paginate(10);
        $count= db('vist_role')->count();

        $data=[

            'count'=>$count,
            'vist'=>$vist,
            
        ];
        $this->assign($data);
        
        return $this->fetch();
    }

    public function vistRole(){
        $vist_id=$_GET['vist_id'];
        if(request()->isAjax()){
            
            $radio=$_POST['radio']; 
            $upd['vist_role']=$radio;
            $roleArticle=$_POST['roleArticle'];
            // dump($radio);
            // dump($roleArticle);die;
           
            $num='';
            for($i=0;$i<strlen($roleArticle);$i++){
                if(is_numeric($roleArticle[$i])){
                    $num.=$roleArticle[$i];
                }
            }
            // dump($num);die;
            $upd_role['vist_role']=$radio;
            if(strpos($num,"1")>-1){
                $upd_role['commentArticle']=1;
            }else{
                $upd_role['commentArticle']=0;
            }
            if(strpos($num,"2")>-1){
                
                $upd_role['commentDel']=1;    
                
            }else{
                $upd_role['commentDel']=0; 
            }
            if(strpos($num,"3")>-1){
               
               $upd_role['writeArticlt']=1;     
                
            }else{
                $upd_role['writeArticlt']=0; 
            }
            if(strpos($num,"4")>-1){
               
               $upd_role['uploadPhoto']=1;     
                
            }else{
                $upd_role['uploadPhoto']=0;
            }
            db('vist_role')->where('vist_id',$vist_id)->setField($upd_role);
            db('vist')->where('vist_id',$vist_id)->setField($upd);
            $status=1;
            $message='修改成功';

        
            return [
                'status'=>$status,
                'message'=>$message,
            ];

        }

        $admin=Session::get('admin');
        
        $vist=db('vist')->where('vist_id',$vist_id)->find();
        $vist_role=db('vist_role')->where('vist_id',$vist_id)->find();
        if($admin['role']<$vist['vist_role']){
            $this->error('普通管理员不能修改会员用户的权限和等级');
        }
        $data=[
            'vist'=>$vist,
            'vist_role'=>$vist_role,
        ];
        $this->assign($data);

        return $this->fetch();
    }

    //管理员权限修改
    public function adminRoleChange(){
        
        $admin=db('user_role')->order('admin_role desc')->paginate(10);
        $count= db('user_role')->count();

        $data=[

            'count'=>$count,
            'admin'=>$admin,
            
        ];
        $this->assign($data);
        
        return $this->fetch();
    }

    public function adminRole(){
        $admin_id=$_GET['admin_id'];
        if(request()->isAjax()){
            
            $radio=$_POST['radio']; 
            $upd['role']=$radio;
            $upd_role['admin_role']=$radio;
            $roleArticle=$_POST['roleArticle'];
            // dump($radio);
            // dump($roleArticle);die;
           
            $num='';
            for($i=0;$i<strlen($roleArticle);$i++){
                if(is_numeric($roleArticle[$i])){
                    $num.=$roleArticle[$i];
                }
            }
            // dump($num);die;
            
            if(strpos($num,"1")>-1){
                $upd_role['delArticle']=1;
            }else{
                $upd_role['delArticle']=0;
            }
            if(strpos($num,"2")>-1){
                
                $upd_role['delComment']=1;    
                
            }else{
                $upd_role['delComment']=0; 
            }
            if(strpos($num,"3")>-1){
               
               $upd_role['delPhoto']=1;     
                
            }else{
                $upd_role['delPhoto']=0; 
            }
            
            db('user_role')->where('admin_id',$admin_id)->setField($upd_role);
            db('user')->where('id',$admin_id)->setField($upd);
            $status=1;
            $message='修改成功';

        
            return [
                'status'=>$status,
                'message'=>$message,
            ];

        }

        $admin=Session::get('admin');
        
        $admin=db('user')->where('id',$admin_id)->find();
        $admin_role=db('user_role')->where('admin_id',$admin_id)->find();
       
        $data=[
            'admin'=>$admin,
            'admin_role'=>$admin_role,
        ];
        $this->assign($data);

        return $this->fetch();
    }
    
       

 
}

<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\User as UserModel;
use think\Validate;
use think\Session;

class User extends Base{

    //登录
    public function login(){
//        判断是否已经登录
        $this->alreadyLogin();
        return $this->fetch();
    }
    
    //检查登录
    public function checkLogin(){
       if(request()->isAjax()){
           $data=input('post.');
           $validate= validate('User');
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
                            Session::set('user',$res);

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
        Session::delete('user');
        $this->success('退出成功', 'user/login');
    }
    
    //admin列表
    public function adminList(){
        
        
        $admin=Session::get('user');
        $this->categoryAssign();
        //超级管理员才能查看所有用户
        if($admin['role']==1){
            $res=db('user')->select();
            $user=db('user')->order('id asc')->paginate(10);
            $count=count($res);
            $data=[
                'adminRole'=>'超级管理员',
               'title'=>'管理员列表',
               'description'=>'管理员',
               'keywords'=>'管理用户',
               'user'=>$user,
               'count'=>$count,
               
            ];
             $this->assign($data);
        }else{
            //普通管理员只能查看自己
            $user=db('user')->where('id',$admin['id'])->select();
            $count=count($user);
             $data=[
               'adminRole'=>'普通管理员',
               'title'=>'管理员列表',
               'description'=>'管理员',
               'keywords'=>'管理用户',
               'user'=>$user,
               'count'=>$count,
               
            ];
              $this->assign($data);
        }
        
        
        
        return $this->fetch();
    }
    
    //admin添加
    public function adminAdd(){
          if(request()->isAjax()){
            $status=0;
            $message="";
            $data=input('post.');
            
            $validate= validate('User');
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
    
    //admin删除
    public function adminDel(){
        $id=$_GET['id'];
        //获取当前管理员级别
        $user= Session::get('user');
        $res=db('user')->where('id',$id)->find();
        //1才可删除管理员
        if($user['role']==1){
            //若对象role=1则不可被删除
                if($res['role']==1){
                   $this->error($res['name'].'是超级管理员不能被删除','user/adminList');
        } else {
                if((db('user')->where('id',$id)->delete())&&(db('user_role')->where('admin_id',$id)->delete())){
                    $this->success('管理员'.$res['name'].'已经被删除了','user/adminList');
                }
            } 
        }else{
            $this->error($res['name'].'不是超级管理员无权删除','user/adminList');
        }
       
    }
    
    //admin停用
    public function adminStop(){
        $id=$_GET['id'];
        $time=time();
        $user= Session::get('user');
        $res=db('user')->where('id',$id)->find();
        if($user['role']==1){
            if($res['role']!=1){
                    $upd=[
                        'status'=>0,
                        'update_time'=>$time,
                    ];
                    if(db('user')->where('id',$id)->setField($upd)){
                        $this->success($res['name'].'已经被禁用，请联系超级管理员','user/adminList');
                    }
            }else{
                   $this->error($res['name'].'是超级管理员不能被禁用','user/adminList'); 
            }
        }else{
            $this->error('无权禁用','user/adminList');
        }
            
    }
    
    //admin启用
    public function adminStart(){
        $id=$_GET['id'];
        $time=time();
        $user= Session::get('user');
        $res=db('user')->where('id',$id)->find();
        if($user['role']==1){
            if($res['role']!=1){
                    $upd=[
                        'status'=>1,
                        'update_time'=>$time,
                    ];
                    if(db('user')->where('id',$id)->setField($upd)){
                        $this->success($res['name'].'已经被启用','user/adminList');
                    }
            }else{
                   $this->error($res['name'].'是超级管理员不存在被禁用','user/adminList'); 
            }
        }else{
            $this->error('无权启用','user/adminList');
        }
    }
    
    //admin编辑
    public function adminEdit(){
        $user= Session::get('user');
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

                    $validate= validate('User');
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
            'title'=>'管理员编辑',
            'user'=>$res,
        ];
        $this->assign($data);
        
        return $this->fetch();
    }
    
    //admin权限页面
    public function adminRole(){
        $user= Session::get('user');
        if($user['role']==1){
            $adminRole='超级管理员';
        }else{
            $adminRole='普通管理员';
        }
        $admin=db('user')->where('id',$user['id'])->find();
        $res=db('user_role')->select();
        $user_role=db('user_role')->order('admin_id asc')->paginate(10);
        $count=count($res);
        $this->categoryAssign();
        $data=[
            'title'=>'角色管理',
            'adminRole'=>$adminRole,
            'user_role'=>$user_role,
            // 'admin'=>$admin,
            'count'=>$count,
            
        ];
        $this->assign($data);
        return $this->fetch();
    }
    
    //admin权限更改页面
    public function adminRoleChange(){
        if(request()->isAjax()){
            $id=$_GET['id'];
            $status=0;
            $message='';
            
            $data=input('post.');
            $role=$data['radio'];
            $upd=['role'=>$role];
            db('user')->where('id',$id)->setField($upd);
            
            $articleRole=$data['roleArticle'];
            $time=time();
            $upd=['update_time'=>$time];

            if(empty($articleRole)){
                $upd_role=[
                    'delArticle'=>0,
                    'modifyArticle'=>0,
                    'delComment'=>0,
                    'delPhoto'=>0,
                ];
                
                db('user_role')->where('admin_id',$id)->setField($upd_role);
                db('user')->where('id',$id)->setField($upd);
                $status=1;
                $message='修改成功';
            }else{
                $num='';
                for($i=0;$i<strlen($articleRole);$i++){
                    if(is_numeric($articleRole[$i])){
                        $num.=$articleRole[$i];
                    }
                }
                if($num==1){
                    $upd_role=[
                        'delArticle'=>1,
                        'modifyArticle'=>0,
                        'delComment'=>0,
                        'delPhoto'=>0,
                    ];
                }else if($num==2){
                    $upd_role=[
                        'delArticle'=>0,
                        'modifyArticle'=>1,
                        'delComment'=>0,
                        'delPhoto'=>0,
                    ];
                }else if($num==3){
                    $upd_role=[
                        'delArticle'=>0,
                        'modifyArticle'=>0,
                        'delComment'=>1,
                        'delPhoto'=>0,
                    ];
                }else if($num==4){
                    $upd_role=[
                        'delArticle'=>0,
                        'modifyArticle'=>0,
                        'delComment'=>0,
                        'delPhoto'=>1,
                    ];
                }
                else if($num==12){
                    $upd_role=[
                        'delArticle'=>1,
                        'modifyArticle'=>1,
                        'delComment'=>0,
                        'delPhoto'=>0,
                    ];
                }
                else if($num==13){
                    $upd_role=[
                        'delArticle'=>1,
                        'modifyArticle'=>0,
                        'delComment'=>1,
                        'delPhoto'=>0,
                    ];
                }else if($num==14){
                    $upd_role=[
                        'delArticle'=>1,
                        'modifyArticle'=>0,
                        'delComment'=>0,
                        'delPhoto'=>1,
                    ];
                }
                else if($num==23){
                    $upd_role=[
                        'delArticle'=>0,
                        'modifyArticle'=>1,
                        'delComment'=>1,
                        'delPhoto'=>0,
                    ];
                }
                else if($num==24){
                    $upd_role=[
                        'delArticle'=>0,
                        'modifyArticle'=>1,
                        'delComment'=>0,
                        'delPhoto'=>1,
                    ];
                }else if($num==34){
                    $upd_role=[
                        'delArticle'=>0,
                        'modifyArticle'=>0,
                        'delComment'=>1,
                        'delPhoto'=>1,
                    ];
                }
                else if($num==123){
                    $upd_role=[
                        'delArticle'=>1,
                        'modifyArticle'=>1,
                        'delComment'=>1,
                        'delPhoto'=>0,
                    ];
                }
                else if($num==124){
                    $upd_role=[
                        'delArticle'=>1,
                        'modifyArticle'=>1,
                        'delComment'=>0,
                        'delPhoto'=>1,
                    ];
                }
                else if($num==134){
                    $upd_role=[
                        'delArticle'=>1,
                        'modifyArticle'=>0,
                        'delComment'=>1,
                        'delPhoto'=>1,
                    ];
                }
                else if($num==234){
                    $upd_role=[
                        'delArticle'=>0,
                        'modifyArticle'=>1,
                        'delComment'=>1,
                        'delPhoto'=>1,
                    ];
                }
                else if($num==1234){
                    $upd_role=[
                        'delArticle'=>1,
                        'modifyArticle'=>1,
                        'delComment'=>1,
                        'delPhoto'=>1,
                    ];
                }
                
                db('user')->where('id',$id)->setField($upd);
                db('user_role')->where('admin_id',$id)->setField($upd_role);
                $status=1;
                $message='权限修改成功';
                
                
            }
            return [
                'status'=>$status,
                'message'=>$message,
            ];
            
        }else{
            $id=$_GET['id'];
            $res=db('user')->where('id',$id)->find();  
            $d=[
                'title'=>'权限更改', 
                'user'=>$res,
            ];
            $this->assign($d);
            return $this->fetch();
        }
            
    }
   
    //admin管理vist
    public function adminManageVist(){
        
        $admin= Session::get('user');
        $res=db('vist')->select();
        $vist=db('vist')->order('vist_id asc')->paginate(10);;
        $count= count($res);
        $this->categoryAssign();
        if($admin['role']==1){
            $adminRole='超级管理员';
        }else{
            $adminRole='普通管理员';
        }
        $data=[
            'title'=>'用户列表',
            'keywords'=>'',
            "description"=>'',
            'adminRole'=>$adminRole,
            'count'=>$count,
            'vist'=>$vist,
            'admin'=>$admin,
            
            
        ];
        $this->assign($data);
        return $this->fetch();
    }
    
    //admin删除vist
    public function adminDelVist(){
        $admin= Session::get('user');
        $id=$_GET['id'];
        $vist=db('vist')->where('vist_id',$id)->find();
        if($vist['vist_role']>$admin['role']){
            $this->error('抱歉，普通管理员无法删除会员用户。','user/adminManageVist');
        }else{
            if((db('vist')->where('vist_id',$id)->delete())&&(db('vist_role')->where('vist_id',$id)->delete())){
                $this->success($vist['vist_name'].'用户删除成功','user/adminManageVist');
            }else{
                $this->error('抱歉，删除失败。','user/adminManageVist');
            }
        }
        
        
    }
    
    //admin停用vist
    public function adminStopVist(){
        $id=$_GET['id'];
        $vist=db('vist')->where('vist_id',$id)->find();
        $admin= Session::get('user');
        $admin=db('user')->where('id',$admin['id'])->find();
        if($vist['vist_role']>$admin['role']){
            $this->error($vist['vist_name'].'该用户只能被超级管理员停用','user/adminManageVist');
        }else{
            $upd=[
                'vist_status'=>0,
                ];
            if(db('vist')->where('vist_id',$id)->setField($upd)){
                $this->success($vist['vist_name'].'用户已经停用','user/adminManageVist');
            }else{
                $this->error($vist['vist_name'].'停用失败','user/adminManageVist');
            }
        }
      
    }
    
    //admin启用vist
    public function adminStartVist(){
        $id=$_GET['id'];
        $vist=db('vist')->where('vist_id',$id)->find();
        $upd=[
            'vist_status'=>1,
        ];
        if(db('vist')->where('vist_id',$id)->setField($upd)){
            $this->success($vist['vist_name'].'用户已经启用','user/adminManageVist');
        }else{
            
        }
    }
    
    //admin查看用户权限
    public function adminCheckVRole(){
        
        $user= Session::get('user');
        if($user['role']==1){
            $adminRole='超级管理员';
        }else{
            $adminRole='普通管理员';
        }
        $res=db('vist_role')->select();
        $vist=db('vist_role')->order('vist_id asc')->paginate(10);
        $count= count($res);
        $this->categoryAssign();
        $data=[
            'title'=>'用户权限管理',
            'adminRole'=>$adminRole,
            'count'=>$count,
            'vist'=>$vist,
            
        ];
        $this->assign($data);
        
        return $this->fetch();
    }
    
    //admin修改用户权限
    public function adminChangeVRole(){
        $vist_id=$_GET['vist_id'];
        if(request()->isAjax()){
            $status=0;
            $message='';
            $input=input('post.');
            $articleRole=$input['roleArticle'];
            if(empty($articleRole)){
                $upd_role=[
                    'commentArticle'=>0,
                    'commentDel'=>0,
                    'writeArticlt'=>0,
                    'uploadPhoto'=>0,
                ];
                db('vist_role')->where('vist_id',$vist_id)->setField($upd_role);
                
                $status=1;
                $message='修改成功';
            }else{
                $num='';
                for($i=0;$i<strlen($articleRole);$i++){
                    if(is_numeric($articleRole[$i])){
                        $num.=$articleRole[$i];
                    }
                }
                if($num==1){
                    $upd_role=[
                        'commentArticle'=>1,
                        'commentDel'=>0,
                        'writeArticlt'=>0,
                        'uploadPhoto'=>0,
                    ];
                }else if($num==2){
                    $upd_role=[
                        'commentArticle'=>0,
                        'commentDel'=>1,
                        'writeArticlt'=>0,
                        'uploadPhoto'=>0,
                    ];
                }else if($num==3){
                    $upd_role=[
                        'commentArticle'=>0,
                        'commentDel'=>0,
                        'writeArticlt'=>1,
                        'uploadPhoto'=>0,
                    ];
                }
                else if($num==4){
                    $upd_role=[
                        'commentArticle'=>0,
                        'commentDel'=>0,
                        'writeArticlt'=>0,
                        'uploadPhoto'=>1,
                        'uploadPhoto'=>0,
                    ];
                }
                else if($num==12){
                    $upd_role=[
                        'commentArticle'=>1,
                        'commentDel'=>1,
                        'writeArticlt'=>0,
                        'uploadPhoto'=>0,
                    ];
                }
                else if($num==13){
                    $upd_role=[
                        'commentArticle'=>1,
                        'commentDel'=>0,
                        'writeArticlt'=>1,
                        'uploadPhoto'=>0,
                    ];
                }else if($num==14){
                    $upd_role=[
                        'commentArticle'=>1,
                        'commentDel'=>0,
                        'writeArticlt'=>0,
                        'uploadPhoto'=>1,
                    ];
                }
                else if($num==23){
                    $upd_role=[
                       'commentArticle'=>0,
                        'commentDel'=>1,
                        'writeArticlt'=>1,
                        'uploadPhoto'=>0,
                    ];
                }else if($num==24){
                    $upd_role=[
                       'commentArticle'=>0,
                        'commentDel'=>1,
                        'writeArticlt'=>0,
                        'uploadPhoto'=>1,
                    ];
                }
                else if($num==34){
                    $upd_role=[
                       'commentArticle'=>0,
                        'commentDel'=>0,
                        'writeArticlt'=>1,
                        'uploadPhoto'=>1,
                    ];
                }
                else if($num==123){
                    $upd_role=[
                        'commentArticle'=>1,
                        'commentDel'=>1,
                        'writeArticlt'=>1,
                        'uploadPhoto'=>0,
                    ];
                }
                else if($num==124){
                    $upd_role=[
                        'commentArticle'=>1,
                        'commentDel'=>1,
                        'writeArticlt'=>0,
                        'uploadPhoto'=>1,
                    ];
                }
                else if($num==134){
                    $upd_role=[
                        'commentArticle'=>1,
                        'commentDel'=>0,
                        'writeArticlt'=>1,
                        'uploadPhoto'=>1,
                    ];
                }
                else if($num==234){
                    $upd_role=[
                        'commentArticle'=>0,
                        'commentDel'=>1,
                        'writeArticlt'=>1,
                        'uploadPhoto'=>1,
                    ];
                }
                else if($num==1234){
                    $upd_role=[
                        'commentArticle'=>1,
                        'commentDel'=>1,
                        'writeArticlt'=>1,
                        'uploadPhoto'=>1,
                    ];
                }
                db('vist_role')->where('vist_id',$vist_id)->setField($upd_role);
                $status=1;
                $message='权限修改成功';
                
            }
            return [
                'status'=>$status,
                'message'=>$message,
            ];
        }else{
            $vist=db('vist')->where('vist_id',$vist_id)->find();
            $user= Session::get('user');
            if($user['role']==1){
                $adminRole='超级管理员';
            }else{
                $adminRole='普通管理员';
            }

             $data=[
                'title'=>'用户权限管理',
                'adminRole'=>$adminRole,
                'vist'=>$vist,
            ];
            $this->assign($data);

            return $this->fetch();
        }
        
    }
    
    //变更用户等级 
    public function vistChange(){
        if(request()->isAjax()){
            $vist_id=$_GET['id'];
            $status=0;
            $message='';
            $input=input('post.');
            $vist_role=$input['radio'];
            if($vist_role==1){
                $upd=['vist_role'=>$vist_role,];
                $upd_role=['commentDel'=>1];
                db('vist')->where('vist_id',$vist_id)->setField($upd);
                db('vist_role')->where('vist_id',$vist_id)->setField($upd_role);
            }else{
                 $upd=['vist_role'=>$vist_role,];
                $upd_role=['commentDel'=>0];
                db('vist')->where('vist_id',$vist_id)->setField($upd);
                db('vist_role')->where('vist_id',$vist_id)->setField($upd_role);
            }
            
            $status=1;
            $message='修改成功';
            return [
                'status'=>$status,
                'message'=>$message,
            ];
        }
        
        
        $vist_id=$_GET['vist_id'];
        $vist=db('vist')->where('vist_id',$vist_id)->find();
        
        $data=[
            'vist'=>$vist,
        ];
        $this->assign($data);
        return $this->fetch();
    }
    
    
    
}


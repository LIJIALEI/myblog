<?php
namespace app\index\controller;
use app\index\controller\Base;
use app\index\model\Vist as VistModel;
use think\Validate;
use think\Session;

class Vist extends Base{
    
    //游客注册
    public function vistRegi(){

            if(request()->isAjax()){
                $data=input('post.');
                $validate= validate('Vist');
               if(!($validate->scene('vistRegi')->check($data))){
                   $status=0;
                   $message=$validate->getError();
               }else{
                   $time=time();
                   $upd=[
                       'vist_name'=>$data['vist_name'],
                       'vist_email'=>$data['vist_email'],
                       'vist_password'=>md5($data['vist_password']),
                       'vist_creat_time'=>time(),

                   ];
                   db('vist')->insert($upd);

                   $res=db('vist')->where('vist_email',$data['vist_email'])->find();
                   $upd_role=[
                       'vist_name'=>$res['vist_name'],
                       'vist_id'=>$res['vist_id'],
                   ];
                   db('vist_role')->insert($upd_role);

                   $status=1;
                   $message="注册成功";
               }
               return [
                   'status'=>$status,
                   'message'=>$message,
               ]; 

            }else{
                $data=[
                    'title'=>'注册',

                ];
                $this->assign($data);
                return $this->fetch();
            }

        }

    //游客登录
    public function vistLogin(){
            if(request()->isAjax()){
                $data=input('post.');
                $validate= validate('Vist');
               if(!($validate->scene('vistLogin')->check($data))){
                   $status=0;
                   $message=$validate->getError();
               }else{
                   $res= db('vist')->where('vist_email',$data['loginEmail'])->find();
                   if(!$res){
                        $status=0;
                        $message='邮箱不存在';
                   }else{
                       if($res['vist_password']!=md5($data['loginPassword'])){
                            $status=0;
                            $message='密码错误';
                       }else{
                           if($res['vist_status']==0){
                                $status=0;
                                $message='该用户已被禁用 不可登录';
                            }else{
                                $time=time();
                                $vist_login_count=$res['vist_login_count'];
                                $upd=[
                                    'vist_login_time'=>$time,
                                    'vist_login_count'=>$vist_login_count+1,
                                ];
                                Session::set('vist',$res);
                                db('vist')->where('vist_email',$res['vist_email'])->setField($upd);

                                $status=1; 
                                $message='欢迎登录';
                            }

                       }
                   }
               }
                return [
                    'status'=>$status,
                    'message'=>$message,
                ];
            }else{
                $data=[
                    'title'=>'登录博客',

                ];
                $this->assign($data);
            return $this->fetch();
            }

        }

    //游客登出
    public function vistLogout(){
           Session::delete('vist');
           $this->success('退出成功','index/index');
       } 

    //游客信息
    public function vistInfo(){
        $vist= Session::get('vist');
        $res=db('vist')->where('vist_id',$vist['vist_id'])->find();
       
        $artiRes=db('vist')->where('vist_id',$vist['vist_id'])->alias('v')->join('article a','v.vist_id = a.vist_author_id')->select();
       if(!empty($artiRes)){
            foreach ($artiRes as $key => $value) {
               $artArr[$key]=$value;
            }  
            
        }else{
            $artArr="";
        }
        

        $data=[
                'title'=>'用户信息中心',
                'vist'=>$res, 
                
              ];
        $this->assign($data);
        $this->assign('artArr',$artArr);
        return $this->fetch();
    }

   //游客信息修改
    public function vistEdit(){
        if(request()->isAjax()){
            $vist= Session::get('vist');
            $res=db('vist')->where('vist_id',$vist['vist_id'])->find();
            $input=input('post.');
            $validate= validate('Vist');
            if(!($validate->scene('vistEdit')->check($input))){
                $status=0;
                $message=$validate->getError();
            }else{
                if($input['vist_age']<=0||$input['vist_age']>120){
                    $status=0;
                    $message='年龄有问题';
                } else {
                    if(!empty($input['des'])){
                        $upd=[
                            'vist_name'=>$input['vist_name'],
                            'vist_age'=>$input['vist_age'],
                            'vist_des'=>$input['des'],
                        ];
                    }else{
                        $upd=[
                            'vist_name'=>$input['vist_name'],
                            'vist_age'=>$input['vist_age'],
                        ];
                    }
                    if(db('vist')->where('vist_id',$res['vist_id'])->setField($upd)){
                        $status=0;
                        $message='修改成功';
                        
                    } else {
                        $status=0;
                        $message='修改失败';
                    }
                }
            }
            return [
                'status'=>$status,
                'message'=>$message,
            ];
        }else{
            $vist= Session::get('vist');
            $res=db('vist')->where('vist_id',$vist['vist_id'])->find();
            $data=[
                'title'=>'用户修改',
                'vist'=>$res,
            ];
            $this->assign($data);
            return $this->fetch();
        }
    }
    
    
    //游客修改密码
    public function vistChangePwd(){
        if(request()->isAjax()){
            $vist= Session::get('vist');
            $res=db('vist')->where('vist_id',$vist['vist_id'])->find();
            $input=input('post.');
            $validate= validate('Vist');
            
                if($res['vist_password']!=md5($input['prePassword'])){
                    $status=0;
                    $message='原密码错误';
                }else{
                    if(!($validate->scene('vistChangePwd')->check($input))){
                        $status=0;
                        $message=$validate->getError();
                    }else{
                        $upd=[
                            'vist_password'=>md5($input['newPassword']),
                        ];
                        if(db('vist')->where('vist_id',$res['vist_id'])->setField($upd)){
                            $status=1;
                            $message='修改成功';
                        }else{
                            $status=0;
                            $message='修改失败';
                        }
                    }
                }
                
            
            return [
                'status'=>$status,
                'message'=>$message,
            ];
        }else{
            
            $vist= Session::get('vist');
            $res=db('vist')->where('vist_id',$vist['vist_id'])->find();
            $data=[
                    'title'=>'用户修改',
                    'vist'=>$res,
            ];
                $this->assign($data);
                return $this->fetch();
        }
    }
    
    //游客评论
    public function vistComment(){
        if(request()->isAjax()){
            $article_id=$_GET['article_id'];
            $vist= Session::get('vist');
            $input=input('post.');
            $validate= validate('Vist');
            if(!($validate->scene('vistComment')->check($input))){
                $status=0;
                $message=$validate->getError();
            }else{
                $time=time();
                $upd=[
                    'article_id'=>$article_id,
                    'vist_id'=>$vist['vist_id'],
                    'vist_name'=>$vist['vist_name'],
                    'comment_create_time'=>$time,
                    'comment_des'=>$input['com'],
                ];
                $article=db('article')->where('article_id',$article_id)->find();

                $com=[
                    'comment_count'=>$article['comment_count']+1,
                ];

                if(db('comment')->insert($upd)){
                    if(db('article')->where('article_id',$article_id)->setField($com)){
                        $status=1;
                        $message='发表成功';
                    }else{
                        $status=0;
                        $message='评论连接超时';
                    }
                    
                }else{
                    $status=0;
                    $message='连接超时';
                }
            }
            return [
                'status'=>$status,
                'message'=>$message,
            ];
            
        }else{

            $id=$_GET['id'];
            $article=db('article')->where('article_id',$id)->find();
            $data=[
                'title'=>'评论',
                'article'=>$article,
                
            ];
            $this->assign($data);
            return $this->fetch();
            
            
        }
        
        
    }
    
    //游客删除评论
    public function vistDelCom(){
        $vist= Session::get('vist');
        if(empty($vist)){
            $this->error('请先登录','vist/vistLogin');
        }
        
        $article_id=$_GET['article_id'];
        $comment_id=$_GET['comment_id'];

        $article=db('article')->where('article_id',$article_id)->find();
        $vist_role=db('vist_role')->where('vist_id',$vist['vist_id'])->find();
        if($vist['vist_id']!=$article['vist_author_id']){
            $this->error('你不是文章的主人怎么可以删除');
        }else{
            if($vist_role['commentDel']==1){
                if(db('comment')->where('comment_id',$comment_id)->delete()){
                    $this->success('删除成功');
                }else{
                    $this->error('连接超时');
                }
            }else{
                $this->error('抱歉你不能删除');
            }
        }
        
        
    }
    
    //游客发送意见
    public function vistSend(){
        if(request()->isAjax()){
            $input=input('post.');
            $vist_id=$_GET['vist_id'];
            $vist=db('vist')->where('vist_id',$vist_id)->find();
            if(empty($input['tit'])){
                $status=0;
                $message='主题不能为空';
            }else{
                if(empty($input['sugg'])){
                    $status=0;
                    $message='内容不能为空';
                }else{
                    $time=time();
                    $upd=[
                        'vist_id'=>$vist['vist_id'],
                        'vist_name'=>$vist['vist_name'],
                        'suggestion_title'=>$input['tit'],
                        'suggestion_content'=>$input['sugg'],
                        'suggestion_create_time'=>$time,
                    ];
                    if(db('suggestion')->insert($upd)){
                        $status=1;
                        $message='感谢您的建议';
                    }else{
                        $status=0;
                        $message='抱歉，连接超时了';
                    }
                }
            }
            
            return [
                'status'=>$status,
                'message'=>$message,
            ];
            
        }
    }
    
    
}


<?php
namespace app\index\validate;
use think\Validate;
use app\index\Vist as Model;

class Vist extends Validate{
    protected  $rule=[
        //游客注册
        ['vist_name','require|max:15','用户名不能为空|用户名过长'],
        ['vist_password','require|max:15','登录密码不能为空|密码过长'],
        ['vist_password_confirm','require|confirm','确认密码不能为空|两次密码不相同'],
        ['vist_email','require|unique:vist|email','邮箱不能为空|邮箱已使用|邮箱格式不正确'],
        // ['vist_tel','require|unique:vist','手机不能为空|手机已使用'],
        
        //游客登录
        ['loginEmail','require','邮箱不能为空'],
        ['loginPassword','require','密码不能为空'],
        
        //游客编辑
        ['vist_name','require','昵称不能为空'],
        ['vist_age','require','年龄不能为空'],
        
        //修改密码
        ['prePassword','require','原密码不能为空'],
        ['newPassword','require|max:15','新密码不能为空|密码不能多于15位'],
        ['newPassword_confirm','require|max:15|confirm','确认密码不能为空|密码不能多于15位|两次密码不相同'],
        
        //发表评论
        ['com','require','评论不能为空'],
        
    ];
    
    
    protected $scene=[
        'vistRegi'=>['vist_name','vist_password','vist_password_confirm','vist_email'],
        'vistLogin'=>['loginEmail','loginPassword'],
        'vistEdit'=>['vist_name'],
        'vistChangePwd'=>['prePassword','newPassword','newPassword_confirm'],
        'vistComment'=>['com'],
    ];
    
}
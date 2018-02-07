<?php
namespace app\admin\validate;
use think\Validate;
use app\admin\Admin as Model;

class Admin extends Validate{
    protected  $rule=[
        //login
        ['loginEmail','require','邮箱不能为空'],
        ['loginPassword','require','密码不能为空'],
        ['verify','require|captcha','验证码不能为空|验证码错误'],
        
        //adminAdd
        ['name','require|unique:user|max:15','管理员名不能为空|用户名已存在|用户名过长'],
        ['password','require|max:15','密码不能为空|密码不能多于15位'],
        ['password_confirm','require|max:15|confirm','确认密码不能为空|密码不能多于15位|两次密码不相同'],
        ['email','require|unique:user|email','邮箱不能为空|该邮箱已使用|邮箱格式不正确'],
        
        //adminEdit
        
        ['newPassword','require|max:15','新密码不能为空|密码不能多于15位'],
        ['newPassword_confirm','require|max:15|confirm','确认密码不能为空|密码不能多于15位|两次密码不相同'],
    ];
    
    protected $scene=[
        'login'=>['loginEmail','loginPassword','verify'],
        'adminAdd'=>['name','password','password_confirm','email'],
        'adminEdit'=>['newPassword','newPassword_confirm'],
    ];
}

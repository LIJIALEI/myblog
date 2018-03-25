<?php
namespace app\admin\validate;
use think\Validate;

class Article extends Validate{
    protected  $rule=[
        //文章编写
        ['article_title','require|max:30','文章标题不能为空|标题过长'],
        ['article_content','require','文章内容不能为空'],
    ];
    
    
    protected $scene=[
        'articleWrite'=>['article_title','article_content'],
    ];
    
}
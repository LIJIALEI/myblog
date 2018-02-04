<?php
namespace app\index\validate;
use think\Validate;
use app\index\Photo as Model;

class Photo extends Validate{
    protected  $rule=[
        ['photo_title','require|max:15','图片标题不能为空|标题过长'],
        
    ];
    
    
    protected $scene=[
        'photoUpload'=>['photo_title'],
    ];
    
}
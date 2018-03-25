<?php
namespace app\admin\validate;
use think\Validate;


class Category extends Validate{
    protected  $rule=[

        //cateAdd
        ['cate_title','require|max:30','栏目名不能为空|标题过长'],
      
      
    ];
    
    protected $scene=[
        'cateAdd'=>['cate_title'],
       
    ];
}

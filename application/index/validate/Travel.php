<?php
namespace app\index\validate;
use think\Validate;
use app\index\Travel as Model;

class Travel extends Validate{
    protected  $rule=[
        
    	
        ['sponsor_tel','require|number|length:11','手机号不能为空|必须为数字|必须为11位'],
        ['travel_place','require','目的地不能为空'],
        ['sponsor_starttime','require','开始日期不能为空'],
        ['sponsor_endtime','require','结束日期不能为空'],

        
    ];
    
    
    protected $scene=[
        'go'=>['sponsor_tel','travel_place','sponsor_starttime','sponsor_endtime'],
    ];
    
}
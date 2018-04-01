<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function getRole($i){
	if($i==1){
		echo '超级管理员';
	}else{
		echo '普通管理员';
	}
}

function getAdminStatus($i){
	if($i==1){
		echo '已启用';
	}else{
		echo '已停用';
	}
}

//栏目递归树
function getTree($cate,$pid=0,$level=0,$html='∟'){
    $arr=array();
    foreach ($cate as $v){
        if($v['pid']==$pid){
            $v['level']=$level+1;
            $v['html']=str_repeat($html,$level);
            $arr[]=$v;
            $arr=array_merge($arr,getTree($cate,$v['id'],$level+1,$html));
        }
    }
    return $arr;
}


//category递归
function loop($data, $id=0) {
        $list = array();
        foreach($data as $v) {
            if($v['pid'] == $id) {
                $v['son'] = loop($data, $v['id']);
                if(empty($v['son'])) {
                    $v['son']=='';
                }
                array_push($list, $v);
            }
        }
        return $list;
}

//url转写
function getCateUrl($url){
    if(empty($url)){
        echo "无";
    }else{
        $str=str_replace("../","","$url");
        $url=str_replace(".html", "", "$str");
        echo $url;
    }
}

function getVistRole($i){
    if($i==1){
        echo '会员用户';
    }else{
        echo '普通用户';
    }
}

function getVistStatus($status){
    if($status==1){
        echo '已启用';
    }else{
        echo '已停用';
    }
}

function vistCArticleFunc($role){
    if($role==1){
        echo '评论文章';
    }else{
        
    }
}

function commentDelFunc($role){
    if($role==1){
        echo '删除评论';
    }else{
        
    }
}

function vistWArticltFunc($role){
    if($role==1){
        echo '发表文章';
    }else{
        
    }
}


function vistUploadPhotoFunc($role){
    if($role==1){
        echo '上传图片';
    }else{
        
    }
}

function delArticle($role){
    if($role==1){
        echo '删除文章';
    }else{
        
    }
}

function delComment($role){
    if($role==1){
        echo '删除评论';
    }else{
        
    }
}

function delPhoto($role){
    if($role==1){
        echo '删除照片';
    }else{
        
    }
}

function onTop($role){
    if($role==1){
        echo '已置顶';
    }else{
        echo '未置顶';
    }
}


function cateRole($role){
    if($role==1){
        echo '不能';
    }else{
        echo '能';
    }
}


function status($i){
    if($i==1){
        echo '已通过';
    }else{
        echo '未通过';
    }
}
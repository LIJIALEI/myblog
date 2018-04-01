<?php 
namespace app\index\controller;
use app\index\controller\Base;
use think\Validate;
use think\Session;

class Travel extends Base{

	//
	public function travel(){
		if(!Session::has('vist')){
			$role=2;
		}else{
			$res=Session::get('vist');
            $vist=db('vist_role')->where('vist_id',$res['vist_id'])->find();
            if($vist['vist_role']==1){
                $role=1;
            }else{
                $role=0;
            }
		}

		$title='旅游团';
		$this->assign('title',$title);
		$this->assign('role',$role);
		return $this->fetch();
	}

	public function sponsTravel(){
		$vist=Session::get('vist');
		$this->assign('vist',$vist);
		return $this->fetch();
	}

	public function go(){
		$vist=Session::get('vist');
		$upd['sponsor_id']=$vist['vist_id'];
		$upd['sponsor_name']=$vist['vist_name'];
		$upd['sponsor_tel']=$_POST['sponsor_tel'];
		$upd['travel_place']=$_POST['travel_place'];
		$upd['sponsor_starttime']=strtotime($_POST['sponsor_starttime']) ;
		$upd['sponsor_endtime']=strtotime($_POST['sponsor_endtime']) ;
		if(!empty($_POST['travel_des'])){
			$upd['travel_des']=$_POST['travel_des'];
		}else{
			$upd['travel_des']='';
		}
		

		$validate=validate('Travel');
		if(!($validate->scene('go')->check($upd))){
			$this->error($validate->getError());
		}else{
			$file = request()->file('image');
            if(!$file){
                $this->error('请选择文件');
            }
            // 移动到框架应用根目录__STATIC__/uploads/travel 目录下
            $url='uploads/travel';
            $info = $file->move(ROOT_PATH . 'public/static' . DS . $url);
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg  ——echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg ——echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg  ——echo $info->getFilename(); 
                $upd['travel_img_url']=$url.'/'.$info->getSaveName();
                $upd['creatime']=time();
                
                
                
                if(db('Travel')->insert($upd)){
                    $this->success('发表成功，等待管理员审批');
                }else{
                    // 上传失败获取错误信息
                    $this->error('连接超时');
                }
			}
		}
	}

}
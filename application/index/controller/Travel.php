<?php 
namespace app\index\controller;
use app\index\controller\Base;
use app\index\model\Travel as TravelModel;
use think\Validate;
use think\Session;

class Travel extends Base{

	//
	public function travel(){
		if(!Session::has('vist')){
			$role=2;
		}else{
			$vist=Session::get('vist');
            $vr=db('vist_role')->where('vist_id',$vist['vist_id'])->find();
            $this->assign('vist',$vist);
            if($vr['vist_role']==1){
                $role=1;
            }else{
                $role=0;
            }
		}


		
		if(!empty($_GET['List'])){
			$list=$_GET['List'];
		}else{
			$list=0;
		}
			
		if($list==1){
			$res=db("travel")->where('status',1)->order('participant_count desc')->paginate(3,false,['query' => request()->param()]);
		}else{
			$res=db("travel")->where('status',1)->order('creatime desc')->paginate(3,false,['query' => request()->param()]);
			
		}
		
		$title='旅游团';
		$this->assign('title',$title);
		$this->assign('role',$role);
		$this->assign('travel',$res);

		return $this->fetch();
	}

	//发起旅游
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

	public function travelDetail(){
		$travel_id=$_GET['travel_id'];
		$model=new TravelModel();
		$res=$model->where('travel_id',$travel_id)->find();
		$this->assign('travel',$res);

		if(!Session::has('vist')){
			$role=2;
		}else{
			$res=Session::get('vist');
            $this->assign('vist',$res);
            $role=1;
		}
		$this->assign('role',$role);
		return $this->fetch();
	}

	//加入旅游
	public function travelAdd(){
		$data['vist_id']=$_GET['vist_id'];
		$data['travel_id']=$_GET['travel_id'];
		$res=db('travel')->where('travel_id',$data['travel_id'])->find();
		$t=db('vist_travel')->where('travel_id',$data['travel_id'])->select();
		// dump($data);die;
	
		$vist_id=[];
		foreach ($t as $key => $value) {
			$vist_id[]=$value['vist_id'];
		}
		// dump($vist_id);die;

		if(in_array($data['vist_id'], $vist_id)){
			$this->error('您已经报名过了');			
		}else{ 
			$d['participant_count']=$res['participant_count']+1;
			if(db('vist_travel')->insert($data)){
				if(db('travel')->where('travel_id',$data['travel_id'])->setField($d)){
					$this->success('加入成功');
				}
			}else{
				$this->error('连接超时','travel/travel');
			}
		}


		
		
		

	}

}
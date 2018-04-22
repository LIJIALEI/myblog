<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Travel as TravelModel;
use think\Session;

class Travel extends Base{

	//旅游列表
	public function travelList(){
		$model=new TravelModel();
		$res=$model->order('status asc')->order('creatime desc')->paginate(10);
		$count=$model->count();
		$this->assign('travel',$res);
		$this->assign('count',$count);
		return $this->fetch();
	}

	//添加旅游
	public function addTravel(){
		$admin=Session::get('admin');
		$this->assign('admin',$admin);
		return $this->fetch();
	}

	public function go(){
		$admin=Session::get('admin');
		$upd['sponsor_id']=$admin['id'];
		$upd['sponsor_name']=$admin['name'];
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
                $upd['status']=1;
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


	//旅游信息详情
	public function travelDetail(){
		$travel_id=$_GET['travel_id'];
		$model=new TravelModel();
		$res=$model->where('travel_id',$travel_id)->find();
		$this->assign('travel',$res);
		return $this->fetch(); 
	}

	//删除旅游信息
	public function delTravel(){
		$travel_id=$_GET['travel_id'];
		$model=new TravelModel();
		if($model->where('travel_id',$travel_id)->delete()){
			$this->success('删除旅游信息成功');
		}else{
			$this->error('连接超时');
		}
	}

	//批量删除
	public function travelAllDel(){
		$travel_id=$_POST['travel_id'];
		// dump($travel_id);die;
		$model=new TravelModel();
		for($i=0;$i<sizeof($travel_id);$i++){
            if($model->where('travel_id',$travel_id[$i])->delete()){
                $status=1;
                $msg='批量删除成功';
            }else{
                $status=0;
                $msg='连接超时';
            }
        }

        return [
            'status'=>$status,
            'message'=>$msg,
        ];
	}


	//通过审批
	public function travelStatus(){
		$upd['status']=$_GET['status'];
		$travel_id=$_GET['travel_id'];
		$model=new TravelModel();
		$res=$model->where('travel_id',$travel_id)->find();
		$upd['creatime']=time();
		if($model->where('travel_id',$travel_id)->update($upd)){
			if($upd['status']==1){
				$this->success($res['travel_place'].'旅游计划审批通过，将在前台显示。');
			}else{
				$this->error($res['travel_place'].'旅游计划已经取消。');
			}
			
		}else{
			$this->error('连接超时');
		}

	}



}
 <?php
	class OndoModel extends Model{
		public $user;
		function __construct(){
			parent::__construct();
			$user=D('User')->find($_SESSION['uid']);
			if ($user) {
				$this->user=$user;//$user['num'],$user['size']
			}else{
				return false;
			}
		}
		function add_tid($tid){
			if($this->user['num']>$this->user['now_num']){
				$file=D('Torrent')->find($tid);
				if(!$file){
					$re['status']=false;
					$re['con']="无该BT文件";
					return $re;
				}
				if($file['size']&&($file['size']<($this->user['size']-$this->user['now_size']))){//存在
					$is_v=$this->is_hash($file);
					if(!$is_v){
						D('User')->oid($file);
						$re['status']=true;
						$re['con']="你已经添加一个离线任务";
						$re['magnet']=$file['magnet'];
					}else{
						$user_d=D('User')->oid($file);
						if($user_d['status']){
							$re['status']=false;
							$re['con']="该任务你已经添加！";
						}else{
							$re['status']=true;
							$re['con']="你已经添加一个离线任务";
						}
					}
				}else{
					$re['status']=false;
					$re['con']="你已经超过配额SIZE";
				}
			}else{
				$re['status']=false;
				$re['con']="你已经超过配额NUM";
			}
			return $re;

		}
		function is_hash($file){
			$hash=$file['hash'];
			$where['hash']=$hash;
			$is=$this->where($where)->select();
			if($is){//任务存在【秒下】
				$re=true;
			}else{
				$re=false;
				$add['tid']=$file['tid'];
				$add['hash']=$file['hash'];
				$add['dir']=DIR."/download/".md5($file['magnet']);
				$add['total']=$file['size'];
				$this->add($add);
			}
			return $re;
		}
		function jugg($info){
			foreach ($info as $key => $value) {
				$where['hash']=$value['hash'];
				$on=$this->where($where)->select();
				if($on){//save
					$this->where($where)->save($value);
				}else{//
					$this->add($value);
				}
				
			}
		}
		function hash_info($info){
			foreach ($info as $key => $value) {
				$where['hash']=$value;
				$torrent=D('Torrent')->where($where)->select();
				$swap_1=$torrent[0];
				$ondo=$this->where($where)->select();
				$swap_2=$ondo[0];
				$swap_2['name']=$swap_1['name'];
				$swap_2['magnet']=$swap_1['magnet'];
				$swap_3[]=$swap_2;
			}
			return $swap_3;
		}
		
	}
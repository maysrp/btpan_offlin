<?php
	class OndoAction extends Action{
		public $Aria2;
		function __construct(){
			parent::__construct();
			if(!$_SESSION['uid']){
				$this->error("请先登入！",U("User/login"));
			}
			include 'Aria2.php';
			$this->Aria2=new Aria2();
		}
		function index(){
			$uid=$_SESSION['uid'];
			$uin=D('User')->find($uid);
			$hash_info=json_decode($uin['num_all'],true);
			$info=D('Ondo')->hash_info($hash_info);
			$this->assign("info",$info);
			$this->assign("user",$uin);
			$this->display();
		}
		function add(){
			$tid=(int)$_GET['tid'];
			if ($_SESSION['uid']) {
				$tid=(int)$_GET['tid'];
				$re=D('Ondo')->add_tid($tid);//判断大小保存
				if($re['status']==false){
					$this->error($re['con'],"/index.php/Ondo/index");
				}else{
					if($re['magnet']){
						$dr=md5($re['magnet']);
						$url=$re['magnet'];
						$dir=DIR."/download/".$dr;
						$this->Aria2->addUri(array($url),array('dir'=>$dir,));
						$this->success("添加成功！","/index.php/Ondo/index");
						//var_dump($re);
					}else{
						$this->success("添加成功！","/index.php/Ondo/index");

					}
				}
			}else{
				$this->errer("请先登入","/index.php/Ondo/index");
			}
		}
		function insert(){
			$ip=$_SERVER['REMOTE_ADDR'];
$ip="127.0.0.1";
			if ($ip=="127.0.0.1") {
				$active=$this->Aria2->tellActive();
				$wait=$this->Aria2->tellWaiting(0,100);
				$stop=$this->Aria2->tellStopped(0,100);
//var_dump($stop);
				$this->setinfo($stop);
				$this->setinfo($wait);
				$this->setinfo($active);
			}
		}
		function setinfo($info){
			$re_inf=$this->getinfo($info);
			D('Ondo')->jugg($re_inf);//用于判断保存
		}
		function getinfo($xinfo){
			$info=$xinfo['result'];
			foreach ($info as $key => $value) {
				$tr['hash']=$value['infoHash'];
				$tr['gid']=$value['gid'];
				$tr['status']=$value['status'];
				$tr['complete']=$value['completedLength'];
				$tr['speed']=$value['downloadSpeed'];
				//$tr['total']=$value['totalLength'];
				$all[]=$tr;
			}
			return $all;
		}
		function dir(){
			if($_GET['hash']){
				$user=D('User')->find($_SESSION['uid']);
				$all=json_decode($user['num_all'],true);
				if(in_array($_GET['hash'], $all)){
					$where['hash']=$_GET['hash'];
					$ondo=D('Ondo')->where($where)->select();
//var_dump($ondo);
					$_SESSION['dir']=$ondo[0]['dir'];
					//var_dump($_SERVER['HTTP_HOST']);
//var_dump($_SESSION['dir']);
					header("Location:http://".$_SERVER['HTTP_HOST']."/dir.php");
					//$this->success("/dir.php");

				}else{
					$this->error("无该文件");
				}
			}
		}
	}

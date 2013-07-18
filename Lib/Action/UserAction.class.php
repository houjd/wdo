<?php
class UserAction extends Action {
	public function add(){
		$user = get_cookie();
		if(!$user[0]){
			JS::Exitframeset(__ROOT__."/index.php/Index/login");
		}
		if($user[0] != 1){
			JS::_Goto(__ROOT__."/index.php");
		}

		if($_POST)
		{
			$username = stripslashes(urldecode(trim($_POST['username'])));	
			$userpwd1 = stripslashes(urldecode(trim($_POST['password1'])));
			$userpwd2 = stripslashes(urldecode(trim($_POST['password2'])));
			$sjname = stripslashes(urldecode(trim($_POST['sjname'])));
			if(empty($username)){
				JS::Alert('用户名不能为空！');
				JS::Back();
			}
			$User = M('User');
			$userinfo = $User->where("uname='$username'")->select();
			if($userinfo){
				JS::Alert('用户名已存在，请重新输入！');
				JS::Back();
			}
			if(empty($sjname)){
				JS::Alert('请输入经销商名称！');
				JS::Back();
			}
			if(empty($userpwd1)){
				JS::Alert('请输入密码！');
				JS::Back();
			}
			if(empty($userpwd2)){
				JS::Alert('请输入确认密码！');
				JS::Back();
			}
			if($userpwd1 != $userpwd2){
				JS::Alert('确认密码输入不正确！');
				JS::Back();
			}
			if (preg_match("/^[0-9a-zA-Z]{4,16}$/",$username)){
				$data['uname'] = $username;
				$data['password'] = md5($userpwd1);
				$data['cname'] = $sjname;
				//$data['sign'] = md5($user_id.rand(100, 999).time());
				$User->add($data);
				}else{
					JS::Alert("输入4-16位字符或数字作为用户名!");
					JS::Back();
			}
			JS::Alert("添加成功");
		}
		
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
	
	
	public function password(){
		$user = get_cookie();
		if(!$user[0]){
			JS::Exitframeset(__ROOT__."/index.php/Index/login");
		}

		$userinfo['uname'] = $user[1];
		if($_POST)
		{
			$username = stripslashes(urldecode(trim($_POST['username'])));
			$userpwd = stripslashes(urldecode(trim($_POST['password'])));	
			$userpwd1 = stripslashes(urldecode(trim($_POST['password1'])));
			$userpwd2 = stripslashes(urldecode(trim($_POST['password2'])));
			if(empty($userpwd)){
				JS::Alert('请输入旧密码！');
				JS::Back();
			}
			if(empty($userpwd1)){
				JS::Alert('请输入密码！');
				JS::Back();
			}
			if(empty($userpwd2)){
				JS::Alert('请输入确认密码！');
				JS::Back();
			}
			if($userpwd1 != $userpwd2){
				JS::Alert('确认密码输入不正确！');
				JS::Back();
			}
			$User = M('User');
			$userinfo = $User->where("uname='{$userinfo['uname']}'")->select();
			$userinfo = $userinfo[0];

			if($userinfo && $userinfo['password'] == md5($userpwd)){
				$data['password'] = md5($userpwd1);
				$User->where("id={$userinfo['id']}")->save($data);
				JS::Alert('密码修改成功！');
			}else{
				JS::Alert("原密码错误!");
				JS::Back();
			}

		}
		$this->userinfo = $userinfo;
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
	public function userlist(){
		$user = get_cookie();
		if(!$user[0]){
			JS::Exitframeset(__ROOT__."/index.php/Index/login");
		}
		if($user[0] != 1){
			JS::_Goto(__ROOT__."/index.php");
		}
		$User = M('User');
		$del_id = $_GET['id'];
		if( $del_id && $del_id != 1){
			$User->where('id='.$del_id)->delete();
		}
		
		import("ORG.Util.Page");
		$count = $User->where('id!=1')->count();
    	$Page = new Page($count,25);
    	$Page->setConfig('theme','%first% %upPage% %prePage% %linkPage% %nextPage% %downPage% %end% 共%totalRow% %header% %nowPage%/%totalPage% 页');
    	$show = $Page->show();
    	$list = $User->where('id!=1')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->list = $list;
		$this->page = $show;
		
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
		}

	public function info(){
		$login = get_cookie();
		if(!$login[0]){
			JS::Exitframeset(__ROOT__."/index.php/Index/login");
		}
		$User = M('User');
		
		if($_POST)
		{
			$info = $User->where("id={$login[0]}")->select();
			$info = $info[0];
			$cname = stripslashes(urldecode(trim($_POST['cname'])));
			$token = stripslashes(urldecode(trim($_POST['token'])));
			$jf_num = intval($_POST['jf']);
			if(empty($cname)){
				JS::Alert('请输入名称！');
				JS::Back();
			}
			if(empty($token)){
				JS::Alert('请输入token！');
				JS::Back();
			}
			$data['cname'] = $cname;
			$data['token'] = $token;
			$data['jf_num'] = $jf_num;
			$User->where("id={$login[0]}")->save($data);
			JS::Alert('修改成功');
				
		}
		$info = $User->where("id={$login[0]}")->select();
		$info = $info[0];
		$this->info = $info;
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
		
	}
	public function news(){
		$login = get_cookie();
		if(!$login[0]){
			JS::Exitframeset(__ROOT__."/index.php/Index/login");
		}
		$t = $_GET['t'];
		$News = M('News');
	
		$id = $_GET['id'];
		$act = $_GET['act'];
		
		if( $act=='del' && $id){
			$info = $News->where("id=$id")->find();
			if(file_exists($info['pic'])){
   					unlink($info['pic']);
  					}
			$News->where('id='.$id)->delete();
		}
		if( $act=='top' && $id){
			$temp = $News->where('id='.$id)->select();
			$td['title'] = $temp[0]['title'];
			$td['content'] = $temp[0]['content'];
			$td['pic'] = $temp[0]['pic'];
			$td['type'] = $temp[0]['type'];
			$td['time'] = $temp[0]['time'];
			$News->where('id='.$id)->delete();
			$News->add($td);
			}
		
		import("ORG.Util.Page");
		$count = $News->where("type=$t")->count();
    	$Page = new Page($count,20);
    	$Page->setConfig('theme','%first% %upPage% %prePage% %linkPage% %nextPage% %downPage% %end% 共%totalRow% %header% %nowPage%/%totalPage% 页');
    	$show = $Page->show();
    	$list = $News->where("type=$t and uid={$login[0]}")->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->list = $list;
		$this->page = $show;
		
		$this->t = $t;
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
	public function editnew(){
		$login = get_cookie();
		if(!$login[0]){
			JS::Exitframeset(__ROOT__."/index.php/Index/login");
		}
		$nid = intval($_GET['id']);
		$t = $_GET['t'];
		$News = M('News');
		if($nid){
			$ninfo = $News->where("id=$nid")->select();
			$ninfo = $ninfo[0];

		}
		
		if($_POST || $_FILES)
		{	
			if(empty($_POST['title'])){
				JS::Alert('请输入名称！');
				JS::Back();
			}
			if(empty($_POST['content'])){
				JS::Alert('请输入内容！');
				JS::Back();
			}
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'png');// 设置附件上传类型
			$upload->savePath =  'Public/uploads/';// 设置附件上传目录
			if(!$upload->upload()) {// 上传错误提示错误信息
				//$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$data['pic'] = $info[0]['savepath'].$info[0]['savename'];
				if(file_exists($ninfo['pic'])){
   					unlink($ninfo['pic']);
  					}
  				$img = 	$info[0]['savepath'].$info[0]['savename'];
			}
			$data['title'] = $_POST['title'];
			$data['content'] = $_POST['content'];
			if($nid){
				$News->where("id=$nid")->save($data);
				JS::Alert('修改成功');
			}else{
				$data['time'] = time();
				$data['type'] = $t;
				$data['uid'] = $login[0];
				$News->data($data)->add();
				JS::Alert('添加成功');
			}
			JS::_Goto(__ROOT__."/index.php/User/news/t/".$t);
		}	
		
		
		require_once 'Lib/Common/plugins/ckeditor/ckeditor.php';
		require_once 'Lib/Common/plugins/ckfinder/ckfinder.php';

		$CKEditor = new CKEditor();
		$CKEditor->returnOutput = true;
		$CKEditor->basePath = __ROOT__.'/Lib/Common/plugins/ckeditor/';
		$CKEditor->config['width'] = 600;
		$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
		CKFinder::SetupCKEditor( $CKEditor,__ROOT__.'/Lib/Common/plugins/ckfinder/') ;
		$code = $CKEditor->editor("content", $ninfo['content']);
		
		$this->code = $code;
		$this->t = $t;
		$this->nid = $nid;
		$this->info = $ninfo;
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
	public function memberlist(){
		$user = get_cookie();
		if(!$user[0]){
			JS::Exitframeset(__ROOT__."/index.php/Index/login");
		}
		
		$Member = M('Member');
        import("ORG.Util.Page");
		$count = $Member->count();
    	$Page = new Page($count,25);
    	$Page->setConfig('theme','%first% %upPage% %prePage% %linkPage% %nextPage% %downPage% %end% 共%totalRow% %header% %nowPage%/%totalPage% 页');
    	$show = $Page->show();
    	$list = $Member->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->list = $list;
		$this->page = $show;
        
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
	public function cost(){
		$user = get_cookie();
		if(!$user[0]){
			JS::Exitframeset(__ROOT__."/index.php/Index/login");
		}
		if($_POST){
			$memberid = $_POST['member'];
			$tel = $_POST['tel'];
			$money = intval($_POST['money']);
			if(empty($memberid) && empty($tel)){
				JS::Alert('请填写会员卡号或手机号！');
				JS::Back();
			}
			if(!$money){
				JS::Alert('请填写消费金额！');
				JS::Back();
			}
			$Member = M('Member');
			if($memberid){
				$info = $Member->where("card='$memberid'")->find();
			}else{
				$info = $Member->where("tel='$tel'")->find();
			}
			if($info){
				$M_cost = M('M_cost');
				$data=array();
				$data['mid'] = $info['id'];
				$data['money'] = $money;
				$data['time'] = time();
				$M_cost->add($data);
				
				$User = M('User');
				$jf_num = $User->where("id=2")->getField('jf_num');
				$c_jf = $money*$jf_num;
				$Member->where("id={$info['id']}")->setInc('total',$money);
				$Member->where("id={$info['id']}")->setInc('score',$c_jf);
				
				$M_jf_logs = M('M_jf_logs');
				$data=array();
				$data['mid'] = $info['id'];
				$data['num'] = $c_jf;
				$data['type'] = 1;
				$data['time'] = time();
				$M_jf_logs->add($data);
				
				JS::Alert('提交成功！');
				}else{
					JS::Alert('会员卡号或手机号不存在！');
				}
			 
			
		}
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
	public function costlogs(){
		
		$Model = new Model();
		import("ORG.Util.Page");
		$mid = intval($_GET['mid']);
			if($mid){
				$count = $Model->query("select count(*) num from wx_member m , wx_m_cost c where m.id=c.mid and m.id=$mid ");
				$count = $count[0]['num'];
    			$Page = new Page($count,25);
    			$Page->setConfig('theme','%first% %upPage% %prePage% %linkPage% %nextPage% %downPage% %end% 共%totalRow% %header% %nowPage%/%totalPage% 页');
    			$show = $Page->show();
				$list = $Model->query("select m.card card, m.name name ,m.tel tel ,c.id id ,c.money money,c.time time from wx_member m , wx_m_cost c where m.id=c.mid and m.id=$mid order by time DESC limit {$Page->firstRow},{$Page->listRows}");
			}else{
				$count = $Model->query("select count(*) num from wx_member m , wx_m_cost c where m.id=c.mid ");
				$count = $count[0]['num'];
    			$Page = new Page($count,25);
    			$Page->setConfig('theme','%first% %upPage% %prePage% %linkPage% %nextPage% %downPage% %end% 共%totalRow% %header% %nowPage%/%totalPage% 页');
    			$show = $Page->show();
				$list = $Model->query("select m.card card, m.name name ,m.tel tel ,c.id id ,c.money money,c.time time from wx_member m , wx_m_cost c where m.id=c.mid order by time DESC limit {$Page->firstRow},{$Page->listRows}");
			}
		if($_POST){
			$card = stripslashes(urldecode(trim($_POST['card'])));
			$tel = stripslashes(urldecode(trim($_POST['tel'])));
			if($card){
				$count = $Model->query("select count(*) num from wx_member m , wx_m_cost c where m.id=c.mid and m.card='$card'");
				$count = $count[0]['num'];
    			$Page = new Page($count,25);
    			$Page->setConfig('theme','%first% %upPage% %prePage% %linkPage% %nextPage% %downPage% %end% 共%totalRow% %header% %nowPage%/%totalPage% 页');
    			$show = $Page->show();
				$list = $Model->query("select m.card card, m.name name ,m.tel tel ,c.id id ,c.money money,c.time time from wx_member m , wx_m_cost c where m.id=c.mid and m.card='$card' order by time DESC limit {$Page->firstRow},{$Page->listRows}");
			}
			if($tel){
				$count = $Model->query("select count(*) num from wx_member m , wx_m_cost c where m.id=c.mid and m.tel='$tel'");
				$count = $count[0]['num'];
    			$Page = new Page($count,25);
    			$Page->setConfig('theme','%first% %upPage% %prePage% %linkPage% %nextPage% %downPage% %end% 共%totalRow% %header% %nowPage%/%totalPage% 页');
    			$show = $Page->show();
				$list = $Model->query("select m.card card, m.name name ,m.tel tel ,c.id id ,c.money money,c.time time from wx_member m , wx_m_cost c where m.id=c.mid and m.tel='$tel' order by time DESC limit {$Page->firstRow},{$Page->listRows}");
			}
		}
		$this->page = $show;
		$this->list = $list;
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
	public function jflogs(){
		$mid = intval($_GET['mid']);
		$Model = new Model();
		import("ORG.Util.Page");
		
		
		if($mid){
			$count = $Model->query("select count(*) num from wx_member m , wx_m_jf_logs j where m.id=j.mid and m.id=$mid ");
			$count = $count[0]['num'];
    		$Page = new Page($count,25);
    		$Page->setConfig('theme','%first% %upPage% %prePage% %linkPage% %nextPage% %downPage% %end% 共%totalRow% %header% %nowPage%/%totalPage% 页');
    		$show = $Page->show();
			$list = $Model->query("select m.card card, m.name name ,m.tel tel ,j.id id ,j.num num,j.time time,j.type type from wx_member m , wx_m_jf_logs j where m.id=j.mid and m.id=$mid order by time DESC limit {$Page->firstRow},{$Page->listRows}");	
		}
		
		$types = array(-1 => '兑换',1 => '消费');
		
		$this->types = $types;
		$this->list = $list;
		$this->page = $show;
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
	public function goodsedit(){
		$login = get_cookie();
		if(!$login[0]){
			JS::Exitframeset(__ROOT__."/index.php/Index/login");
		}
		$id = intval($_GET['id']);
		$Jf_goods = M('Jf_goods');
		if($id){
			$ninfo = $Jf_goods->where("id=$id")->select();
			$ninfo = $ninfo[0];

		}
		if($_POST || $_FILES)
		{	
			if(empty($_POST['name'])){
				JS::Alert('请输入名称！');
				JS::Back();
			}
			if(empty($_POST['info'])){
				JS::Alert('请输入简介！');
				JS::Back();
			}
			if(empty($_POST['jf'])){
				JS::Alert('请输入消耗积分！');
				JS::Back();
			}
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'png');// 设置附件上传类型
			$upload->savePath =  'Public/uploads/';// 设置附件上传目录
			if(!$upload->upload()) {// 上传错误提示错误信息
				//$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$data['pic'] = $info[0]['savepath'].$info[0]['savename'];
				if(file_exists($ninfo['pic'])){
   					unlink($ninfo['pic']);
  					}
  				$img = 	$info[0]['savepath'].$info[0]['savename'];
			}
			$data['name'] = $_POST['name'];
			$data['info'] = $_POST['info'];
			$data['jf'] = intval($_POST['jf']);
			$data['prin'] = intval($_POST['prin']);
			if($id){
				$Jf_goods->where("id=$id")->save($data);
				JS::Alert('修改成功');
			}else{
				$Jf_goods->data($data)->add();
				JS::Alert('添加成功');
			}
			JS::_Goto(__ROOT__."/index.php/User/goods");
		}	
		
		
		require_once 'Lib/Common/plugins/ckeditor/ckeditor.php';
		require_once 'Lib/Common/plugins/ckfinder/ckfinder.php';

		$CKEditor = new CKEditor();
		$CKEditor->returnOutput = true;
		$CKEditor->basePath = __ROOT__.'/Lib/Common/plugins/ckeditor/';
		$CKEditor->config['width'] = 600;
		$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
		CKFinder::SetupCKEditor( $CKEditor,__ROOT__.'/Lib/Common/plugins/ckfinder/') ;
		$code = $CKEditor->editor("info", $ninfo['info']);
		
		$this->code = $code;
		$this->id = $id;
		$this->info = $ninfo;
		
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
	public function goods(){
		$login = get_cookie();
		$Jf_goods = M('Jf_goods');
		if(!$login[0]){
			JS::Exitframeset(__ROOT__."/index.php/Index/login");
		}
		$id = intval($_GET['id']);
		if($id){
			$info = $Jf_goods->where("id=$id")->find();
			if(file_exists($info['pic'])){
   					unlink($info['pic']);
  					}
			$Jf_goods->where('id='.$id)->delete();
		}
		
		$list = $Jf_goods->select();
		
		$this->list = $list;
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
	public function dhlogs(){
		$login = get_cookie();
		if(!$login[0]){
			JS::Exitframeset(__ROOT__."/index.php/Index/login");
		}
		$id = intval($_GET['id']);
		if($id){
			$M_dh_logs = M('M_dh_logs');
			$data['gtime'] = time();
			$M_dh_logs->where("id=$id")->save($data);
			
		}
		if($_GET['card'] || $_GET['tel']){
			$card = stripslashes(urldecode(trim($_GET['card'])));
			$tel = stripslashes(urldecode(trim($_GET['tel'])));
			$Member = M('Member');
			if($card){
				$info = $Member->where("card='$card'")->find();
				$this->card = $card;
			}
			if($tel){
				$info = $Member->where("tel='$tel'")->find();
				$this->tel = $tel;
			}
			if($info){
				$sql = " and mid={$info['id']} ";
			}else{
				$sql = " and mid=0 ";
			}
		}
		$Model = new Model();
		import("ORG.Util.Page");
		$count = $Model->query("select count(*) num from wx_member m,wx_m_dh_logs l where m.id=l.mid $sql ");
		$count = $count[0]['num'];
    	$Page = new Page($count,25);
    	$Page->setConfig('theme','%first% %upPage% %prePage% %linkPage% %nextPage% %downPage% %end% 共%totalRow% %header% %nowPage%/%totalPage% 页');
    	$show = $Page->show();
		$list = $Model->query("select m.card card,m.tel tel,l.gname gname,l.gtime gtime,l.id id from wx_member m,wx_m_dh_logs l where m.id=l.mid $sql order by l.ctime DESC limit {$Page->firstRow},{$Page->listRows}");
		$this->page = $show;
		$this->list = $list;
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
	public function actedit(){
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
	public function ggk(){
		import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
	}
}
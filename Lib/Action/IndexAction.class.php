<?php
class IndexAction extends Action {
    public function index(){
       	$login = get_cookie();
		if(!$login[0]){
			JS::_Goto(__ROOT__."/index.php/Index/login");
		}
     	$this->display();
    }
    public function test(){
    	import('head1','Tpl','.html');
     	$this->display();
     	import('foot1','Tpl','.html');
    }
    public function indexs(){
    	$login = get_cookie();
		if(!$login[0]){
			JS::Exitframeset(__ROOT__."/index.php/Index/login");
		}
		
    	$User = M('User');
		$sign = $User->where("id={$login[0]}")->getField('sign');
		$this->sign = $sign;
    	import('head','Tpl','.html');
     	$this->display();
     	import('foot','Tpl','.html');
    }
    public function head(){
    	$login = get_cookie();
		$this->login = $login;
     	$this->display();
    }
	public function foot(){
		$login = get_cookie();
		$this->login = $login;
     	$this->display();
    }
    public function top(){
    	$login = get_cookie();
		$this->login = $login;
     	$this->display();
    }
	public function left(){
		$login = get_cookie();
		$this->login = $login;
     	$this->display();
    }
//登陆
	public function login(){
header("Content-Type: text/html; charset=utf-8");
	if($_GET){
	$act = $_GET['act'];
	if($act == 'out'){
		setcookie("auth_wx",'',time()-1000,'/');
		JS::Exitframeset("login.php");
		}
	}
	if($_POST)
	{
		$username = stripslashes(urldecode(trim($_POST['username'])));	
		$userpwd = stripslashes(urldecode(trim($_POST['userpwd'])));	
	
		if(empty($userpwd)){
			JS::Alert('密码不能为空！');
			JS::Back();
		}
		$User = M('User');
		$userinfo = $User->where("uname='$username'")->select();
		if($userinfo && $userinfo[0]['password'] == md5($userpwd)){
			setcookie("auth_wx",authcode($userinfo[0]['id'].'|'.$userinfo[0]['uname'],'ENCODE',C('WX_KEY')),time()+(24*3600),'/');
		}else{
			JS::Alert("用户名不存在或者密码错误!");
			JS::Back();
		}		

	
	
		JS::_Goto(__ROOT__."/index.php");
	}
		$this->display();
    }
}
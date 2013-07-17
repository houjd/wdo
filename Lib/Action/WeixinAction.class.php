<?php
class WeixinAction extends Action {
    public function index(){
    	import('@.Common.weixin.Weixin');
       	include_once('Weixin.class.php');
		define("TOKEN", "bigauto");
		$sign = $_GET['s'];
		$weixin = new Weixin(TOKEN);
		$weixin->valid();
		$weixin->getMsg();
		$type = $weixin->msgtype;
		$username = $weixin->msg['FromUserName'];
		if ($type=='text') {
			$keyword = $weixin->msg['Content'];
			import('@.Common.weixin.Text');
			$Text= new Text($keyword,$sign);
			$results =$Text->search();

			switch ($results['type'])
    		{
       		 	case "text":
           		 $reply = $weixin->makeText($results['content']);
            	break;
            	case "news":
           		 $reply = $weixin->makeNews($results['content']);
            	break;
   			 }


		}elseif ($type=='event'){
			switch ($weixin->msg['Event'])
    		{
        		case "subscribe":
            	$contentStr = "感谢您关注港宏汽车大众4S店，我们为您提供最优质的服务，最贴心的价格。
请回复数字选择菜单：
1.联系客服
2.查看地址
3.最新活动
4.申请试驾
5.联系售后保养
6.旧车置换
";
            $reply = $weixin->makeText($contentStr);
            break;
    		}
	
		}elseif ($type=='location'){
	
		}elseif ($type=='image') {
	
		}elseif ($type=='voice') {
	
		}
		$weixin->reply($reply);
    	$this->display();
    }
	public function message(){
		$this->display();
	}
	public function messagere(){
		$this->display();
	}
	public function address(){
		$this->display();
	}
	public function act(){
		$this->display();
	}
	public function asignup(){
		$this->display();
	}
	public function asignupre(){
		$this->display();
	}
	public function drive(){
		$this->display();
	}
	public function drivere(){
		$this->display();
	}
	public function by(){
		$this->display();
	}
	public function byinfo(){
		$this->display();
	}
	public function byxq(){
		$this->display();
	}
	public function byrs(){
		$this->display();
	}	
	public function weixiu(){
		$this->display();
	}
	public function weixiurs(){
		$this->display();
	}
	public function gj(){
		$this->display();
	}
	public function gjrs(){
		$this->display();
	}
	public function zhcx(){
		$this->display();
	}
	public function zhcxinfo(){
		$this->display();
	}
	public function zhys(){
		$this->display();
	}
	public function zhysrs(){
		$this->display();
	}
	public function sqzh(){
		$this->display();
	}
	public function sqzhrs(){
		$this->display();
	}

}
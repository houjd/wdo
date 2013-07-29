<?php
class CgAction extends Action {
    public function index(){
    	import('@.Common.weixin.Weixin');
    	$User = M('User');
		$userinfo = $User->where("id=2")->select();
		$userinfo = $userinfo[0];
		define("TOKEN", $userinfo['token']);

		$weixin = new Weixin(TOKEN);	
		$weixin->valid();

		$weixin->getMsg();

		$type = $weixin->msgtype;
		$username = $weixin->msg['FromUserName'];

		if ($type=='text') {
			$keyword = $weixin->msg['Content'];
			import('@.Common.weixin.Text');
			$Text= new Text($keyword,$username);
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
        			$Member = M('Member');
        			$info = $Member->where("wxid='$username'")->find();
        			if(!$info){
        				$data['card'] = intval($Member->order("id DESC")->getField('card'))+1;
        				if($data['card'] < 10){
        					$data['card'] = 102001;
        				}
        				$data['wxid'] = $weixin->msg['FromUserName'];
        				$data['time'] = time();
        				$Member->add($data);
        			}
            		$contentStr = "感谢您关注{$userinfo['cname']}，我们为您提供最优质的服务，最贴心的价格。
请回复数字选择菜单：
1.活动及优惠
2.橱柜样式选择
3.联系咨询
4.查看图纸
5.查看工期进度，安排
6.售后服务
7.我的会员卡
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
    public function news(){
    	$id = intval($_GET['id']);
    	$News = M('News');
    	$info = $News->where("id=$id")->select();
    	$this->info = $info;
    	$this->display();
    }
    public function member(){
    	$uid = $_GET['uid'];
    	$Member = M('Member');
        $info = $Member->where("wxid='$uid'")->find();
        
        $this->uid = $uid;
    	$this->info = $info;
    	$this->display();
    }
    public function bdtel(){
    	header("Content-Type: text/html; charset=utf-8");
    	$uid = $_GET['uid'];
    	$Member = M('Member');
        $info = $Member->where("wxid='$uid'")->find();
       	if($_POST){
       		$name = $_POST['name'];
       		$tel = $_POST['tel'];
       		if(empty($name)){
				JS::Alert('请填写姓名！');
				JS::Back();
			}
       		if(empty($tel)){
				JS::Alert('请填写手机号！');
				JS::Back();
			}
			$data['name'] = $name;
			$data['tel'] = $tel;
			$Member->where("id={$info['id']}")->save($data);
			JS::Alert('绑定成功！');
			JS::_Goto(__URL__.'/member/uid/'.$uid);
		} 
        $this->uid = $uid;
    	$this->info = $info;
    	$this->display();
    }
    public function jflogs(){
    	$uid = $_GET['uid'];
    	$this->uid = $uid;
    	$Member = M('Member');
        $info = $Member->where("wxid='$uid'")->find();
        $Model = new Model();
   	 	if($info){
			$list = $Model->query("select j.num num,j.time time,j.type type from wx_member m , wx_m_jf_logs j where m.id=j.mid and m.id={$info['id']} order by time DESC");	
		}
		$types = array(-1 => '兑换',1 => '消费');
		
		$this->types = $types;
		$this->list = $list;
    	$this->display();
        
    }
	public function costlogs(){
		$uid = $_GET['uid'];
    	$this->uid = $uid;
    	$Member = M('Member');
        $info = $Member->where("wxid='$uid'")->find();
        $Model = new Model();
   	 	if($info){
			$list = $Model->query("select c.money money,c.time time from wx_member m , wx_m_cost c where m.id=c.mid and m.id={$info['id']} order by time DESC");
			}

		$this->list = $list;
    	$this->display();
    }
    public function goods(){
    	$uid = $_GET['uid'];
    	$this->uid = $uid;
    	$Member = M('Member');
        $minfo = $Member->where("wxid='$uid'")->find();
    	$Jf_goods = M('Jf_goods');
		$list = $Jf_goods->where("prin>0")->order("jf DESC")->select();
		$this->minfo = $minfo;
		$this->list = $list;
    	$this->display();
    }
 	public function goodsinfo(){
    	$uid = $_GET['uid'];
    	$this->uid = $uid;
    	$Member = M('Member');
        $minfo = $Member->where("wxid='$uid'")->find();
    	$id = intval($_GET['id']);
    	$Jf_goods = M('Jf_goods');
		$info = $Jf_goods->where("prin>0 and id=$id")->find();
		
		if($_GET['act']=='dh' && $info){

        	if(intval($minfo['score'])>=$info['jf']){
        		$data['score'] = intval($minfo['score'])-$info['jf'];
        		$Member->where("wxid='$uid'")->save($data);
        		$M_jf_logs = M('M_jf_logs');
        		$data = array();
        		$data['mid'] = $minfo['id'];
        		$data['num'] = -$info['jf'];
        		$data['type'] = -1;
        		$data['time'] = time();
        		$M_jf_logs->add($data);
        		$M_dh_logs = M('M_dh_logs');
        		$data = array();
        		$data['mid'] = $minfo['id'];
        		$data['gid'] = $info['id'];
        		$data['gname'] = $info['name'];
        		$data['ctime'] = time();
        		$M_dh_logs->add($data);
        		JS::Alert('兑换成功');
        	}else{
        		JS::Alert('抱歉，您的积分不够！');
        	}
		}
		$this->minfo = $minfo;
		$this->info = $info;
    	$this->display();
    }
    public function dhlogs(){
   	 	$uid = $_GET['uid'];
    	$this->uid = $uid;
    	$Member = M('Member');
        $minfo = $Member->where("wxid='$uid'")->find();
        
        $M_dh_logs = M('M_dh_logs');
        $list = $M_dh_logs->where("mid={$minfo['id']}")->select();;
        
        $this->list = $list;
    	$this->display();
    	
    }
    public function ggk(){
   	 	$uid = $_GET['uid'];
    	$this->uid = $uid;
    	$Member = M('Member');
        $minfo = $Member->where("wxid='$uid'")->find();
        
        
        $Acts = M('Acts');
        $act = $Acts->where("type=1")->order('s_time DESC')->find();
        $now = time();
        $arr = array(0=>'0dj.png',1=>'1dj.png',2=>'2dj.png',3=>'3dj.png');
        if($act['s_time']>$now){
        	$str = '暂未开始';
        	$awardid = 0;
        }elseif($act['e_time']<$now){
        	$str = '已结束';
        	$awardid = 0;
        }else{
        	$Acts_logs = M('Acts_logs');
        	if($act['isd']){
        		$day = strtotime(date('Ymd',time()));
        		$dayinfo = $Acts_logs->where("aid={$act['id']} and mid={$minfo['id']} and time>$day")->find();
        	}else{
        		$dayinfo = $Acts_logs->where("aid={$act['id']} and mid={$minfo['id']}")->find();
        		if($dayinfo){
        			$dayinfo = 1;
        		}
        	}
        	if(!$dayinfo){
        		$Model = new Model();
        		$count = $Model->query("select award,count(id) num from wx_acts_logs where aid={$act['id']} group by award");
        		$awards = array();
        		$awards[1] = $act['f_num'];
        		$awards[2] = $act['s_num'];
        		$awards[3] = $act['t_num'];
        		$awards[0] = $act['pnum']-$awards[1]-$awards[2]-$awards[3];
        		foreach ($count as $v){
        			$awards[$v['award']] -= $v['num'];
        		}
        		if($awards[0]+$awards[1]+$awards[2]+$awards[3]>0){
        			$awardid = $this->get_rand($awards);
        		}else{
        			$awardid = 0;
        		}
        	
        		$data['mid'] = $minfo['id'];
        		$data['award'] = $awardid;
        		$data['aid'] = $act['id'];
        		$data['time'] = time();
        		$Acts_logs->add($data);
        		if($awardid){
        			$Acts_sn = M('Acts_sn');
        			$sn = $Acts_sn->where("aid={$act['id']} and type=$awardid and mid=0")->find();
        			if($sn){
        				$sndata['mid'] = $minfo['card'];
        				$Acts_sn->where("id={$sn['id']}")->save($sndata);
        			}
        		}
       	 	}else{
       	 		if($dayinfo==1){
       	 			$str="抱歉，你已达到抽奖次数上限！";
       	 		}else{
       	 			$str="抱歉，你已达到今日抽奖次数上限！";
       	 		}
       	 		
       	 		$awardid = 0;
       	 	}
        }
        $this->sn = $sn;
        $this->award = $arr[$awardid];
        $this->str = $str;
    	$this->display();
    	
    }
 	public function tel(){
    	$this->display();
    }
 	public function tuzhi(){
    	$this->display();
    }
    public function gq(){
    	$this->display();
    }
    public function ask(){
    	$this->display();
    }
    public function sh(){
    	$this->display();
    }
    //抽奖
    function get_rand($proArr) { 
    $result = ''; 
 
    //概率数组的总概率精度 
    $proSum = array_sum($proArr); 
 
    //概率数组循环 
    foreach ($proArr as $key => $proCur) { 
        $randNum = mt_rand(1, $proSum); 
        if ($randNum <= $proCur) { 
            $result = $key; 
            break; 
        } else { 
            $proSum -= $proCur; 
        } 
    } 
    unset ($proArr); 
 
    return $result; 
} 
	
}
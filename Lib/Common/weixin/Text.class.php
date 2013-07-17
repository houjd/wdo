<?php 
class Text
{
	public $keyword = '';
	public $type = 1;
	public $username = '';

	
	public function __construct($keyword,$username)
    {
        $str = stripslashes(urldecode(trim($keyword)));
        $this->type = substr($str, 0, 1 );
        $this->username = $username;
        
    }
    public function search(){
    	$User = M('User');
		$userinfo = $User->where("id=2")->select();
    	switch ($this->type){
    		case 1:
    			$return = $this->youhui();
    			break;
    		case 2:
    			$return = $this->yangshi();
    			break;
    		case 3:
    			$return = $this->zixun();
    			break;
    		case 4:
    			$return = $this->tuzhi();
    			break;
    		case 5:
    			$return = $this->gongqi();
    			break;
    		case 6:
    			$return = $this->shouhou();
    			break;
    		case 7:
    			$return = $this->member();
    		break;
    		default:
    			$return = array();
    			$return['type'] = 'text';
    			$return['content'] = "感谢您关注{$userinfo['cname']}，我们为您提供最优质的服务，最贴心的价格。
请回复数字选择菜单：
1.活动及优惠
2.橱柜样式选择
3.联系咨询
4.查看图纸
5.查看工期进度，安排
6.售后服务
7.我的会员卡";
    	}
    	return $return;
		}
		
		private function youhui(){
			$return = array();
    		$return['type'] = 'news';
    		$News = M('News');
    		$list = $News->where("type=1 and uid=2")->order('id DESC')->limit(5)->select();
    		$newsData['items'] = array();
    		foreach ($list as $v){
    			$item['title'] = $v['title'];
				$item['description'] = $v['title'];
    			$item['picurl'] = T_URL.__ROOT__.'/'.$v['pic'];
    			$item['url'] = T_URL.__ROOT__.'/index.php/Cg/news/id/'.$v['id'];
    			$newsData['items'][] = $item;
    		}
    		$return['content'] = $newsData;
			
    		return $return;
			
			}
		private function yangshi(){
			$return = array();
    		$return['type'] = 'news';
			$News = M('News');
    		$list = $News->where("type=2 and uid=2")->order('id DESC')->limit(10)->select();
    		$newsData['items'] = array();
    		foreach ($list as $v){
    			$item['title'] = $v['title'];
				$item['description'] = $v['title'];
    			$item['picurl'] = T_URL.__ROOT__.'/'.$v['pic'];
    			$item['url'] = T_URL.__ROOT__.'/index.php/Cg/news/id/'.$v['id'];
    			$newsData['items'][] = $item;
    		}
    		$return['content'] = $newsData;
			
    		return $return;
			
			}
		private function zixun(){
			$return = array();
    		$return['type'] = 'news';
    		$newsData['items'] = array();
    		$item['title'] = '您可以拨打电话400-69878834直接咨询客服，也可以直接微信留言';
			$item['description'] = '您可以拨打电话400-69878834直接咨询客服，也可以直接微信留言';
    		$item['picurl'] = T_URL.__ROOT__.'/Public/wximg/youhui1.png';
    		$item['url'] = T_URL.__ROOT__.'/index.php/Cg/ask';
    		$newsData['items'][] = $item;
    		$item['title'] = '点击此处，拨打电话';
			$item['description'] = '点击此处，拨打电话';
    		$item['picurl'] = T_URL.__ROOT__.'/Public/wximg/tel.png';
    		$item['url'] = T_URL.__ROOT__.'/index.php/Cg/tel';
    		$newsData['items'][] = $item;
    		$return['content'] = $newsData;
			
    		return $return;
			
			}
		private function tuzhi(){
			$return = array();
    		$return['type'] = 'news';
//    		$User = M('User');
//    		$img = $User->where("sign={$this->sign}")->getField('sj_img');
    		$newsData['items'] = array();
    		$item['title'] = '查看图纸';
			$item['description'] = '查看图纸';
    		$item['picurl'] = T_URL.__ROOT__.'/Public/wximg/tuzhi.png';
    		$item['url'] = T_URL.__ROOT__.'/index.php/Cg/tuzhi';
    		$newsData['items'][] = $item;
    		$item['title'] = '点击此处，查看图纸';
			$item['description'] = '点击此处，查看图纸';
    		$item['picurl'] = T_URL.__ROOT__.'/Public/wximg/tz.png';
    		$newsData['items'][] = $item;
    		$return['content'] = $newsData;
			
    		return $return;
			
			}
		private function gongqi(){
			$return = array();
    		$return['type'] = 'news';
//    		$User = M('User');
//    		$img = $User->where("sign={$this->sign}")->getField('sh_img');
    		$item['title'] = '查看工期进度，安排';
			$item['description'] = '查看工期进度，安排';
    		$item['picurl'] = T_URL.__ROOT__.'/Public/wximg/youhui1.png';
    		$item['url'] = T_URL.__ROOT__.'/index.php/Cg/gq';
    		$newsData['items'][] = $item;
    		$item['title'] = '您可以点击此处查看您现在的工程进度，同时会有对应工程提示给到您';
			$item['description'] = '您可以点击此处查看您现在的工程进度，同时会有对应工程提示给到您';
    		$item['picurl'] = T_URL.__ROOT__.'/Public/wximg/wenhao.png';
    		$item['url'] = T_URL.__ROOT__.'/index.php/Cg/gq';
    		$newsData['items'][] = $item;
    		$return['content'] = $newsData;
			
    		return $return;
			
			}
		private function shouhou(){
			$return = array();
    		$return['type'] = 'news';
//    		$User = M('User');
//    		$img = $User->where("sign={$this->sign}")->getField('zh_img');
    		$item['title'] = '售后服务';
			$item['description'] = '售后服务';
    		$item['picurl'] = T_URL.__ROOT__.'/Public/wximg/youhui1.png';
    		$item['url'] = T_URL.__ROOT__.'/index.php/Cg/sh';
    		$newsData['items'][] = $item;
    		$item['title'] = '台面问题:台面开裂，发黑，渗水或者其他原因请点击';
			$item['description'] = '台面问题:台面开裂，发黑，渗水或者其他原因请点击';
    		$item['picurl'] = T_URL.__ROOT__.'/Public/wximg/sh1.png';
    		$item['url'] = T_URL.__ROOT__.'/index.php/Cg/sh';
    		$newsData['items'][] = $item;
    		$item['title'] = '板材问题:板材发霉，变形，渗水或者其他原因请点击';
			$item['description'] = '板材问题:板材发霉，变形，渗水或者其他原因请点击';
    		$item['picurl'] = T_URL.__ROOT__.'/Public/wximg/sh2.png';
    		$item['url'] = T_URL.__ROOT__.'/index.php/Cg/sh';
    		$newsData['items'][] = $item;
    		$item['title'] = '厨房电器问题:电器失灵，损坏，或者其他原因请点击';
			$item['description'] = '厨房电器问题:电器失灵，损坏，或者其他原因请点击';
    		$item['picurl'] = T_URL.__ROOT__.'/Public/wximg/sh3.png';
    		$item['url'] = T_URL.__ROOT__.'/index.php/Cg/sh';
    		$newsData['items'][] = $item;
    		$return['content'] = $newsData;
			
    		return $return;
			
			}
		private function member(){
			$Member = M('Member');
        	$info = $Member->where("wxid='{$this->username}'")->find();
        	if(!$info){
        		$data['card'] = intval($Member->order("id DESC")->getField('card'))+1;
        		if($data['card'] < 10){
        			$data['card'] = 102001;
        		}
        		$data['wxid'] = $weixin->msg['FromUserName'];
        		$data['time'] = time();
        		$Member->add($data);
        	}
        	
        	$return = array();
    		$return['type'] = 'news';
    		$item['title'] = '我的会员卡';
			$item['description'] = '我的会员卡';
    		$item['picurl'] = T_URL.__ROOT__.'/Public/wximg/huiyuan1.png';
    		$item['url'] = T_URL.__ROOT__.'/index.php/Cg/member/uid/'.$this->username;
    		$newsData['items'][] = $item;
    		$item['title'] = '点击查看会员卡信息';
			$item['description'] = '点击查看会员卡信息';
    		$item['picurl'] = T_URL.__ROOT__.'/Public/wximg/huiyuan2.jpg';
    		$item['url'] = T_URL.__ROOT__.'/index.php/Cg/member/uid/'.$this->username;
    		$newsData['items'][] = $item;
    		$return['content'] = $newsData;
			
    		return $return;
		}

}


?>
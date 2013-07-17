<?php 
class Weixin
{
    public $token = '';//token
    public $setFlag = false;
    public $msgtype = 'text';   //('text','image','location')
    public $msg = array();
 
    public function __construct($token)
    {
        $this->token = $token;
    }
    public function getMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        	if (!empty($postStr)) {
            $this->msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->msgtype = strtolower($this->msg['MsgType']);
        }
         
    }
	public function makeText($text='')
    {
        $CreateTime = time();
        $FuncFlag = $this->setFlag ? 1 : 0;
        $textTpl = "<xml>
            <ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
            <FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
            <CreateTime>{$CreateTime}</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>%s</FuncFlag>
            </xml>";
        return sprintf($textTpl,$text,$FuncFlag);
    }
    public function makeNews($newsData=array())
    {
        $CreateTime = time();
        $FuncFlag = $this->setFlag ? 1 : 0;
        $newTplHeader = "<xml>
            <ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
            <FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
            <CreateTime>{$CreateTime}</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <ArticleCount>%s</ArticleCount><Articles>";
        $newTplItem = "<item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>";
        $newTplFoot = "</Articles>
            <FuncFlag>%s</FuncFlag>
            </xml>";
        $Content = '';
        $itemsCount = count($newsData['items']);
        $itemsCount = $itemsCount < 10 ? $itemsCount : 10;
        if ($itemsCount) {
            foreach ($newsData['items'] as $key => $item) {
                if ($key<=9) {
                    $Content .= sprintf($newTplItem,$item['title'],$item['description'],$item['picurl'],$item['url']);
                }
            }
        }
        $header = sprintf($newTplHeader,$newsData['content'],$itemsCount);
        $footer = sprintf($newTplFoot,$FuncFlag);
        return $header . $Content . $footer;
    }
    public function reply($data)
    {
		
        echo $data;
    }
    public function valid()
    {
        if ($this->checkSignature()) {
            if( $_SERVER['REQUEST_METHOD']=='GET' )
            {
                echo $_GET['echostr'];
                exit;
            }
        }else{ 
            exit;
        }
        
    }
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $tmpArr = array($this->token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
		}
		 public function w_log($str)
    {
		file_put_contents ('log.txt', $str);
    }
	}
?>

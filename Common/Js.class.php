<?php
//
// +----------------------------------------------------------------------+
// | javascript 类 |
// +----------------------------------------------------------------------+

/**
* Purpose
* 封装了一些常用的Javascript代码，以便在PHP中快速调用
* @author : Mark.Ma(marenjun126@126.com)
* @version : 0.1
* @date : 2010/10/18
*/

class JS
{

/**
*　退出框架跳转到url
* @param $url 跳转到地址
*/
static function Exitframeset($url)
{
	$msg = "parent.window.location.href='$url';";
	JS::_Write($msg);
	exit;
}

/**
*　返回上页
* @param $step 返回的层数 默认为1
*/
static function Back($step = -1)
{
$msg = "history.go(".$step.");";
JS::_Write($msg);
exit;
}

/**
* 弹出警告的窗口
* @param $msg 警告信息
*/
static function Alert($msg)
{
$msg = "alert('".$msg."');";
JS::_Write($msg);
}
/**
* 写JS
* @param $msg
*/
static function _Write($msg)
{
echo '<script type="text/javascript">';
echo $msg;
echo "</script>";
}

/**
* 刷新当前页
*/
static function Reload()
{
$msg = "location.reload();";
JS::_Write($msg);
exit;
}
/**
* 刷新弹出父页
*/
static function ReloadOpener()
{
$msg = "if (opener) opener.location.reload();";
JS::_Write($msg);
}

/**
* 跳转到url
* @param $url 目标页
*/
static function _Goto($url)
{
$msg = "location.href = '$url';";
JS::_Write($msg);
exit;
}
/**
* 关闭窗口
*/
static function Close()
{
$msg = "window.close()";
JS::_Write($msg);
exit;

}
/**
* 提交表单
* @param $frm 表单名
*/
static function Submit($frm)
{
$msg = $frm.".submit();";
JS::_Write($msg);
}
}
?>
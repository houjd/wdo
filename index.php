<?php
	//开启调试模式
    define('APP_DEBUG', true);
    //定义项目名称
    define('APP_NAME', '');
    //定义项目路径
    define('APP_PATH', './');
    //加载框架入口文件
    require_once('Common/config.php');
    require_once('Common/global.php');

    require_once('Common/Js.class.php');

    require './ThinkPHP/ThinkPHP.php';
    
   
    
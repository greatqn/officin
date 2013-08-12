<?php
//引入模块公共方法文件
require("foundation/fcontent_format.php");
require("foundation/module_share.php");
require("foundation/aintegral.php");
require("api/base_support.php");
require("foundation/ftag.php");

//引入语言包
$s_langpackage=new sharelp;

//变量声明区
	$user_id=get_sess_userid();
	$user_name = get_sess_usercnname();
	$blog_id = intval(get_argg('uid'));
	$s_type = intval(get_argg('s_type'));
	

//    $s_comment=short_check(get_argp('comment'));
//	$tag=short_check(get_argp('tag'));
//	$isadd = short_check(get_argp('isadd'));
//Logs::addLog("collect begin $user_id,$s_type,$s_content_id, $isadd");
	$rs = api_proxy("blog_coll_by_typeid","*",$s_type,$blog_id);
	//var_dump($rs);
	echo api_proxy("blog_coll_view_like",$rs,$s_type);
?>
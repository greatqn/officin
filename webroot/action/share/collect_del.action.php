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
	$s_type=short_check(get_argg('s_type'));
	$s_content_id=intval(get_argg('share_content_id'));

	$rs = api_proxy("blog_coll_del",$user_id,$s_type,$s_content_id);
	action_return(1,'','-1');
?>
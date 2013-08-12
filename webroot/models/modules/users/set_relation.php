<?php
 //引入模块公共方法文件
 require("foundation/fgrade.php");
 require("foundation/module_users.php");
 require("api/base_support.php");
 require("foundation/auser_mustlogin.php");
	//语言包引入
	$pu_langpackage=new publiclp;
	$l_langpackage = new loginlp;
	$u_langpackage = new userslp;
	//变量获得
	$ses_uid=get_sess_userid();
	$url_uid=$ses_uid;
	$show_type=intval(get_argg('single'));
	$is_finish=intval(get_argg('is_finish'));

  //引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");
	
	//数据表定义
	$t_user_information=$tablePreStr."user_information";

	dbtarget('r',$dbServs);
	$dbo=new dbex;
	
	//用户已定义资料
	$user_row = api_proxy("user_self_by_uid","*",$ses_uid);


	//var_dump($user_row);
	//echo "<hr>";
	$harbors = api_proxy("user_harbor_get_list","*",'','',2,'portname','asc');
	//var_dump($harbors);

	$my_harbors = api_proxy("user_harbor_get_mine","*",$ses_uid);

	
	
?>
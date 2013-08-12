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
	$user_row = api_proxy("user_self_by_uid","*",$url_uid);

	$user_card = api_proxy("user_card_by_uid","*",$url_uid);
//var_dump($user_card);
//if($user_card==false){
//	$user_card = api_proxy("user_card_get_demo");
//}
	if($user_card){
		$card_template = api_proxy("user_card_get_template","*",$user_card["template_card_id"]);
		$my_user_business_card_text = api_proxy("user_card_build_template",$card_template,$user_card,$user_row);
	
		$background_color=$user_card["background_color"]=="default"?$card_template["background_color"]:$user_card["background_color"];
		$font_color      =$user_card["font_color"]=="default"?$card_template["font_color"]:$user_card["font_color"];
		$corp_color      =$user_card["corp_color"]=="default"?"ACD6FF":$user_card["corp_color"];
	}
	
?>
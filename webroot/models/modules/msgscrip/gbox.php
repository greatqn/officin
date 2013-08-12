<?php
	//引入模块公共权限过程文件
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	
	//引入语言包
	$m_langpackage=new msglp;
	
	//变量获得
	$user_id=get_sess_userid();//发件人id
	$user_name=get_sess_username();//发件人姓名
	$user_ico = get_sess_userico();
  
	$to_user_id = intval(get_argg("2id"));
	$touser = api_proxy("user_self_by_uid","user_id,user_name,user_ico",$to_user_id);

	//当前页面参数
	$page_num=trim(get_argg('page'));

	//$msg_inbox_rs=api_proxy("scrip_inbox_get_mine","*");
	$msg_inbox_rs=api_proxy("scrip_inbox_get_mymsg_key","*",$user_id,$to_user_id);
	//var_dump($msg_inbox_rs);
	$isNull=0;
	$content_data_none="content_none";
	$show_data="";
	if(empty($msg_inbox_rs)){
		$isNull=1;
		$show_data="content_none";
		$content_data_none="";
	}
?>
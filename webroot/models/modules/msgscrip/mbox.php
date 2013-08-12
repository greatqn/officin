<?php
	//引入模块公共权限过程文件
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	
	//引入语言包
	$m_langpackage=new msglp;
	
	//变量获得
	$user_id=get_sess_userid();
	
	//当前页面参数
	$page_num=trim(get_argg('page'));

//	$msg_inbox_rs=api_proxy("scrip_inbox_get_mine","*");
	$msg_inbox_rs=api_proxy("scrip_inbox_get_mymsg","*",$user_id);
	$isNull=0;
	$content_data_none="content_none";
	$show_data="";
	//var_dump($msg_inbox_rs);

	if(empty($msg_inbox_rs)){
		$isNull=1;
		$show_data="content_none";
		$content_data_none="";
	}
?>
<?php
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("servtools/pinyin.php");

	//引入公共模块
	require("foundation/module_mypals.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");

require("foundation/module_users.php");
require("foundation/fplugin.php");

$is_self = 'Y';
//语言包引入
$u_langpackage=new userslp;
$ef_langpackage=new event_frontlp;
$mn_langpackage=new menulp;
$pu_langpackage=new publiclp;
$s_langpackage=new sharelp;
$hi_langpackage=new hilp;
$l_langpackage=new loginlp;
$rp_langpackage=new reportlp;
$ah_langpackage=new arrayhomelp;

$user_id=get_sess_userid();
$user_name=get_sess_username();

//取得主人信息
$user_info=api_proxy("user_self_by_uid","*",$user_id);

	//引入语言包
	$mp_langpackage=new mypalslp;
	$user_id=get_sess_userid();
	$user_ico=get_sess_userico();
	$sort_id=intval(get_argg('sort_id'));
	$search_name=short_check(get_argp('search_name'));

	//数据表定义区
	$t_mypals=$tablePreStr."pals_mine";
	$t_pals_sort=$tablePreStr."pals_sort";

	//当前页面参数
	$page_num=trim(get_argg('page'));
	$show_none_str=$mp_langpackage->mp_no_pals;

	$dbo=new dbex;
	dbtarget('r',$dbServs);
	$sort_str='';
	$mp_list_rs=array();
	$mp_sort_list=array();
	$sql="select * from $t_mypals where user_id=$user_id and accepted > 0 ";

	if($sort_id!=''){
		$str=$mp_langpackage->mp_whole;
		$show_none_str=$mp_langpackage->mp_sort_pals;
		$sql.=" and pals_sort_id = $sort_id ";
	}else if($search_name!=''){
		$show_none_str=$mp_langpackage->mp_none_search;
		$sql.=" and cn_name like '%$search_name%' ";
	}
	$sql.=" order by pals_sort_id desc ";

	//$dbo->setPages(20,$page_num);//设置分页
	$mp_list_rs=$dbo->getRs($sql);
	$page_total=$dbo->totalPage;//分页总数

	$mp_list_rs = api_proxy("user_card_referpals",$mp_list_rs);
	$none_data="content_none";
	$isNull=0;
	if(empty($mp_list_rs)){
		$none_data="";
		$isNull=1;
	}else{
		$az_list_rs = change_array_to_AZ($mp_list_rs);
	}
	
	$mp_sort_list=api_proxy("pals_sort");//取得好友圈分类
	
	//var_dump($az_list_rs);
?>
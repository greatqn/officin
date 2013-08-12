<?php
	require("foundation/module_users.php");
	require("foundation/module_mypals.php");
	require("foundation/aintegral.php");
	require("api/base_support.php");
Logs::addLog("report_add_sed");
Logs::addhttp();

	//数据表定义区
	$t_report=$tablePreStr."report";

	//引入语言包
	$rp_langpackage=new reportlp;
	
	$dbo = new dbex;

	//变量区
	$type = 11;
	$user_id = get_sess_userid();
	$user_name = get_sess_username();
	$reason = short_check(get_argp('reason'));
	$location_str = urldecode(short_check(get_argp('location_str')));

	$t_report=$tablePreStr."report";

	dbtarget('r',$dbServs);

	
	$content=$location_str;
	dbtarget('w',$dbServs);
	$sql="insert into $t_report (user_id,reason,user_name,type,content,add_time,reported_id,userd_id) "
		."values ('$user_id','$reason','$user_name','$type','$content','".constant('NOWTIME')."','','')";
	if($dbo->exeUpdate($sql)){
		echo 'true';
	}else{
		echo "提交失败,请重新尝试,或能过其它途径(mail,qq,weibo)";
	}
?>
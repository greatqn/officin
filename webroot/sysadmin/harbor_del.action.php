<?php
	require("session_check.php");	
//	require("../foundation/aintegral.php");
//	require("../foundation/module_affair.php");
	
	$is_check=check_rights("c17");
	if(!$is_check){
		echo 'no permission';
		exit;
	}

	
		//接收参数
$info_id=intval(get_argp('id'));
//表定义
$t_harbor=$tablePreStr."harbor";

$dbo = new dbex;
dbtarget('w',$dbServs);
$sql="delete from $t_harbor where id =$info_id";
$is_sucess=$dbo->exeUpdate($sql);
if($is_sucess===false){
	echo 'failure';
}else{
	echo 'success';
}
?>

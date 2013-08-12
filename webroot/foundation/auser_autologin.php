<?php

//登录成功返回true.
function login_from_cookie(){
	global $tablePreStr;
	global	$dbServs;
	
	$u_email = getCookie("iweb_email");
	$user_key = getCookie("user_key");
	
	//数据表定义区
	$t_users=$tablePreStr."users";
	$t_group_members=$tablePreStr."group_members";
	$t_online=$tablePreStr."online";
	$t_mypals=$tablePreStr."pals_mine";
	$t_frontgroup=$tablePreStr."frontgroup";
	
	//定义读操作
	dbtarget('r',$dbServs);
	$dbo=new dbex;
	$sql="select * from $t_users where user_email='$u_email'";
	$user_info=$dbo->getRow($sql);
	
	if(empty($user_info)){
		return false;
	}
	$get_pws=$user_info['user_pws'];
	$u_user_key = md5("diyc".$get_pws);
	if($u_user_key!=$user_key){
		return false;
	}
	if($user_info['is_pass']==0){
		return false;
	}
	
	set_login_session($user_info);
	return true;
}

function set_login_session($user_info){
	global $tablePreStr;
	global $dbServs;
	global $int_login;
	//数据表定义区
	$t_users=$tablePreStr."users";
	$t_group_members=$tablePreStr."group_members";
	$t_online=$tablePreStr."online";
	$t_mypals=$tablePreStr."pals_mine";
	$t_frontgroup=$tablePreStr."frontgroup";
	
	dbtarget('r',$dbServs);
	$dbo=new dbex;
	$mypals=getMypals_u($dbo,$user_info['user_id'],$t_mypals);
	set_sess_mypals($mypals);
	set_sess_username($user_info['user_name']);
	set_sess_usercnname($user_info['cn_name']);
	set_sess_userid($user_info['user_id']);
	set_sess_usersex($user_info['user_sex']);
	set_sess_cgroup($user_info['creat_group']);
	set_sess_jgroup($user_info['join_group']);
	set_sess_userico($user_info['user_ico']);
	set_session('reside_province',$user_info['reside_province']);
	set_session('reside_city',$user_info['reside_city']);
	set_session('hidden_pals',$user_info['hidden_pals_id']);
	set_session('hidden_type',$user_info['hidden_type_id']);
require_once("api/base_support.php");
set_session('pals_count',api_proxy("pals_self_count_by_uid",$user_info['user_id']));
set_session('blog_count',api_proxy("blog_self_count_by_uid",$user_info['user_id']));
set_session('msg_count',api_proxy("scrip_inbox_count_by_uid",$user_info['user_id']));
	set_sess_plugins($user_info['use_plugins']);
	set_sess_apps($user_info['use_apps']);
	//set_sess_online($hidden);
	set_session($user_info['user_id']."_dressup",$user_info['dressup']);
	$sql="select * from $t_frontgroup where gid='$user_info[user_group]'";
	$rights=$dbo->getRow($sql);
	if($rights)set_sess_rights($rights['rights']);
	else  set_sess_rights("");
	
	//if($u_tmpid) set_cookie("user_key",$user_key,24*7);
	
	//定义写操作
	dbtarget('w',$dbServs);
	$now_time=time();
	
	$last_data=date("Y-m-d",strtotime($user_info['lastlogin_datetime']));
	$now_data=date("Y-m-d",$now_time);
	


	if($last_data!=$now_data){
		//require("foundation/aintegral.php");
		//increase_integral($dbo,$int_login,$user_info['user_id']);
	}
	
	$sql="delete from $t_online where user_id=$user_info[user_id]";
	Logs::addLog("autologin:del:$sql");
	$dbo->exeUpdate($sql);
	
	$sql="insert into $t_online (user_id,user_name,user_sex,user_ico,birth_province,birth_city,reside_province,reside_city,active_time,hidden,birth_year) values ".
	"($user_info[user_id],'$user_info[user_name]',$user_info[user_sex],'$user_info[user_ico]','$user_info[birth_province]','$user_info[birth_city]','$user_info[reside_province]','$user_info[reside_city]',$now_time,$hidden,'$user_info[birth_year]')";
	$dbo->exeUpdate($sql);
	
	$sql="update $t_users set lastlogin_datetime='".constant('NOWTIME')."',login_ip='$_SERVER[REMOTE_ADDR]' where user_id=$user_info[user_id]";
	$dbo->exeUpdate($sql);
}

//获取我的好友列表字符串
function getMypals_u($dbo,$user_id,$t_mypals){
	$mypals_id='';
	$sql="select pals_id from $t_mypals where user_id='$user_id' and accepted>='1' order by active_time desc";
	$mypals_rs=$dbo->getRs($sql);
	$comma_str='';
	$i=0;
	foreach($mypals_rs as $rs){
		if($i>0){
			 $comma_str=',';
		}
	  $mypals_id.=$comma_str.$rs[0];
	  $i++;
	}
	return $mypals_id;
}

if(!get_sess_userid()){
login_from_cookie();
}

?>
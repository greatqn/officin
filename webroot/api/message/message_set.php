<?php
include(dirname(__file__)."/../includes.php");

//消息机制写操作
function message_set($id='',$title,$content='',$type=0,$is_mod=0,$mod='affair'){
	Logs::addLog("message_set:1:$id,$title,$content,$type,$is_mod,$mod");
	
	$title=addslashes(short_check($title));
	$content=addslashes($content);
	switch ($mod){
		case "remind":
		$touid=intval($id);
		$link=$content;
		$is_focus=intval($type);
		$type=intval($is_mod);
		return message_set_remind($touid,$title,$link,$type,$is_focus);
		break;
		default:
		$id=intval($id);
		$show_type_id=intval($type);
		$mod_type=intval($is_mod);
		return message_set_affair($id,$title,$content,$show_type_id,$mod_type,$mod);
		break;
	}
}

function message_set_del($type,$rid='',$uid=''){
	$rid=intval($rid);
	switch($type){
		case "remind":
		return message_del_remind($rid,$uid);
		break;
		case "affair":
		return message_del_affair($rid='');
		break;
		default:
		echo 'error';
		break;
	}
}

function message_set_affair($id,$title,$content,$show_type_id,$mod_type,$to_user_id){
	global $tablePreStr;
	$t_recent_affair=$tablePreStr."recent_affair";
	$t_users=$tablePreStr."users";
	$t_mypals=$tablePreStr."pals_mine";
	$dbo=new dbex;
  dbplugin('w');
  if(intval($to_user_id)){
  	$sql="select user_name,user_ico from $t_users where user_id=$to_user_id";
  	$user_info=$dbo->getRow($sql);
  	$uid=$to_user_id;
  	$userico=$user_info['user_ico'];
  	$uname=$user_info['user_name'];
  }else{
		$uid=get_sess_userid();
		$userico=get_sess_userico();
		$uname=get_sess_username();
	}
	$title=htmlspecialchars_decode($title);
	$content=htmlspecialchars_decode($content);
	if($id){
		$sql="delete from $t_recent_affair where for_content_id=$id and mod_type=$mod_type and user_id=$uid";
		$dbo->exeUpdate($sql);
	}
	$sql=" insert into $t_recent_affair (title,content,user_id,user_name,user_ico,date_time,update_time,type_id,mod_type,for_content_id) values ('$title','$content',$uid,'$uname','$userico','".constant('NOWTIME')."','".constant('NOWTIME')."',$show_type_id,$mod_type,$id) ";
	$result=$dbo->exeUpdate($sql);
	if($result){
		$active_time=NOWTIME;
		$sql="update $t_mypals set active_time='$active_time' where pals_id=$uid";
		return $dbo->exeUpdate($sql);
	}
}

function message_set_remind($touid,$content,$link,$type,$is_focus){
	Logs::addLog("message_set_remind:1:$touid,$content,$link,$type,$is_focus,");
	$uid=get_sess_userid();
	$userico=get_sess_userico();
	$uname=get_sess_usercnname();
	global $tablePreStr;
	$t_remind=$tablePreStr."remind";
	$dbo=new dbex;
	dbplugin('w');
	$content=htmlspecialchars_decode($content);
	$link=htmlspecialchars_decode($link);
	if($is_focus==0){
		$update_con=" and type_id = $type ";
	}else{
		$update_con=" and link = '$link' ";
	}
	$sql_check=" select id from $t_remind where user_id=$touid $update_con ";
	Logs::addLog("message_set_remind.23:$sql_check");
	$is_set=$dbo->getRow($sql_check);
	if(empty($is_set)){
		$sql=" insert into $t_remind (user_id,type_id,date,content,is_focus,from_uid,from_uname,from_uico,link) values ($touid,$type,'".constant('NOWTIME')."','$content',$is_focus,$uid,'$uname','$userico','$link') ";
	}else{
		$sql=" update $t_remind set count = count+1,date = '".constant('NOWTIME')."' where user_id = $touid $update_con ";
	}
	Logs::addLog("message_set_remind.24:$sql");
	return $dbo->exeUpdate($sql);
}

function message_set_remind_privacy($privacy,$title,$content='',$type=0,$is_mod=0){
	$link=$content;
	$is_focus=intval($type);
	$type=intval($is_mod);

	$groups = str_cut_1($privacy,"[,",",]");
	$users = str_cut_1($privacy,"{,",",}");
	//Logs::addLog("message_set_remind_privacy:1:$groups,$users");
	 if(strlen($groups)>0){
	$group_users=api_proxy('pals_self_by_sortid',"pals_id",$groups);
	if(count($group_users)){
		foreach($group_users as $g_user){
			//Logs::addLog("message_set_remind_privacy:1:".$g_user["pals_id"].",$title,$link,$type,$is_focus");
			message_set_remind($g_user["pals_id"],$title,$link,$type,$is_focus);
		}
	}
	 }
	
	if(strlen($users)>0){
		$f_users=explode(",",$users);
		foreach($f_users as $g_user){
			//Logs::addLog("message_set_remind_privacy:2:$g_user,$title,$link,$type,$is_focus");
			message_set_remind($g_user,$title,$link,$type,$is_focus);
		}
	}

}


function message_del_remind($rid,$uid){
	$uid=intval($uid) ? $uid : get_sess_userid();
	$condition=intval($rid) ? " and id=$rid ":" limit 1 ";
	global $tablePreStr;
  $t_remind=$tablePreStr."remind";
	$dbo=new dbex;
  dbplugin('w');
  $sql="delete from $t_remind where user_id=$uid $condition ";
  return $dbo->exeUpdate($sql);
}

function message_del_affair($rid){
	global $tablePreStr;
  $t_recent_affair=$tablePreStr."recent_affair";
	$dbo=new dbex;
  dbplugin('w');
  $uid=get_sess_userid();
  $sql="delete from $t_recent_affair where id=$rid and user_id=$uid";
  return $dbo->exeUpdate($sql);
}

function message_set_test(){
	Logs::addLog("message_set_test");
	return "xx";
}

function str_cut_1($s,$a,$d="")
{
	$f=strpos($s,$a)+strlen($a);
	$l=strpos($s,$d);
	if($l)
		$out= substr($s,$f,$l-$f);

	return $out;
}
	
?>
<?php
header("content-type:text/html;charset=utf-8");
//引入小纸条模块功能
	require("foundation/module_users.php");
	require("foundation/fstring.php");
	require("api/base_support.php");
//引入语言包
	$mp_langpackage=new mypalslp;
	$m_langpackage=new msglp;
	
	$t_mypals=$tablePreStr."pals_mine";
	$dbo = new dbex;
	dbtarget('r',$dbServs);
//变量区
Logs::addLog("pals_referred 引荐");
Logs::addhttp();

 	$user_id=get_sess_userid();//推荐人id
	$user_name=get_sess_username();//推荐人姓名
	$user_sex=get_sess_usersex();//推荐人性别
	$userico=get_sess_userico();//推荐人的头像
	$user_cnname = get_sess_usercnname();

	$toids  = get_argg("toids");
	$fromid = get_argg("fromid");
	$note   = get_argg("note");
	$note = $note=="undefined"?"":$note;
Logs::addLog("$toids,$fromid,$note");
	//取发申请人信息
	$user_row=api_proxy("user_self_by_uid","user_id,user_name,user_sex,user_ico,cn_name,palsreq_limit",$fromid);
	if($user_row){
		require_once("foundation/module_mypals.php");
		$user_row['user_mypals']=getMypals($dbo,$fromid,$t_mypals);
	 }else{
		action_return(2,$mp_langpackage->mp_info_wrong,0);
	 }
	 
	 $user_note = str_replace('{userid}',$user_id,$mp_langpackage->mp_referr_send);
	 $user_note = str_replace('{username}',$user_cnname,$user_note);
	 $user_note = str_replace('{msg}',$note,$user_note);
	 $user_note = str_replace('{date}',date("Y-m-d H:i:s"),$user_note);
	 $user_note = str_replace('\'',"\\'",$user_note);
	 
	 $user_row["pal_note"] = $user_note;
	 $user_row["referr_user"] = $user_id;
	 
	 //取被推荐人id
	 $groups = str_cut($toids,"[,",",]");
     $users = str_cut($toids,"{,",",}");
Logs::addLog("g=$groups,u=$users,");	 
	 $count = 0;
	 $success = 0;
	 
	 if(strlen($groups)>0){
	 $group_users=api_proxy('pals_self_by_sortid',"pals_id",$groups);
	 if(count($group_users)){
		foreach($group_users as $g_user){
			Logs::addLog("referred:1:".$g_user["pals_id"].",");
			$count ++;
			$result = api_proxy('pals_self_add',$g_user["pals_id"],$fromid,$user_row);
			Logs::addLog("referred:1:result:$result");
			$success += $result==$mp_langpackage->mp_suc_reg?1:0;
		}
	 }
	 }
	
	if(strlen($users)>0){
		$f_users=explode(",",$users);
		foreach($f_users as $g_user){
			$count ++;
			Logs::addLog("referred:2:$g_user");
			$result = api_proxy('pals_self_add',$g_user,$fromid,$user_row);
			Logs::addLog("referred:2:result:$result");
			$success += $result==$mp_langpackage->mp_suc_reg?1:0;
		}
	}
	
	 Logs::addLog("referred:1:count=$count,success=$success");
	 if($count>0 && $success>0){
	 	echo "引荐请求已发出";
	 }else{
	 	echo "操作失败";
	 }
	
?>
<?php
//引入语言包
	$m_langpackage=new msglp;
	$mp_langpackage=new mypalslp;
	require("api/base_support.php");

//变量区
  $user_id=get_sess_userid();
  $user_name=get_sess_username();
  $userico=get_sess_userico();
  $req_id=intval(get_argg('req_id'));
  $req_user_id=intval(get_argg('req_user_id'));
  $my_pals1=get_sess_mypals();
  $user_pals_row=array();
 

//数据表定义区
	$t_mypals=$tablePreStr."pals_mine";
	$t_pals_request=$tablePreStr."pals_request";
	$dbo=new dbex();

		dbtarget('r',$dbServs);
		$sql="select * from $t_pals_request where user_id=$user_id and id=$req_id";
		$req_user_row=$dbo->getRow($sql);
		
//如果对方不是自己好友
	if(!api_proxy("pals_self_isset",$user_id,$req_user_id)){
	  	$res_uid  =$req_user_row["req_id"];
	  	$res_uname=$req_user_row["req_name"];
	  	$res_usex =$req_user_row["req_sex"];
	  	$res_rpid =$req_user_row["from_id"];
	  	$res_ico  =$req_user_row["req_ico"];
		dbtarget('w',$dbServs);
	  	$sql="insert into $t_mypals (user_id,pals_id,pals_name,pals_sex,add_time,pals_ico,accepted) value($user_id,$res_uid,'$res_uname','$res_usex','".constant('NOWTIME')."','$res_ico',2)";
	  	$dbo->exeUpdate($sql);
	}
	else{
		dbtarget('w',$dbServs);
		$sql="update $t_mypals set accepted=2 where user_id=$user_id and pals_id=$req_user_id";
		$dbo->exeUpdate($sql);
	}

	  $sql="update $t_mypals set accepted=2 where user_id=$req_user_id and pals_id=$user_id";
	  $dbo->exeUpdate($sql);	
		
	  $sql="delete from $t_pals_request where user_id=$user_id and id=$req_id";
	  $dbo->exeUpdate($sql);
		$my_pals='';
		if(get_sess_mypals()){
			 $my_pals=get_sess_mypals().','.$req_user_id;
		}else{
			 $my_pals=$req_user_id;
		}
		set_sess_mypals($my_pals);

		$title=$user_name.$m_langpackage->m_each_fri;
		$scrip_content=$user_name.$m_langpackage->m_each_friend;
		$is_success=api_proxy('scrip_send',$mp_langpackage->mp_system_sends,$title,$scrip_content,$req_user_id,0);
		if($is_success){
			if(!empty($req_user_row["referr_user"])){
				Logs::addLog("message_set:2:$user_name,".$req_user_row["req_name"]);
				api_proxy("message_set_remind",$req_user_row["referr_user"],"您已成功向$user_name引荐了".$req_user_row["req_name"],"",0,1);
				$user_row = api_proxy("user_self_by_uid","cn_name",$req_user_row["referr_user"]);
				api_proxy("message_set_remind",$req_user_id,$user_row["cn_name"]."已成功将您引荐给$user_name","",0,1);
			}else{
				api_proxy("message_set",$req_user_id,"{num}".$mp_langpackage->mp_a_notice,"modules.php?app=msg_notice",0,1,"remind");
			}
			action_return(2,$mp_langpackage->mp_treatment_success,-1);
		}else{
			action_return(2,$mp_langpackage->mp_treatment_failure,-1);
		}
?>
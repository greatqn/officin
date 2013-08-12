<?php
	//引入模块公共方法文件
	require("foundation/aanti_refresh.php");
	require("api/base_support.php");

	//引入语言包
	$m_langpackage=new msglp;

  //变量获得
  $msgStr="";
  $msg_touser=intval(get_argp("msToId"));
  $msg_title=short_check(get_argp("msTitle"));
  $msg_txt=long_check(get_argp("msContent"));
  $touser_id="";//收件人id
  $touser="";//收件人name
  $tousex="";
  $user_id=get_sess_userid();//发件人id
  $user_name=get_sess_username();//发件人姓名
  $user_ico = get_sess_userico();

  if(strlen($msg_txt) >=500){
		action_return(0,$m_langpackage->m_add_exc,-1);exit;
  }
  //数据表定义
  $t_users = $tablePreStr."users";
  $t_msg_outbox = $tablePreStr."msg_outbox";
  $t_msg_inbox = $tablePreStr."msg_inbox";
	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('r',$dbServs);
  $toidUrlStr="";
  if(get_argp("2id")!=""){
     $msg_touser=intval(get_argp("2id"));
     $toidUrlStr="&2id=".$msg_touser;
     if(get_argp("nw")!=""){$toidUrlStr=$toidUrlStr."&nw=1";}//判断是否为新窗口
  }
  $users_row = api_proxy("user_self_by_uid","user_id,user_name,user_ico",$msg_touser);
  if($users_row){
		$touser_id=$users_row[0];
		$touser=$users_row[1];
		$touser_ico=$users_row[2];
		if($touser_id==$user_id){
			action_return(0,$m_langpackage->m_no_mys,"modules.php?app=msg_creator".$toidUrlStr);
		}
  }else{
		action_return(0,$m_langpackage->m_one_err,"modules.php?app=msg_creator".$toidUrlStr);
  }
  
  
  $ent = array(
'mess_content'=>$msg_txt,
'from_user_id'=>$touser_id,
'from_user'=>$touser,
'from_user_ico'=>$touser_ico,
'user_id'=>$user_id,
'user_name'=>$user_name,
'user_ico'=>$user_ico,
'add_time'=>constant('NOWTIME')
);
  
  $result = api_proxy("scrip_inbox_add_msg",$ent);
  if($result != 1){
  	action_return(0,$m_langpackage->m_data_err." code:".$result,"-1");exit;
  }
  
  api_proxy("message_set",$touser_id,$m_langpackage->m_remind,"modules.php?app=msg_mbox",0,5,"remind");

	if(get_argp('nw')=="2"){
		action_return(1,'',"modules.php?app=hstart&user_id=".$touser_id);
	}else if(get_argp('nw')=="3"){
		action_return(1,'',"modules.php?app=msg_gbox&2id=".$touser_id);
	}else if(get_argp('nw')=="4"){
		//取数据，json
		action_return(2,"回复成功",-1);
	}else{
		action_return(1,'',"modules.php?app=msg_mbox".$toidUrlStr);
	}

?>


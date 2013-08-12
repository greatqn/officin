<?php
include(dirname(__file__)."/../includes.php");

//好友圈基础api函数
function pals_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="pals_sort_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$t_pals=$tablePreStr."pals_mine";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	$limit=$num ? " limit $num ":"";
  $sql=" select $fields from $t_pals where $condition order by $by_col $order $limit ";
	if(empty($result_rs)){
		if($limit==''){
			$dbo->setPages(20,$page_num);
		}
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function pals_self_by_paid($fields="*",$id='',$accept=1){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$uid=get_sess_userid();
	$condition=" user_id = $uid ";
	if($accept!=''){
		$accept=intval($accept);
	}
	$get_type="";
	if($id_str!=''){
		if(strpos($id_str,",")){
			$condition.=" and pals_id in ($id_str) ";
		}else{
			$condition.=" and pals_id = $id_str ";
			$get_type="getRow";
		}
	}
	$condition.=" and accepted >= $accept ";
	return pals_read_base($fields,$condition,$get_type);
}

function pals_self_by_sort($fields="*",$sort){
	$uid=get_sess_userid();
	$fields=filt_fields($fields);
	$sort_str=filt_str_array($sort);
	$condition=" user_id = $uid and pals_sort_name in ($sort_str) and accepted>0 ";
	return pals_read_base($fields,$condition);
}

function pals_self_by_sortid($fields="*",$sort){
	$uid=get_sess_userid();
	$fields=filt_fields($fields);
	$sort_str=filt_num_array($sort);
	$condition=" user_id = $uid and pals_sort_id in ($sort_str) and accepted>0 ";
	return pals_read_base($fields,$condition);
}

function pals_self_by_online($fields="*",$num=""){
	$num=intval($num);
	$limit='';
	if($num!=0){
		$limit=" limit $num ";
	}
	$fields=filt_fields($fields);
	$pals_id_str=get_sess_mypals();
	global $tablePreStr;
	$t_online=$tablePreStr."online";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
  $sql=" select $fields from $t_online where user_id in ($pals_id_str) and hidden = 0 order by active_time desc $limit ";
  return $dbo->getRs($sql);
}

function pals_self_by_uid($fields="*",$id,$num=''){
	$num=intval($num);
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,",") ? " user_id in ($id_str) and accepted>0 " : " user_id = $id_str and accepted>0 ";
	return pals_read_base($fields,$condition,'getRs',$num);
}

function pals_self_count_by_uid($id){
	$result_rs=array();
	$dbo=new dbex;
  	dbplugin('r');
	global $tablePreStr;
	$t_pals=$tablePreStr."pals_mine";
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,",") ? " user_id in ($id_str) and accepted>0 " : " user_id = $id_str and accepted>0 ";
	$sql="select count(*) from $t_pals where $condition";
	$rs = $dbo->getRow($sql);
	return intval($rs[0]);
}

function pals_self_isset($holder_id,$pals_id=''){
	global $tablePreStr;
	$t_pals=$tablePreStr."pals_mine";
	$result_rs=array();
	$pals_id=$pals_id ? $pals_id:get_sess_userid();
	if($pals_id){
	  $dbo=new dbex;
	  dbplugin('r');
	  $sql="select id,accepted from $t_pals where user_id=$holder_id and pals_id=$pals_id";
	  $result_rs=$dbo->getRow($sql);
	}else{
		$result_rs=0;
	}
	return $result_rs;
}

function pals_self_add($mreq_touser,$user_id,$user_data){
	//引入语言包
	$mp_langpackage=new mypalslp;
	$m_langpackage=new msglp;
	//Logs::addLog("pals_self_add 1");
	//添加好友是自己
  if($user_id==$mreq_touser){
    return $mp_langpackage->mp_not_self;
  }
  
	global $tablePreStr;
	require_once("foundation/module_mypals.php");
	//数据表定义区
	$t_users=$tablePreStr."users";
	$t_mypals=$tablePreStr."pals_mine";
	$t_pals_request=$tablePreStr."pals_request";
	
	dbplugin('r');
	$dbo=new dbex();
//是否已经提交了申请
	$sql="select user_id from $t_pals_request where user_id=$mreq_touser and req_id=$user_id";
	$is_req=$dbo->getRow($sql);
	if(!empty($is_req['user_id'])){
		return $mp_langpackage->mp_wait_confirm;
	}
//看看好友列里是否已经有该人
	$sql="select user_id from $t_mypals where user_id=$user_id and pals_id=$mreq_touser";
	$is_pals=$dbo->getRow($sql);
 	if(!empty($is_pals['user_id'])){
      return $mp_langpackage->mp_rep_req;
  	}
//变量区
	if(is_array($user_data)){
		$user_name=$user_data['user_name'];
		$user_sex=$user_data['user_sex'];
		$userico=$user_data['user_ico'];
		$user_mypals=$user_data['user_mypals'];
		$user_cnname = $user_data['cn_name'];
		Logs::addLog("pals_self 22:user_cnname = $user_cnname");
	}else{
		$user_row=api_proxy("user_self_by_uid","user_id,user_name,cn_name,user_sex,user_ico,palsreq_limit",$user_id);
		if($user_row){
			$user_name=$user_row['user_name'];
			$user_sex=$user_row['user_sex'];
			$userico=$user_row['user_ico'];
			$user_mypals=getMypals($dbo,$user_id,$t_mypals);
			//或者用cn_name.
			  $user_card = api_proxy("user_card_by_uid","cn_name,en_name",$mreq_touser);
			  if($user_card){
			  	$user_cnname = $user_card["cn_name"]."(".$user_card["en_name"].")";
			  }else{
			  	$user_cnname = "$touser_name";
			  }
			Logs::addLog("pals_self 33:user_cnname = $user_cnname");
		 }else{
			return $mp_langpackage->mp_info_wrong;
		 }
	}
	
	$user_data["pal_note"] = str_replace('{touserid}',$user_id,$user_data["pal_note"]);
  	$user_data["pal_note"] = str_replace('{tousername}',$user_cnname,$user_data["pal_note"]);
  
//	$user_name=get_sess_username();//发申请人姓名
//	$user_sex=get_sess_usersex();//发申请人性别
//	$userico=get_sess_userico();//发申请人的头像
//	$user_mypals=get_sess_mypals();

//取得该人的资料信息
  $user_row=api_proxy("user_self_by_uid","user_id,user_name,user_sex,user_ico,palsreq_limit",$mreq_touser);
  if($user_row){
		$touser_id=$user_row['user_id'];
		$touser_name=$user_row['user_name'];
		$touser_sex=$user_row['user_sex'];
		$touser_ico=$user_row['user_ico'];
		$touser_pals_limit=$user_row['palsreq_limit'];
  }else{
		return $mp_langpackage->mp_info_wrong;
  }
  $user_card = api_proxy("user_card_by_uid","cn_name,en_name",$mreq_touser);
  if($user_card){
  	$cn_name = $user_card["cn_name"]."(".$user_card["en_name"].")";
  }else{
  	$cn_name = "$touser_name";
  }
  

  
  
//此人拒绝加入
	if($touser_pals_limit==2){return $mp_langpackage->mp_ref_add;}
//定义写操作
	dbplugin('w');
	if($touser_pals_limit==0){
//判断自己是否存在对方朋友圈中
	$is_pals=api_proxy("pals_self_isset",$mreq_touser,$user_id);
	if(!$is_pals){
		$accepted=1;
	}else{
		$accepted=2;
		$sql="update $t_mypals set accepted=2 where pals_id=$user_id and user_id=$mreq_touser";
		$dbo->exeUpdate($sql);
	}
	$sql="insert into $t_mypals (user_id,pals_id,pals_name,pals_sex,add_time,pals_ico,accepted,cn_name) value($user_id,$mreq_touser,'$touser_name','$touser_sex','".constant('NOWTIME')."','$touser_ico',$accepted,'$cn_name')";
	$dbo->exeUpdate($sql);

		$title=$user_name.$m_langpackage->m_app_fri."[test]";
		$scrip_content=$user_name.$m_langpackage->m_app_friend;
		$is_success=api_proxy('scrip_send',$mp_langpackage->mp_system_sends,$title,$scrip_content,$mreq_touser,0);
		if($is_success){
			api_proxy("message_set",$mreq_touser,"{num}".$mp_langpackage->mp_a_notice,"modules.php?app=msg_notice",0,1,"remind");
			return $mp_langpackage->mp_treatment_success;
		}else{
			return $mp_langpackage->mp_treatment_failure;
		}
}
//验证加入
if($touser_pals_limit==1){
 $sql="insert into $t_mypals (user_id,pals_id,pals_name,pals_sex,add_time,pals_ico)"
                         ."value($user_id,$touser_id,'$touser_name','$touser_sex','".constant('NOWTIME')."','$touser_ico')";
 $dbo->exeUpdate($sql);
 $from_pals_id=MYSQL_INSERT_ID();
 $sql="insert into $t_pals_request (user_id,req_id,req_name,req_sex,add_time,from_id,req_ico,pal_note,referr_user)"
                        ."value($touser_id,$user_id,'$user_name','$user_sex','".constant('NOWTIME')."',$from_pals_id,'$userico','".$user_data["pal_note"]."',".$user_data["referr_user"].")";
 if($dbo->exeUpdate($sql)){
// 	api_proxy("message_set",$mreq_touser,$mp_langpackage->mp_remind,"modules.php?app=mypals_request",0,3,"remind");//提醒机制
 	api_proxy("message_set",$mreq_touser,"{num}个好友引荐","modules.php?app=mypals_referred",0,2,"remind");//提醒机制
  return $mp_langpackage->mp_suc_reg;
 }else{
  $sql="delete from $t_mypals where id=$from_pals_id";
  $dbo->exeUpdate($sql);
  return $mp_langpackage->mp_req_false;
 }
}
	//end
}
?>
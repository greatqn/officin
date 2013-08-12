<?php
include(dirname(__file__)."/../includes.php");

//群组基础api函数
function inbox_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="mess_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$uid=get_sess_userid();
	$t_scrip=$tablePreStr."msg_inbox";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " mess_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
  $sql=" select $fields from $t_scrip where user_id = $uid and mesinit_id!='' $condition order by $by_col $order ";
  Logs::addLog("scrip_inbox:$sql");
  if($cache==1&&$cache_key!=''){
		$key='inbox_'.$cache_key.$uid.'_'.$num;
		$key_mt='inbox_'.$cache_key.'mt_'.$uid.'_'.$num;
		$result_rs=model_cache($key,$key_mt,$dbo,$sql,$get_type);
	}
	if(empty($result_rs)){
  	$dbo->setPages(20,$page_num);
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}


function inbox_read_base2($fields="*",$condition="",$get_type="",$num="",$by_col="mess_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
//	$uid=get_sess_userid();
	$t_scrip=$tablePreStr."msg_inbox";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " mess_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
  $sql=" select $fields from $t_scrip where $condition order by $by_col $order ";
  Logs::addLog("scrip_inbox:$sql");
  if($cache==1&&$cache_key!=''){
		$key='inbox_'.$cache_key.$uid.'_'.$num;
		$key_mt='inbox_'.$cache_key.'mt_'.$uid.'_'.$num;
		$result_rs=model_cache($key,$key_mt,$dbo,$sql,$get_type);
	}
	if(empty($result_rs)){
  	$dbo->setPages(20,$page_num);
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}
function outbox_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="mess_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	//$uid=get_sess_userid();
	$t_scrip=$tablePreStr."msg_outbox";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " mess_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
  $sql=" select $fields from $t_scrip where $condition order by $by_col $order ";
  if($cache==1&&$cache_key!=''){
		$key='outbox_'.$cache_key.$uid.'_'.$num;
		$key_mt='outbox_'.$cache_key.'mt_'.$uid.'_'.$num;
		$result_rs=model_cache($key,$key_mt,$dbo,$sql,$get_type);
	}
	if(empty($result_rs)){
  	$dbo->setPages(20,$page_num);
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function scrip_inbox_get_mine($fields="*",$date="",$is_read="",$from_id=""){
	$from_id_str=filt_num_array($from_id);
	$fields=filt_fields($fields);
	$condition="";
	$condition.=str_replace("{date}","add_time",date_filter($date));
	if($is_read=='0'||$is_read=='1'){
		$is_read=intval($is_read);
		$condition.=" and readed = $is_read ";
	}
	if($from_id!=''){
		$condition.=" and from_user_id in ($from_id_str) ";
	}
	return inbox_read_base($fields,$condition);
}

//增，查all,查key,

function scrip_inbox_add_msg($ent){
	//inbox添加两条数据，收发。
	//outbox，更新汇总数据。收发。

	
	$ent["mess_title"] = $ent["user_id"]."to".$ent["from_user_id"];
	if(!scrip_inbox_add_msg_ent($ent)){
		return -1;
	}
	if(!scrip_inbox_add_msg_count($ent)){ 
		return -2;
	}
	
	$uid=get_sess_userid();
	$ent["mess_title"] = $ent["from_user_id"]."to".$ent["user_id"];
	if(!scrip_inbox_add_msg_ent($ent)){
		return -3;
	}
	$ent["mess_title"] = $ent["user_id"]."to".$ent["from_user_id"];
	$ent["user_id"] = $ent["from_user_id"];
	$ent["from_user_id"] = get_sess_userid();
	$ent["from_user"] = get_sess_username();
	$ent["from_user_ico"] = get_sess_userico();
	if(!scrip_inbox_add_msg_count($ent)){
		return -4;
	}
	return 1;
}

function scrip_inbox_add_msg_ent($ent){
	global $tablePreStr;
	$t_msg_inbox = $tablePreStr."msg_inbox";
	$sql = "insert into $t_msg_inbox(mess_title,mess_content,from_user_id,from_user,from_user_ico,user_id,add_time,mesinit_id)" .
			"values('".$ent["mess_title"]."','".$ent["mess_content"]."','".$ent["from_user_id"]."','".$ent["from_user"]."','".$ent["from_user_ico"]."','".$ent["user_id"]."','".$ent["add_time"]."','1')";
	$dbo = new dbex;
	dbplugin('w');
	return $dbo ->exeUpdate($sql);
}
//汇总
function scrip_inbox_add_msg_count($inent){
	global $tablePreStr;
	$t_msg_outbox = $tablePreStr."msg_outbox";
	$condition = " user_id = ".$inent['user_id']." and to_user_id = ".$inent['from_user_id'];
	$row = outbox_read_base("*",$condition,"getRow");
	if($row){
		$ent = array('mess_id'=>$row['mess_id'],
			'mess_title'=>$inent['mess_title'],
			'mess_content'=>$inent['mess_content'],
			'to_user'=>$inent['from_user'],
			'to_user_ico'=>$inent['from_user_ico'],
			'state'=>1,
			'count'=>$row["count"]+1,
			'add_time'=>$inent["add_time"]);

		$sql = "update $t_msg_outbox set mess_title='".$ent["mess_title"]."',mess_content='".$ent["mess_content"]."',to_user='".$ent["to_user"]."',to_user_ico='".$ent["to_user_ico"]."',state='".$ent["state"]."',add_time='".$ent["add_time"]."',count='".$ent["count"]."'  where mess_id=".$ent["mess_id"]."";
	}else{
		$ent = array('mess_id'=>$row['mess_id'],
			'mess_title'=>$inent['mess_title'],
			'mess_content'=>$inent['mess_content'],
			'to_user_id'=>$inent['from_user_id'],
			'to_user'=>$inent['from_user'],
			'to_user_ico'=>$inent['from_user_ico'],
			'user_id'=>$inent['user_id'],
			'state'=>1,
			'count'=>1,
			'add_time'=>$inent["add_time"]);
		$sql = "insert into $t_msg_outbox(mess_title,mess_content,to_user_id,to_user,to_user_ico,user_id,state,add_time,count)" .
				"values('".$ent["mess_title"]."','".$ent["mess_content"]."','".$ent["to_user_id"]."','".$ent["to_user"]."','".$ent["to_user_ico"]."','".$ent["user_id"]."','".$ent["state"]."','".$ent["add_time"]."','".$ent["count"]."')";
	}
	$dbo = new dbex;
	dbplugin('w');
	Logs::addLog("scrip_inbox_add_msg_count:$sql");
	$result = $dbo->exeUpdate($sql);
	Logs::addLog("scrip_inbox_add_msg result:$result");
	return result;
}

function scrip_inbox_get_mymsg($fields,$user_id,$date="",$is_read=""){
//	$from_id_str=filt_num_array($from_id);
//	$fields=filt_fields($fields);
	$condition=" user_id=$user_id ";
//	$condition.=str_replace("{date}","add_time",date_filter($date));
//	if($is_read=='0'||$is_read=='1'){
//		$is_read=intval($is_read);
//		$condition.=" and readed = $is_read ";
//	}
//	if($from_id!=''){
//		$condition.=" and from_user_id in ($from_id_str) ";
//	}
	return outbox_read_base($fields,$condition);
}

function scrip_inbox_get_mymsg_key($fields,$user_id,$to_user_id=0){
	if($to_user_id == 0) return null;
	$key = $user_id."to".$to_user_id;
	$condition=" mess_title='$key'";
	return inbox_read_base2($fields,$condition);
}

function scrip_inbox_count_by_uid($id){
	$result_rs=array();
	$dbo=new dbex;
  	dbplugin('r');
	global $tablePreStr;
	$t_scrip=$tablePreStr."msg_outbox";
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,",") ? " user_id in ($id_str) " : " user_id = $id_str ";
	$sql="select count(*) from $t_scrip where $condition";
	$rs = $dbo->getRow($sql);
	return intval($rs[0]);
}

?>
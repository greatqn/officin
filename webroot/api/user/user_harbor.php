<?php
include(dirname(__file__)."/../includes.php");

function harbor_mine_base($fields="*",$condition="",$get_type="",$num="",$by_col="user_id",$order="desc",$cache="",$cache_key=""){
	global $allow_cols;
	global $tablePreStr;
	$t_business_card=$tablePreStr."harbor_mine";
	$fields_str='';
	$result_rs=array();
	$dbo=new dbex;
    dbplugin('r');
	$allow_cols=array(
		'id','user_id','harbor_id','is_port','tag'
	);
  $limit=$num ? " limit $num ":"";
	$by_col = $by_col ? " $by_col " : " user_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	if($fields=="*"){
		$fields=join(",",$allow_cols);
	}elseif(strpos($fields,",")){
		$fields=check_item($fields,$allow_cols);
	}else{
		if(!in_array($fields,$allow_cols)){
			$fields='user_id';
		}
	}
    $sql=" select $fields from $t_business_card where 1=1 $condition order by $by_col $order $limit ";
	if(empty($result_rs)){
		$result_rs=$dbo->{$get_type}($sql);
	}
	return $result_rs;
}

function harbor_base($fields="*",$condition="",$get_type="",$num="",$by_col="id",$order="desc",$cache="",$cache_key=""){
	
	global $allow_cols;
	global $tablePreStr;
	$t_harbor=$tablePreStr."harbor";
	$fields_str='';
	$result_rs=array();
	$dbo=new dbex;
    dbplugin('r');
	$allow_cols=array(
		'id','portname','portname_en','portname_city','portname_country','parent','sort','is_port','tag'
	);
    $limit=$num ? " limit $num ":"";
	$by_col = $by_col ? " $by_col " : " id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	if($fields=="*"){
		$fields=join(",",$allow_cols);
	}elseif(strpos($fields,",")){
		$fields=check_item($fields,$allow_cols);
	}else{
		if(!in_array($fields,$allow_cols)){
			$fields='user_id';
		}
	}
    $sql=" select $fields from $t_harbor where 1=1 $condition order by $by_col $order $limit ";
	if(empty($result_rs)){
		$result_rs=$dbo->{$get_type}($sql);
	}
	return $result_rs;
}


function user_harbor_get_list($fields="*",$parent="",$item_id="",$is_post="",$by_col="id",$order="desc"){
	$fields=filt_fields($fields);
	$condition="";
	$get_type="";
	if(!empty($parent)){
		if($parent == 'null'){
			$condition=" and parent is null";
		}else{
			$condition=" and parent='$parent'";
		}
	}
	if(!empty($item_id)){
		$condition=" and id='$item_id'";
	}
	return harbor_base($fields,$condition,$get_type,"",$by_col,$order);
}

function user_harbor_addorset_item($ent){
	global $tablePreStr;
	$t_harbor = $tablePreStr."harbor";
	if(empty($ent["id"])){
		$sql = "insert into $t_harbor(portname,portname_en,portname_city,portname_country,parent,sort,is_port,tag)values('".$ent["portname"]."','".$ent["portname_en"]."','".$ent["portname_city"]."','".$ent["portname_country"]."','".$ent["parent"]."','".$ent["sort"]."','".$ent["is_port"]."','".$ent["tag"]."')";
	}else{
		$sql = "update $t_harbor set portname='".$ent["portname"]."',portname_en='".$ent["portname_en"]."',portname_city='".$ent["portname_city"]."',portname_country='".$ent["portname_country"]."',parent='".$ent["parent"]."',sort='".$ent["sort"]."',is_port='".$ent["is_port"]."',tag='".$ent["tag"]."' where id=".$ent["id"]."";
	}
	$dbo = new dbex;
	dbplugin('w');
	return $dbo->exeUpdate($sql);
}


function user_harbor_get_mine($fields="*",$user_id){
	$fields=filt_fields($fields);
	$condition="";
	$get_type="";

	$condition=" and user_id='$user_id'";

	return harbor_mine_base($fields,$condition,$get_type);
}

function user_harbor_set_mine($user_id,$arr_harbors){
	global $tablePreStr;
	$t_harbor_mine = $tablePreStr."harbor_mine";
	$dbo = new dbex;
	dbplugin('w');

	$sql = "delete from $t_harbor_mine where user_id=$user_id";
	$dbo->exeUpdate($sql);

	foreach ( $arr_harbors as $harbor_id) {
		$sql = "insert into $t_harbor_mine(user_id,harbor_id,is_port,tag)values('$user_id','$harbor_id','','')";
		$dbo->exeUpdate($sql);
	}
	return true;
}

?>
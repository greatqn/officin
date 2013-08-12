<?php
include(dirname(__file__)."/../includes.php");

//日志基础api函数
function blog_coll_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="edit_time",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	global $is_self;
	$is_pass=' b.is_pass = 1 ';
	$is_admin=get_sess_admin();
	$t_blog=$tablePreStr."blog";
	$t_blog_coll=$tablePreStr."collect";
	$result_rs=array();
	$dbo=new dbex;
  	dbplugin('r');
	$limit=$num ? " limit $num ":"";
	$by_col = $by_col ? " $by_col " : " log_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	$is_pass = ($is_self=='Y' || $is_admin) ? '1' : $is_pass;
    $sql = "select $fields from $t_blog_coll as a join $t_blog as b on (a.`for_content_id` = b.`log_id`)
where $is_pass $condition order by $by_col $order $limit";

    //$sql=" select $fields from $t_blog where $is_pass $condition order by $by_col $order $limit ";
	if(empty($result_rs)){
		if($limit==''){
  		$dbo->setPages(20,$page_num);
  	}
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

//日志基础api函数
function collect_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="collect_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	global $is_self;
	$is_pass=' 1 = 1 ';
	$is_admin=get_sess_admin();
	$t_collect=$tablePreStr."collect";
	$result_rs=array();
	$dbo=new dbex;
  	dbplugin('r');
	$limit=$num ? " limit $num ":"";
	$by_col = $by_col ? " $by_col " : " log_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	$is_pass = ($is_self=='Y' || $is_admin) ? '1' : $is_pass;
    $sql=" select $fields from $t_collect where $is_pass $condition order by $by_col $order $limit ";
	//Logs::addLog("$sql");
	if(empty($result_rs)){
		if($limit==''){
  		$dbo->setPages(20,$page_num);
  	}
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

function blog_coll_by_id($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" and collect_id in ($id_str) ";
	}else{
		$condition=" and collect_id = $id_str ";
		$get_type="getRow";
	}
	return collect_read_base($fields,$condition,$get_type);
}

function blog_coll_by_userid($fields="*",$id){
	$fields=filt_fields($fields);
	//$id_str=filt_num_array($id);
	$get_type="getRs";
	$condition=" and a.user_id = $id ";
	return blog_coll_read_base($fields,$condition,$get_type);
}

function blog_coll_by_typeandcid($fields="*",$userid,$typeid,$for_content_id){
	//$fields=filt_fields($fields);
	//$id_str=filt_num_array($id);
	$get_type="getRs";
	$condition=" and user_id = $userid and for_content_id = $for_content_id";
	if($typeid!=''){
		$condition.=" and type_id= $typeid ";
		$get_type="getRow";
	}
	return collect_read_base($fields,$condition,$get_type);
}

function blog_coll_by_typeid($fields="*",$typeid,$for_content_id){
	$fields=filt_fields($fields);
	$get_type="getRs";
	$condition=" and type_id= $typeid and for_content_id = $for_content_id";
	return collect_read_base($fields,$condition,$get_type);
}

function blog_coll_addorset($ent){
	global $tablePreStr;
	$t_collect = $tablePreStr."collect";
	if(empty($ent["collect_id"])){
		$sql = "insert into $t_collect(user_id,type_id,for_content_id,title,content,add_time,comments,is_pass,tag)values('".$ent["user_id"]."','".$ent["type_id"]."','".$ent["for_content_id"]."','".$ent["title"]."','".$ent["content"]."','".$ent["add_time"]."','".$ent["comments"]."','".$ent["is_pass"]."','".$ent["tag"]."')";
	}else{
		$sql = "update $t_collect set user_id='".$ent["user_id"]."',type_id='".$ent["type_id"]."',for_content_id='".$ent["for_content_id"]."',title='".$ent["title"]."',content='".$ent["content"]."',add_time='".$ent["add_time"]."',comments='".$ent["comments"]."',is_pass='".$ent["is_pass"]."',tag='".$ent["tag"]."' where collect_id=".$ent["collect_id"]."";
	}
	$dbo=new dbex;
	dbplugin('w');
	return $dbo->exeUpdate($sql);
}

function blog_coll_del($user_id,$type_id,$blog_id){
	global $tablePreStr;
	$t_collect = $tablePreStr."collect";
	$sql = "delete from $t_collect where user_id=$user_id and type_id = $type_id and for_content_id = $blog_id";
	$dbo = new dbex;
	dbplugin('w');
	$rs = $dbo->exeUpdate($sql);
	return $rs;
}

function blog_coll_view_like($rs,$type){
	if(count($rs)==0) return "";
	
	$str = "";
	$str .= count($rs);
	$str .= "票";
	$str .= $type==2 ? "赞同":"感谢";
	$str .= "来自于：";
	foreach($rs as $row){
		//<a href="home.php?h=2" target="_blank">开始</a>
		$str .= "<a href=\"home.php?h=".$row["user_id"]."\" target=\"_blank\">".$row["title"]."</a>";
		$str .= ","; 
	}
	return substr($str,0,strlen($str)-1);;
}

?>
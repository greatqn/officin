<?php
include(dirname(__file__)."/../includes.php");

function user_card_base($fields="*",$condition="",$get_type="",$num="",$by_col="user_id",$order="desc",$cache="",$cache_key=""){
	global $allow_cols;
	global $tablePreStr;
	$t_business_card=$tablePreStr."business_card";
	$fields_str='';
	$result_rs=array();
	$dbo=new dbex;
    dbplugin('r');
	$allow_cols=array(
		"user_id", "cn_name", "en_name", "cn_corp_name", "en_corp_name", "address", "telephone", "mobile", "postcode", "fax", "department", "roles", "modify_time", "template_card_id", "background_img", "background_color", "font_color",
		'msn','qq','corp_color','email'
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

function user_template_base($fields="*",$condition="",$get_type="",$num="",$by_col="template_card_id",$order="desc",$cache="",$cache_key=""){
	global $allow_cols;
	global $tablePreStr;
	$t_template_card=$tablePreStr."template_card";
	$fields_str='';
	$result_rs=array();
	$dbo=new dbex;
    dbplugin('r');
	$allow_cols=array(
		"template_card_id", "template", "background_img", "background_color", "font_color", "is_pass", "add_time"
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
  $sql=" select $fields from $t_template_card where 1=1 $condition order by $by_col $order $limit ";
	if(empty($result_rs)){
		$result_rs=$dbo->{$get_type}($sql);
	}
	return $result_rs;
}

function user_card_by_uid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" and user_id in ($id_str) ";
	}else{
		$condition=" and user_id = $id_str ";
		$get_type="getRow";
	}
	return user_card_base($fields,$condition,$get_type);
}

function user_card_get_demo(){
	$tid = user_card_get_default_template();
	
	return array(
"user_id"=>1, 
"cn_name"=>"马丁", 
"en_name"=>"Martin", 
"cn_corp_name"=>"中海集装箱运输有限公司", 
"en_corp_name"=>"Chinashipping Container Line co.,Ltd", 
"address"=>"上海市东大名路378号", 
"telephone"=>"86-21-35124888", 
"mobile"=>"12344448888", 
"msn"=>"123456@msn.com", 
"qq"=>"123456", 
"postcode"=>"200080", 
"fax"=>"86-21-65458984", 
"department"=>"全球机构", 
"roles"=>"总裁", 
"modify_time"=>"2012-03-31 17:17:17", 
"template_card_id"=>$tid, 
"background_img"=>"default", 
"background_color"=>"default", 
"font_color"=>"default",
"corp_color"=>"default"
	);
}

function user_card_get_template($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" and template_card_id in ($id_str) ";
	}else{
		$condition=" and template_card_id = $id_str ";
		$get_type="getRow";
	}
	return user_template_base($fields,$condition,$get_type);
	/*
	return array(
"template_card_id"=>1, 
"template"=>'<div style="{background}{color}height:240px;width:400px;display:block;">
<div style="display:inline-block; margin:20px 0 0 20px;text-align:left;width:363px">
<span style="font:30px Arial,Verdana,\'宋体\',sans-serif;">{cn_name}</span><br />
<span style="margin:2px 0 0 10px;font-size:14px ">{en_name}</span><br/>
<span style="margin:2px 0 0 10px ">Moblie:{mobile}</span>
</div>
<div style="display:inline-block;margin:30px 0 0 0px;text-align:right;width:363px">
<span style="font-size:20px">{cn_corp_name}</span><br />
<span style="font-size:12px">{en_corp_name}</span><br />
<span style="font-size:12px">{address}</span><br />
<span style="font-size:12px">电话：{telephone} 传真：{fax}</span><br />
</div>
</div>', 
"background_img"=>"none", 
"background_color"=>"999", 
"font_color"=>"C03", 
"is_pass"=>1, 
"add_time"=>"2012-03-31 17:17:17"
	);*/
}


function tpl_get_background($card_template,$user_card){
	$background_img  =$user_card["background_img"]=="default"?$card_template["background_img"]:$user_card["background_img"];
	$background_color=$user_card["background_color"]=="default"?$card_template["background_color"]:$user_card["background_color"];
	$background = "background:";
	if($background_img != "none" && $background_img != "undefined"){
		$background .= "url('$background_img');";
	}
	$background .= " no-repeat ";
	if($background_color != "none" && $background_color != "undefined"){
		$background .= "#$background_color";
	}
	$background .= " ;";
	return $background;
	//return "background:#FFD6FF;";
}
function tpl_get_color($card_template,$user_card){
	$font_color      =$user_card["font_color"]=="default"?$card_template["font_color"]:$user_card["font_color"];
	$result = "";
	if($font_color != "none"){
		$result = "color:#$font_color;";
	}
	return $result;
}
function tpl_get_corpcolor($card_template,$user_card){
	$corp_color      =$user_card["corp_color"]=="default"?"ACD6FF":$user_card["corp_color"];
	$result = "";
	if($corp_color != "none"){
		$result = "background:#$corp_color;";
	}
	return $result;
}
function tpl_getvalue($value,$per,$fix){
	if(empty($value)) return "";
	return $per.$value.$fix;
}

function tpl_isempty($value1,$value2,$fix){
	
	if(empty($value2) && empty($value2)) return "";
	return $fix;
}

function user_card_build_template($card_template,$user_card,$user_info){
	$result = $card_template["template"];
	return eval($result);
}

function user_card_build_template_byuserid($user_id){
	$user_row = api_proxy("user_self_by_uid","*",$user_id);
	$user_card = api_proxy("user_card_by_uid","*",$user_id);
	$card_template = api_proxy("user_card_get_template","*",$user_card["template_card_id"]);
	return api_proxy("user_card_build_template",$card_template,$user_card,$user_row);
}

function user_card_get_default_template(){
	global $tablePreStr;
	$t_template_card = $tablePreStr."template_card";
	$dbo=new dbex;
    dbplugin('r');
    $sql=" select template_card_id from $t_template_card where tag = 'default' ";
	$result_rs=$dbo->getRow($sql);
	if($result_rs){
		return $result_rs["template_card_id"];
	}
	return 0;
	
}

//刷新cn_name
function user_card_referpals($pals){
	global $tablePreStr;
	$t_template_card = $tablePreStr."template_card";
	$t_mypals=$tablePreStr."pals_mine";
	$dbo=new dbex;

//    foreach($pals as $item){
//		if(empty($item["cn_name"])){
//			$user_card = api_proxy("user_card_by_uid","cn_name,en_name",$item["pals_id"]);
//			$cn_name = $user_card["cn_name"];
//			if(!empty($user_card["en_name"])){
//				$cn_name .= "(".$user_card["en_name"].")";
//			}
//			dbplugin('w');
//			$sql = "update $t_mypals set cn_name='$cn_name' where pals_id = ".$item["pals_id"];
//			$dbo->exeUpdate($sql);
//			$item["cn_name"] = $cn_name;
//			var_dump($item);
//		}
//    }
    
    for ($i=0;$i<count($pals);$i++) {
    	$item = $pals[$i];
    	if(empty($item["cn_name"])){
			$user_card = api_proxy("user_card_by_uid","cn_name,en_name",$item["pals_id"]);
			$cn_name = $user_card["cn_name"];
			if(!empty($user_card["en_name"])){
				$cn_name .= "(".$user_card["en_name"].")";
			}
			dbplugin('w');
			$sql = "update $t_mypals set cn_name='$cn_name' where pals_id = ".$item["pals_id"];
			$dbo->exeUpdate($sql);
//			$item["cn_name"] = $cn_name;
			$pals[$i]["cn_name"] = $cn_name;
//			var_dump($item);
		}
    }
    
//    var_dump($pals);
	return $pals;

}


function user_card_info_base($fields="*",$condition="",$get_type="",$num="",$by_col="t1.user_id",$order="desc",$cache="",$cache_key=""){
	global $allow_cols;
	global $tablePreStr;
	$t_business_card=$tablePreStr."business_card";
	$t_user=$tablePreStr."users";

	$fields_str='';
	$result_rs=array();
	$dbo=new dbex;
    dbplugin('r');
	$allow_cols=array(
		"user_id", "cn_name", "en_name", "cn_corp_name", "en_corp_name", "address", "telephone", "mobile", "postcode", "fax", "department", "roles", "modify_time", "template_card_id", "background_img", "background_color", "font_color",
		'msn','qq','corp_color','email'
	);
  $limit=$num ? " limit $num ":"";
	$by_col = $by_col ? " $by_col " : " user_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	// if($fields=="*"){
	// 	$fields=join(",",$allow_cols);
	// }elseif(strpos($fields,",")){
	// 	$fields=check_item($fields,$allow_cols);
	// }else{
	// 	if(!in_array($fields,$allow_cols)){
	// 		$fields='user_id';
	// 	}
	// }
    $sql=" select $fields from $t_business_card as t1 inner join $t_user as t2 on t1.user_id = t2.user_id where 1=1 $condition order by $by_col $order $limit ";
	// echo $sql;
	// exit;
	if(empty($result_rs)){
		$result_rs=$dbo->{$get_type}($sql);
	}
	return $result_rs;
}

function user_card_info_get($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" and t1.user_id in ($id_str) ";
	}else{
		$condition=" and t1.user_id = $id_str ";
		//$get_type="getRow";
	}
	return user_card_info_base($fields,$condition,$get_type);
}
?>
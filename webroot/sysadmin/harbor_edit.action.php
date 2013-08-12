<?php
require("session_check.php");	
require("../api/base_support.php");
	
	$is_check=check_rights("c17");
	if(!$is_check){
		echo 'no permission';
		exit;
	}

	
		//接收参数
$id = get_argg('id');
if(!empty($id))$id = intval($id);
$portname = short_check(get_argp('portname'));
$portname_en = short_check(get_argp('portname_en'));
$portname_city = short_check(get_argp('portname_city'));
$portname_country = short_check(get_argp('portname_country'));
$parent = short_check(get_argp('parent'));
$sort = intval(get_argp('sort'));
$is_port = short_check(get_argp('is_port'));

$ent = array('id'=>$id,'portname'=>$portname,'portname_en'=>$portname_en,'portname_city'=>$portname_city,'portname_country'=>$portname_country,'parent'=>$parent,'sort'=>$sort,'is_port'=>$is_port,'tag'=>'');

api_proxy("user_harbor_addorset_item",$ent);
//var_dump($ent);
echo "<script language='javascript' type='text/javascript'>window.location.href='harbor_list.php';</script>";

?>

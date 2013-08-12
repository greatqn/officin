<?php
//引入公共类
require("api/base_support.php");
require("foundation/module_users.php");
require("foundation/csmtp.class.php");
require("foundation/asmtp_info.php");

//设置名片样式的操作

//数据表定义区
$t_business_card = $tablePreStr."business_card";

$tempid = short_check(get_argg('tempid'));
$bgimg = short_check(get_argg('img'));
$bgcolor = short_check(get_argg('bgcolor'));
$ftcolor = short_check(get_argg('ftcolor'));
$corpcolor = short_check(get_argg('corpcolor'));

//定义数据库读写操作
$dbo=new dbex;
dbtarget('w',$dbServs);

$user_id = get_sess_userid();
//template_card_id=$tempid,background_img='$bgimg',
if($tempid == "defaultcard"){
	
	$bgcolor = "default";
	$ftcolor = "default";
	$corpcolor = "default";
}

$sql = "update $t_business_card set background_color='$bgcolor',font_color='$ftcolor',corp_color='$corpcolor' where user_id=$user_id";
Logs::addLog("$sql");
$dbo->exeUpdate($sql);

//输出名片
$user_row = api_proxy("user_self_by_uid","*",$ses_uid);

$user_card = api_proxy("user_card_by_uid","*",$ses_uid);

$card_template = api_proxy("user_card_get_template","*",$user_card["template_card_id"]);

echo api_proxy("user_card_build_template",$card_template,$user_card,$user_info);


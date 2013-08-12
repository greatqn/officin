<?php
//引入公共类
require("api/base_support.php");
require("foundation/module_users.php");
require("foundation/csmtp.class.php");
require("foundation/asmtp_info.php");

//展示名片模板的操作

//数据表定义区
$t_business_card = $tablePreStr."business_card";

$user_id = intval(get_argg('uid'));
$from_user_id = get_sess_userid();
$rs = api_proxy("pals_self_isset",$from_user_id,$user_id);
//array(4) { [0]=> string(1) "7" ["id"]=> string(1) "7" [1]=> string(1) "0" ["accepted"]=> string(1) "0" } 

if(empty($rs)||$rs["accepted"]==0)
exit;

	$user_card = api_proxy("user_card_by_uid","*",$user_id);
	$user_row = api_proxy("user_self_by_uid","*",$user_id);
	$card_template = api_proxy("user_card_get_template","*",$user_card["template_card_id"]);
	$my_user_business_card_text = api_proxy("user_card_build_template",$card_template,$user_card,$user_row);
	echo $my_user_business_card_text;
	

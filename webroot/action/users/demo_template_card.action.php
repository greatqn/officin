<?php
//引入公共类
require("api/base_support.php");
require("foundation/module_users.php");
require("foundation/csmtp.class.php");
require("foundation/asmtp_info.php");

//展示名片模板的操作

//数据表定义区
$t_business_card = $tablePreStr."business_card";

$tempid = intval(get_argg('tempid'));

//输出名片
//$user_row = api_proxy("user_self_by_uid","*",$ses_uid);
$user_row = null;

$user_card = api_proxy("user_card_get_demo");

$card_template = api_proxy("user_card_get_template","*",$tempid);

echo api_proxy("user_card_build_template",$card_template,$user_card,$user_row);


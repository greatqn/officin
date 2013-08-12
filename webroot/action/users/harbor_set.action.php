<?php
//保存港口的操作

//引入公共类
require("api/base_support.php");
require("foundation/module_users.php");
require("foundation/csmtp.class.php");
require("foundation/asmtp_info.php");

//数据表定义区
$t_business_card = $tablePreStr."business_card";
$t_users=$tablePreStr."users";
$t_mypals=$tablePreStr."pals_mine";
$t_online=$tablePreStr."online";
$t_user_info=$tablePreStr."user_info";
$t_uploadfile=$tablePreStr.'uploadfile';
$t_pals_req=$tablePreStr."pals_request";
    
//定义数据库读写操作
$dbo=new dbex;
dbtarget('w',$dbServs);

$user_id = get_sess_userid();
$is_finish=intval(get_argg('is_finish'));

$introduction = short_check(get_argp_d('introduction'));

$myharbors =  get_argp('myharbors');
$ses_uid=get_sess_userid();

$my_harbors = api_proxy("user_harbor_set_mine",$ses_uid,$myharbors);


$sql = "update $t_users set introduction='$introduction' where user_id = $user_id";
$dbo->exeUpdate($sql);

if($is_finish == 1){
	$user_row = api_proxy("user_self_by_uid","*",$user_id);
	$user_email = $user_row["user_email"];
	
	// $data = array('user_email'=>$user_email);
	// $rs = api_proxy("user_mail_reg",$data);
//location.href='modules.php?app=user_activate_succ&user_email';
//location.href='modules.php?app=set_relation&is_finish=1';
	action_return(1,'','modules.php?app=user_activate_succ&user_email='.$user_email);

 }else{ 
action_return(1,'','main.php');
 }
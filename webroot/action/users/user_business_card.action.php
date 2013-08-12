<?php
//保存名片信息的操作


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

$cn_name = short_check(get_argp_d('card_cn_name'));
$en_name = short_check(get_argp_d('card_en_name'));
$cn_corp_name = short_check(get_argp_d('card_cn_corp_name'));
$en_corp_name = short_check(get_argp_d('card_en_corp_name'));
$address = short_check(get_argp_d('card_address'));
$telephone = short_check(get_argp_d('card_telephone'));
$mobile = short_check(get_argp_d('card_mobile'));
$postcode = short_check(get_argp_d('card_postcode'));
$fax = short_check(get_argp_d('card_fax'));
$department = short_check(get_argp_d('card_department'));
$roles = short_check(get_argp_d('card_roles'));
$msn = short_check(get_argp_d('card_msn'));
$qq = short_check(get_argp_d('card_qq'));
$email = short_check(get_argp_d('card_email'));

$user_sex=intval(get_argp("user_sex"));
	
$modify_time =  date('Y-m-d H:i:s',time());


//更新头像
$s_ico=($user_sex==0)?"skin/$skinUrl/images/d_ico_0_small.gif":"skin/$skinUrl/images/d_ico_1_small.gif";

if(get_sess_userico()=="skin/$skinUrl/images/d_ico_0_small.gif" or get_sess_userico()=="skin/$skinUrl/images/d_ico_1_small.gif"){
	$field_ico='user_ico';$field_id='user_id';
	$pals_ico="pals_ico";$p_field_id="pals_id";
	$req_ico="req_ico";$q_field_id="req_id";
	update_user_ico($dbo,$t_users,$field_ico,$field_id,$user_id,$s_ico);
	update_user_ico($dbo,$t_mypals,$pals_ico,$p_field_id,$user_id,$s_ico);
	update_user_ico($dbo,$t_pals_req,$req_ico,$q_field_id,$user_id,$s_ico);
	set_sess_userico($s_ico);
}
//更新用户名
if(!empty($cn_name) && $cn_name != get_sess_username()){
	set_sess_username($cn_name);
	$field_ico='user_name';$field_id='user_id';
	$pals_ico="pals_name";$p_field_id="pals_id";
	$req_ico="req_name";$q_field_id="req_id";
	update_user_ico($dbo,$t_users,$field_ico,$field_id,$user_id,$cn_name);
	update_user_ico($dbo,$t_mypals,$pals_ico,$p_field_id,$user_id,$cn_name);
	update_user_ico($dbo,$t_pals_req,$req_ico,$q_field_id,$user_id,$cn_name);
}


$user_card = api_proxy("user_card_by_uid","user_id",$user_id);
if($user_card){
	$sql = "update $t_business_card set cn_name='$cn_name',en_name='$en_name',cn_corp_name='$cn_corp_name',en_corp_name='$en_corp_name',address='$address',telephone='$telephone',mobile='$mobile',postcode='$postcode',fax='$fax',department='$department',roles='$roles',modify_time='$modify_time',msn='$msn',qq='$qq',email='$email' where user_id=$user_id";
}else{
	$defalut_template_id = api_proxy("user_card_get_default_template");
	$sql = "insert into $t_business_card(user_id,cn_name,en_name,cn_corp_name,en_corp_name,address,telephone,mobile,postcode,fax,department,roles,modify_time,template_card_id,background_img,background_color,font_color,corp_color,msn,qq,email)values('$user_id','$cn_name','$en_name','$cn_corp_name','$en_corp_name','$address','$telephone','$mobile','$postcode','$fax','$department','$roles','$modify_time',$defalut_template_id,'default','default','default','default','$msn','$qq','$email')";
}


	
$rs = $dbo->exeUpdate($sql);

if(!empty($en_name)){
	$cn_name .= "($en_name)";
}

if($rs){
	$sql = "update $t_users set cn_name='$cn_name',user_sex='$user_sex' where user_id = $user_id";
	$dbo->exeUpdate($sql);
	$sql = "update $t_mypals set cn_name='$cn_name' where pals_id = $user_id";
	$dbo->exeUpdate($sql);
}
if($is_finish == 4 ){
	$user_card = api_proxy("user_card_by_uid","*",$user_id);
	$user_row = api_proxy("user_self_by_uid","*",$user_id);
	$card_template = api_proxy("user_card_get_template","*",$user_card["template_card_id"]);
	$my_user_business_card_text = api_proxy("user_card_build_template",$card_template,$user_card,$user_row);
	echo $my_user_business_card_text;
}else if($is_finish == 1){
	$user_row = api_proxy("user_self_by_uid","*",$user_id);
	$user_email = $user_row["user_email"];
	
	$data = array('user_email'=>$user_email);
	$rs = api_proxy("user_mail_reg",$data);
//location.href='modules.php?app=user_activate_succ&user_email';
//location.href='modules.php?app=set_relation&is_finish=1';
	?>
	location.href='modules.php?app=set_relation&is_finish=1';
<?php }else{ ?>
location.href='main.php';
<?php } ?>

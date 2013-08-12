<?php
//激活码发送成功后跳转至此页面
$mail =array(
	'qq.com'=>"http://mail.qq.com",
	'163.com'=>"http://mail.163.com",
	'126.com'=>"http://mail.126.com",
	'188.com'=>"http://mail.188.com",
	'139.com'=>"http://mail.139.com",
	'sohu.com'=>"http://mail.sohu.com",
	'sina.com'=>"http://mail.sina.com",
	'sina.com.cn'=>"http://mail.sina.com.cn",
	'gmail.com'=>"http://mail.gmail.com"
);
$user_email = short_check(get_argg('user_email'));
  $user_id=get_sess_userid();//发件人id
  $user_name=get_sess_username();//发件人姓名
  $user_cnname = get_sess_usercnname();
if(empty($user_cnname)){
	$t_users=$tablePreStr."users";
	//定义读操作
	dbtarget('r',$dbServs);
	$dbo=new dbex;
	$sql="select cn_name from $t_users where user_id='$user_id'";
	$user_info=$dbo->getRow($sql);
	set_sess_usercnname($user_info['cn_name']);
	$user_cnname = get_sess_usercnname();
}


preg_match("/(.*?)@(.*)/",$user_email,$mail_add);

	//语言包引入
	$pu_langpackage=new publiclp;
	$l_langpackage = new loginlp;
	$u_langpackage = new userslp;
	
?>
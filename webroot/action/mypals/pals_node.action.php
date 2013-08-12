<?php
//引入小纸条模块功能
	require("foundation/module_users.php");
	require("api/base_support.php");

//引入语言包
	$mp_langpackage=new mypalslp;
	$m_langpackage=new msglp;
//变量区

 	$user_id=get_sess_userid();//发申请人id
	$user_name=get_sess_username();//发申请人姓名
	$user_sex=get_sess_usersex();//发申请人性别
	$userico=get_sess_userico();//发申请人的头像

//数据表定义区
	$t_users=$tablePreStr."users";
	$t_mypals=$tablePreStr."pals_mine";
	$t_pals_request=$tablePreStr."pals_request";

	$dbo=new dbex();
//备注，展示，设置。
$op = get_argg('op');
$pals_id = get_argg('palid');
//展示操作
if($op=="show"){
	//定义读操作
	dbtarget('r',$dbServs);
	$sql = "select pal_note from $t_mypals where user_id=$user_id and pals_id = $pals_id";
	$rs=$dbo->getRow($sql);
	
  if(!empty($rs)){
      echo $rs["pal_note"];
      exit();
  }
}
//修改操作
if($op=="edit"){
	$pal_note = short_check(get_argg('palnote'));
	dbtarget('r',$dbServs);
	$sql = "update $t_mypals set  pal_note ='$pal_note' where user_id=$user_id and pals_id = $pals_id";
	$rs = $dbo->exeUpdate($sql);

      echo $rs;
      exit();
}


?>
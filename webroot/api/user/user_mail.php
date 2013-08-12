<?php
include(dirname(__file__)."/../includes.php");
require_once(dirname(__file__)."/../../foundation/csmtp.class.php");


/**
 * 发送注册激活邮件
 */
function user_mail_reg($data){
	require(dirname(__file__)."/../../foundation/asmtp_info.php");
	//echo "|user_mail_reg|begin";
	
	global $tablePreStr;
	$dbo=new dbex;
	
	$t_users=$tablePreStr."users";
	$t_online=$tablePreStr."online";
	$t_pals_def_sort=$tablePreStr."pals_def_sort";
	$t_pals_sort=$tablePreStr."pals_sort";
	$t_mypals=$tablePreStr."pals_mine";
	$t_invite_code=$tablePreStr."invite_code";
	$t_user_activation=$tablePreStr."user_activation";

	//$user_name = $data[user_name];
	$user_email = $data['user_email'];
	
	//邮箱配置信息检测
	if(!$smtpAddress || !$smtpPort || !$smtpEmail || !$smtpUser || !$smtpPassword){
		Logs::addLog("user_mail_reg:邮箱信息配置不正确,请联系管理员");
		return -1;
	}

	//生成MD5加密后的激活码
	$activation_code = md5($user_email.time());
	dbplugin('w');
	//在激活码表中压入新数据
	$sql="insert into $t_user_activation (time,activation_code) values ('".constant('NOWTIME')."','$activation_code')";
	$dbo->exeUpdate($sql);

	//获取激活码的id
	$new_activation_id = mysql_insert_id();

	//查询此注册用户的user_id
	$sql="select user_id,user_name,user_sex from $t_users where user_email='$user_email'";
	$new_user=$dbo->getRow($sql);
	$new_user_id = $new_user['user_id'];
	$user_name = $new_user['user_name'];
	$user_sex = $new_user['user_sex'];
	
		//将此注册用户的激活码表id关联到用户表
		$sql="update $t_users set activation_id='$new_activation_id' where user_id=$new_user_id ";
		$dbo->exeUpdate($sql);
		
	//$user_card = api_proxy("user_card_by_uid","*",$new_user_id);
	
	global $siteDomain;
	
		//激活邮件的title和body信息
		$mailtitle = "Officin注册验证";
		$mailbody = $user_name.($user_sex?'先生':'女士');
		$mailbody .= ":<br/>";
		$mailbody .= "欢迎加入Officin（www.officin.com.cn ）！<br/>";
		$mailbody .= "这是一个为工作而存在的社交网络。请点击下面的链接验证注册：<br/>";
		
		$mailbody .= "<a href='".$siteDomain."modules.php?app=user_activation&user_email=".$user_email."&activation_code=".$activation_code."'>href='".$siteDomain."modules.php?app=user_activation&user_email=".$user_email."&activation_code=".$activation_code."</a>";
		
		$email_array=explode('@',$user_email);
		$email_site=strtolower($email_array[1]);

		//为hotmail和gmail邮箱单独设置字符集
		$utf8_site=array("hotmail.com","gmail.com");
		if(!in_array($email_site,$utf8_site)){
			$mailbody = iconv('UTF-8','GBK',$mailbody);
			$mailtitle = iconv('UTF-8','GBK',$mailtitle);
		}


//echo "$smtpAddress,$smtpPort,true,$smtpUser,$smtpPassword";
//echo "[$user_email,$smtpEmail,$mailtitle,$mailbody,]";
		//发送邮件
		$smtp = new smtp($smtpAddress,$smtpPort,true,$smtpUser,$smtpPassword);
		$result=$smtp->sendmail($user_email,$smtpEmail,$mailtitle,$mailbody,'HTML');
		//echo $mailbody;
}
?>
<?php
//d:\xampp\php\phpunit MessageTest
require("init.php");
class MessageTest extends PHPUnit_Framework_TestCase
{
	public function testRemind()
	{
		set_sess_userid(31);
		//api_proxy("message_set_remind",13,"cc接受了你推荐的ff","",0,1);
	//13 被提醒人
	//title
	//url
	//0
	//1  列表提醒.0 右上提醒
	api_proxy("message_set_remind",13,"姓名的名片已添加进您的名片夹","31",0,1);
	}

	public function t1estMessage(){
		set_sess_userid(13);
		$remind_rs=api_proxy("message_get","remind",1,"*");
		var_dump($remind_rs);
	}
	
	public function te1stReferr(){
		$mp_langpackage=new mypalslp;
		set_sess_userid(13);
		api_proxy("message_set",1,"{num}个好友推荐","modules.php?app=mypals_request",0,10,"remind");//提醒机制
 	
	}
	
	public function t1estkk(){
		echo 'kkk';
		$rs = api_proxy("pals_self_count_by_uid",13);
		var_dump($rs);
		$rs = api_proxy("pals_self_count_by_uid",1300);
		var_dump($rs);
	}
	
	public function te1stBlog(){
		echo 'test blog\r\n';
		$blog_rs=api_proxy("blog_self_count_by_uid",13);
		var_dump($blog_rs);
		$blog_rs=api_proxy("blog_self_count_by_uid",1300);
		var_dump($blog_rs);
	}
	
	public function te1st_msgbox(){
		echo 'test_msgbox';
		$rs = api_proxy("scrip_inbox_count_by_uid",13);
		var_dump($rs);
		$rs = api_proxy("scrip_inbox_count_by_uid",1300);
		var_dump($rs);
	}
	
	public function te1st_user_mail_reg(){
		echo 'test_user_mail_reg';
		$data = array('user_email'=>'greatqn@163.com');
		$rs = api_proxy("user_mail_reg",$data);
		var_dump($rs);
		echo 'test_user_mail_reg end';
	}
}

?>
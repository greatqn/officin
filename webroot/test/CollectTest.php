<?php
//d:\xampp\php\phpunit CollectTest
require("init.php");
class CollectTest extends PHPUnit_Framework_TestCase
{
	public function testAddColl()
	{
		set_sess_userid(13);
		set_sess_usercnname("cnname");
		
		$user_id=get_sess_userid();
		$user_name = get_sess_usercnname();
		$s_type=2; //0.collect,2.agree,3.oppose,4.thank;
		$s_content_id=25;
	    $s_comment=1;
		$tag=1;
	
		$ent = array('collect_id'=>'',
				'user_id'=>$user_id,
				'type_id'=>$s_type,
				'for_content_id'=>$s_content_id,'title'=>$user_name,
				'content'=>$s_comment,
				'add_time'=>date("Y-m-d H:i:s"),
				'comments'=>'',
				'is_pass'=>'0',
				'tag'=>$tag);
		$result =  api_proxy("blog_coll_addorset",$ent);

		$this->assertEquals(1,$result,"blog_coll_addorset err");
	}

	public function te1stReadColl()
	{
		$user_id = 13;
		$s_type=0;
		$s_content_id=25;
		$ent = api_proxy("blog_coll_by_typeandcid","*",$user_id,$s_type,$s_content_id);
		var_dump($ent);
	}
	
	public function te1stAddCount(){
		$blog_id = "24,25";
//		`hagrees`,
//        `hopposes`,
//        `hthanks` 
		$field = "hagrees";
		api_proxy("blog_self_add_count",$blog_id,$field);
	}
	
	public function testReadLike(){
		
		$userid = "";
		$s_type=2;
		$s_content_id=25;
		$rs = api_proxy("blog_coll_by_typeid","*",$s_type,$s_content_id);
		var_dump($rs);
	}
}

?>
<?php
//引入模块公共方法文件
require("foundation/fcontent_format.php");
require("foundation/module_share.php");
require("foundation/aintegral.php");
require("api/base_support.php");
require("foundation/ftag.php");

//引入语言包
$s_langpackage=new sharelp;

//act	like_action
//blog_id	26
//s_type	4


//变量声明区
	$user_id=get_sess_userid();
	$user_name = get_sess_usercnname();
	$s_type   =intval(get_argg('s_type'));
	$s_blog_id=intval(get_argg('blog_id'));

//  $s_comment=short_check(get_argp('comment'));
//	$tag=short_check(get_argp('tag'));
//	$isadd = short_check(get_argp('isadd'));
//Logs::addLog("collect begin $user_id,$s_type,$s_content_id, $isadd");
	$rs = api_proxy("blog_coll_by_typeandcid","type_id",$user_id,"",$s_blog_id);
//	var_dump($rs);
	$olds = array();
	foreach($rs as $row){
		
		$olds[$row["type_id"]] = $row;
	}
//var_dump($olds);
//return;

	if(array_key_exists($s_type,$olds)){
		echo "0";
		return;
	}
	if($s_type == 2 && array_key_exists("3",$olds)){
		echo "0";
		return;
	}
	if($s_type == 3 && array_key_exists("2",$olds)){
		echo "0";
		return;
	}
	
		$ent = array('collect_id'=>'',
				'user_id'=>$user_id,
				'type_id'=>$s_type,
				'for_content_id'=>$s_blog_id,
				'title'=>$user_name,
				'content'=>$s_comment,
				'add_time'=>date("Y-m-d H:i:s"),
				'comments'=>'',
				'is_pass'=>'0',
				'tag'=>$tag);


//var_dump($ent);
	$result =  api_proxy("blog_coll_addorset",$ent);
//Logs::addLog("collect end $result");
	if($result){

		$field = $s_type==2?"hagrees":"hthanks";
		$field = $s_type==3?"hopposes":$field;
		api_proxy("blog_self_add_count",$s_blog_id,$field);
		
		if($s_type==3){
			echo "1";
		}else{
			$rs = api_proxy("blog_coll_by_typeid","*",$s_type,$s_blog_id);
			echo api_proxy("blog_coll_view_like",$rs,$s_type);
		}
	
	}else{
		echo "0";
	}
?>
<?php
//引入模块公共方法文件
require("foundation/fcontent_format.php");
require("foundation/module_share.php");
require("foundation/aintegral.php");
require("api/base_support.php");
require("foundation/ftag.php");

//引入语言包
$s_langpackage=new sharelp;

//变量声明区
	$user_id=get_sess_userid();
	$user_name = get_sess_usercnname();
	$s_type=short_check(get_argg('s_type'));
	$s_content_id=intval(get_argg('share_content_id'));
	//$title_data=short_check(get_argp('title_data'));
	//$re_link=short_check(get_argp('re_link'));
	//$re_thumb=get_session('m_thumb');
	//$re_m_link=get_session('m_link');
    $s_comment=short_check(get_argp('comment'));
	$tag=short_check(get_argp('tag'));
	$isadd = short_check(get_argp('isadd'));
//Logs::addLog("collect begin $user_id,$s_type,$s_content_id, $isadd");
	$ent = api_proxy("blog_coll_by_typeandcid","*",$user_id,$s_type,$s_content_id);
//Logs::addLog("collect ent $ent");
//	var_dump($ent);
	if($ent==false){
		$ent = array('collect_id'=>'',
				'user_id'=>$user_id,
				'type_id'=>$s_type,
				'for_content_id'=>$s_content_id,
				'title'=>$user_name,
				'content'=>$s_comment,
				'add_time'=>date("Y-m-d H:i:s"),
				'comments'=>'',
				'is_pass'=>'0',
				'tag'=>$tag);
	}else{
		if($isadd=="true") {
			echo "您已经收藏了．";
			exit;
		}
		$ent['content']=$s_comment;
		$ent['tag']=$tag;
	}
//var_dump($ent);
	$result =  api_proxy("blog_coll_addorset",$ent);
//Logs::addLog("collect end $result");
	if($result){
		echo "success";
	}else{
		echo "操作失败";
	}
?>
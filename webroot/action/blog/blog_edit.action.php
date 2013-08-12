<?php
	require("foundation/ftag.php");
	require("api/base_support.php");
    require("foundation/fcontent_format.php");
	//引入语言包
	$b_langpackage=new bloglp;
Logs::addLog("blog_edit");
Logs::addhttp();
	//变量取得
  $ulog_id=intval(get_argg("id"));
  $privacy=short_check(get_argp("privacy"));
  $ulog_title=short_check(get_argp("blog_title"));
  $tag=short_check(get_argp("tag"));
  $can_comment = short_check(get_argp("can_comment"));
  $can_comment = $can_comment=="on"?1:0;
  
  if(get_argp("blog_sort_list")){
  	$ulog_sort=short_check(get_argp("blog_sort_list"));
  }else{
  	$ulog_sort=0;
  }
  Logs::addLog(strlen(get_argp("CONTENT")));
  //$ulog_txt=big_check(get_argp("CONTENT"),1000000);
  $ulog_txt=get_argp("CONTENT");

  $blog_sort_name=short_check(get_argp('blog_sort_name'));
	$user_id=get_sess_userid();
	$user_name=get_sess_username();
	
	//数据表定义区
	$t_blog=$tablePreStr."blog";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);
	
	//标签自动化
	$old_tag=get_tag($t_blog,'log_id',$ulog_id);
	auto_tag($tag,$old_tag,$ulog_id,0);
	
	if($privacy==''){
		$privacy = "!all";
	}
	
	$sql= "update $t_blog set log_title='$ulog_title',privacy='$privacy',log_sort='$ulog_sort',log_content='$ulog_txt',edit_time='".constant('NOWTIME')."',log_sort_name='$blog_sort_name',tag='$tag',can_comment=$can_comment where user_id=$user_id and log_id=$ulog_id";
//Logs::addLog($sql);
 	if($dbo->exeUpdate($sql)){
 		if($privacy != '!all'){
 		//Logs::addLog("edit privacy:$privacy,$ulog_title,$ulog_txt");
		$title=$b_langpackage->b_write_edit_log."<a href=\"home.php?h=".$user_id."&app=blog&id=".$ulog_id."\" target=\"_blank\">".$ulog_title."</a>";
		$content=get_lentxt($ulog_txt);
		//Logs::addLog("edit privacy２:$privacy,$title,$content");
		api_proxy("message_set_remind_privacy",$privacy,$title,$content,1,0);
 		}
		action_return(1,'','modules.php?app=blog&id='.$ulog_id);
	}else{
		action_return(0,'error','-1');
	}
?>

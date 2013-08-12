<?php
//引入模块公共方法文件
require("session_check.php");
require("../foundation/fpages_bar.php");
require("../foundation/fsqlseletiem_set.php");
require("../foundation/fback_search.php");
require("../api/base_support.php");
//读写初始化
$dbo = new dbex;
dbtarget('w',$dbServs);
//定义数据库表名称
$t_template_card = $tablePreStr."template_card";
//接收参数
$op = get_argg('op');
//修改操作
if($op=="edit"){
	$template_card_id = intval(get_argp('template_card_id'));
	$name = short_check(get_argp('name'));
	$tag = short_check(get_argp('tag'));
	$template = big_check(get_argp('template'));
	$background_color = short_check(get_argp('background_color'));
	$font_color = short_check(get_argp('font_color'));
	$is_pass = 1;
	$sort = intval(get_argp('sort'));
	$add_time = date('Y-m-d H:i:s',time());
	$background_img = get_argp('background_img');
	//判断是否上传新的海报图片
	if($_FILES['attach']['name']){
		$_FILES['attach']['name']=array($_FILES['attach']['name']);
		$_FILES['attach']['size']=array($_FILES['attach']['size']);
		$_FILES['attach']['type']=array($_FILES['attach']['type']);
		$_FILES['attach']['tmp_name']=array($_FILES['attach']['tmp_name']);
		$_FILES['attach']['error']=array($_FILES['attach']['error']);
		$up = new upload();
		//$up->set_thumb(150,150);	//缩略图设置
		$up->set_dir('../uploadfiles/card/','template/');	//文件路径
		$fs = $up->execute();
		$fileSrcStr=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['name'];	//原图
		//$thumb_src=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['thumb'];	//缩略图
		$background_img = substr($fileSrcStr,3);
	}
	$sql = "update $t_template_card set name='$name',tag='$tag',template='$template',background_img='$background_img',background_color='$background_color',font_color='$font_color',is_pass='$is_pass',sort='$sort',add_time='$add_time' where template_card_id=$template_card_id";
	//Logs::addLog("card update:$sql");
	$rs = $dbo->exeUpdate($sql);
	if($rs){
		echo "<script language='javascript' type='text/javascript'>window.location.href='card_template_list.php?order_by=template_card_id&order_sc=desc';</script>";
	}else{
		echo "更新失败";
		if($_FILES['attach']['name']){
			@unlink("../".$background_img); 
		}
	}
}


//添加操作
if($op=="add"){
	$name = short_check(get_argp('name'));
	$tag = short_check(get_argp('tag'));
	$template = big_check(get_argp('template'));
	$background_color = short_check(get_argp('background_color'));
	$font_color = short_check(get_argp('font_color'));
	$is_pass = 1;
	$sort = intval(get_argp('sort'));
	$add_time = date('Y-m-d H:i:s',time());
	$background_img = "none";
	//判断是否上传新的海报图片
	if($_FILES['attach']['name']){
		$_FILES['attach']['name']=array($_FILES['attach']['name']);
		$_FILES['attach']['size']=array($_FILES['attach']['size']);
		$_FILES['attach']['type']=array($_FILES['attach']['type']);
		$_FILES['attach']['tmp_name']=array($_FILES['attach']['tmp_name']);
		$_FILES['attach']['error']=array($_FILES['attach']['error']);
		$up = new upload();
		//$up->set_thumb(150,150);	//缩略图设置
		$up->set_dir('../uploadfiles/card/','template/');	//文件路径
		$fs = $up->execute();
		$fileSrcStr=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['name'];	//原图
		//$thumb_src=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['thumb'];	//缩略图
		$background_img = substr($fileSrcStr,3);
	}
	$sql = "insert into $t_template_card(name,tag,template,background_img,background_color,font_color,is_pass,sort,add_time)values('$name','$tag','$template','$background_img','$background_color','$font_color','$is_pass','$sort','$add_time')";
	//Logs::addLog("$sql");
	$rs = $dbo->exeUpdate($sql);
	if($rs){
		echo "<script language='javascript' type='text/javascript'>window.location.href='card_template_list.php?order_by=template_card_id&order_sc=desc';</script>";
	}else{
		echo "更新失败";
		if($_FILES['attach']['name']){
			@unlink("../".$background_img); 
		}
	}
}

//删除活动类型默认海报
if($op=="del_poster"){
	$typeid = get_argg('typeid');
	$sql = "select * from $t_template_card where template_card_id=$typeid";
	$this_type = $dbo->getRow($sql);
	$background_img = $this_type['background_img'];
	//$type_poster_thumb = $this_type['poster_thumb'];
	$sql = "update $t_template_card set background_img='none' where template_card_id=$typeid";
	$dbo->exeUpdate($sql);
	@unlink("../".$background_img); 
	echo "<script language='javascript' type='text/javascript'>window.location.href='card_template_detail.php?op=edit&typeid=".$typeid."';</script>";
}

if($op=="del"){
	$typeid = get_argg('typeid');
	$t_business_card = $tablePreStr."business_card";
	$sql = "select user_id from $t_business_card where template_card_id=$typeid limit 0,1";
	$rs = $dbo->getRow($sql);
	//var_dump($rs);
	if($rs){
		echo "该模板已在使用，不能删除。";
	}else{
		$sql = "select * from $t_template_card where template_card_id=$typeid";
		$this_type = $dbo->getRow($sql);
		$background_img = $this_type['background_img'];
		if($background_img != "none"){
			@unlink("../".$background_img); 
		}
		$sql = "delete from $t_template_card where  template_card_id=$typeid";
		$dbo->exeUpdate($sql);
		echo "<script language='javascript' type='text/javascript'>window.location.href='card_template_list.php?order_by=template_card_id&order_sc=desc';</script>";
	}
}
?>

<?php
	require("session_check.php");
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	require("../foundation/fback_search.php");
	
	//语言包引入
	$m_langpackage=new modulelp;
	$ad_langpackage=new adminmenulp;
	$bp_langpackage=new back_publiclp;
	$eb_langpackage = new event_backstagelp;
	$is_check=check_rights("c01");
	if(!$is_check){
		echo 'no permission';exit;
	}
	
	//表定义区
	$t_template_card = $tablePreStr."template_card";
	
	$dbo = new dbex;
	dbtarget('w',$dbServs);

	//当前页面参数
	$page_num=trim(get_argg('page'));

	//变量区
	$c_province=short_check(get_argg('province'));
	$c_city=short_check(get_argg('city'));
	$c_online=intval(get_argg('online'));
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;
	
	$t_table=$t_template_card;
	
	$eq_array=array('template_card_id');
	$like_array=array("cn_name");
	$date_array=array("user_add_time","lastlogin_datetime");
	$num_array=array("integral");
	$join_cond="";
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,'','',$join_cond);
	
	
	if(!empty($c_orderby)){
		$sql.=" order by $t_template_card.$c_orderby ";
	}

	$sql.=" $c_ordersc ";
//Logs::addLog("card_list:$sql");
	//设置分页
	$dbo->setPages($c_perpage,$page_num);

	//取得数据
	$member_rs=$dbo->getRs($sql);

	//分页总数
	$page_total=$dbo->totalPage;

	//用户状态
	$s_no_limit='';$s_lock='';$s_normal='';
	if(get_argg('is_pass')==''){$s_no_limit="selected";}
	if(get_argg('is_pass')=='0'){$s_lock="selected";}
	if(get_argg('is_pass')=='1'){$s_normal="selected";}

	//在线状态
	if(get_argg('online')==1){$is_online='checked';}
	if(get_argg('online')==''){$is_online='';}
	//用户性别
	$sex_no_limit='';$sex_women='';$sex_man='';
	if(get_argg('user_sex')==''){$sex_no_limit="selected";}
	if(get_argg('user_sex')=='0'){$sex_women="selected";}
	if(get_argg('user_sex')=='1'){$sex_man="selected";}

	//按字段排序
	$o_def='';$o_reg_time='';$o_guest_num='';$o_integral='';$o_lastlogin_datetime='';
	if(get_argg('order_by')==''||get_argg('order_by')=="template_card_id"){$o_def="selected";}
	if(get_argg('order_by')=="user_add_time"){$o_reg_time="selected";}
	if(get_argg('order_by')=="guest_num"){$o_guest_num="selected";}
	if(get_argg('order_by')=="integral"){$o_integral="selected";}
	if(get_argg('order_by')=="lastlogin_datetime"){$o_lastlogin_datetime="selected";}

	//显示控制
	$isNull=0;
	$isset_data="";
	$none_data="content_none";
	if(empty($member_rs)){
		$isset_data="content_none";
		$none_data="";
		$isNull=1;
	}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript' src='../servtools/calendar.js'></script>
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
<script type='text/javascript' src='../servtools/dialog/zDialog.js'></script>
<script type='text/javascript' src='js/jy.js'></script>
<script type='text/javascript'>
function inserts(){
	window.location.href="card_template_detail.php?op=add";
}

function deletes(typeid){
	window.location.href="card_template_detail.action.php?op=del&typeid="+typeid;
}



function showcard(id){
	console.log("showcard_back");
	var c = '<iframe frameborder="0" src="../do.php?act=demo_template_card&tempid='+id+'" style="width:416px;height:256px" ></iframe>';
	var diag = new Dialog();
	diag.Width = 416;
	diag.Height = 260;
	diag.Top="50%";
	diag.Left="50%";
	diag.Title = "名片示例";
	diag.InnerHtml= c;
	diag.OKEvent = function(){diag.close();};
	diag.show();
}
</script>
</head>
<body>
	<div id="maincontent">
		<div class="wrap">
			<div class="crumbs">当前位置 &gt;&gt;管理首页&gt;&gt;名片模板</div>
			<hr />
			<div class="infobox">
            <h3><?php echo $m_langpackage->m_check_condition;?></h3>
            <div class="content">
<form action="" method="GET" name='form' onsubmit='return check_form();'>
<TABLE class="form-table">
  <TBODY>
  <TR>
    <th width="90">模板ID</th>
    <TD><input type="text" class="small-text" name='template_card_id' value="<?php echo get_argg('template_card_id');?>"></TD>
    <th>中文名</th>
    <TD><INPUT type='text' class="small-text" name='cn_name' value='<?php echo get_argg('cn_name');?>'>&nbsp;<font color=red>*</font></TD>
  </TR>
  

  <TR>
    <th><?php echo $m_langpackage->m_result_order;?></th>
    <TD colSpan="3" height="20">
        <SELECT name='order_by'>
            <OPTION value='template_card_id' <?php echo $o_def;?>><?php echo $m_langpackage->m_def_order;?></OPTION>
            <OPTION value='user_add_time' <?php echo $o_reg_time;?>><?php echo $m_langpackage->m_reg_date;?></OPTION>
            <OPTION value='guest_num' <?php echo $o_guest_num;?>><?php echo $m_langpackage->m_guest;?></OPTION>
            <OPTION value='integral' <?php echo $o_integral;?>><?php echo $m_langpackage->m_inter;?></OPTION>
            <OPTION value='lastlogin_datetime' <?php echo $o_lastlogin_datetime;?>><?php echo $m_langpackage->m_last_login;?></OPTION>
        </SELECT>
      <?php echo order_sc();?>
      <?php echo perpage();?>
  </TD>
  </TR>
  	<tr><td colspan=2><?php echo $m_langpackage->m_red;?></td></tr>
    <tr><td colspan=2><INPUT class="regular-button" type="submit" value="<?php echo $m_langpackage->m_search;?>" /></td></tr>
  </TBODY>
  </TABLE>
</form>
	</div>
</div>

<div class="infobox">
    <h3><?php echo $m_langpackage->m_member_list;?></h3>
    <div class="content">
<table class="list_table" id="mytable">
<thead>
	<tr>
<th width="10%">模板ID</th>
<th width="10%">名称</th>
<th width="10%">分类</th>
<th width="10%">背景图</th>
<th width="10%">背景颜色</th>
<th width="10%">字体颜色</th>
<th width="10%">is_pass</th>
<th width="10%">添加时间</th>
            <th><?php echo $eb_langpackage->eb_operation;?></th>
    </tr>
	</thead>
<?php foreach($member_rs as $rs){?>
	<tr>
<td>
    <?php echo $rs['template_card_id'];?>
</td>
<td>
    <?php echo $rs['name'];?>
</td>
<td>
    <?php echo $rs['tag'];?>
</td>
<td>
    <?php echo $rs['background_img'];?>
</td>
<td>
    <?php echo $rs['background_color'];?>
</td>
<td>
    <?php echo $rs['font_color'];?>
</td>
<td>
    <?php echo $rs['is_pass'];?>
</td>
<td>
    <?php echo $rs['add_time'];?>
</td>
    <td>
    <a href="card_template_detail.php?op=edit&typeid=<?php echo $rs['template_card_id'];?>"><?php echo $eb_langpackage->eb_update; ?></a>
    &nbsp;|&nbsp;
    <a href="javascript:deletes('<?php echo $rs['template_card_id'];?>')" onclick='return confirm("<?php echo $eb_langpackage->eb_confirm_delete; ?>");'><?php echo $eb_langpackage->eb_delete; ?></a>
    &nbsp;|&nbsp;
    <a href="javascript:showcard('<?php echo $rs['template_card_id'];?>')" >示例</a>
    </td>
    </tr>
<?php }?>
	<tr>
    <td colspan="3">
    <input class="regular-button" type="button" id="adds" name="adds" value="<?php echo $eb_langpackage->eb_insert; ?>" onClick="inserts()" />
    </td>
    </tr>
</table>
<?php page_show($isNull,$page_num,$page_total);?>
<div class='guide_info <?php echo $none_data;?>'><?php echo $m_langpackage->m_none_data;?></div>
</div>
</div>
</div>
</form>
</body>
</html>
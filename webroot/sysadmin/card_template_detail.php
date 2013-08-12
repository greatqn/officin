<?php
require("session_check.php");
require("../foundation/fpages_bar.php");
require("../foundation/fsqlseletiem_set.php");

//语言包引入
$eb_langpackage = new event_backstagelp;
Logs::addLog("card_template");
Logs::addhttp();
require("../foundation/fback_search.php");
require("../api/base_support.php");
//读写初始化
$dbo = new dbex;
dbtarget('w',$dbServs);
//接收参数
$op = get_argg('op');
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
<SCRIPT language=JavaScript src="../servtools/kindeditor/kindeditor.js"></SCRIPT>
<script src="../servtools/jscolor/jscolor.js" type="text/javascript"></script>

<script type='text/javascript'>
function del_poster(typeid){
	window.location.href="card_template_detail.action.php?op=del_poster&typeid="+typeid;
}

function event_detail(){
	var typename = document.getElementById('name').value;
	console.log(typename);
	var tag = document.getElementById('tag').value;
	if(typename=="" || typename==null){
		alert("请输入名称");
		return false;
	}
	if(tag=="" || tag==null){
		alert("请输入分类");
		return false;
	}
}
</script>
</head>
<body>
<div id="maincontent">
<div class="wrap">
<div class="crumbs"><?php echo $eb_langpackage->eb_location; ?> &gt;&gt;<a href="javascript:void(0);">管理首页</a>&gt;&gt;<a href="card_template_list.php">名片模板</a>
<?php 
	$rs = array('template_card_id'=>'','name'=>'','tag'=>'','template'=>'','background_img'=>'','background_color'=>'','font_color'=>'','is_pass'=>'','sort'=>'','add_time'=>'');
	if($op=="edit"){
		$typeid = get_argg('typeid');
		$t_template_card = $tablePreStr."template_card";
		$sql = "select * from $t_template_card where template_card_id=$typeid";
		$rs = $dbo->getRow($sql);
		//var_dump($rs);
	}
?>
<hr/>
<form enctype="multipart/form-data" id="event_type_detail" name="event_type_detail" method="post" action="card_template_detail.action.php?op=<?php echo $op; ?>&typeid=<?php echo $rs['type_id'];?>" onSubmit="return event_detail();">
  <table class="form-table" border="0">

<tr>
     <th>名称</th>
     <td>
     <input type="hidden"  name="template_card_id" id="template_card_id" value="<?php echo $rs['template_card_id'];?>" />
     <input type="hidden"  name="background_img" id="background_img" value="<?php echo $rs['background_img'];?>" />
     <input class="regular-text" type="text" name="name" id="name" value="<?php echo $rs['name'];?>" /></td>
</tr>
<tr>
     <th>分类</th>
     <td><input class="regular-text" type="text" name="tag" id="tag" value="<?php echo $rs['tag'];?>" /></td>
</tr>
<tr>
     <th>template</th>
     <td>
     <textarea style='width:550px;height:400px;_width:550px;'  name="template" id="template"><?php echo $rs['template'];?></textarea>
</tr>
    <tr>
      <th>默认背景图</th>
      <td>
	  <?php if($rs['background_img'] && $rs['background_img']!="none"){ ?>
      <img src="../<?php echo $rs['background_img']; ?>" alt="<?php echo $eb_langpackage->eb_image_loading; ?>" />
	  	<?php if($op=="edit"){ ?>
            <a class="red" href="javascript:del_poster('<?php echo $rs['template_card_id'];?>')" ><?php echo $eb_langpackage->eb_delete; ?></a>
         <?php } ?>
	  <?php } ?>
      <p><input  type="file" name="attach" id="attach" /> </p>
      </td>
    </tr>
<tr>
     <th>默认背景色</th>
     <td><input class="color" type="text" name="background_color" id="background_color" value="<?php echo $rs['background_color'];?>" /></td>
</tr>
<tr>
     <th>字体颜色</th>
     <td><input class="color" type="text" name="font_color" id="font_color" value="<?php echo $rs['font_color'];?>" /></td>
</tr>
    <tr>
      <th><?php echo $eb_langpackage->eb_display_order; ?></th>
      <td><input class="small-text" type="text" name="sort" id="sort" value="<?php echo $rs['sort'];?>" /></td>
    </tr>
    <tr>
      <th><input class="regular-button" type="submit" name="sm" id="sm" value="<?php echo $eb_langpackage->eb_submit; ?>" /></th>
      <td></td>
    </tr>
  </table>
</form>
</div>
</div>
</div>
<script type="text/javascript">
KE.show({
	id:'template33',
	resizeMode:0
});
</script>
</body>
</html>
<?php
require("session_check.php");	
require("../api/base_support.php");

//表定义区
$t_harbor=$tablePreStr."harbor";
//数据库操作初始化
$dbo = new dbex;
dbtarget('w',$dbServs);
//变量区
$id=intval(get_argg('id'));
$info_row=array(

);
$submit_str='添加';
$hidd_value='harbor_edit.action.php';
if($id){
$sql="select * from $t_harbor where id=$id";
$info_row=$dbo->getRow($sql);
$submit_str='修改';
$hidd_value="harbor_edit.action.php?id=$id";
}
$input_parent_value=api_proxy("user_harbor_get_list","*",'null');
$input_parent_value[] = array('id'=>'','portname'=>'');
//var_dump($input_parent_value);
//$input_type_value=array("0"=>"文本框","1"=>"下拉列表","2"=>"单选按钮","3"=>"多选按钮");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
    <script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
    <script type="text/javascript">
    	function  del_information(info_id){
			var del_inf=new Ajax();
			del_inf.getInfo("user_information_del.action.php","post","app","id="+info_id,function(c){del_information_callback(c);}); 
		} 
		function del_information_callback(c){
			if(c=='success'){
				alert('删除成功');
			}else{
				alert('删除失败');
			}
			window.location.reload();
		}
    </script>
</head>
<body>
    <div id="maincontent">
        <div class="wrap">
            <div class="crumbs">当前位置 &gt;&gt; <a href="javascript:void(0);">管理首页</a> &gt;&gt; <a href="harbor_list.php"><?php echo $submit_str;?>港口</a></div>         
            
             <div class="infobox">
                <h3>港口信息</h3>
                <div class="content">
 				  <form action="<?php echo $hidd_value; ?>" method="post" name='form' onsubmit='return check_form();'>
                  
                    <table class="form-table">
                        <tr>
                            <th width="90">*港口名称</th>
                            <td><input type="text" class="regular-text" name='portname' value="<?php echo $info_row['portname'];?>" /></td>
                        </tr>
                         <tr>
                            <th width="90">*上级</th>
                            <td><select name="info_type">
                                 <?php 
								 	foreach($input_parent_value as $ent){
									  $select='';
									  if($ent['id']==$info_row['parent']){
									     $select="selected";
									  }
									echo "<option value='".$ent['id']."' $select >".$ent['portname']."</option>";
									}
								 ?>
                             	</select>   大地区为空                        
                            </td>
                        </tr>
                         <tr>
                            <th width="90">*英文名</th>
                            <td><input type="text" class="regular-text" name='portname_en' value="<?php echo $info_row['portname_en'];?>" /></td>
                        </tr>
                                                 <tr>
                            <th width="90">*城市</th>
                            <td><input type="text" class="small-text" name='portname_city' value="<?php echo $info_row['portname_city'];?>" /></td>
                        </tr>
                                                 <tr>
                            <th width="90">*国家</th>
                            <td><input type="text" class="small-text" name='portname_country' value="<?php echo $info_row['portname_country'];?>" /></td>
                        </tr>
                         <tr>
                            <th width="90">*排序</th>
                            <td><input type="text" class="small-text" name='sort' value="<?php echo $info_row['sort'];?>" />默认为0</td>
                        </tr>
                        <tr>
                            <th width="90">*是否港口</th>
                            <td>
					<input type="radio" name="is_port" value="2"  <?php echo $info_row['is_port']!='1'?'checked':'';?> >是 &nbsp; 
					<input type="radio" name="is_port" value="1"  <?php echo $info_row['is_port']=='1'?'checked':'';?> >否</td>
                        </tr>
                         <tr>
                            <th width="90"></th>
                            <td><input type="submit" class="regular-button" value="<?php echo $submit_str;?>"/></td>
                        </tr>
                    </table>
                  </form>
                </div>
            </div>  
        </div>
    </div>
</body>
</html>
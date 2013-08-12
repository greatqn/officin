<?php
require("session_check.php");	
require("../foundation/fpages_bar.php");
require("../api/base_support.php");

//表定义区
$t_harbor=$tablePreStr."harbor";
//数据库操作初始化
$dbo = new dbex;
dbtarget('w',$dbServs);
//当前页面参数
$page_num=trim(get_argg('page'));
//变量区
$portname=short_check(get_argg('portname'));
$parent=short_check(get_argg('parent'));

$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;
$dbo->setPages(10,$page_num);//设置分页
//搜索条件设置
$condition="where 1=1";

if(get_argg('search')){
  $condition=$condition."  and portname like '%$portname%'";
  if($parent){
  	$condition=$condition."  and parent = $parent";
  }
}
//取出数据列表
$sql="select * from $t_harbor ".$condition ."  order by sort asc";
//取得数据
$info_rs=$dbo->getRs($sql);
$page_total=$dbo->totalPage; //分页总数
//显示控制
$isNull=0;
$isset_data="";
$none_data="content_none";
if(empty($info_rs)){
	$isset_data="content_none";
	$none_data="";
	$isNull=1;
}
$input_type_value=array();
$input_type_value=array("0"=>"文本框","1"=>"下拉列表","2"=>"单选按钮","3"=>"多选按钮");
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
			del_inf.getInfo("harbor_del.action.php","post","app","id="+info_id,function(c){del_information_callback(c);}); 
		} 
		function del_information_callback(c){
			if(c!='success'){
				alert('删除失败');
			}
			window.location.reload();
		}
		function inserts(){
			window.location.href="harbor_edit.php";
		}
    
    </script>
</head>
<body>
    <div id="maincontent">
        <div class="wrap">
            <div class="crumbs">当前位置 &gt;&gt; <a href="javascript:void(0);">管理首页</a> &gt;&gt; <a href="harbor_list.php">港口管理</a></div>
            <hr />
            <div class="infobox">
                <h3>筛选条件</h3>
                <div class="content">
                    <form action="" method="get" name='form' onsubmit='return check_form();'>
                    <input type="hidden" name="mod" id="mod" value="user_custom" />
                    <input type="hidden" name="search" id="search" value="1" />
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th width="90">港口名称</th>
                            <td><input type="text" class="small-text" name='portname' value="<?php echo get_argg('portname');?>"></td>
                        	<th width="90">上级</th>
                            <td><input type="text" class="small-text" name='parent' value="<?php echo get_argg('parent');?>"></td>
                        </tr>
                        <tr>
                        	<td ><input class="regular-button" type="submit" value="搜索" /></td>
                        </tr>
                        </tbody>
                    </table>
                    </form>
                </div>
            </div>           
            <div class="infobox">
                <h3>港口列表</h3>
                <div class="content">
                    <table class="list_table <?php echo $isset_data;?>">
                        <thead>
                        <tr>
                            <th>港口名称</th>
                            <th style="text-align:center">英文名</th>
                            <th style="text-align:center">城市</th>
                            <th style="text-align:center">国家</th>
                            <th style="text-align:center">上级</th>
                            <th style="text-align:center">排序</th>
                            <th style="text-align:center">操作</th>
                        </tr>
                        </thead>
                        <?php foreach($info_rs as $rs){ ?>
                        <tr>
                            <td><?php echo $rs['portname'];?></td>
                            <td style="text-align:center"><?php echo $rs['portname_en'];?></td>
                            <td style="text-align:center"><?php echo $rs['portname_city'];?></td>
                            <td style="text-align:center"><?php echo $rs['portname_country'];?></td>
                            <td style="text-align:center"><?php echo $rs['parent'];?></td>
                            <td style="text-align:center"><?php echo $rs['sort'];?></td>
                            <td align="center">
                                <a href="harbor_edit.php?id=<?php echo $rs['id'];?>" >修改</a> |
                                <a href="javascript:del_information(<?php echo $rs['id'];?>);" onclick='return confirm("确认删除");'>删除</a>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        <td colspan="3">
    <input type="button" onclick="inserts()" value="添加" name="adds" id="adds" class="regular-button">
    </td>
                    </table>
                    <?php page_show($isNull,$page_num,$page_total);?>
                    <div class='guide_info <?php echo $none_data;?>'>没有查询到与条件相匹配的数据</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
  //引入模块公共方法文件
  require("foundation/fcontent_format.php");
  require("api/base_support.php");
  require("foundation/aanti_refresh.php");
  require("foundation/aintegral.php");
  require("foundation/fplugin_form.php");
  require("foundation/ftag.php");

	//数据表定义区
	$t_day_remark=$tablePreStr."day_remark";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

$file = "C:\Users\Administrator.PC-20110910NOQE\Desktop\kk.txt";
$i = 0;		
$file_handle = fopen("$file", "r");

while (!feof($file_handle)) {
   $line = fgets($file_handle);
   echo $i.strlen($line);
   echo $line;
   echo "<br>";
   $i += 1;
   //if($i>10) break;
if(strlen($line)<=2) continue;

$remark = $line;
$sort_time = date('Y-m-d',strtotime("+$i day"));
$comments = 0;
$is_pass = 0;
$tag = "";
   $sql = "insert into $t_day_remark(remark,sort_time,comments,is_pass,tag)values('$remark','$sort_time','$comments','$is_pass','$tag')";
$dbo->exeUpdate($sql);

}
fclose($file_handle);
echo "end";
	//action_return(2,'222','');
?>
<?php

$file = "D:\\xampp\\htdocs\\intouch.net.cn\\action\\message\\yan.txt";	
$file_handle = fopen($file, "r");
while (!feof($file_handle)) {
   $line = fgets($file_handle);
   echo $line;
}
fclose($file_handle);


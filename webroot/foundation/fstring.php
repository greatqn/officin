<?php
//裁剪字符串. 去头,去尾.
function str_cut($s,$a,$d="")
{
	$f=strpos($s,$a)+strlen($a);
	$l=strpos($s,$d);
	if($l)
		$out= substr($s,$f,$l-$f);

	return $out;
}
?>
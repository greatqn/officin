<?php
$LAST_DELAY_TIME=1333034466;
function delay($delay_time){
	global $LAST_DELAY_TIME;
	$now_time=time();
	if(intval($now_time-$LAST_DELAY_TIME) < intval($delay_time*60)){
		return 0;
	}else{
		return 1;
	}
}
?>
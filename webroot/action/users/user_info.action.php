<?php
	require("foundation/module_users.php");

	//引入语言包
	$u_langpackage=new userslp;

	//变量获得
	$user_id =get_sess_userid();
	$model = short_check(get_argg('model'));
	$user_name = short_check(get_argp('user_name'));
	$user_sex=intval(get_argp("user_sex"));
	$birth_year = short_check(get_argp('birth_year'));
	$birth_month = short_check(get_argp('birth_month'));
	$birth_day = short_check(get_argp('birth_day'));
	$reside_city = short_check(get_argp('reside_city'));
	$reside_province = short_check(get_argp('reside_province'));
	$birth_city = short_check(get_argp('birth_city'));
	$birth_province = short_check(get_argp('birth_province'));
	$is_finish=intval(get_argg('is_finish'));
	$info = get_argp('info');
	
	//表声明区
	$t_users = $tablePreStr."users";
	$t_online=$tablePreStr."online";
	$t_user_info=$tablePreStr."user_info";
    $t_uploadfile=$tablePreStr.'uploadfile';
    $t_mypals=$tablePreStr."pals_mine";
    $t_pals_req=$tablePreStr."pals_request";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);
	
	//执行删除旧的自定义信息
	userInforDel($dbo,$user_id);
	
	//更新自定义信息表
	if(!empty($info)){
		foreach($info as $key => $value){
			if($value!==''){
				$key=explode('|',$key);
				$sql="insert into $t_user_info (user_id,info_id,info_value) values ($user_id,'".$key[0]."','$value')";
				$dbo -> exeUpdate($sql);
			}
		}
	}
	
//更新头像
$s_ico=($user_sex==0)?"skin/$skinUrl/images/d_ico_0_small.gif":"skin/$skinUrl/images/d_ico_1_small.gif";

if(get_sess_userico()=="skin/$skinUrl/images/d_ico_0_small.gif" or get_sess_userico()=="skin/$skinUrl/images/d_ico_1_small.gif"){
	$field_ico='user_ico';$field_id='user_id';
	$pals_ico="pals_ico";$p_field_id="pals_id";
	$req_ico="req_ico";$q_field_id="req_id";
					
	update_user_ico($dbo,$t_users,$field_ico,$field_id,$user_id,$s_ico);
	update_user_ico($dbo,$t_mypals,$pals_ico,$p_field_id,$user_id,$s_ico);
	update_user_ico($dbo,$t_pals_req,$req_ico,$q_field_id,$user_id,$s_ico);
	set_sess_userico($s_ico);
}


//更新users表
	$sql = "update $t_users set user_name = '$user_name',user_sex = $user_sex,birth_province='$birth_province',birth_city='$birth_city',reside_province='$reside_province',reside_city='$reside_city',birth_year='$birth_year',birth_month='$birth_month',birth_day='$birth_day'
			where user_id = $user_id;";
	$dbo -> exeUpdate($sql);

//更新online表
	$sql = "update $t_online set birth_province='$birth_province',birth_city='$birth_city',reside_province='$reside_province',reside_city='$reside_city',birth_year='$birth_year' where user_id = $user_id;";
	$dbo -> exeUpdate($sql);

	//回应信息
	action_return(1,"","modules.php?app=user_info&single=$is_finish&user_id=$user_id");
?>

<?php

	require('plugin/mypdo_class.php');
	require('plugin/db_setting.php');
	require('plugin/Mobile_Detect.php');
	require('plugin/function.php');
error_reporting(0);
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";

	$db_table = 'act170523';
	//$code = trim($_POST['code']);
	$mobile = trim($_POST['mobile']);

	
	//var_dump($mobile);
	//$code = trim($_POST['code']);	
	$keep_time = time()-intval($_POST['from_time']);
	//echo "time is ".time(). 'posttime:'.$_POST['from_time'];
	$from = trim($_POST['from']);				//来自
	
	if(!$mobile){				//必须留一个手机号码
		$output['status'] = '0';
		$output['tips'] = '请留下你的手机号码';	
		echo $output = json_encode($output);
		exit;
	}

	  
	$detect = new Mobile_Detect;
	if($detect->isAndroidOS()){
		$device = 'android';		//设备
	}elseif($detect->isiOS()){
		$device = 'ios';
	}elseif($detect->isWindowsMobileOS()){
		$device = 'WindowsMobile';
	}elseif($detect->isWindowsPhoneOS()){
		$device = 'WindowsPhone';
	}else{
		$device = 'other';
	}
	
	$db = new MyPDO($mysql_server_host, $mysql_username, $mysql_password, $mysql_database,'mysql','utf8',$mysql_port);

	//判断是否已经存在
	if($mobile){
		$rs = $db->getOne("select `id` from {$db_table} where mobile='{$mobile}'");
		if($rs){
			$output['status'] = '0';
			$output['tips'] = '您的资料之前已提交过了';	
			echo $output = json_encode($output);
			exit;
		}
	
	}

	$insert1 = array(
		'code_number'=>$code,
		'mobile'=>$mobile,
		'keep_time'=>intval($keep_time),
		'device'=>$device,
		'url_from'=>$from,
		'ip'=>get_ip(),
		'create_time'=>date("Y-m-d H:i:s")
	);
	//var_dump($insert1);

	$rs1 = $db->insert($db_table,$insert1);
	if($rs1){
		$output['status'] = '1';
		$output['tips'] = '提交成功';	
		echo $output = json_encode($output);
		exit;
	}else{
		
		$output['status'] = '0';
		//$output['tips'] = '提交失败,可能是网络原因,请稍后再试';	
		$output['tips'] = '提交失败,可能是网络原因,请稍后再试';	
		echo $output = json_encode($output);
		exit;
	}
	exit;

?>
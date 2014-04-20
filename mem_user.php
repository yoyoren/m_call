<?php
$uid=isset($_GET['agentuid'])?trim($_GET['agentuid']):'';
require(dirname(__FILE__) . '/includes/init.php');
require('includes/lib_user.php');
if (isset($_GET['id'])) 
{	
	$user_id = $_GET['id'];
	$smarty->assign("user_id", $user_id);
}
if (isset($_REQUEST['type']))
{
	switch ($_REQUEST['type'])
	{
		case 1:
			$type='建议';
			break;
		case 4:
			$type='催单';
			break;
		case 3:
			$type='投诉';
			break;
	}
}
$_GET['act'] = empty($_GET['act']) ? 'list' : trim($_GET['act']);		

/*---------------------------------------*/
//--	Action "list" Start 列表显示
/*---------------------------------------*/
if($_GET['act']=='list')
{
	include('includes/adodb5/adodb-pager.bisc.php');
	$smarty->assign('full_page',   1);
	$mem = get_mem_list();
	$smarty->assign('mem',    $mem['list']);
    $smarty->assign('filter',       $mem['filter']);
    $smarty->assign('record_count', $mem['record_count']);
    $smarty->assign('page_count',   $mem['page_count']);
	$smarty->display("mem_query.html");
}
	
	
/*---------------------------------------*/
//--	Action "query" Start
/*---------------------------------------*/
else if ($_GET['act'] == 'query')
{
	include('includes/adodb5/adodb-pager.bisc.php');
	include('includes/lib_main.php');
	$mem = get_mem_list();
	$smarty->assign('mem',    $mem['list']);
    $smarty->assign('filter',       $mem['filter']);
    $smarty->assign('record_count', $mem['record_count']);
    $smarty->assign('page_count',   $mem['page_count']);
	
    make_json_result($smarty->fetch('mem_query.html'), '',array('filter' => $mem['filter'], 'page_count' => $mem['page_count'],'sql'=>$mem['sql']));
}


/*---------------------------------------*/
//--	Action "addands" Start 新建用户
/*---------------------------------------*/
else if ($_REQUEST['act'] == 'addands')
{
	$rea_name     = isset($_REQUEST['rea_name'])     ? trim($_REQUEST['rea_name']) : '';
	$mobile_phone = isset($_REQUEST['mobile_phone']) ? trim($_REQUEST['mobile_phone']) : '';
	$home_phone   = isset($_REQUEST['home_phone'])   ? trim($_REQUEST['home_phone']) : '';
	$office_phone = isset($_REQUEST['office_phone']) ? trim($_REQUEST['office_phone']) : '';
	$agentuid=isset($_REQUEST['agentuid']) ? trim($_REQUEST['agentuid']) : '';
	$city = isset($_REQUEST['city']) ? $_REQUEST['city'] : 'sh';
	$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
	
	$kfgh = empty($_COOKIE['Callagentsn']) ? 0 : intval($_COOKIE['Callagentsn']);	

	//$db->query("insert into user_genid (kfgh) values($kfgh)");
	//$user_id = $db->insert_id();		
	
	$user_name = "C";
	$tel1=!empty($home_phone)?$home_phone:$office_phone;
	$user_name .=!empty($mobile_phone)?$mobile_phone:$tel1;
	$salt = substr(uniqid(rand()), -6);
	$password = md5(md5($salt).$salt);
	$arr = array
	(
		//"user_id"       => $user_id,
		"rea_name"		=> $rea_name,
		"user_name"		=> $user_name,
		"mobile_phone"	=> $mobile_phone,
		"home_phone"	=> $home_phone,
		"office_phone"	=> $office_phone,
		"back_info"		=> addslashes($_POST['content']),
		"province"		=> ($city =='bj') ? 441 :443,
		"sex"  			=> isset($_REQUEST['sex']) ? $_REQUEST['sex'] : 0,
		"reg_time"		=> time(),
		"ec_salt"       =>$salt,
		"password"		=>$password
	);
	if($_REQUEST['sex']==3){
		$arr2 = array
		(
			//"id"       		=> $user_id,
			"name"			=> $rea_name,
			"mobilephone"	=> $mobile_phone,
			"otherphone"	=> $office_phone,
			"sex"  			=> 3,
			//"province"		=> $city =='bj' ? 441 : ($city =='sh' ? 442 : ($city =='hz' ? 440 : 443)),
		);
		$res = $db->insert("agreement", $arr2);
	}
	//12.4~12.31号的活动
	if(date("Y-m-d H:i:s",time())>='2013-12-04 00:00:00'&&date("Y-m-d H:i:s",time())<='2013-12-31 23:59:59')
	{
		$arr['user_money']=50.00;
		$arr['charge_num']=1;
	}
	$res = $db->insert("ecs_users", $arr);
	
	$user_id = $db->insert_id();	
    //$sql = "INSERT INTO center.uc_members SET username='$user_name', password='$password', email='', regip='', regdate='".time()."', salt='$salt'";
	//$ress = $db->query($sql);
    //echo $sql;exit;
	if($res)
	{//12.4~12.31号的活动
		if(date("Y-m-d H:i:s",time())>='2013-12-04 00:00:00'&&date("Y-m-d H:i:s",time())<='2013-12-31 23:59:59')
			{
				 $account_log = array(
										'user_id'       => $user_id,
										'user_money'    => 50.00,
										'change_time'   => time(),
										'change_desc'   => "2013圣诞礼遇赠送50元",
										'change_type'   => 2
									);
				$GLOBALS['db']->autoExecute("mcard_log", $account_log, 'INSERT');
			}
	   echo "<script>window.location = 'mem_user.php?act=showuser&id={$user_id}&agentuid={$agentuid}';</script>";
    }
	else
	{
	   //echo $res;
	   //echo $ress;
	   echo "新建用户出错！";
	}
}


/*---------------------------------------*/
//--	Action "service" Start
/*---------------------------------------*/
else if ($_GET['act'] == 'service')
{
	$smarty->assign("type", $_GET['type']);
	$smarty->assign("typeName", $type);
	$sql = "select order_id,order_sn from ecs_order_info where user_id = '$user_id' order by add_time desc";
	$orders = $db->getAll2($sql);
	$smarty->assign("orders", $orders);	
	$smarty->display("service_add.html");
} 

else if ($_GET['act'] == 'addservice')
{
	$arr = array
	(
		'user_id'		=> $_POST['user_id'],
		'order_id'		=> intval($_POST['order_id']),
		'serv_type'		=> $_POST['serv_type'],
		'serv_desc'		=> $_POST['title'],
		'station'	    => $_POST['station'],
		'sender'	    => $_POST['sender'],
		'serv_content'	=> '',
		'admin'	        => empty($_COOKIE['Callagentsn']) ? 0 : $_COOKIE['Callagentsn'],
		'add_time'		=> date('Y-m-d H:i:s')
	);
	$db->insert("call_service", $arr);
	echo "<script>window.location = 'mem_user.php?act=showuser&id={$_POST['user_id']}';</script>";
}


/*---------------------------------------*/
//--	Action "showuser" Start
/*---------------------------------------*/
elseif ($_GET['act'] == 'showuser')
{
	$smarty->assign('ur_here', '客户详情');
	$user_info = user_info($user_id);
	//print_r($user_info);
	// 查询最近订单
	$sql="select o.*,group_concat(g.goods_name,':',g.goods_attr) as goods from ecs_order_info as o,ecs_order_goods as g where user_id = " . $user_info['user_id'] . 
	     " and o.order_id = g.order_id and g.goods_price>100 group by o.order_id ".
		 " order by best_time desc limit 10";
	$order = $db->getAll2($sql);
	if($order)
	{
		foreach($order as $key => $value)
		{
			$order[$key]['status'] = $GLOBALS['os'][$value['order_status']];
		}
	}
	$smarty->assign("order", $order);
    $sql = "select count(*) from ecs_order_info where  user_id = " . $user_info['user_id'];
    $count = $db->getOne($sql);	
	$smarty->assign("count", $count);
		
	$smarty->assign("user", $user_info);
	$tousu=get_tousu($user_id);
	$smarty->assign("tousu", $tousu);
	$smarty->display("mem_detail.html");
}


/*---------------------------------------*/
//--	Action "adduser" Start
/*---------------------------------------*/
else if ($_GET['act'] == 'adduser')
{
	$smarty->assign('ur_here', '客户添加');
	$smarty->display("mem_add.html");
}


/*---------------------------------------*/
//--	Action "edit" Start
/*---------------------------------------*/
else if ($_GET['act'] == 'edit')
{
	$user_id = $_GET['id'];
	$smarty->assign('ur_here', '客户修改');
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$user=array
		(
			'rea_name'		=> $_POST['rea_name'],
			'birthday'		=> $_POST['birthdayYear'].'-'.$_POST['birthdayMonth'].'-'.$_POST['birthdayDay'],
			'sex'			=> $_POST['sex'],
			'mobile_phone'	=> $_POST['mobile_phone'],
			'office_phone'	=> $_POST['office_phone'],
			'home_phone'	=> $_POST['home_phone'],
			'back_info'		=> $_POST['back_info'],
			'question'		=> $_POST['question'],
		);
		if($_POST['sex']==3){
			$user2=array
			(
				'id'      	 	=> $user_id,
				'name'			=> $_POST['rea_name'],
				'sex'			=> $_POST['sex'],
				'mobilephone'	=> $_POST['mobile_phone'],
				'otherphone'	=> $_POST['office_phone']
			);
			foreach ($user2 as $key=>$value)
			{
				$sets2[]= $key."='".$value."'";
			}
			$sql1="select * from agreement where id=".$user_id;
			$result = $db->getRow($sql1);
			if(empty($result)){
				$res = $db->insert("agreement", $user2);
			}else{
				$sql = "update agreement set " . implode(",", $sets2) . " where id = " . $user_id;
				$r = mysql_query($sql);
			}
		}
		// var_dump($user);
		$sets = array();
		foreach ($user as $key=>$value)
		{
			$sets[]= $key."='".$value."'";
		}
		$sql = "update ecs_users set " . implode(",", $sets) . " where user_id = " . $user_id;
		//echo $sql;exit;
		$r = mysql_query($sql);
		if ($r)  
		{
			echo "<script>window.location = 'mem_user.php?act=showuser&id={$user_id}';</script>";
//			$smarty->display("mem_detail.html");
		}
//		$smarty->assign("msg",'修改成功');
	}
	$user_info = user_info($user_id);
	$smarty->assign("user", $user_info);
	
	$year = array();
	for ($i = 1942; $i <= intval(date("Y", time())); $i++) { $year[$i] = $i; }
	$smarty->assign("year", $year);
	for ($i = 1; $i < 10; $i++)
	{
		$a = '0' . $i;
		$month[$a] = $a;
		$day[$a] = $a;
	}
	for ($i = 10; $i < 13; $i++)
	{
		$month[$i] = $i;
	}
	$smarty->assign("month", $month);
	for ($i = 10; $i < 32; $i++)
	{
		$day[$i] = $i;
	}
	$smarty->assign("day", $day);
	$sex=array('0' => '未知', '1' => '男', '2' => '女');
	$smarty->assign("sex", $sex);
	$birthday=explode('-', $user_info['birthday']);
	$smarty->assign("birthday", $birthday);
	$smarty->display("mem_edit.html");
}
/*---------------------------------------*/
//--	Action "edit" Start
/*---------------------------------------*/
else if ($_GET['act'] == 'account')
{
	$user_id = $_GET['id'];
	//echo $_COOKIE['Callagentsn'];
	$smarty->assign('ur_here', '客户账户修改');
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$points = floatval($_POST['pay_points']) - floatval($_POST['fore_points']);
		$money  = floatval($_POST['user_money']) - floatval($_POST['fore_money']);

		$question	= addslashes($_POST['question']);

        log_account_change($user_id, $money, 0, $points, $question, 3);
		echo "<script>window.location = 'mem_user.php?act=showuser&id={$user_id}';</script>";

	}
	$user_info = user_info($user_id);
	$smarty->assign("user", $user_info);
	
	$account_log = user_acount_log($user_id);
	$smarty->assign("log", $account_log);
	
	$smarty->display("mem_account.html");
}

/*---------------------------------------*/
//--	Action "merge" Start 合并用户
/*---------------------------------------*/
else if ($_GET['act'] == 'merge')
{
	$user_id = $_POST['main'];			// 主帐号的 user_id
	$s = $_POST['secondary'];			// $s 为数组，存储所有辅助帐号的 user_id
	$intergal = 0;			// 积分
	$user_money = 0;			// 帐户余额
	$freeze = 0;
	$sql = "select * from ecs_users where user_id = " . $user_id;
	foreach ($s as $val) { $sql .= " or user_id = $val"; }			// 循环查询辅助帐号的所有信息
	$arr = $db->getAll2($sql);
	foreach ($arr as $val)			// 循环取出每个帐号的所需信息，存入数组中
	{	
		if ($val['office_phone'] && is_norepeat($val['office_phone'], $office))			$office[]=$val['office_phone'];
		if ($val['mobile_phone'] && is_norepeat($val['mobile_phone'], $mobile))	$mobile[]=$val['mobile_phone'];
		if ($val['home_phone'] && is_norepeat($val['home_phone'], $home))			$home[]=$val['home_phone'];
		if ($val['back_info'] && is_norepeat($val['back_info'], $back_info))				$back_info[]=$val['back_info'];
		if ($val['question'] && is_norepeat($val['question'],$question))		$question[]=$val['question'];
		$intergal += $val['pay_points'];			// K 金（积分）
		$user_money += $val['user_money'];			// 帐户余额
		$freeze += $val['frozen_money'];			// 冻结金额
	}
	$back_info[] = '合并原因:' . $_POST['reason'];
	$user = array();
	
	// 将存入数组中的元素按规则进行拼接起来
	if ($office)			$user['office_phone'] = implode(",", $office);
	if ($mobile)		$user['mobile_phone'] = implode(",", $mobile);
	if ($home)			$user['home_phone'] = implode(",", $home);
	if ($back_info)	$user['back_info'] = implode(",", $back_info);
	if ($question)			$user['question'] = implode(",", $question);
	$user['pay_points'] = $intergal;
	$user['user_money'] = $user_money;
	$user['frozen_money'] = $freeze;
//	print_r($user);
	
	/* implode: Join array elements with a string */
	$sets = array();
	foreach ($user as $key => $value) { $sets[] = $key . " = '" . $value . "'"; }
	$sql = "update ecs_users set " . implode(",", $sets) . " where user_id = " . $user_id;
//	echo $sql;
	mysql_query($sql);
	
	// 同时更新 ecs_order_info，mem_bonus，mem_address 表中的 user_id，删除 ecs_users 表中的辅助帐号相关信息
	foreach ($s as $val)			// $val 为辅助帐号的 user_id
	{
		$sql = "update ecs_order_info set user_id = $user_id where user_id = $val";
		mysql_query($sql);
		$sql = "update ecs_user_bonus set user_id = $user_id where user_id = $val";
		mysql_query($sql);
		$sql = "update ecs_user_address set user_id = $user_id where user_id = $val";
		mysql_query($sql);
		$sql = "delete from ecs_users where user_id = $val";
		mysql_query($sql);
	}
	echo "<script>window.location = 'mem_user.php?act=showuser&id={$user_id}';</script>";
}

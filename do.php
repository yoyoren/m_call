<?php
$uid=isset($_GET['agentuid'])?trim($_GET['agentuid']):'';
require(dirname(__FILE__) . '/includes/init.php');
require('includes/lib_order.php');
require('includes/lib_user.php');
$smarty->assign("agentuid", $uid);
if(!isset($_GET['tel']) || empty($_GET['tel']))
{
   $smarty->display('index.html');
}
else
{
	$tel = trim($_GET['tel']);
	
	if (strlen($tel) == 11&&substr($tel,0,1)!="0")			
	{
		$sql = "select user_id,user_name,rea_name,mobile_phone,home_phone,office_phone,back_info,question from ecs_users where mobile_phone = '" . $tel . "'";
		$smarty->assign("mobile", $tel);
	}
	elseif(strlen($tel) < 7)
	{
	    echo "错误的电话号码！请查证！";exit;
	}
	else			
	{
		$sql = "select user_id,user_name,rea_name,mobile_phone,home_phone,office_phone,back_info,question".
		       " from ecs_users where office_phone = '" . $tel . "' or home_phone = '" . $tel . "'";
		$smarty->assign("tel1", $tel);
	}
	$arr = $db->getAll2($sql);
	if (count($arr) > 0)			// 有用户资料
	{
		if (count($arr) == 1)			// 单用户资料
		{
			$user_id = $arr['0']['user_id'];
            echo "<script>window.location = 'mem_user.php?act=showuser&id={$user_id}&agentuid={$uid}';</script>";
		}
		else			// 多用户
		{
			if ($_GET['tel'])			// 如果请求的是此号码，就跳转到用户合并页面
			{
				$smarty->assign("ur_here", '合并客户');
				$smarty->assign("users", $arr);
				$smarty->display("mem_merge.html");			// 2929999
			}
		}	
	}
	else			// 无用户资料，跳转到添加用户页面，让其完成新用户注册
	{
		$smarty->assign("ur_here", '新建用户');
		$smarty->display("mem_add.html");			// 3275253
	}
}

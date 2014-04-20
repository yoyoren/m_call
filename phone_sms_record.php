<?php
/**
 * 短信发送内容
 * ============================================================================
 * $Author: bisc
 * $Id: phpone_sms_record.php
*/

require(dirname(__FILE__) . '/includes/init_local.php');

/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}

if ($_REQUEST['act'] == 'list')
{
    $smarty->assign('ur_here',     '短信发送');
    $smarty->assign('action_link', array('href'=>'phone_sms_record_query.php', 'text' => '查询记录'));
    $smarty->assign('full_page',   1);

	$sql = "select * from phone_sms "; 
	
    $rs = $GLOBALS['db']->getAll($sql);
		
	$smarty->assign('form_act',      'record');	
			
	$smarty->assign('phone_sms_list', $rs);  
	$smarty->assign('kfgh', $_GET['kfgh']);
		
	$smarty->display('phone_sms_record_list.htm');
}

/*
* 添加界面
*/
elseif ($_REQUEST['act'] == 'add')
{
    /* 检查权限 */
    //admin_priv('31');

    $smarty->assign('ur_here',          '短信添加');
    $smarty->assign('action_link',      array('text' => '短信发送内容', 'href'=>'phone_sms.php?act=list'));
    $smarty->assign('form_action',      'insert');
	
	$smarty->assign('form_act',      'insert');	
	$smarty->display('phone_sms_info.htm');
}
/*
* 将添加的信息插入到数据库操作
*/
elseif ($_REQUEST['act'] == 'record')
{
	$phone_sms_id = $_REQUEST['sms_name'];
	$sms_content = $db->getRow("select * from phone_sms where id =".$phone_sms_id);
	$num = $_REQUEST['num'];
	
	if($num == '1')
	{
		$number = trim($_REQUEST['phone_num']);
		$content =str_replace(" ","",trim($sms_content['content']));
		//echo $sms_content['content'];
		//发送短信
		$status = sms_send($number,$content);
		if(strpos($status, '03'))
		{
			$list = array();
			$list['kfgh'] = $_REQUEST['kfgh'];
			$list['phone'] = $number;
			$list['content'] = trim($sms_content['content']);
			$list['status'] = '1';
			$list['account_id'] = $_SESSION['admin_id'];
			$list['operate_time'] = date("Y-m-d H:i:s");
			$db->autoExecute('phone_sms_record', $list, 'INSERT');

			echo '发送成功！<br />';
			echo "<a href='javascript:history.back(-1)'>返回上一页</a>";
		}
		else
		{
			echo '发送失败！<br />';
			echo "<a href='javascript:history.back(-1)'>返回上一页</a>";
		}
	}
	elseif($num == '2')
	{
		$number = trim($_REQUEST['phone_nums']);
		$number = str_replace("，",",",$number);
		$content =str_replace(" ","",trim($sms_content['content']));
		/*发送短信*/
		$status = sms_send($number,$content);

		if(strpos($status, '01'))
		{
			$list = array();
			$list['kfgh'] = $_REQUEST['kfgh'];
			$list['content'] = $sms_content['content'];
			$list['status'] = '1';
			$list['account_id'] = $_SESSION['admin_id'];
			$list['operate_time'] = date("Y-m-d H:i:s");
			$phone = explode( ',',$number);
			foreach($phone as $val)
			{
				$list['phone'] = $val;
				$db->autoExecute('phone_sms_record', $list, 'INSERT');
			}
			echo '发送成功！<br />';
			echo "<a href='javascript:history.back(-1)'>返回上一页</a>";
		}
		else
		{
			echo '发送失败！<br />';
			echo "<a href='javascript:history.back(-1)'>返回上一页</a>";
		}
	}
	else
	{
		make_json_error('出错请检查');
	}
}

function sms_send($mobile_phone,$content)
{
	$content = mb_convert_encoding($content,'gb2312', 'utf-8');
	//echo $content;exit;
	$res = file_get_contents('http://221.179.180.158:9000/QxtSms/QxtFirewall?OperID=21cake&OperPass=123456&SendTime=&ValidTime=&DesMobile='.$mobile_phone.'&Content='.$content.'&ContentType=15');
		return $res;
}

?>

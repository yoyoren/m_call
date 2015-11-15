<?php
/**
 * 短信发送
 * ============================================================================
 * $Author: bisc
 * $Id: sendsms.php 0001
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
    $smarty->assign('ur_here',     '短信发送内容');
    $smarty->assign('action_link', array('href'=>'phone_sms.php?act=add', 'text' => '短信添加'));
    $smarty->assign('full_page',   1);

    $list = phone_sms();
	
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);
    $smarty->assign('filter',       $list['filter']);
			
	$smarty->assign('phone_sms_list', $list['phone_sms']);  	
	$smarty->display('phone_sms_list.htm');
}
/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $list = phone_sms();

    $smarty->assign('phone_sms_list',   $list['phone_sms']);
    $smarty->assign('record_count',   $list['record_count']);
    $smarty->assign('page_count',     $list['page_count']);
    $smarty->assign('filter',         $list['filter']);
    
    make_json_result($smarty->fetch('phone_sms_list.htm'), '', array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}
/*
* 添加界面
*/
elseif ($_REQUEST['act'] == 'add')
{
    $smarty->assign('ur_here',          '短信添加');
    $smarty->assign('action_link',      array('text' => '短信发送内容', 'href'=>'phone_sms.php?act=list'));
    $smarty->assign('form_action',      'insert');
	
	$smarty->assign('form_act',      'insert');	
	$smarty->display('phone_sms_info.htm');
}
/*
* 将添加的信息插入到数据库操作
*/
elseif ($_REQUEST['act'] == 'insert')
{
	$list = array();
	$list['name']                     = trim($_REQUEST['name']);
	$list['content']                     = trim($_REQUEST['content']);
	$list['account_id']               = $_SESSION['admin_id'];
	$list['operate_time']             = date('Y-m-d H:i:s');
	
	//print_r($list);exit;	
	$db->autoExecute('phone_sms', $list, 'INSERT');
	echo "<script>window.location = 'phone_sms.php?act=list';</script>";    

}
elseif ($_REQUEST['act'] == 'edit')
{
	$smarty->assign('ur_here',     '短信内容修改');
    $smarty->assign('action_link', array('text' => '短信发送内容', 'href'=>'phone_sms.php?act=list'));

    $id= !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

    /* 获取信息 */
	$phone_sms = $db->getRow("SELECT * FROM phone_sms WHERE id = $id ");

	//echo '<pre>';print_r($phone_sms);echo '</pre>';
    /* 模板赋值 */
    $smarty->assign('phone_sms',    $phone_sms);
    $smarty->assign('form_act',    'update');
    $smarty->assign('action',      'edit');

    $smarty->display('phone_sms_info.htm');
}
elseif ($_REQUEST['act'] == 'update')
{
    $_REQUEST['id'] = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	
	$list = array();

	if(!empty($_REQUEST['name']))
	{
	   $list['name'] = trim($_REQUEST['name']);
	}
	if(!empty($_REQUEST['content']))
	{
	   $list['content'] = trim($_REQUEST['content']);
	}

	$list['account_id']     = $_SESSION['admin_id'];
	$list['operate_time']   = date('Y-m-d H:i:s');
	//echo '<pre>';print_r($list);echo '</pre>';exit;
	$db->autoExecute('phone_sms', $list, 'UPDATE','id = '.$_REQUEST['id']);
	echo "<script>window.location = 'phone_sms.php?act=list';</script>"; 
	
}
/*------------------------------------------------------ */
//-- 删除地址
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    /* 检查权限 */
    //admin_priv('order_edit');

    $id = intval($_REQUEST['id']);

    $res = $GLOBALS['db']->query("DELETE FROM  phone_sms WHERE id = '$id'");

    if ($res)
    {
        $url = 'phone_sms.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
        //los_header("Location: $url\n");
		echo "<script>window.location = $url;</script>";
        exit;
    }
    else
    {
        make_json_error('删除出错!请检查！');
    }
}


/*
* 取得地址信息列表
*/
function phone_sms()
{
	$sql = "select * from phone_sms "; 

	$row = $GLOBALS['db']->getALL($sql);
    $arr = array('phone_sms' => $row, 'filter' => $filter, 'page_count' => 1, 'record_count' => count($row));

    return $arr;
	
  
}
?>

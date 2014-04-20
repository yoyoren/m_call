<?php
/**
 * kf log
 * $Author: bisc $
 * $Id: log.php 
*/

require(dirname(__FILE__) . '/includes/init.php');
include('includes/adodb5/adodb-pager.bisc.php');
include('includes/lib_main.php');
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
    $smarty->assign('ur_here',     '订单日志列表');
    $smarty->assign('full_page',   1);
	$_REQUEST['flag'] = 9;
	$list = log_list();
	//echo "<pre>";print_r($list);echo "</pre>";exit;
	$smarty->assign('services',   	$list['list']);  
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);
	$smarty->display('order_log.html');
}
elseif ($_REQUEST['act'] == 'query')
{
	$list = log_list();
	$smarty->assign('services',   	$list['list']);  
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);
 
    make_json_result($smarty->fetch('order_log.html'), '', array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}
elseif ($_REQUEST['act'] == 'info')
{
    $sid = intval($_REQUEST['id']);
    $sql = "select * from call_service where serv_id = '$sid'";
    $service = $db->getRow2($sql);

    if (empty($service))
    {
        die('售后记录不存在！');
    }
    $user = $db->getRow2("select user_name,rea_name,mobile_phone,home_phone,office_phone from ecs_users where user_id = ".$service['user_id']);
	$smarty->assign('user', $user);
	if($service['order_id'])
	{
		$order = $db->getOne("select order_sn from ecs_order_info where order_id = ".$service['order_id']);
		$smarty->assign('order', $order);	
	}

	$smarty->assign('service', $service);
	$smarty->display('service_info.html');
	
}
elseif ($_REQUEST['act'] == 'update')
{
	$serv_id = intval($_POST['serv_id']);
	$sql = "update call_service set serv_desc='".$_POST['title']."',serv_content='".$_POST['content'].
	       "',admin='".$_COOKIE['uid']."',add_time='" .date('Y-m-d H:i:s'). "',flag='".$_POST['flag']."' where serv_id='$serv_id'";
    $db->query($sql);
	echo "<script>window.location = 'service.php?act=list';</script>";
    exit;
}
function log_list()
{  	
    if (!empty($_GET['is_ajax']) && $_GET['is_ajax'] == 1)
    {
        $_REQUEST['ntype'] = json_str_iconv($_REQUEST['ntype']);				
    } 	
	$filter['order_sn']  = empty($_REQUEST['order_sn']) ? '' : trim($_REQUEST['order_sn']);
    $filter['ntype']     = empty($_REQUEST['ntype']) ? '' : trim($_REQUEST['ntype']);
	$filter['kfgh']      = empty($_REQUEST['kfgh'])  ? '' : trim($_REQUEST['kfgh']);
    $filter['sdate']     = empty($_REQUEST['sdate']) ? '' : trim($_REQUEST['sdate']);
	$filter['edate']     = empty($_REQUEST['edate'])  ? '' : trim($_REQUEST['edate']);	
		
	$filter['page']      = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
	$filter['page_size'] = empty($_REQUEST['pagesize'])? 15 :intval($_REQUEST['pagesize']);		
	$where = " WHERE 1 ";
		
	if($filter['kfgh'])
	{
		$where .= " and a.admin ='". $filter['kfgh'] ."' ";
	}
	if($filter['sdate'])
	{
		$where .= " and a.editime >='". strtotime($filter['sdate']) ."' ";
	}
	if($filter['edate'])
	{
		$where .= " and a.editime <='". (strtotime($filter['edate']) + 86000) ."' ";
	}
	if($filter['order_sn'])
	{
		$where .= " and b.order_sn = '".$filter['order_sn']."' ";
	}
	if($filter['ntype'])
	{
		$where .= " and a.alter_type like '". $filter['ntype'] ."' ";
	}
	
	$sql = "SELECT a.*,b.order_sn FROM order_log as a left join ecs_order_info as b on a.order_id=b.order_id ".$where.
	       "ORDER BY a.editime desc";

	$res = Pager($sql,$filter['page_size'],$filter['page']);
	//print_r($res);
	if(!empty($res['list']))
	{
		foreach($res['list'] as $key => $val)
		{
		   $res['list'][$key]['addtime'] = date('Y-m-d H:i',$val['editime']+8*3600); 
		}
	}
	$res['filter'] = $filter;
    return $res;
}

?>
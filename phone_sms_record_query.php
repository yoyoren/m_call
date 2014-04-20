<?php
/**
 * 发送短信记录查询
 * ============================================================================
 * $Id: phone_sms_record_query.php
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
    $smarty->assign('ur_here',     '查询记录');
    $smarty->assign('action_link', array('href'=>'phone_sms_record.php', 'text' => '短信发送'));
    $smarty->assign('full_page',   1);

    $list = address_list();
	//echo '<pre>';print_r($list);echo '</pre>';
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);
    $smarty->assign('filter',       $list['filter']);
			
	$smarty->assign('address_list', $list['list']);  	
	$smarty->display('phone_sms_record.htm');
}
/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $list = address_list();

    $smarty->assign('address_list',   $list['list']);
    $smarty->assign('record_count',   $list['record_count']);
    $smarty->assign('page_count',     $list['page_count']);
    $smarty->assign('filter',         $list['filter']);
    
    make_json_result($smarty->fetch('phone_sms_record.htm'), '', array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}

/*
* 取得地址信息列表
*/
function address_list()
{
   
	$filter['kfgh'] 	= empty($_REQUEST['kfgh']) 		? '' 	: trim($_REQUEST['kfgh']);
    $filter['phone']    = empty($_REQUEST['phone']) 	? '' 	: trim($_REQUEST['phone']);
       //echo $filter['kfgh'] ;
    $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);	
		
    $filter['sort_by']  = empty($_REQUEST['sort_by']) ? 'address_name' : trim($_REQUEST['sort_by']);
    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'ASC' : trim($_REQUEST['sort_order']);

    $where = " where id >0 ";
	if($filter['kfgh'])
	{
	   $where .= " and kfgh LIKE '%" . mysql_like_quote($filter['kfgh']) . "%'";
	}
	if($filter['phone'])
	{
	   $where .= " and phone LIKE '%" . $filter['phone'] . "%'";
	}
	
    $sql = "select count(*) from phone_sms_record ".$where; 
	
    $record_count   = $GLOBALS['db']->getOne($sql);
    $page_count     = $record_count > 0 ? ceil($record_count / 30) : 1;

    $sql = "select * from phone_sms_record ".$where." order by operate_time ".
		   " LIMIT " . ($filter['page'] - 1) * 30 . ",30"; 

	$row = $GLOBALS['db']->getALL($sql);
	
    $arr = array('list' => $row, 'filter' => $filter, 'page_count' => $page_count, 'record_count' => $record_count);

    return $arr;
	
  
}
?>

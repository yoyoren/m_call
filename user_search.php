<?php
require(dirname(__FILE__) . '/includes/init2.php');
require('includes/lib_user.php');
$_GET['act'] = empty($_GET['act']) ? 'list' : trim($_GET['act']);		

/*---------------------------------------*/
//--	Action "list" Start 列表显示
/*---------------------------------------*/
if ($_REQUEST['act'] == '')
{
    $smarty->display('user_search.html');
}
elseif ($_REQUEST['act'] == 'top')
{

    $smarty->display('user_top.html');
}
elseif($_GET['act']=='list')
{
		
	$list = user_list();   
   
    $smarty->assign('record_count', 	$list['record_count']);
    $smarty->assign('page_count',   	$list['page_count']);
    $smarty->assign('page',       	    $list['page']);	
    $smarty->assign('pagen',       	    $list['page'] + 1);
    $smarty->assign('pagef',       	    $list['page'] - 1);
	$smarty->assign('users',   		    $list['list']);
	
	$str = '';
	foreach($list['filter'] as $key => $val)
	{
	   $str .= '&'.$key.'='.$val;
	}  
    $smarty->assign('querystr', 	$str);

	$smarty->display("user_list.html");
}

function user_list()
{
	//print_r($_REQUEST);
	$filter['mobile_phone'] = empty($_REQUEST['mobile_phone']) ? '' : trim($_REQUEST['mobile_phone']);
	$filter['tel']          = empty($_REQUEST['tel'])    ? '' : trim($_REQUEST['tel']);
	$filter['rea_name']     = empty($_REQUEST['rea_name']) ? '' : trim($_REQUEST['rea_name']);
	$filter['user_name']    = empty($_REQUEST['user_name'])  ? '' : trim($_REQUEST['user_name']);
	
    $page     = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);	
	$filter['page_size']= $_REQUEST['page_size'] > 0 ? $_REQUEST['page_size'] : 15;	
	$where = " where 1 ";
	
	if($filter['mobile_phone'])
	{
		$where .= " and mobile_phone  = '".$filter['mobile_phone']."' ";
	}
	if($filter['rea_name'])
	{
		$where .= " and rea_name = '".$filter['rea_name']."' ";
	}
	if($filter['user_name'])
	{
		$where .= " and user_name = '".$filter['user_name']."' ";
	}
	if($filter['tel'])
	{
	   $where .= " and (home_phone = '".$filter['tel']."' or office_phone like '%".$filter['tel']."' ) ";
	}

    $sql = "SELECT COUNT(*) FROM ecs_users AS a ". $where;

    $record_count   = $GLOBALS['db2']->getOne($sql);
    $page_count     = $record_count > 0 ? ceil($record_count / $filter['page_size']) : 1;

    $sql = "select user_id,user_name,rea_name,mobile_phone,home_phone,office_phone,pay_points,back_info,question from ecs_users as a ".$where.
	       " ORDER BY user_id desc LIMIT " . ($page - 1) * $filter['page_size'] . ",$filter[page_size]";
	//echo $sql;exit;
    $rs = $GLOBALS['db2']->getAll($sql);
	
    $arr = array('list' => $rs, 'filter' => $filter, 'page_count' => $page_count, 'record_count' => $record_count,'page' => $page);

    return $arr;

}
?>
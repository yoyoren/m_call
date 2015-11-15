<?php
$uid=isset($_GET['agentuid'])?trim($_GET['agentuid']):'';
require(dirname(__FILE__) . '/includes/init.php');
require('includes/lib_order.php');
require('includes/lib_user.php');
$act=$_REQUEST['step'];
if($act=='sousuo')
{
	//搜索
	$agentuid=$_SESSION['agentuid'];
	$order_num=$_POST['order_num'];
	$result=$db->getAll("select * from order_log where order_id={$order_num}");
	foreach($result as $key=>$value)
	{
		$result[$key]['editime']=date('Y-m-d H:i:s', $value['editime']);
	}
	if($result==null)
	{$smarty->assign('kong','kong');}
	$smarty->assign("result",$result);
	$smarty->assign('act','sousuo');
}
elseif($act=='shijian')
{//按时间搜索
	$time1=$_REQUEST['order_time1'];	
	$time2=$_REQUEST['order_time2'];
	$time1=explode('-',$time1);
	$time2=explode('-',$time2);
	
	$time1=mktime(0, 0, 0, $time1[1], $time1[2], $time1[0]);
	$time2=mktime(23, 59, 59, $time2[1], $time2[2], $time2[0]);
	$res=$db->getAll("select * from order_log where editime>=$time1 and editime<=$time2 order by log_id desc");
	foreach($res as $key=>$value)
	{
		$res[$key]['editime']=date('Y-m-d H:i:s', $value['editime']);
	}
	if($res==null)
	{$smarty->assign('kong1','kong1');}
	$smarty->assign('res',$res);
	$smarty->assign('act','shijian');
}
else
{
	//不搜索
	$smarty->assign("agentuid", $uid);
	$currentPage = $_GET["currentPage"];
		$currentPage = $currentPage==NULL?1:$currentPage;
		$pageSize = 10;
		$totalRow = 0;
		$totalPage = 0;
		$first = ($currentPage-1)*$pageSize;//循环起始值
		$last = $first + $pageSize;//循环结束值
	$rows=$db->getAll("select * from order_log where admin_id not in(1000,1012) order by log_id desc limit $first,$pageSize");
		
		//求总记录数
		$totalRow = $db->getOne("select count(*) from order_log where admin_id not in(1000,1012) order by log_id desc");
		//求总页数
		$totalPage = ceil($totalRow / $pageSize);
		//对$last做判断，不能超过总记录数
		$last = $last>$totalRow?$totalRow:$last;

	//$t=date('Y-m-d H:i:s', 1388505600);
	foreach($rows as $key=>$value)
	{
		$rows[$key]['editime']=date('Y-m-d H:i:s', $value['editime']);
	}
	$prepage=$currentPage-1;
	if($prepage<1)
	{
		$prepage=1;
	}
	$nextpage=$currentPage+1;
	if($nextpage>$totalPage)
	{
		$nextpage=$totalPage;
	}
	$smarty->assign('rows',$rows);
	$smarty->assign('prepage',$prepage);
	$smarty->assign('nextpage',$nextpage);
	$smarty->assign('last',$totalPage);
	$smarty->assign('act','check');
}
$smarty->display("change_order.html");	
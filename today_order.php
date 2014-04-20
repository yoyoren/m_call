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
	$result=$db->getAll("select order_id,order_sn,orderman,ordertel,consignee,best_time,from_unixtime(add_time) as addtime1 from ecs_order_info where order_id={$order_num}");
	if($result==null)
	{
		$smarty->assign('order_num','$order_num');
	}
	$smarty->assign('order_num',$order_num);
	$smarty->assign("res",$result);
	$smarty->assign('act','sousuo');
}
elseif($act=='shijian')
{//按时间搜索
	$time1=$_REQUEST['order_time1'];
	$res=$db->getAll("select order_id,order_sn,orderman,ordertel,consignee,best_time,from_unixtime(add_time) as addtime1 from ecs_order_info where from_unixtime(add_time,'%Y-%m-%d') ='".$time1."' order by add_time desc");
	if($res==null)
	{
		$smarty->assign('kong','kong');
	}
	$smarty->assign('time',$time1);
	$smarty->assign('res',$res);
	$smarty->assign('act','shijian');
}
else
{
	$time1=date('Y-m-d',time());
	//不搜索
	$smarty->assign("agentuid", $uid);
	$currentPage = $_GET["currentPage"];
		$currentPage = $currentPage==NULL?1:$currentPage;
		$pageSize = 10;
		$totalRow = 0;
		$totalPage = 0;
		$first = ($currentPage-1)*$pageSize;//循环起始值
		$last = $first + $pageSize;//循环结束值
	$rows=$db->getAll("select order_id,order_sn,orderman,ordertel,consignee,best_time,from_unixtime(add_time) as addtime1 from ecs_order_info where from_unixtime(add_time,'%Y-%m-%d') ='".$time1."' order by add_time desc limit $first,$pageSize");
		
		//求总记录数
		$totalRow = $db->getOne("select count(*) from ecs_order_info where from_unixtime(add_time,'%Y-%m-%d') ='".$time1."'");
		//求总页数
		$totalPage = ceil($totalRow / $pageSize);
		//对$last做判断，不能超过总记录数
		$last = $last>$totalRow?$totalRow:$last;
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
	$smarty->assign('time',$time1);
	$smarty->assign('rows',$rows);
	$smarty->assign('prepage',$prepage);
	$smarty->assign('nextpage',$nextpage);
	$smarty->assign('last',$totalPage);
	$smarty->assign('act','check');
}
$smarty->display("today_order.html");	
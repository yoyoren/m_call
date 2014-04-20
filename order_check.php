<?php
/**
 * kf order detail
 * $Author: bisc $
 * $Id: order_check.php 
*/

require(dirname(__FILE__) . '/includes/init.php');
require_once('includes/lib_order.php');

if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'load_info';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}
if ($_REQUEST['act'] == 'load_info')
{
    include_once('includes/cls_json.php');
    $json = new JSON;
    $result = array('error' => '', 'content' => '');
	
    $order_sn = trim($_REQUEST['order_sn']);
    $order = ecs_order_info($order_sn);
    if (empty($order))
    {
        $result['error'] = 'order does not exist';
    }
    $sql = "SELECT concat(IFNULL(c.region_name, ''), '  ', IFNULL(p.region_name, ''), " .
                "'  ', IFNULL(t.region_name, '')) AS region " .
            "FROM ecs_order_info AS o " .
                "LEFT JOIN ship_region AS c ON o.country = c.region_id " .
                "LEFT JOIN ship_region AS p ON o.province = p.region_id " .
                "LEFT JOIN ship_region AS t ON o.city = t.region_id " .
            "WHERE o.order_id = '$order[order_id]'";
    $order['region'] = $db->getOne($sql);

    $order['order_time']    = date('Y-m-d H:i:s', $order['add_time']);
    $order['status']        = $GLOBALS['os'][$order['order_status']].','.$GLOBALS['ps'][$order['pay_status']];

    $goods_list = '';
    $sql = "SELECT goods_name,goods_sn,goods_attr,goods_number FROM ecs_order_goods WHERE order_id = '$order[order_id]' and (goods_price >100 or goods_sn ='34') ";
    $res = $db->GetAll2($sql);
    foreach ($res as $val)
    {
        $goods_list .= $val['goods_sn'].$val['goods_name'].'*'.$val['goods_attr'].'X'.$val['goods_number'].'|';
    }
    $sql= "SELECT sum(goods_number) FROM ecs_order_goods WHERE order_id = '$order[order_id]' and (goods_sn='K1' OR goods_sn='00')";
    $order['canju'] = $db->getOne($sql);
    $sql= "SELECT sum(goods_number) FROM ecs_order_goods WHERE order_id = '$order[order_id]' and goods_sn='K2'";
    $order['candle'] = $db->getOne($sql);

    $order['goods'] = $goods_list;

    $sql = "SELECT * FROM order_log WHERE order_id = '$order[order_id]'";
    $log = $db->GetAll2($sql);
	foreach($log as $key => $val)
	{
	    $log[$key]['editime'] = date('Y-m-d',$val['editime'] + 8 * 3600);
	}
    //$sql = "SELECT shipping_timeplan_id,shipping_station_name,shipping_pack_no,status_value,operate_time ".
	  //     "FROM view_shipping_orders_dispatch WHERE order_id = '$order[order_id]' ";	
	
//    $dis = $db->GetRow2($sql);	
	$smarty->assign('action', $log);
	$smarty->assign('order', $order);
	//$smarty->assign('dis', $dis);
	
    $result['content'] = $smarty->fetch('order_load.html');
    echo $json->encode($result);
    exit;
}
elseif ($_REQUEST['act'] == 'info')
{
    $order_id = intval($_REQUEST['order_id']);
    $order = ecs_order_info($order_id);

    /* ڣ˳ */
    if (empty($order))
    {
        die('order does not exist');
    }

    /* ȡ */
    $sql = "SELECT concat(IFNULL(c.region_name, ''), '  ', IFNULL(p.region_name, ''), " .
                "'  ', IFNULL(t.region_name, ''), '  ', IFNULL(d.region_name, '')) AS region " .
            "FROM ecs_ecs_order_info AS o " .
                "LEFT JOIN ecs_region AS c ON o.country = c.region_id " .
                "LEFT JOIN ecs_region AS p ON o.province = p.region_id " .
                "LEFT JOIN ecs_region AS t ON o.city = t.region_id " .
                "LEFT JOIN ecs_region AS d ON o.district = d.region_id " .
            "WHERE o.order_id = '$order[order_id]'";
    $order['region'] = $db2->getOne($sql);

    /* ʽ */
    if ($order['order_amount'] < 0)
    {
        $order['money_refund']          = abs($order['order_amount']);
        $order['formated_money_refund'] = price_format(abs($order['order_amount']));
    }

    /*  */
    $order['order_time']    = date('Y-m-d H:i:s', $order['add_time']);
    $order['status']        = $GLOBALS['os'][$order['order_status']].','.$GLOBALS['ps'][$order['pay_status']];

    /* ȡöƷ */
    $goods_list = '';
    $sql = "SELECT goods_name,goods_sn,goods_attr,goods_number FROM ecs_order_goods WHERE order_id = '$order[order_id]' and (goods_price >100 or goods_sn ='34') ";
    $res = $db2->GetAll2($sql);
    foreach ($res as $val)
    {
        $goods_list .= $val['goods_sn'].$val['goods_name'].'*'.$val['goods_attr'].'X'.$val['goods_number'].'|';
    }
    //print_r($goods_list);
    $sql= "SELECT sum(goods_number) FROM ecs_order_goods WHERE order_id = '$order[order_id]' and (goods_sn='K1' OR goods_sn='00')";
    $order['canju'] = $db2->getOne($sql);
    $sql= "SELECT sum(goods_number) FROM ecs_order_goods WHERE order_id = '$order[order_id]' and goods_sn='K2'";
    $order['candle'] = $db2->getOne($sql);

    $order['goods'] = $goods_list;

    $sql = "SELECT * FROM ecs_change_log WHERE order_sn = '$order[order_sn]'";
    $res = $db2->GetAll2($sql);
	$smarty->assign('action', $res);
	$smarty->assign('order', $order);
	$smarty->display('order_info.htm');
	
}
function ecs_order_info($order_sn)
{
    /* 㶩ַ֮͵ */
    $total_fee = " (goods_amount - discount + shipping_fee + pay_fee) AS total_fee ";
    $sql = "SELECT *, " . $total_fee . " FROM ecs_order_info WHERE order_sn = '$order_sn'";
    $order = $GLOBALS['db']->getRow2($sql);

    return $order;
}

?>
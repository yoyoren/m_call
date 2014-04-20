<?php
header('Content-type: text/html; charset=utf-8');
require(dirname(__FILE__) . '/includes/init_local.php');
require('includes/lib_order.php');
require('includes/lib_user.php');
require('includes/cls_json.php');
if($_GET['act']=='selattr')
{
	$id = intval($_REQUEST['id']);
	$sql = "select attr_price,attr_value from call_goods_attr where attr_id=6 and goods_id = '$id' order by attr_price asc";
	$res = $db1->getAll($sql);
	$arr['attrs'] = $res;	
	$json = new JSON;
	echo $json->encode($arr);
}
elseif($_GET['act']=='selregion')
{
	$id = intval($_REQUEST['id']);
	$te = intval($_REQUEST['te']);
	$sql = "SELECT region_id, region_name FROM ship_region WHERE region_type = '$te' AND parent_id = '$id'";
	$res = $db->getAll($sql);
	$json = new JSON;
	echo $json->encode($res);
}
elseif ($_GET['act'] == 'route')
{
	$address = $_REQUEST['address'];
	$sql = "SELECT route_id FROM ship_area WHERE locate(area_name,'{$address}') > 0 and area_name=(SELECT max(area_name) FROM ship_area WHERE locate(area_name,'{$address}') > 0)";
	$res = $db->getOne($sql);
	if($res)
	{
	    $sql = "SELECT route_name,fee FROM ship_route where route_id= '$res'";
	    $route = $GLOBALS['db']->getRow($sql);
		$sql="SELECT shipping_fee FROM ecs_shipping_fee WHERE locate(address,'{$address}') > 0 and address=(SELECT max(address) FROM ecs_shipping_fee WHERE locate(address,'{$address}') > 0)";
		$route['fee1'] = $GLOBALS['db']->getOne($sql);
		echo $route['fee'].','.$route['route_name'].','.$res.',44444444444'.$route['fee1'];
	}else{
		echo "no";
	}
}
elseif($_GET['act']=='getad'){

	$key  = urldecode($_GET['key']);
	
	$sql = "select route_id,area_name from ship_area where area_name like '%".$key."%'";
	$res = $db->getAll($sql);
    $json = new JSON;
    echo $json->encode($res);
	
}
elseif($_GET['act']=='getfee'){

	$region = intval($_POST['area']);
	
	$sql = "select fee from ship_region where region_id = ".$region;
	$res = $db->getOne($sql);
    $json = new JSON;
    echo $json->encode(intval($res));
	
}
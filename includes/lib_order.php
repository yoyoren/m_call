<?php

function get_onsale_goods()
{
     $sql = "select goods_id, goods_sn, goods_name from ecs_goods where is_real=1 order by goods_sn";
     $rs = $GLOBALS['db']->getAll($sql);
     $res = array();
     foreach ($rs as $val) { $res[$val[0]] = $val[1] . '-' . $val[2]; }
	 return $res;
}

function get_attr_goods()
{
     $sql = "select goods_id, goods_sn, goods_name_style from ecs_goods where is_on_sale=1 and cat_id=42 order by goods_sn";
     $rs = $GLOBALS['db']->getAll($sql);
     $res = array();
     foreach ($rs as $val) { $res[$val[0]] = $val[2]; }
	 return $res;
}

function get_unused_attr_goods($id)
{	 
	 $sql = "select goods_id,goods_sn,goods_name_style from ecs_goods where is_on_sale=1 and cat_id=42";
	 if(is_array($id)) 
	 {
		 $attrs_id=implode(",",$id);
		 $sql .=" and goods_id not in ($attrs_id)";
	 }
	 $sql .=" order by goods_sn";
     $rs = $GLOBALS['db']->getAll($sql);
	 if($rs)
	 {
		 $res = array();
		 foreach ($rs as $val) { $res[$val[0]] = $val[2]; }
	 }
	 else
	 {
		 $rs="";
	 }
	 return $res;
}

function get_regions($type = 0, $parent = 0)
{
    $sql = "SELECT region_id, region_name FROM ship_region WHERE region_type = '$type' AND parent_id = '$parent'  ";
    foreach($GLOBALS['db']->getAll($sql) as $val)
	{
		$res[$val[0]]=$val[1];
	}
	return $res;
}


function get_regions1($type = 0, $parent = 0)
{
    $sql = "SELECT region_id, region_name FROM ship_region WHERE region_type = '$type' AND parent_id = '$parent'  ";
    return  $GLOBALS['db']->getAll($sql);
}

function get_ship_fee($district, $city)
{
    $region = $district > 0 ? $district : $city;
	$sql = "SELECT fee FROM ship_region WHERE region_id = '$region'  ";
    return  $GLOBALS['db']->getOne($sql);
}
function get_consignee_list($user_id)
{
	$sql = "SELECT address_id,consignee,address FROM ecs_user_address WHERE country<>442 and user_id = '$user_id' order by address_id desc limit 5";
    return $GLOBALS['db']->getAll2($sql);
}

/* · */
function get_regions_name($region_id)
{
	$sql = "SELECT region_name FROM ship_region WHERE region_id = '$region_id'";
	return $GLOBALS['db']->getOne($sql);
}

/* Ʒۿۺļ۸ */
function get_discout_goods_price($discount_way,$number,$price)
{
	 if ($discount_way>0)
     {      
		$disprice = floor($price * $number * $discount_way);
	 }
	 elseif($discount_way < -1)
     {
		$disprice = floor($price * $number + $discount_way);
	 }
     else
	 {
		$disprice = 0;
	 }
	if ($disprice< 0) $disprice = 0;
	return $disprice;
}

function get_free_count($weight) 
{ 
    $sum = 0;
if(intval($weight)>=5)
{
   $sum = intval($weight) * 4;
}
elseif(intval($weight)<5 && intval($weight)>=1)
{
   $sum = intval($weight) * 5;
}
elseif(intval($weight)<1 && intval($weight)>0)
{
   $sum = 1;
}
else
{
   $sum = 0;
}
if(intval($weight)==15)
{
   $sum = 50;
}
    return $sum;
}

/* ۿϢ */
function get_discount_info()
{
	 $sql = "SELECT * FROM call_discount";
     $rs=$GLOBALS['db']->getAll($sql);
	 $res = array();
     foreach ($rs as $val) { $res[$val[1]] = $val[2]; }
	 return $res;
}

/* ˷ */
function shipping_fee($route_id)
{
	$sql = "SELECT route_name,fee FROM ship_route where route_id= '$route_id'";
	$route = $GLOBALS['db']->getRow2($sql);
    return $route;
}

/* */
function get_pay_name()
{
	$sql="select pay_id,pay_name from call_payment where is_cod=0 order by pay_id";
	$rs = $GLOBALS['db']->getAll($sql);
	$pay_name=array();
	foreach($rs as $v) { $pay_name[$v[0]]=$v[1]; }
	return $pay_name;
}

/* */
function get_pay_note($id)
{
	$sql="select pay_id,pay_name from call_payment where is_cod=".$id." order by pay_id";
	$rs = $GLOBALS['db']->getAll($sql);
	$pay_note=array();
	foreach($rs as $v) { $pay_note[$v[0]]=$v[1]; }
	return $pay_note;
}

/* */
function get_one_payName($id)
{
	$sql="select pay_name from call_payment where pay_id=".$id ;
	$rs = $GLOBALS['db']->getOne($sql);
	return $rs;
}

/* */
function get_station($route_id)
{
	$sql="select station_name from ship_station left join ship_route on ship_station.station_id=ship_route.station_id and route_id=".$route_id;
	return $res=$GLOBALS['db']->getOne($sql);
}

/* */
function get_catName($goods_id)
{
	$sql="select cat_name from ecs_category where  ecs_category.cat_id=(select cat_id from ecs_goods where goods_id=".$goods_id.")";
	return  $GLOBALS['db']->getOne($sql);
}
function get_discount_name($way)
{
	 $sql = "SELECT disname FROM call_discount where disvalue=".$way;
     return $GLOBALS['db']->getOne($sql);
}
function get_order_list()
{
	$sql="select i.*,o.goods_id,o.goods_name,o.goods_attr from ecs_order_info i , ecs_order_goods o,ecs_goods g where 1 ";
	
	
	$query = array();
	
	/* ԲͬͬĴ */
	if (!empty($_REQUEST['order_sn']))			$query['order_sn'] = trim($_REQUEST['order_sn']);			// ŲΪ
	if (!empty($_REQUEST['address']))			$query['address'] = trim($_REQUEST['address']);			// ַΪ
	if (!empty($_REQUEST['orderman']))			$query['orderman'] = trim($_REQUEST['orderman']);			// ˲Ϊ
	
	if (!empty($_REQUEST['tel']))			// 绰Ϊ
	{
		if (strlen(trim($_REQUEST['tel'])) == 11)			// жֻ
			$query['ordermobile'] = trim($_REQUEST['tel']);
		else
			$query['ordertel'] = trim($_REQUEST['tel']);
	}
	if (!empty($_REQUEST['goods_name']))			$query['o.goods_id'] = trim($_REQUEST['goods_name']);			// 
	if (!empty($_REQUEST['pt']))// 							
	{
		$query['goods_attr'] = trim($_REQUEST['pt']);	
		if(!strpos($_REQUEST['pt'],"."))  $query['goods_attr'] = trim($_REQUEST['pt']) . ".0";		
				
	}
	if(!empty($_REQUEST['pici']))  		$query['ship_code'] =trim( $_REQUEST['pici']);
	if(!empty($_REQUEST['scts']))		$query['scts'] = trim($_REQUEST['scts']);	
	if(!empty($_REQUEST['wsts']))		$query['wsts'] = trim($_REQUEST['wsts']);	
	if(!empty($_REQUEST['to_buyer']))	$query['to_buyer'] = trim($_REQUEST['to_buyer']);	
	if(!empty($_REQUEST['referer']))	$query['referer'] = trim($_REQUEST['referer']);
	if(!empty($_REQUEST['card_name']))	$query['card_name'] = trim($_REQUEST['card_name']);
	if(!empty($_REQUEST['state']) || ($_REQUEST['state']=="0")) $query['order_status'] = trim($_REQUEST['state']);
	if (count($query) > 0)		
	{
		$k = array_keys($query);
		$v = array_values($query);
		for ($i = 0; $i < count($query); $i++)
		{
			
			$sql .=" and ".$k[$i]." like '%".$v[$i]."%'";	
				
		}
		
		
	}
				if(!empty($_REQUEST['startdate'])) 
				{
					$query['startdate']=trim($_REQUEST['startdate']);
					$query['enddate']=trim($_REQUEST['enddate']);
					$sql .=" and cast(best_time as date) between '{$_REQUEST['startdate']}' and '{$_REQUEST['enddate']}'";
				}
				
				
				
				if(!empty($_REQUEST['ship_station'])){+
				$query['ship_station']=trim($_REQUEST['ship_station']);
				$sql1="select route_id from ship_route where station_id=".$_REQUEST['ship_station'];
				$route=$db->getAll2($sql1);
				for($i=0;$i<count($route);$i++)
				{
					if($i==count($route)-1)
					{
						$route_id .=$route[$i]['route_id'];
					}else{
						$route_id .=$route[$i]['route_id'].",";
					}
				}
				 
				$sql .= " and route_id in(".$route_id.")";
				}
				
				if(!empty($_REQUEST['add_time_s']))
				{
					$query['add_time_s']=trim($_REQUEST['add_time_s']);
					$query['add_time_e']=trim($_REQUEST['add_time_e']);
					$sql .=" and i.add_time between ".strtotime($_REQUEST['add_time_s'])." and ". strtotime($_REQUEST['add_time_e']." 23:59:59");
				}
				
			
	$sql .=" and i.order_id = o.order_id and g.goods_id=o.goods_id and cat_id=4 order by add_time desc";
	$query['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
	$query['page_size']=empty($_REQUEST['pagesize'])?  5:intval($_REQUEST['pagesize']);
	$order=Pager($sql,$query['page_size'],$query['page']);
	$order['page_count']=$order['page_count']<0?0:$order['page_count'];
	$order['sql']=$sql;
	$order['filter'] = $query;
	if ($order['list'])			// 
	{
		foreach ($order['list'] as $key => $value)
		{
			$order['list'][$key]['s_name'] = ($value['route_id'] != 0) ? get_station($value['route_id']) : '&nbsp;';
			$order['list'][$key]['addtime'] = date("Y-m-d H:i:s", $value['add_time']);
		}
	}
	return $order;
}
?>
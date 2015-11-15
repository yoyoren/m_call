<?php
header('Content-type: text/html; charset=utf-8');
require(dirname(__FILE__) . '/includes/init.php');
require('includes/lib_order.php');
require('includes/lib_user.php');
require('includes/cls_json.php');
if($_GET['act']=='selattr')
{
	$id = intval($_REQUEST['id']);
	$sql = "select attr_price,attr_value from call_goods_attr where attr_id=6 and goods_id = '$id' order by attr_price asc";
	$res = $db->getAll($sql);
	if(isset($_REQUEST['trcount']))$arr['trcount']=$_REQUEST['trcount'];
	if(isset($_REQUEST['weight']))$arr['weight']=$_REQUEST['weight'];
	$arr['attrs'] = $res;	
	$json = new JSON;
	echo $json->encode($arr);
}
elseif($_GET['act']=='selregion')
{
	$id = intval($_REQUEST['id']);
	$te = intval($_REQUEST['te']);
	$sql = "SELECT region_id, region_name FROM ship_region WHERE region_type = '$te' AND parent_id = '$id'";
	$res = $db->getAll2($sql);
	$json = new JSON;
	echo $json->encode($res);
}
elseif ($_GET['act'] == 'seladdr')
{
	$id = intval($_REQUEST['aid']);
	$sql = "SELECT * FROM ecs_user_address WHERE address_id = '$id'";
	$res = $db->getRow2($sql);
	$arr['con'] = $res;
	
	$arr['province'] = get_regions1(1, $res['country']);
	$arr['cities'] = get_regions1(2, $res['province']);
	if($res['district'])
	{
	   $arr['district'] = get_regions1(3, $res['city']);
	}
	if($res['route_id'] == "0")
	{
		$address = $res['address'];
		$sql="SELECT route_id FROM ship_area WHERE locate(area_name,'{$address}') > 0 and area_name=(SELECT max(area_name) FROM ship_area WHERE locate(area_name,'{$address}') > 0)";
		$rs = $db->getOne($sql);
		if($rs)
		{
			$route = shipping_fee($rs);
			$arr['fee']  =$route['fee'];
			$arr['route_code'] = $route['route_name'];
			$arr['route_id'] = $rs;			
		}
		else
		{
			$arr['fee'] = get_ship_fee($res['district'],$res['city']);
			$arr['route_code'] = "";
			$arr['route_id'] = 0;
		}
	}
	else
	{
		$route = shipping_fee($res['route_id']);
		$arr['fee']  =$route['fee'];
		$arr['route_code'] = $route['route_name'];
		$arr['route_id'] = $res['route_id'];
	}
	
	$json = new JSON;
	echo $json->encode($arr);
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
		$sql="SELECT shipping_fee FROM ecs_shipping_fee WHERE locate(address,'{$address}') > 0";
		$route['fee'] = $GLOBALS['db']->getOne($sql);
		$route['fee']=empty($route['fee'])?0:$route['fee'];
		echo $route['fee'].','.$route['route_name'].','.$res;
	}else{
		echo "no";
	}
}
elseif($_GET['act'] == 'pay_note')
{
	$pay_note=get_pay_note($_GET['id']);
	if($_GET['id'] == "1" || $_GET['id'] == "4")
	{
	    echo "<input type=\"checkbox\" value=\"现金\" name=\"pay_note[]\" checked>现金";
	    echo "<input type=\"text\" style=\"width:50px;\" value=\"{$v}\" name=\"way[]\" class=\"fontred fontbig\" /><br />";
        echo "<input type='checkbox' value='POS机' name='pay_note[]' >POS机";
		echo "<input type='text' style='width:50px;display:none' value='' name='way[]' class='fontred fontbig' /><br />";
	    echo "<script type='text/javascript'>";
		echo  "$(\"#pay input[type='checkbox']\").bind('click', function(){";
		echo "$(\"#pay input[type='checkbox']\").next().hide();";
		echo "$(\"#pay input[type='checkbox']:checked\").next().show();";
		echo "$(\"#pay input[type='checkbox']:not(:checked)\").next().val('');";
		echo "$(this).next().focus();";
		echo "if($(\"#pay input[type='checkbox']:checked\").length==1) $(\"#pay input[type='checkbox']:checked\").next().val(orderFee.unPayed);";
		echo "});";
		echo "</script>";
	}
	else
	{
		echo "<select name='pay_note' id='pay_note'>";
		if ($_GET['id']!="2" && $_GET['id']!="3") echo "<option value=''>请选择</option>";
		foreach($pay_note  as $k=>$val){
			echo "<option value='{$val}'>{$val}</option>";
		}
		echo "</select><span id='pay_msg'></span>";
		$uid=$_GET['uid'];
		$user=user_info($uid);
		echo "<script>";
		echo "$(\"#pay_note\").change(function(){";
		echo "$('#pay_msg').html('');";
		echo "if($(this).val()=='预付款'){";
		echo "$('#pay_msg').append(\"可用余额:{$user['user_money']}\");";
		echo "if(parseFloat(orderFee.orderAmount)> {$user['user_money']}){ $(this).val(''); $('#pay_msg').append(' 余额不足');}";
		echo "}";
		echo "if($(this).val()=='月结'){";
		$current_month=date("Y-m",time());
		$current_first_day=strtotime($current_month."-01 00:00:00");
		$sql="select sum(order_amount) from ecs_order_info where user_id={$uid} and pay_note='月结' and add_time between {$current_first_day} and ".time();
		$a=$db->getOne($sql);
		if(!$a) $a="0";
		echo "$('#pay_msg').html(\"已月结金额:{$a}\");";
		echo "}";
		echo "});";
		echo "</script>";
	}
}
elseif($_GET['act']=='check_bonus')
{
	if(isset($_REQUEST['cardnum']))
	{
		/*$bonus_cardnum = $_REQUEST['cardnum'];
		$sql = "SELECT bonus_cardnum,bonus_sn,bonus_type_id,type_money,bonus_id,order_id,use_start_date,use_end_date FROM ecs_user_bonus,ecs_bonus_type  ";
		$sql .="WHERE type_id=bonus_type_id and right(bonus_cardnum,5) ='".$bonus_cardnum."'";*/
	}
	else
	{
		$bonus_sn = $_REQUEST['sn'];

		//$sql = "SELECT bonus_cardnum,bonus_sn,bonus_type_id,type_money,bonus_id,order_id,use_start_date,use_end_date FROM ecs_user_bonus,ecs_bonus_type ";

		$sql = "SELECT bonus_sn,bonus_type_id,type_money,bonus_id,order_id,use_start_date,use_end_date,bonus_money,sdate,edate,flag,reusable FROM ecs_user_bonus,ecs_bonus_type ";
		$sql .="WHERE type_id=bonus_type_id and bonus_sn ='".$bonus_sn."'";
	}
	$res = $db->getAll2($sql);
	//if($res)
	if(($res[0]['type_money'] > 0||($res[0]['bonus_money'] > 0&&$res[0]['flag']==1)) && empty($res[0]['user_id']) && $res[0]['order_id'] <= 0)
	{
		$arr=array();
		foreach($res as $key=>$v)
		{
			$arr[$key]['CardNumber']=$v['bonus_sn'];
			//$arr[$key]['CheckCode']=$v['bonus_sn'];
			$arr[$key]['Type']=$v['bonus_type_id'];
			$arr[$key]['value']=intval($v['type_money'])==0?intval($v['bonus_money']):intval($v['type_money']);
			$arr[$key]['cardid']=$v['bonus_id'];
			$arr[$key]['cardused']=0;
			$arr[$key]['order_id']=$v['order_id'];
			$arr[$key]['reusable']=$v['reusable'];
			if($v['order_id']) $arr[$key]['cardused']=1;//已使用
			if($v['use_end_date']< time()&&$v['use_end_date']>0) $arr[$key]['cardused']=2;//过期
			if($v['edate']< time()&&$v['edate']>0) $arr[$key]['cardused']=2;//过期			
		}
	}
	else
	{
		$arr = "";
	}
	$json = new JSON;
	echo $json->encode($arr);
}
elseif($_GET['act']=='getattr')
{
	$id = intval($_REQUEST['id']);
	$sql = "select goods_id,shop_price from ecs_goods where  goods_id = '$id'";
	$res = $db->getRow($sql);
	echo $res[0].",".$res[1];
}
elseif($_GET['act']=='mem_repeat')
{
	$val=$_GET['val'];
	
    if(strlen($val)==11)
	{			 
		$sql="select count(user_id) from ecs_users where mobile_phone='{$val}'";
	}
	else
	{		
		$sql="select count(user_id) from ecs_users where office_phone='{$val}'";
	}
	$u=$db->getOne($sql);
	if($u) 
	{
		$msg='repeat';	
	}
	else
	{
		$msg='norepeat';
	}	
	
	echo $msg;
	
}
elseif($_GET['act']=='getad'){

	$key  = urldecode($_GET['key']);
	
	$sql = "select route_id,area_name from ship_area where area_name like '%".$key."%'";
	$res = $db->getAll2($sql);
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
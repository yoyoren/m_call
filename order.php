<?php
require(dirname(__FILE__) . '/includes/init.php');
include('includes/adodb5/adodb-pager.bisc.php');
require('includes/lib_order.php');
require('includes/lib_user.php');
require('includes/lib_main.php');

$_GET['act'] = empty($_GET['act']) ? 'list' : trim($_GET['act']);		
$smarty->assign('step',$_GET['act']);
/*-----------------------------------------*/
//--	Action "add"订单添加 Start
/*-----------------------------------------*/
if ($_GET['act'] == 'add')
{
	$user = user_info($_GET['id']);

    $city = 441;
	$smarty->assign('inv', get_user_inv($user['user_id']));	
	$smarty->assign('sale_goods',    get_onsale_goods());			
	$smarty->assign('discount_info', get_discount_info());		
	$smarty->assign('attr_goods',    get_attr_goods());			
	$smarty->assign('country_list',  get_regions());		
	$user['province'] = empty($city) ? (intval($user['province']) ?  $user['province'] : 441) : $city;
	$smarty->assign('province_list', get_regions(1,$user['province']));		
	$smarty->assign('consignee_list',get_consignee_list($user['user_id']));
	date_default_timezone_set("Asia/Shanghai");
	$hour = date("H",time());
	$hour =  intval($user['province']) == 443 ? intval($hour)+8 : intval($hour)+5;
	$btime = date("Y-m-d",time());
	if($hour>22) 
	{
		 $btime = date("Y-m-d",strtotime("+1 day"));
		 $hour = 10;
	}
	$m = "30";
	if(date("i",time())>30) 
	{
		$hour = $hour+1;
		$m = "00";
	}
	$smarty->assign('hour',$hour);
	$smarty->assign('btime',$btime);
	$smarty->assign('m',$m);
	$hours = array();			
	for($i = 10; $i <= 22; $i++)
	{
		array_push($hours, $i);			
	}
	$smarty->assign('hours',  $hours);	
	$pay_name = get_pay_name();	
	foreach($pay_name as $key=>$val)
	{
		if($key==2||$key==3)
		{
			unset($pay_name[$key]);
		}
	}	
	$dt=date("Y-m-d",time());
	$xiangkuangnum=$db->getOne("select count(*) from ecs_order_info where from_unixtime(add_time,'%Y-%m-%d')='".$dt."' and (order_status=1 or order_status=0) and (scts like '%相框%' or wsts like '%相框%') ");
	//echo $xiangkuangnum;
	//echo "select count(*) from ecs_order_info where from_unixtime(add_time,'%Y-%m-%d')='".$dt."' and (order_status=1 or order_status=0) and (scts like '%相框%' or wsts like '%相框%') ";
	$smarty->assign("xiangkuangnum",$xiangkuangnum);	
	$smarty->assign("pway",$pay_name);			
	$pay_note = get_pay_note("4");		
	$smarty->assign("pay_note",$pay_note);
	$smarty->assign("timekey",time());	
	$smarty->assign('user', $user);
	
	$smarty->display("order_add.htm");			
}


/*-------------------------------------------------*/
//--	Action "done" Start 订单添加完成后的操作
/*-------------------------------------------------*/
else if($_GET['act'] == 'done')
{	
	//echo "<pre>";print_r($_POST);echo "<pre>";exit;

    if(isset($_REQUEST['timeKey']))
    {
    	if($_SESSION['timeKey'] == $_REQUEST['timeKey'])
    	{
	   		echo('订单数据不能被重复提交');
	   		exit;
    	}
    	else
    	{
    		$_SESSION['timeKey'] = $_REQUEST['timeKey'];
    	}
    }

	$card_name = implode(";",$_POST['cards']).";";											 
	$cdmessage = implode(";",$_POST['msgs']).";";
	
	$pay_note = array();
	if(is_array($_POST['pay_note']))
	{
		foreach($_POST['way'] as $v)
		{
			if(!empty($v)) $amount[] = $v;
		}
		foreach($_POST['pay_note'] as $k => $v)
		{
			$pay_note[] = $v .':'. $amount[$k];
		}
		$pay = implode(",",$pay_note);
	}
	else
	{
		$pay = $_POST['pay_note'];
	}
	//如果使用了礼金卡pay_note也显示礼金卡信息
	$pay_note_ljk=$_POST['pay_note_ljk'];
	if($pay_note_ljk)
	{
		$pay_note_ljk.=",".$pay;//将礼金卡信息添加到pay_note
		$pay=$pay_note_ljk;
	}
	$pay_name = get_one_payName($_POST['pay_pway']);

    $tel = !empty($_POST['user_mobile']) && !empty($_POST['user_tel']) ? $_POST['user_mobile'].'/'.$_POST['user_tel'] : $_POST['user_mobile'].$_POST['user_tel'];
		
	$order =  array('user_id'       => intval($_POST['user_id']),									 							
					'order_status'  => $_POST['order_status'],      									 
					'consignee'     => empty($_POST['consignee']) ? $_POST['rea_name'] : $_POST['consignee'],												
					'orderman'      => $_POST['rea_name'],
					'ordertel' 	    => $tel,													 												
					'country'       => intval($_POST['country']),										 
					'province'      => intval($_POST['province']),									     
					'city'	        => intval($_POST['city']),	
					'district'	    => intval($_POST['district']),
					'address'       => addslashes($_POST['address']),												  
					'money_address' => $_POST['pay_pway'] == '1' ? addslashes($_POST['balanceaddress']) : '',													  
					'tel' 	        => empty($_POST['tel']) ? $_POST['user_tel'] : $_POST['tel'],													 
					'mobile'        => empty($_POST['mobile']) ? $_POST['user_mobile'] : $_POST['mobile'],												  
					'best_time'     => $_POST['best_time'].' '.$_POST['hours'].':'.$_POST['minute'].':00',
					'wsts'          => addslashes($_POST['textsendinfo']),											  
					'scts'          => addslashes($_POST['scts']),													  
					'card_name'     => $card_name,											   			  
					'card_message'  => $cdmessage,													  
					'pay_name'      => $pay_name,														 
					'pay_note'      => $pay,											  			
					'inv_payee'	    => $_POST['inv_payee'],											
					'inv_content'   => addslashes($_POST['inv_content']),												 
					'integral'      => $_POST['integral'],														 
					'integral_money'=> $_POST['integral'],													  
					'pay_id'        => $_POST['pay_pway'],											  						  
					'shipping_fee'  => intval($_POST['shipping_fee']),									  
					'pay_fee'       => $_POST['pay_pway'] == '1' ? 10 : 0,
					'agency_id'	    => 3,													  
					'goods_amount'  => intval($_POST['hidegoodssum']),									 												  
					'money_paid'    => floatval($_POST['money_paid']),											  
					'discount'      => intval($_POST['discount']),												 					 
					'add_time' 	    => time(),	
					'kfgh'			=> empty($_COOKIE['Callagentsn']) ? '' : $_COOKIE['Callagentsn'],						              		  
					'confirm_time'  => $_POST['order_status'] == 1 ? time() : 0,
					'bonus'			=> intval($_POST['mbonus']),
   				    'referer'	    => addslashes($_POST['textspaicl']),												
					'to_buyer'	    => addslashes($_POST['remark']),														
					'shipping_id'   => strpos($_POST['address'],'自提') ? 3 : 1,
					'surplus'=>$_POST['ljk']
	);	
	
	$amount = $order['goods_amount'] + $order['shipping_fee'] + $order['pay_fee'] +$order['integral'] + $_POST['append'];
	$order['order_amount'] = $amount - floatval($_POST['money_paid']) - floatval($_POST['discount']) - $order['integral'] - $order['bonus']-$order['surplus'];
	$order['pack_fee'] =$_POST['append'];
	//礼金卡备注
	if($amount==$_POST['ljk'])//如果礼金卡支付的金额等于该订单的所有需付金额 则pay_note只有礼金卡信息
	{
		$order['pay_note']=$pay_note_ljk;
	}
	if($_POST['pay_pway'] == 5 )//&& $pay == '预付款'
	{
       /*$order['surplus'] = $order['order_amount'];
	   $order['order_amount'] = 0; */
	}
    
	$kfgh = empty($_COOKIE['Callagentsn']) ? 0 : $_COOKIE['Callagentsn'];		
	$db->query("insert into order_genid (remark) values('$kfgh')");
	$order['order_id'] = $db->insert_id();
	$csn = $order['country'] == '441' ? 'bc' : ($order['country'] == '443'?'tc':($order['country'] == '442'?'sc':($order['country'] == '440'?'hc':'cc')));
	$order['order_sn'] = $csn.date("Ymd") . substr($order['order_id'], -5);	
	
    $db->insert("ecs_order_info",$order);	
   
	$dispatch['order_id'] = $order['order_id'];
	$dispatch['route_id'] = intval($_POST['routeid']);
	$dispatch['turn']     = intval($_POST['turn']);	
	$ttt = substr($order['best_time'],11,5);
	if($_POST['country']=='443')
	{
		if($ttt < '15:30')
	    {
	       $dispatch['turn'] = 1;
	    }
	    else
	    {
	       $dispatch['turn'] = 2;
	    }
	}
    if($order['city'] == '742')
	{
	   $dispatch['route_id'] = 265;
	}
	elseif($order['city'] == '740')
	{
	   $dispatch['route_id'] = 266;
	}
	elseif($order['city'] == '743')
	{
	   $dispatch['route_id'] = 267;
	}
	elseif($order['city'] == '739')
	{
	   $dispatch['route_id'] = 268;
	}
	elseif($order['city'] == '741')
	{
	   $dispatch['route_id'] = 269;
	}
	elseif($order['city'] == '744')
	{
	   $dispatch['route_id'] = 270;
	}
	elseif($order['city'] == '748')
	{
	   $dispatch['route_id'] = 271;
	}
    $db->insert("order_dispatch",$dispatch);			
		
	$goods = array();
	$free_canju = 0;	
														
	foreach($_POST['goods'] as $val)
	{
		$good = explode(',',$val);
		$cake['order_id']   = $order['order_id'];										
		$cake['goods_id']   = $good[1];								
		$cake['goods_name'] = $db->getOne("select goods_name from ecs_goods where goods_id = ".$good[1]);
		$cake['goods_sn']   = $db->getOne("select goods_sn from ecs_goods where goods_id = ".$good[1]);
		$cake['goods_number'] = $good[3];										
		$cake['goods_price'] = $good[5];										
		$cake['goods_attr'] = $good[2];										
		$cake['goods_discount'] = $good[4];	
		if($good[1]!=67)
		{								
			$free_canju += get_free_count($good[2])*$good[3];
		}
	    $db->insert("ecs_order_goods",$cake);
		$goods[] = $cake;
	}
	
	$pay_canju = ($_POST['canju'] - $free_canju < 0) ? 0 : $_POST['canju'] - $free_canju;
	$free_canju=array(
		'order_id'      => $order['order_id'],													
		'goods_id'      => 60,												
		'goods_name'    => '餐具套装',  												
		'goods_sn'      => 'K1',													
		'goods_number'  => $_POST['canju'] - $pay_canju,												
    	'goods_price'   => 0,  													
		'goods_attr'    => '',											 				
		'goods_discount'=> '1'																		
	);
	$db->insert("ecs_order_goods",$free_canju);
	if($pay_canju>0)
	{
	   $pay_canju=array(
			'order_id'      => $order['order_id'],																		
			'goods_id'      => 60,													
			'goods_name'    => '餐具套装',  													
			'goods_sn'      => 'K1',														
			'goods_number'  => $pay_canju,												
			'goods_price'   => 0.5,  										
			'goods_attr'    => '',											 				
			'goods_discount'=> '1'																		
	   );
	   $db->insert("ecs_order_goods",$pay_canju);
	}
	
	if(intval($_POST['candle'])>0)
	{															
		$candle=array(
			'order_id'      => $order['order_id'],																													
			'goods_id'      => 61,																	
			'goods_name'    => '方形蜡烛',  																	
			'goods_sn'      => 'K2',																			
			'goods_number'  => $_POST['candle'],																														
			'goods_price'   => 5.0,															
			'goods_attr' =>'',											 								
			'goods_discount'=> '1'																		
			);
			$db->insert("ecs_order_goods",$candle);
	}
	
	if(empty($_POST['consignee_address']))
	{
		$address=array(
			'user_id'       => $_POST['user_id'],
			'consignee'     => $_POST['consignee'],												 
			'country'       => intval($_POST['country']),										 
			'province'      => intval($_POST['province']),									     
			'city'	        => intval($_POST['city']),											 
			'district'      => intval($_POST['district']),										 
			'address'       => $_POST['address'],												
			'tel' 	        => $_POST['tel'],												
			'mobile'        => $_POST['mobile'],												 
	        'route_id'	    => $_POST['routeid']
		);
		$db->insert("ecs_user_address",$address);
	}
    if($order['surplus']>0)
    {
    	log_mcard_change($order['user_id'], $order['surplus'] * (-1),"支付订单".$order['order_sn'],0,$order['order_id'],1);
    }else{
    log_account_change($order['user_id'], (-1) * $order['surplus'], $order['order_amount'], (-1) * $order['integral'],$order['order_sn'] , 1);
    }
		
	if (!empty($_POST['bonus']))
	{
		foreach ($_POST['bonus'] as $value)
		{
			$bn = explode(":", $value);
			if(!$bn['3'])
			{
				$sql = "update ecs_user_bonus set user_id = ".$order['user_id'].", used_time = " .time(). ", order_id = " .$order['order_id']. 
					   " where bonus_id = '" . $bn['0'] ."'";
				$db->query($sql);
			}
		}
	}				

	$order['append'] = $_POST['append'];
	$order['canju']  = $_POST['canju'];
	$order['candle'] = $_POST['candle'];
	$order['amount'] = $amount;
	
	$order['attr_amount'] = $amount - $order['goods_amount']-$order['shipping_fee']-$order['pay_fee']-$order['integral'];
	$order['goods_amount'] = $order['goods_amount']+$order['integral'];
		
	$country  = get_regions_name($order['country']);
	$province = get_regions_name($order['province']);
	$city     = get_regions_name($order['city']);
	$address  = $country.$province.$city.$order['address'];
	$smarty->assign('address',$address);
	$smarty->assign('order',$order);

	$card_name=explode(";",$order['card_name']);
	$card_message=explode(";",$order['card_message']);
	
	$smarty->assign('cards',$card_name);
	$smarty->assign('msgs',$card_message);

	$smarty->assign('order_goods',$goods);
	include_once('includes/tendercall.php');
	tendercall($order['order_id'],$order['user_id'],$order['pay_id'],$order['pay_note'],$order['order_amount'],$order['bonus'],0,$order['surplus'],$order['integral']);	
	$smarty->display('order_detail.html');
	
	if($_POST['order_status'] == 1)
	{
	   //$_POST['best_time'] == date('Y-m-d');
	   //$_POST['best_time'] == date('Y-m-d',strtotime('tomorrow'))
	  if($_POST['user_mobile']){
	    include('includes/sendsms.php');
	   sms_send($_POST['user_mobile'],1,$order['order_sn']);
	   }
	   $smarty->assign('sms',1);
	}
}
/*==================== Action "detail" End ====================*/ 

/*-----------------------------------------*/
//--	Action "detail" Start
/*-----------------------------------------*/

else if ($_GET['act']=='detail')//订单修改
{
	$order_id = $_GET['order_id'];
	//订单表信息
	$sql = "select * from ecs_order_info where order_id = ".$order_id;
	$order = $db->GetRow2($sql);
	
	$sql = "select route_id,turn from order_dispatch where order_id = ".$order_id;
	$disp = $db->GetRow2($sql);
	
	$order['turn']     = $disp['turn'];
	$order['route_id'] = $disp['route_id'];
	
	$tip = "";
	$cur_time = time()+60;
	if($order['order_lock'])
	{
		if($order['order_lock']>=time()) //被锁定
		{
			$tip = '订单已被锁定，工号：'.$order['lockflag'];	
		}else{
			
			$db->query("update ecs_order_info set order_lock=".$cur_time.",lockflag='".$_COOKIE['Callagentsn']."' where order_id=".$order_id);
		}
	}else{
			$db->query("update ecs_order_info set order_lock=".$cur_time.",lockflag='".$_COOKIE['Callagentsn']."' where order_id=".$order_id);
	}
	$smarty->assign("tip",$tip);	

	//订单物品表信息
	$sql = "select * from ecs_order_goods where order_id = ".$order_id;
	$order_goods = $db->getAll2($sql);
	
	$tel = explode('/',$order['ordertel']);
	$order['ordertel'] = strlen($tel['0']) != 11 ? $tel['0'] : $tel['1'];
	$order['ordermobile'] = strlen($tel['0']) != 11 ? '' : $tel['0'];
		
	//订单状态
	$order_status = array('0'=>'未确认','1'=>'已确认','2'=>'取消','3'=>'无效','4'=>'退订');
	$smarty->assign('order_status',$order_status);
	//用户信息		
	$user = user_info($order['user_id']);
	$smarty->assign('user',$user);
	//商品信息
	$smarty->assign('sale_goods', get_onsale_goods());
	
	$card_name=explode(";",$order['card_name']);
	$card_message=explode(";",$order['card_message']);
	
	$canju = $candle_number = $i = $j = 0;
	$goods =  $cake = array();
	$fore_goods = '';
	foreach($order_goods as $k => $val)
	{
		//$no_card = array('38','43','47','53','55','80','91','65','66','82','67','60','61','92','93');
		$no_card = array('60','61','91');
		if(($val['goods_id'] != 60 && $val['goods_id'] != 61) || $val['goods_discount'] == -1)
		{
			$cake['goods_id'] = $val['goods_id'];
			$cake['goods_name'] = $val['goods_sn'].'-'.$val['goods_name'];
			$cake['goods_attr'] = $val['goods_name']=='猫爪蛋糕'?"1  套":$val['goods_attr'];
			$cake['goods_price'] = intval($val['goods_price']);
			$cake['goods_number'] = $val['goods_number'];
			$cake['goods_sumprice'] = intval($val['goods_price']*$val['goods_number']);
			$discount = $cake['discount_name'] = get_discount_name($val['goods_discount']);
			$cake['discount_price'] = get_discout_goods_price($val['goods_discount'],$val['goods_number'],$val['goods_price']);
			$cake['trname'] = 'trcake'.$i;
			$cake['card'] = 'card'.$i;
			$cake['msg'] = 'msg'.$i;
			$cake['value'] = 'trcake'.$i.','.$val['goods_id'].','.$val['goods_attr'].','.$val['goods_number'];
			$cake['value'] .= ','.$val['goods_discount'].','.intval($val['goods_price']).','.$discount;
			$cake['is_card'] = in_array($val['goods_id'],$no_card);
			$cake['no_card'] = $val['goods_id'] == 61 || $val['goods_id'] == 60 ? 0 : 1;
			$cake['cards'] = !$cake['is_card'] ? $card_name[$j] : '';
			$cake['cardm'] = !$cake['is_card'] ? $card_message[$j] : '';
			if($cake['no_card'])
			{
			    $j++;
			}
			$i++;
			$fore_goods .= $cake['value'];
			$goods[] = $cake;
		}
		//蜡烛数量
		if($val['goods_id'] == 61 && $val['goods_discount'] != -1)
		{
			$candle_number += $val['goods_number'];
		}
		//餐具数量
		if($val['goods_id'] == 60 && $val['goods_discount'] != -1 )
		{
			$canju += $val['goods_number'];
			if($val['goods_price']==0) $freecanju = $val['goods_number'];
			 
		}	
	}
	$pay_canju=	($canju-$freecanju)*0.5;
	$pay_candle=$candle_number*5;
	$smarty->assign("pay_canju",$pay_canju);
	$smarty->assign("pay_candle",$pay_candle);
    $order['route_code'] = route_info($order['route_id']);
	
	$order['shipping_fee'] = intval($order['shipping_fee']);
	$amount = $order['order_amount'] + $order['money_paid'] + $order['bonus'] + $order['discount'] + $order['integral'] + $order['surplus'];
	$order['money_paid']   = floatval($order['money_paid']);
	//var_dump($order['pay_id']);
	//echo $amount,$order['goods_amount'],$order['pay_fee'],$order['integral'];
	$order['attr_amount'] = $amount - $order['goods_amount']-$order['shipping_fee']-$order['pay_fee']-$order['integral'];
	$order['add_date'] = date('Y-m-d H:i:s',$order['add_time']);

	//送货时间
	$order['date']=substr($order['best_time'],0,10);
	$order['hours'] = substr($order['best_time'],11,2);
	$order['minute'] = substr($order['best_time'],14,2);
if($order['city'])
	{
		$city1=$db->getOne("select region_name from ship_region where region_id={$order['city']}");
		$order['qu']=$city1;
	}	
	$smarty->assign('minute', array('00','30'));
	//折扣信息
	$smarty->assign('discount_info', get_discount_info());
	//附件信息
	$smarty->assign('unused_attrs',get_unused_attr_goods($id));
	$smarty->assign('attr_goods', $attr_goods);
	//地址信息
	$smarty->assign('country_list',  get_regions());
	
	$smarty->assign('province_list',get_regions(1,$order['country']));
	$smarty->assign('city_list',get_regions(2,$order['province']));	
	//联系人历史地址
	$smarty->assign('consignee_list', get_consignee_list($user['user_id']));
	    if($order['district']!=0){
			$sql="select region_name from ecs_region where region_id=".$order['district'];
			$dist=$db->getOne($sql);
			$order['address']=(strripos($order['address'], $dist)===0)?$order['address']:$dist.'-'.$order['address'];
		}
		
	//时间
	$hours = array('08','09');
	for($i=10;$i<=22;$i++)
	{
		array_push($hours,$i);
	}
	$smarty->assign('hours',  $hours);	
	//支付方式
	$pay_name = get_pay_name();
	foreach($pay_name as $key=>$val)
	{
		if($key==2||$key==3)
		{
			unset($pay_name[$key]);
		}
	}
	$smarty->assign("pway",$pay_name);
	//付费方式
	$pay_note = get_pay_note($order['pay_id']);
	$smarty->assign("pay_note",$pay_note);
	if($order['pay_id'] == 1 || $order['pay_id'] == 4)
	{
		$pay = explode(",",$order['pay_note']);
		foreach($pay_note  as $k=>$val)
		{
			foreach($pay as $v)
			{
				if($val==substr($v,0,strpos($v,":")))
				{
					$pname[$k]['name']=$val;
					$pname[$k]['value']=substr($v,strpos($v,":")+1);
				}
			}
			$p .= "<input type=\"checkbox\" value=\"{$val}\" ";
			if($pname[$k]['name']==$val){
			 $p.=" checked='checked' ";
			}
			$p .="name=\"pay_note[]\" >".$val;
			$p .= "<input type='text' style='width:50px;";
			
			if($pname[$k]['name']==$val){
				$p .="' value='{$pname[$k]['value']}'"; 
			}else{
				$p .="display:none' value=''"; 
			}
			$p .=" name='way[]' ";
			$p .= " class='fontred fontbig' />&nbsp;<br />";
		}
	}
	else
	{
		$p ="<select name='pay_note' id='pay_note'>";
		$p .= "<option value=''>请选择</option>";
		foreach($pay_note  as $k=>$val)
		{
			$p .= "<option value='{$val}'";
			if($val==$order['pay_note']) $p .= "  selected='selected'"; 				
			$p .=">{$val}</option>";
		}
		
		$p .= "</select><span id='pay_msg'></span>";
		//if($order['pay_note']=='预付款')
		//{
		//	$p .="可用余额:{$user['user_money']}";
		//}
		//if($order['pay_note']=='月结')
		//{
		//	$current_month=date("Y-m",time());
		//	$current_first_day=strtotime($current_month."-01 00:00:00");
		//	$sql="select sum(order_amount) from ecs_order_info where user_id={$uid} and pay_note='月结' and add_time between {$current_first_day} and ".time();
		//	$a=$db->getOne($sql);
		//	if(!$a) $a="0";
		//	$p .= "已月结金额:{$a}";
		//}
		//$p .="</span>";
		//$p .= "<script>";
		//$p .= "$(\"#pay_note\").change(function(){";
		//$p .= "$('#pay_msg').html('');";
		//$p .= "if($(this).val()=='预付款'){";
		//$p .= "$('#pay_msg').append(\"可用余额:{$user['user_money']}\");";
		//$p .= "if(parseFloat(orderFee.orderAmount)> {$user['user_money']}){ $(this).val(''); $('#pay_msg').append(' 余额不足');}";
		//$p .= "}";
		//$p .= "if($(this).val()=='月结'){";
		//$current_month=date("Y-m",time());
		//$current_first_day=strtotime($current_month."-01 00:00:00");
		//$sql="select sum(order_amount) from ecs_order_info where user_id={$uid} and pay_note='月结' and add_time between {$current_first_day} and ".time();
		//$a=$db->getOne($sql);
		//if(!$a) $a="0";
		//$p .= "$('#pay_msg').html(\"已月结金额:{$a}\");";
		//$p .= "}";
		//$p .= "});";
		//$p .= "</script>";
	}
	$smarty->assign("p",$p);
	$bonus = $bonusnum = array();			
	if($order['bonus'] != 0)
	{
		$sql ="SELECT bonus_id,bonus_sn,type_money FROM ecs_user_bonus,ecs_bonus_type  ";
		$sql .="WHERE type_id=bonus_type_id and order_id =".$order_id; 
		$bonus = $db->getAll2($sql);
		foreach($bonus as $key=>$value)
		{
			$bonus[$key]['value']=$value['bonus_id'].":".$value['type_money'].":".$value['bonus_sn']; 
			$bonus[$key]['trname']="trbonus".$key; 
			$bonus[$key]['bonus_sn']=$value['bonus_sn']; 
			$bonusnum[] = $value['bonus_id'];
		}
	}
	$order['bonus_str'] = implode(',',$bonusnum);
	$order['amount'] = $amount;
	//echo "<pre>";print_r($order);echo "</pre>";
	//echo "<pre>";print_r($bonus);echo "</pre>";exit;
	$dt=date("Y-m-d",time());
	$xiangkuangnum=$db->getOne("select count(*) from ecs_order_info where from_unixtime(add_time,'%Y-%m-%d')='".$dt."' and (order_status=1 or order_status=0) and (scts like '%相框%' or wsts like '%相框%') ");
	$smarty->assign("xiangkuangnum",$xiangkuangnum);
	$smarty->assign('bonus',$bonus);
	$smarty->assign('card_name',$card_name);
	$smarty->assign('card_message',$card_message);
	$smarty->assign('fore_goods',$fore_goods);
	$smarty->assign('order_goods',$goods);
	$smarty->assign('candle',$candle_number);
	$smarty->assign('canju',$canju);
	$smarty->assign('freecanju',$freecanju);
	$smarty->assign('order',$order);
	$smarty->display('order_edit.html');
}
/*==================== Action "detail" End ====================*/ 


elseif ($_GET['act']=='update')
{
	//echo "<pre>";print_r($_POST);echo "</pre>";exit;	
    if(($_POST['fore_status']==2 ||$_POST['fore_status']==3||$_POST['fore_status']==4)&& $_POST['order_status']!= $_POST['fore_status'])
    {    	
	   echo("'取消'、'无效'、'退订'的订单不可编辑");
	   exit;
    	
    }
	$order_id = intval($_GET['order_id']);
    $user_id = intval($_POST['user_id']);
	$order = $db->getRow2("select * from ecs_order_info where order_id = ".$order_id);
	$order_sn=$order['order_sn'];
	$fore = $after = $type = $bonus = array();
	
	if (!empty($_POST['bonus']))
	{
		foreach ($_POST['bonus'] as $value)
		{
			$bn = explode(":", $value);
            $bonus[] = $bn['0'];
		}
	}
    $bonus_str = implode(',',$bonus);
	
	$order = array();
	$after_goods = implode('',$_POST['goods']);
	$btime = $_POST['best_time'].' '.$_POST['hours'].':'.$_POST['minute'].':00';
	$tel = empty($_POST['tel']) ? $_POST['user_tel'] : $_POST['tel'];													 
	$mobile = empty($_POST['mobile']) ? $_POST['user_mobile'] : $_POST['mobile'];
	$money_address = $_POST['pay_pway'] =="1" ? $_POST['balanceaddress'] : '';
    $pay_fee = $_POST['pay_pway'] == 1 ? 10 : 0;	

	if(!empty($_POST['cards']))
	{
		$card_name = implode(";",$_POST['cards']);											  
		$card_message = implode(";",$_POST['msgs']);										
	}		
		
	$pay_note = array();
	if (is_array($_POST['pay_note']))
	{
		foreach($_POST['way'] as $v)
		{
			if(!empty($v)) $amount[] = $v;
		}
		foreach($_POST['pay_note'] as $k => $v)
		{
			$pay_note[] = $v . ":" . $amount[$k];
		}
		$pay = implode(",",$pay_note);
	}
	else
	{
		$pay = $_POST['pay_note'];
	}
    if($_POST['surplus']>0)//如果礼金卡支付的金额不等于0,显示礼金卡信息
	{
		$pay_note_ljk="礼金卡：".$_POST['surplus'];
        if($pay)
        {
       		$pay=$pay_note_ljk.",".$pay;
        }
        else
        {
        	$pay=$pay_note_ljk;
        }
	}
	$pay_name = get_one_payName($_POST['pay_pway']);
	
	if($_POST['order_status'] != $_POST['fore_status'])
	{
		$fore[]  = $_POST['fore_status'];
	    $after[] = $_POST['order_status'];
		if($_POST['order_status'] == 2) 
		{
			$type[]  = '取消';
			if($_POST['user_mobile']){
	    include('includes/sendsms.php');
	   sms_send($_POST['user_mobile'],2,$order['order_sn']);
	   }
			
		}
		if($_POST['order_status'] == 3) $type[]  = '无效';
		if($_POST['order_status'] == 4) 
		{
			$type[]  = '退订';
			if($_POST['user_mobile']){
	    include('includes/sendsms.php');
	   sms_send($_POST['user_mobile'],2,$order['order_sn']);
	   }
		}		
	}
	if(($after_goods != $_POST['fore_goods']) || ($_POST['append'].','.$_POST['candle'] != $_POST['fore_fujian']))
	{
	    $sql="delete from ecs_order_goods where order_id = ".$order_id;
	    $db->query($sql);
		
		foreach($_POST['goods'] as $val)
		{
			$good = explode(',',$val);
			$cake['order_id']   = $order_id;										
			$cake['goods_id']   = $good[1];								
			$cake['goods_name'] = $db->getOne("select goods_name from ecs_goods where goods_id = ".$good[1]);
			$cake['goods_sn']   = $db->getOne("select goods_sn from ecs_goods where goods_id = ".$good[1]);
			$cake['goods_number'] = $good[3];										
			$cake['goods_price'] = $good[5];										
			$cake['goods_attr'] = $good[2];										
			$cake['goods_discount'] = $good[4];									
			$free_count += get_free_count($good[2])*$good[3];
			//print_r($cake);
			$db->insert("ecs_order_goods",$cake);
			$goods[] = $cake;
		}   
		$pay_count = ($_POST['canju'] - $free_count < 0) ? 0 : $_POST['canju'] - $free_count;
		$free_canju=array(
			'order_id'      => $order_id,													
			'goods_id'      => 60,												
			'goods_name'    => '餐具套装',  												
			'goods_sn'      => 'K1',													
			'goods_number'  => $_POST['canju'] - $pay_count,												
			'goods_price'   => 0,  													
			'goods_attr'    => '',											 				
			'goods_discount'=> '1'																		
		);
		$db->insert("ecs_order_goods",$free_canju);
		if($pay_count>0)
		{
		   $pay_canju=array(
				'order_id'      => $order_id,																		
				'goods_id'      => 60,													
				'goods_name'    => '餐具套装',  													
				'goods_sn'      => 'K1',														
				'goods_number'  => $pay_count,												
				'goods_price'   => 0.5,  										
				'goods_attr'    => '',											 				
				'goods_discount'=> '1'																		
		   );
		   $db->insert("ecs_order_goods",$pay_canju);
		}
		
		if(intval($_POST['candle'])>0)
		{															
			$candle=array(
				'order_id'      => $order_id,																													
				'goods_id'      => 61,																	
				'goods_name'    => '方形蜡烛',  																	
				'goods_sn'      => 'K2',																			
				'goods_number'  => $_POST['candle'],																														
				'goods_price'   => 5.0,															
				'goods_attr' =>'',											 								
				'goods_discount'=> '1'																		
				);
				$db->insert("ecs_order_goods",$candle);
		}
		
		$fore[]  = $_POST['fore_goods'];
	    $after[] = $after_goods;
	    $type[]  = '商品';
		if($_POST['append'].','.$_POST['candle'] != $_POST['fore_fujian'])
		{
			$fore[]  = $_POST['fore_fujian'];
			$after[] = $_POST['append'].','.$_POST['candle'];
			$type[]  = '附件';			 		
		}				 
	}
	if($_POST['fore_time'] != $btime)
	{
		$fore[]  = $_POST['fore_time'];
	    $after[] = $btime;
	    $type[]  = '时间';
		$turn =  intval($_POST['turn']);	
		$sql = "update order_dispatch set turn = '$turn',status=0 where order_id = '$order_id'";
		$db->query($sql);
	}
	if($_POST['fore_addr'] != $_POST['address'])
	{
		$fore[]  = $_POST['fore_addr'];
	    $after[] = $_POST['address'];
	    $type[]  = '地址';
		$route = intval($_POST['routeid']);
		$sql = "update order_dispatch set route_id = '$route',status=0 where order_id = '$order_id'";
		$db->query($sql);
	}
	$order['country']  = $_POST['country'];
	$order['province'] = $_POST['province'];
	$order['city']     = $_POST['city'];
	$order['route_id'] = $_POST['route_id'];
	$order['address']  = $_POST['address'];
	
	if(empty($_POST['routeid']) && $_POST['fore_status'] == 0)
	{
		$address = $_POST['address'];
	    $sql = "SELECT route_id FROM ship_area WHERE locate(area_name,'{$address}') > 0  and area_name=(SELECT max(area_name) FROM ship_area WHERE locate(area_name,'{$address}') > 0)";
		$res = $db->getOne($sql);
		if($_POST['country'] == '443')
		{
		    if($_POST['city'] == '742')
			{
			   $res = 265;
			}
			elseif($_POST['city'] == '740')
			{
			   $res = 266;
			}
			elseif($_POST['city'] == '743')
			{
			   $res = 267;
			}
			elseif($_POST['city'] == '739')
			{
			   $res = 268;
			}
			elseif($_POST['city'] == '741')
			{
			   $res = 269;
			}
			elseif($_POST['city'] == '744')
			{
			   $res = 270;
			}
			elseif($_POST['city'] == '748')
			{
			   $res = 271;
			}
		}
		$sql = "update order_dispatch set route_id = '$res',status=0 where order_id = '$order_id'";
		$db->query($sql);
	}
	
	$order['money_address']  = $money_address;
	if($_POST['fore_contect'] != $_POST['orderman'].$_POST['consignee'].$mobile.$tel)
	{
		$fore[]  = $_POST['fore_contect'];
	    $after[] = $_POST['orderman'].$_POST['consignee'].$mobile.$tel;
	    $type[]  = '联系人';   
	}
	if($_POST['fore_payment'] != $pay_name.$pay)
	{
		$fore[]  = $_POST['fore_payment'];
	    $after[] = $pay_name.$pay;
	    $type[]  = '支付'; 
	}		
	if($_POST['fore_inv'] != $_POST['inv_content'].$_POST['inv_payee'])
	{
		$order['inv_payee']   = addslashes($_POST['inv_payee']);
		$order['inv_content'] = $_POST['inv_content'];	
		$fore[]  = $_POST['fore_inv'];
	    $after[] = $_POST['inv_content'].$_POST['inv_payee'];
	    $type[]  = '发票'; 		   
	}
	if($_POST['fore_bonus'] != $bonus_str)
	{
	    $order['bonus'] = $_POST['mbonus'];
		$org_bonus = explode(',',$_POST['fore_bonus']);
		foreach($org_bonus as $val)
		{
		   $db->query("update ecs_user_bonus set used_time = 0, order_id = 0,user_id=0 where bonus_id = '".$val."'");
		}
		foreach($bonus as $val)
		{
			if(!$bn['3'])
            {
		  		 $db->query("update ecs_user_bonus set user_id = ".intval($_POST['user_id']).", used_time = '".time()."', order_id = ".$order_id." where bonus_id = '".$val."'");
            }
		}
		$fore[]  = $_POST['fore_bonus'];
	    $after[] = $bonus_str;
	    $type[]  = '代金卡'; 
	}	
	$order['order_status'] = intval($_POST['order_status']);
	$order['pay_id']   = $_POST['pay_pway'];	
	$order['pay_name'] = $pay_name;
	$order['pay_note'] = $pay;
	$order['pay_fee']  = $pay_fee;
	$order['best_time'] = $btime;
	
	$order['order_status']   = intval($_POST['order_status']);
	$order['pay_status']     = intval($_POST['pay_status']);
	$order['wsts']           = addslashes($_POST['textsendinfo']);
	$order['scts']           = addslashes($_POST['scts']);
	$order['card_name']      = empty($card_name)? '' : $card_name;
	$order['card_message']   = empty($card_message)?  '' : $card_message;
	$order['integral']       = $_POST['integral'];
	$order['integral_money'] = $_POST['integral'];
	$order['goods_amount']   = $_POST['hidegoodssum'];
	$order['money_paid']     = floatval($_POST['money_paid']);
	$order['discount']       = intval($_POST['discount']);
	$order['referer']	     = addslashes($_POST['textspaicl']);												
	$order['to_buyer']	     = addslashes($_POST['remark']);
	$order['money_address']=$money_address;
	$order['orderman']     = $_POST['orderman'];
	$order['consignee']    = $_POST['consignee'];
	$order['mobile']       = empty($mobile) ? '' : $mobile;
	$order['tel']          = empty($tel) ? '' : $tel;
	$ordertel = !empty($_POST['user_mobile']) && !empty($_POST['ordertel']) ? $_POST['ordermobile'].'/'.$_POST['ordertel'] : $_POST['ordermobile'].$_POST['ordertel'];
	$order['ordertel']     = $ordertel;	
    $order['shipping_id']  = strpos($_POST['address'],'自提') ? 3 : 1;
    $order['shipping_fee'] = intval($_POST['shipping_fee']); 
    $order['exchangestate'] = 1;
		
	$amount = floatval($order['goods_amount']) + floatval($_POST['shipping_fee']) + floatval($pay_fee) + floatval($order['integral']) + floatval($_POST['append']);
	$order['order_amount'] = $amount - floatval($_POST['money_paid']) - floatval($_POST['discount']) - floatval($order['integral']) - floatval($_POST['mbonus'])-floatval($_POST['fore_surplus']);
	
	 $order['surplus'] =floatval($_POST['fore_surplus']);
	//if($_POST['pay_pway'] == 5) //&& $pay == '预付款'
	//{
	//   $order['surplus'] = $order['order_amount'];
	//   $order['order_amount'] = 0; 
	//}	
	if($_POST['order_status'] == 1 && $_POST['fore_status'] != 1)
	{
	   $order['confirm_time'] = time();
	   $type[]  = '确认'; 
	   log_account_change($user_id , 0, 0 , (-1) * $_POST['integral'],$order['order_sn'] , 2);
	   if($_POST['user_mobile']){
	    include('includes/sendsms.php');
	   sms_send($_POST['user_mobile'],1,$order['order_sn']);
	   }
	}	

	$db->AutoExecute('ecs_order_info', $order ,'UPDATE', "order_id = '$order_id'");
	
	$integral = floatval($_POST['fore_integral']) - floatval($_POST['integral']);		
	$surplus  = floatval($_POST['fore_surplus'])  - floatval($order['surplus']);

	/*if($integral != 0 || $surplus != 0)
 {
    log_account_change($user_id , $surplus, 0 , $integral,$order['order_sn'] , 2);
 }*/
	 if($_POST['fore_status'] <= 1 && $_POST['order_status'] > 1)
   {
   if(floatval($_POST['fore_surplus'])>0 ){
   log_mcard_change($user_id, floatval($_POST['fore_surplus']), "订单号：".$order_sn,floatval($_POST['integral']),$order_id,3);   
   }   
   else
   {
  	log_account_change($user_id , floatval($_POST['fore_surplus']), $order['order_amount'], floatval($_POST['integral']),$order['order_sn'] , 2);  
   }
   $org_bonus = explode(',',$_POST['fore_bonus']);
   foreach($org_bonus as $val)
   {
      $db->query("update ecs_user_bonus set used_time = 0,user_id=0,order_id = 0 where bonus_id = '".$val."'");
   } 
 }
    
	$log_fore = implode('|',$fore);
	$log_after = implode('|',$after);
	$log_type = implode('|',$type);
	$admin = empty($_COOKIE['Callagentsn']) ? 0 : $_COOKIE['Callagentsn'];
	order_log_change($order_id, $admin, $log_fore, $log_after, $log_type,1);
	
	$order = $db->getRow2("select * from ecs_order_info where order_id = ".$order_id);
	$goods = $db->getAll2("select * from ecs_order_goods where order_id = ".$order_id);

	$order['append'] = $_POST['append'];
	$order['canju']  = $_POST['canju'];
	$order['candle'] = $_POST['candle'];
	$order['amount'] = $amount;

	$order['attr_amount'] = $amount - $order['goods_amount']-$order['shipping_fee']-$order['pay_fee']-$order['integral'];
	$order['goods_amount']=$order['goods_amount']+$order['integral'];
			
	$country  = get_regions_name($order['country']);
	$province = get_regions_name($order['province']);
	$city     = get_regions_name($order['city']);
	$address  = $country.$province.$city.$order['address'];

	$card_name=explode(";",$order['card_name']);
	$card_message=explode(";",$order['card_message']);
	
    $db->query("update ecs_order_info set order_lock=0 where order_id=".$order_id);
	$sql = "update order_dispatch set status=0 where order_id = '$order_id'";
	$db->query($sql);
	$smarty->assign('cards',$card_name);
	$smarty->assign('msgs',$card_message);	

	$smarty->assign('address',$address);
	$smarty->assign('order',$order);
	$smarty->assign('order_goods',$goods);
	include_once('includes/tendercall.php');
	tendercall($order['order_id'],$order['user_id'],$order['pay_id'],$order['pay_note'],$order['order_amount'],$order['bonus'],$order['bonus_id'],$order['surplus'],$order['integral'],1);
	$smarty->display('order_detail.html');
					
}
elseif($_GET['act'] == 'show')
{
    $order_id = intval($_GET['order_id']);
	
	$order = $db->getRow2("select * from ecs_order_info where order_id = ".$order_id);
	$goods = $db->getAll2("select * from ecs_order_goods where order_id = ".$order_id);
    
	$order['canju'] = $order['candle'] = 0;
	foreach($goods as $key=>$val)
	{
	   if($val['goods_id'] == 60)
	   {
	      $order['canju'] += $val['goods_number'];
	   }
       elseif($val['goods_id'] == 68)
       {
       	$goods[$key]['goods_attr']="1  套";
       }
	   elseif($val['goods_id'] == 61)
	   {
	      $order['candle'] += $val['goods_number'];
	   }
	}
	$amount = $order['order_amount'] + $order['money_paid'] + $order['bonus'] + $order['discount'] + $order['integral'] + $order['surplus'];
	//$order['money_paid']=$order['money_paid']+$order['surplus'];
	//echo $amount,$order['goods_amount'],$order['pay_fee'],$order['integral'];
	$order['attr_amount'] = $amount - $order['goods_amount']-$order['shipping_fee']-$order['pay_fee']-$order['integral'];
	
	//$order['append'] = $order['order_amount']+$order['bonus']+$order['surplus']-$order['goods_amount']-$order['shipping_fee']-$order['pay_fee']-$order['integral'];
	$order['amount'] =$amount;
	$order['goods_amount']=$order['goods_amount']+$order['integral'];
	$country  = get_regions_name($order['country']);
	$province = get_regions_name($order['province']);
	$city     = get_regions_name($order['city']);
	$address  = $country.$province.$city.$order['address'];
	$smarty->assign('address',$address);
	$smarty->assign('order',$order);

	$card_name=explode(";",$order['card_name']);
	$card_message=explode(";",$order['card_message']);
	
	$smarty->assign('cards',$card_name);
	$smarty->assign('msgs',$card_message);	
	
	$smarty->assign('order_goods',$goods);
	$smarty->display('order_detail.html');
}
/*-----------------------------------------*/
//--	Action "list"（订单列表） Start
/*-----------------------------------------*/
else if ($_GET['act'] == 'list')
{
	$smarty->assign('full_page',   1);
    
    $order = mem_order_list();
	
	$smarty->assign('order',        $order['list']);
    $smarty->assign('filter',       $order['filter']);
    $smarty->assign('record_count', $order['record_count']);
    $smarty->assign('page_count',   $order['page_count']);

	$smarty->display('order_list.html');
/*==================== Action "list" End ====================*/ 


/*==================== Action "query" start ====================*/ 
}
elseif($_GET['act'] == 'query')
{
    $order = mem_order_list();

	$smarty->assign('order',        $order['list']);
    $smarty->assign('filter',       $order['filter']);
    $smarty->assign('record_count', $order['record_count']);
    $smarty->assign('page_count',   $order['page_count']);
	
	make_json_result($smarty->fetch('order_list.html'), '',array('filter' => $order['filter'], 'page_count' => $order['page_count']));
}
function mem_order_list()
{
	$filter['id'] = $_REQUEST['id'];
	$sql = "select o.*,group_concat(concat(g.goods_name,g.goods_attr)) as goods from ecs_order_info as o , ecs_order_goods as g ".
	       "where o.order_id = g.order_id and g.goods_price > 40 and o.user_id = ".$filter['id'].
	       " group by o.order_id ";
	//echo $sql;
	$filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
	$filter['page_size'] = empty($_REQUEST['pagesize'])? 15 : intval($_REQUEST['pagesize']);
	
	$order = Pager($sql,$filter['page_size'],$filter['page']);
    //echo "<pre>";print_r($order);echo "</pre>";
	$order['filter'] = $filter;

	if ($order['list'])			// 处理结果集
	{
		foreach ($order['list'] as $key => $value)
		{
  		    $order['list'][$key]['status'] = $GLOBALS['os'][$value['order_status']];
			$order['list'][$key]['s_name'] = ($value['route_id'] != 0) ? get_station($value['route_id']) : '&nbsp;';
			$order['list'][$key]['addtime'] = date("Y-m-d H:i:s", $value['add_time']);
		}
	}
	return $order;
}


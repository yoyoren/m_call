<?php

//根据pay_id,pay_name判断支付方式
function tendercall($order_id,$user_id,$opay_id,$note,$amount,$bonus=0,$bonus_id=0,$surplus=0,$integral=0,$update=0)
{
	if($update) $GLOBALS['db']->query("delete from tender_info where order_id=".$order_id);
	if($amount>0){
		/*if($opay_id==1||$opay_id==4)
		{			
			if(strpos($note,',')){
				$way=explode(",",$note);			
				$order_pay2['pay_id']=($opay_id==4)?1:3;
				$n=$order_pay2['pay_name']='现金';
				if($opay_id==1) $order_pay['pay_name']='异地'.$n;
				$order_pay2['amount']=substr($way[0],strpos($way[0],":")+1);
				$order_pay2['order_id']=$order_id;
				$order_pay2['user_id']=$user_id;
				$order_pay2['type']=get_type($order_pay2['pay_id']);
				//print_r($order_pay2);exit;
				$GLOBALS['db']->insert('tender_info', $order_pay2);
				// pos机			
				$order_pay['pay_id']=($opay_id==4)?1:3;
				$n=$order_pay['pay_name']='POS机';
				if($opay_id==1) $order_pay['pay_name']='异地'.$n;
				$order_pay['amount']=substr($way[1],strpos($way[1],":")+1);			
			}else{
				if(strpos($note,'S')){				
					$order_pay['pay_id']=($opay_id==4)?2:4;
					$n=$order_pay['pay_name']='POS机';
				}else{				
					$order_pay['pay_id']=($opay_id==4)?1:3;
					$n=$order_pay['pay_name']='现金';
				}
				if($opay_id==1){
					$order_pay['pay_name']='异地'.$n;
				}		
				$order_pay['amount']=$amount;
			}*/
			//echo $opay_id."aaa";
			if($opay_id==1||$opay_id==4)
			{
				$way=explode(",",$note);
				foreach($way as $val)
				{
					$order_pay=array();
					if($opay_id==4)
					{
						$order_pay['pay_id']=1;
						if(strpos($val,'金:'))
						{
							$order_pay['amount']=substr($val,strpos($val,":")+1);
							$order_pay['pay_name']='现金';	
						}
						if(strpos($val,'OS'))
						{
							$order_pay['amount']=substr($val,strpos($val,":")+1);
							$order_pay['pay_name']='POS机';	
						}
					}
					else
					{
						$order_pay['pay_id']=3;
						if(strpos($val,'金:'))
						{
							$order_pay['amount']=substr($val,strpos($val,":")+1);
							$order_pay['pay_name']='异地现金';	
						}
						if(strpos($val,'OS'))
						{
							$order_pay['amount']=substr($val,strpos($val,":")+1);
							$order_pay['pay_name']='异地POS机';	
						}
					}
					if($order_pay['amount']!=NULL&&$order_pay['pay_name']!=NULL)
					{
						$order_pay['order_id']=$order_id;
						$order_pay['user_id']=$user_id;
						$order_pay['type']=get_type($order_pay['pay_id']);
						//print_r($order_pay);
						$GLOBALS['db']->insert('tender_info', $order_pay);
						}
				}
			}
		
	}
	if($opay_id==2 || $opay_id==3|| $opay_id==21 || $opay_id==6){
			$order_pay['pay_id']=($opay_id==2)?5:(($opay_id==3)?6:($opay_id==21)?7:8);
			if($opay_id==2)
			{
				$order_pay['pay_name']='支付宝';
			}
			if($opay_id==3)
			{
				$order_pay['pay_name']='快钱';
			}
			if($opay_id==6)
			{
				$order_pay['pay_name']='免费支付';
			}
			//$order_pay['pay_name']=$note;
			$order_pay['amount']=$amount;
	}
	if($opay_id==5){//大客户
		if($note=='月结'){
			$order_pay['pay_id']=11;
			$order_pay['pay_name']='大客户月结';
			//$order_pay['amount']=$surplus;
			$order_pay['amount']=$amount;			
		}else{
			$order_pay['pay_id']=12;
			$order_pay['pay_name']='大客户预付款';
			$order_pay['amount']=$surplus;	
		}			
	}
	if($opay_id!=1&&$opay_id!=4&&isset($order_pay)){
		$order_pay['order_id']=$order_id;
		$order_pay['user_id']=$user_id;
		$order_pay['type']=get_type($order_pay['pay_id']);
		//echo "<pre>";print_r($order_pay);
		$GLOBALS['db']->insert('tender_info', $order_pay);
	}
	if($bonus>0){
		$order_pay['pay_id']=9;
		$order_pay['order_id']=$order_id;
		$order_pay['user_id']=$user_id;
		//客服
		$bonus = get_orderbonus($order_id);
		//print_r($bonus);
		foreach($bonus as $val){
			$order_pay['pay_name']=$val['type_name'];
			$order_pay['amount']=$val['cardcount'];	
			$order_pay['remark']=$val['cardnum'];
			$order_pay['type']=get_type($order_pay['pay_id']);
			//print_r($order_pay);
			$GLOBALS['db']->autoExecute('tender_info', $order_pay);
		}
	}
	if($surplus>0)
	{
		$order_pay['order_id']=$order_id;
		$order_pay['user_id']=$user_id;
		$order_pay['pay_id']=14;
		$order_pay['type']=get_type($order_pay['pay_id']);		
		$order_pay['pay_name']='礼金卡';
		$order_pay['amount']=$surplus;
		$GLOBALS['db']->autoExecute('tender_info', $order_pay);
		
	}	
}
function get_type($pay_id)
{
	if($pay_id>0&&$pay_id<5) $type=1;	
	if($pay_id>4&&$pay_id<8) $type=2;
	if($pay_id==8) $type=3;
	if($pay_id==9) $type=4;
	if($pay_id>9&&$pay_id<13) $type=2;
	if($pay_id==13) $type=5;
    if($pay_id==14) $type=6;
	return $type;
}
function get_orderbonus($order_id){
	 $sql = "SELECT t.type_name, t.type_money,count(bonus_id) as cardcount,GROUP_CONCAT(b.bonus_sn separator ';') as cardnum  " .
            "FROM " . 'ecs_bonus_type' . " AS t," .'ecs_user_bonus' . " AS b " .
            "WHERE t.type_id = b.bonus_type_id ";
	$sql .= "AND b.order_id = '$order_id' GROUP BY type_id";
	return $GLOBALS['db']->getAll($sql);
}
?>
<?php

require(dirname(__FILE__) . '/includes/init.php');
include('includes/adodb5/adodb-pager.bisc.php');
include('includes/lib_main.php');
include('includes/lib_user.php');

$action = empty($_GET['act']) ? 'list' : trim($_GET['act']);		
$smarty->assign('step',$action);
if($action == 'list'){
	$user_id=$_REQUEST['id'];
	$smarty->assign('id',$user_id);
	$smarty->assign('ur_here',     '查看记录');
	
	//查看储值卡的余额
	$sql = "SELECT user_money as ye FROM ecs_users "
	." WHERE user_id=".$user_id;
	$ye = $db->getOne($sql);
	$smarty->assign("ye",$ye);
	$account_type = 'user_money';
	$sql = "SELECT * FROM mcard_log" .
           " WHERE user_id = $user_id" .
           " AND user_money <> 0 " .
           " ORDER BY log_id DESC";
	$res=$db->getAll2($sql);
	if($res){
    foreach($res as $key=>$row)
    {
        $res[$key]['change_time'] = date("Y-m-d H:i:s", $row['change_time']); 
        $res[$key]['short_change_desc'] = sub_str($row['change_desc'], 60);
    }//var_dump($res);exit;
	}else $smarty->assign('nolist',1);
	$smarty->assign('record',   	$res);
	$smarty->assign('user_id',$user_id);    
	$smarty->display('moneycard.html');	
}
elseif($action == 'charge')
{
	$user_id=$_REQUEST['id'];
	$smarty->assign('id',$user_id);
	$smarty->assign('ur_here',     '充值');
	$smarty->display('moneycard.html');
}
//检查储值卡
elseif($action=="check_card"){
	$cardpassword=$_REQUEST['card_password'];
	$cardid=$_REQUEST['card_num'];
    $sql="select mc.* from moneycards" 
	." as mc where "
	."mc.cardid='".$cardid."' and mc.cardpassword='".$cardpassword."'";
	$record = $db->getRow($sql);//print_r($record);exit;
	//echo $sql;
	if($record)
	{
		if($record['flag']==1)
		{
			if($record['user_id']==0 && $record['used_time']==0)
			{
			    $time=time();
				if($time<$record['sdate'])
				{
					echo "5";//储值卡未生效
				}elseif ($time>$record['edate']){
					echo "4";//储值卡已过期
				}
			}else{
				echo "3";//储值卡已使用
			}
		}else{
			echo "2";//储值卡不可用
		}
	}else{
		echo "1";//卡号或密码错误
	}
}
//将储值卡数据插入数据库
elseif($action == 'chargecard_do'){
	$user_id=$_REQUEST['uid'];
	//接收卡号、密码
	$card_num = htmlspecialchars(trim($_POST["card_num"]));
	$card_pwd = htmlspecialchars(trim($_POST["card_password"]));
		//验证卡号
		$sql="select mc.* from moneycards" 
		." as mc where "
		."mc.cardid='".$card_num."' and mc.cardpassword='".$card_pwd."'";
		
		$record = $db->getRow($sql);//var_dump($record);
		if($record)
		{
			if($record['flag']==1)
			{
				if($record['user_id']==0 && $record['used_time']==0)
				{
				    $time=time();
					if($time>=$record['sdate'] && $time<=$record['edate'])
					{
						$sql1="update moneycards set user_id='$user_id', used_time ="
						. gmtime() ." where cardid='$card_num'";
						$res=$db->query($sql1);
						//注册时间
						$regt=$db->getOne("select reg_time from ecs_users where user_id=$user_id ");
						$charge_num=$db->getOne("select charge_num from ecs_users where user_id=$user_id ");
						if($res)
						{
							$user_money = floatval($record['cardmoney']);
							if(time()>1385827200 && time()<1391097600)
							{
								if($regt>=1386086400 && $regt<1388505600)
								{
									$change_d='储值卡：'.$card_num.'充值';
									$current_time=time();
									$db->query("insert into mcard_log(user_id,change_type,user_money,change_desc,change_time)values($user_id,2,$user_money,'{$change_d}',{$current_time}) ");
									$db->query("insert into ecs_account_log(user_id,user_money,frozen_money,rank_points,pay_points,change_time,change_desc,change_type)values($user_id,$user_money,0,0,0,$current_time,'$change_d',99)");
									if($charge_num==1){
									//$user_m=$user_money-50;
									$r=$db->query("update ecs_users set user_money=user_money-50+{$user_money} where user_id=$user_id");
									}else{
									//$user_m=$user_money;
									$r=$db->query("update ecs_users set user_money=user_money+{$user_money} where user_id=$user_id");
									}
									
									if($r)
									{
										$charge_num=$db->query("update ecs_users set charge_num=2 where user_id=$user_id");	
										//$user_money = $GLOBALS['db']->getOne('SELECT user_money FROM ' . $ecs->table('users') ." WHERE user_id=$us_id");
									}
								
								
								}
								else
								{
								log_mcard_change($user_id, $user_money,'储值卡：'.$card_num.'充值',0,0,2);
								}
							}
							else
							{
								log_mcard_change($user_id, $user_money,'储值卡：'.$card_num.'充值',0,0,2);
							}
							echo "<script type=\"text/javascript\">window.alert('充值成功'); location.href='moneycard.php?act=list&id={$user_id}' </script>";
						}
					}else{
						echo "<script type=\"text/javascript\">window.alert('您的储值卡已过期'); history.go(-1);</script>";
					}
				}else{
					echo "<script type=\"text/javascript\">window.alert('储值卡已使用'); history.go(-1);</script>";
				}
			}else{
				echo "<script type=\"text/javascript\">window.alert('此储值卡不可用'); history.go(-1);</script>";
			}
		}else{
			echo "<script type=\"text/javascript\">window.alert('卡号或密码错误'); history.go(-1);</script>";
		}	
		
}
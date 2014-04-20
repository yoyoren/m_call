<?php
/* 获取指定用户的信息 */
function user_info($user_id)
{
	$sql = "SELECT * FROM ecs_users WHERE user_id = '$user_id'";
	$user = $GLOBALS['db']->getRow($sql);
	return $user;
}

/* 获取指定用户投诉信息 */
function get_tousu($id)
{
	$sql = "SELECT * FROM call_service WHERE user_id = {$id} order by add_time desc limit 5";
	$toushu = $GLOBALS['db']->getAll2($sql);
	return $toushu;
}

/* 判断数组中的元素是否存在重复 */
function is_norepeat($repeat, $arr)
{	
	// in_array() 检查数组中是否存在某个值	
	return is_array($arr) ? (in_array($repeat, $arr) ? false : true) : true;
}
function get_mem_list()
{
	$arr = array();
	$sql = "select * from ecs_users where 1";
	$mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';
	$tel = isset($_REQUEST['tel']) ? trim($_REQUEST['tel']) : '';
	$rea_name = isset($_REQUEST['rea_name']) ? trim($_REQUEST['rea_name']) : '';
	$user_name = isset($_REQUEST['user_name']) ? trim($_REQUEST['user_name']) : '';
	if (!empty($mobile)) $arr['mobile_phone'] = $mobile;
	if (!empty($tel)) $arr['office_phone'] = $tel;
	if (!empty($rea_name)) $arr['rea_name'] = $rea_name;
	if (!empty($user_name)) $arr['user_name'] = $user_name;
	
	if (count($arr) > 0)
	{
		$keys = array_keys($arr);
		$values = array_values($arr);
		for ($i = 0; $i < count($arr); $i++)
		{
		 	$sql .= " and " . $keys[$i] . " like  '%" . $values[$i] . "%'";
		}
	}
	
	$sql .="  order by user_id";
	$arr['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
	$arr['page_size']=empty($_REQUEST['pagesize'])? 15 :intval($_REQUEST['pagesize']);
	$mem=Pager($sql,$arr['page_size'],$arr['page']);
	$mem['page_count']=$mem['page_count']<0?0:$mem['page_count'];
	$mem['sql']=$sql;
	$mem['filter'] = $arr;
	return $mem;
}
function log_account_change($user_id, $user_money = 0, $rank_points = 0, $pay_points = 0, $change_desc = '', $change_type = ACT_OTHER)
{
    $account_log = array(
        'user_id'       => $user_id,
        'user_money'    => $user_money,
		'admin'         => empty($_COOKIE['Callagentsn']) ? '' : $_COOKIE['Callagentsn'],
        'rank_points'   => $rank_points,
        'pay_points'    => $pay_points,
        'change_time'   => gmtime(),
        'change_desc'   => $change_desc.real_ip(),
        'change_type'   => $change_type
    );
    $GLOBALS['db']->autoExecute('mem_account_log', $account_log, 'INSERT');

    $sql = "UPDATE ecs_users SET user_money = user_money + ('$user_money')," .
            " rank_points = rank_points + ('$rank_points')," .
            " pay_points = pay_points + ('$pay_points')" .
            " WHERE user_id = '$user_id' LIMIT 1";
    $GLOBALS['db']->query($sql);
}
function user_acount_log($user_id =0)
{
    $filter = intval($user_id) > 0 ? "and user_id = '$user_id'" : ''; 
	$sql = "select * from mem_account_log where 1 ".$filter;
	$res = $GLOBALS['db']->Getall2($sql);
	foreach($res as $key => $val)
	{
	   $res[$key]['change_time'] = date('Y-m-d H:i',$val['change_time'] + 8 * 3600);
	   $res[$key]['change_type'] = $GLOBALS['alog'][$val['change_type']];
	}
	return $res;
}
function order_log_change($order_id, $admin, $order_fore, $order_after, $alter_type,$is_msg)
{
    $order_log = array(
        'order_id'    => $order_id,
        'admin_id'    => $admin,
        'order_fore'  => $order_fore,
        'order_after' => $order_after,
        'editime'     => time(),
        'alter_type'  => $alter_type,
        'is_msg'      => $is_msg
    );
    $GLOBALS['db']->autoExecute('order_log', $order_log, 'INSERT');
}
function get_user_inv($user_id)
{
   $sql = "select inv_payee from ecs_order_info where user_id = '$user_id' and inv_payee > '' limit 2";

   $res = $GLOBALS['db']->getCol($sql);
   $rs = "['";
   foreach($res as $val)
   {
      $rs .= $val."','";
   }
   $rs .= "']";  
   
   return $rs;
}
/**
 * 获得用户的真实IP地址
 *
 * @access  public
 * @return  string
 */
function real_ip()
{
    static $realip = NULL;

    if ($realip !== NULL)
    {
        return $realip;
    }

    if (isset($_SERVER))
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip)
            {
                $ip = trim($ip);

                if ($ip != 'unknown')
                {
                    $realip = $ip;

                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            if (isset($_SERVER['REMOTE_ADDR']))
            {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $realip = '0.0.0.0';
            }
        }
    }
    else
    {
        if (getenv('HTTP_X_FORWARDED_FOR'))
        {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('HTTP_CLIENT_IP'))
        {
            $realip = getenv('HTTP_CLIENT_IP');
        }
        else
        {
            $realip = getenv('REMOTE_ADDR');
        }
    }

    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

    return $realip;
}
/**
 * 记录帐户变动
 * @param   int     $user_id        用户id
 * @param   float   $user_money     可用余额变动
 * @param   string  $change_desc    变动说明
 * @param   int   $order_id    订单号
 * @param   int     $change_type    变动类型：参见常量文件
 * @return  void
 */
function log_mcard_change($user_id, $user_money = 0, $change_desc = '',$pay_points = 0,$order_id=0,$change_type = 1)
{
    /* 插入帐户变动记录 */
    $account_log = array(
        'user_id'       => $user_id,
        'user_money'    => $user_money,
        'change_time'   => time(),
        'change_desc'   => $change_desc,
        'change_type'   => $change_type,
     'order_id'    => $order_id
    );
    $GLOBALS['db']->autoExecute("mcard_log", $account_log, 'INSERT');

    /* 更新用户信息 */
    $sql = "UPDATE ecs_users" .
            " SET user_money = user_money + ('$user_money')," .
            " frozen_money = frozen_money + ('$frozen_money')," .
            " rank_points = rank_points + ('$rank_points')," .
            " pay_points = pay_points + ('$pay_points')" .
            " WHERE user_id = '$user_id' LIMIT 1";
    $GLOBALS['db']->query($sql);
}
?>
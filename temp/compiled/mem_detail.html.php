<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<title><?php if ($this->_var['ur_here']): ?> - <?php echo $this->_var['ur_here']; ?> <?php endif; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/Layer.css" rel="stylesheet" type="text/css" />
<link href="styles/Render.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="page">
<fieldset>
    <legend>客户详情</legend>
    <table width="100%">
    <tr>
        <td align="right" width="7%">用户名称:</td><td align="left" width="13%"><?php echo $this->_var['user']['user_name']; ?></td>
        <td align="right" width="7%">真实姓名:</td><td align="left" width="13%"><?php echo $this->_var['user']['rea_name']; ?>(<?php if ($this->_var['user']['sex'] == 0): ?>未知<?php elseif ($this->_var['user']['sex'] == 1): ?>男<?php elseif ($this->_var['user']['sex'] == 2): ?>女<?php else: ?>协议客户<?php endif; ?>)</td>
        <td align="right" width="7%">手机号码:</td><td align="left" width="13%"><?php echo $this->_var['user']['mobile_phone']; ?></td>
        <td align="right" width="7%">座机号码:</td><td align="left" width="13%"><?php echo $this->_var['user']['home_phone']; ?></td>
		<td>&nbsp;</td><td>&nbsp;</td>  
    </tr>
    <tr>
        <td align="right" width="7%">用户生日:</td><td align="left" width="13%"><?php echo $this->_var['user']['birthday']; ?></td>
        <td align="right" width="7%">用户积分:</td><td align="left" width="13%"><?php echo $this->_var['user']['pay_points']; ?></td>
        <td align="right" width="7%">用户余额:</td><td align="left" width="13%"><?php echo $this->_var['user']['user_money']; ?></td>
        <td align="right" width="7%">初始密码</td><td align="left" width="13%"><?php echo $this->_var['user']['ec_salt']; ?></td>
		<td>&nbsp;</td><td>&nbsp;</td> 
    </tr>
	<tr><td align="right">用户备注:</td><td align="left" colspan="9"><?php echo $this->_var['user']['back_info']; ?></td></tr>
	<tr><td align="right">账户备注:</td><td align="left" colspan="9"><?php echo $this->_var['user']['question']; ?></td></tr>
    </table>
        <p>
    <a href="mem_user.php?act=adduser" >新建</a>  |   
    <a href="order.php?act=add&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>" >订购</a>  |   
    <a href="mem_user.php?act=service&type=1&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>" >建议咨询</a>  |   
    <a href="mem_user.php?act=service&type=4&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>" >推单催单</a>  |   
    <a href="mem_user.php?act=service&type=3&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>" >投诉</a>  |   
    <a href="mem_user.php?act=edit&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>">修改</a>|
    <a href="mem_user.php?act=account&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>">账户变动</a>  |
	<a href="moneycard.php?act=list&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>">礼金卡</a>  |
    </p>
<!--<p>
    <a href="mem_user.php?act=adduser" target="_blank">新建</a>  |   
    <a href="order.php?act=add&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>" target="_blank">订购</a>  |   
    <a href="mem_user.php?act=service&type=1&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>" target="_blank">建议咨询</a>  |   
    <a href="mem_user.php?act=service&type=4&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>" target="_blank">推单催单</a>  |   
    <a href="mem_user.php?act=service&type=3&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>" target="_blank">投诉</a>  |   
    <a href="mem_user.php?act=edit&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>">修改</a>|
    <a href="mem_user.php?act=account&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>">账户变动</a>  |
	<a href="moneycard.php?act=list&id=<?php echo $this->_var['user']['user_id']; ?>&agentuid=<?php echo $this->_var['uid']; ?>">礼金卡</a>  |
    </p>-->
</fieldset>
<hr />
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="tablemargin table_02">
    <tr>
      <td width="9%"  align="center" class="bglime">最近订单</td>
      <td width="91%" valign="top">
       <table width="100%" border="0" cellpadding="0" cellspacing="1" class="tablemargin center table_02">
        <tr class="bglime">
          <td width="4%">查看</td>
          <td width="7%">修改</td>
         <!-- <td width="7%">打印</td>-->
          <td width="7%">订单状态</td>
          <td width="12%">订单号</td>
          <td width="16%">送货时间</td>
          <td width="6%">订货人</td>
          <td width="12%">电话</td>
          <td width="7%">金额</td>
          <td>产品信息</td>
        </tr>
        <?php $_from = $this->_var['order']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
        <tr class="<?php echo $this->cycle(array('values'=>'bggray,')); ?>">
          <td><a href="order.php?act=show&order_id=<?php echo $this->_var['v']['order_id']; ?>" target="_blank">详情</a></td>
          <td><a href="order.php?act=detail&order_id=<?php echo $this->_var['v']['order_id']; ?>" target="_blank">详情</a></td>
         <!-- <td>打印</td>-->
          <td><?php if ($this->_var['v']['status'] == '已确认'): ?><span style="font:bold;color:#FF0000;"><?php echo $this->_var['v']['status']; ?></span><?php else: ?><?php echo $this->_var['v']['status']; ?><?php endif; ?></td>
          <td><?php echo $this->_var['v']['order_sn']; ?></td>
          <td><?php echo $this->_var['v']['best_time']; ?></td>
          <td><?php echo $this->_var['v']['consignee']; ?></td>
          <td><?php if ($this->_var['v']['ordertel'] && $this->_var['v']['ordermobile']): ?><?php echo $this->_var['v']['ordertel']; ?>/<?php echo $this->_var['v']['ordermobile']; ?><?php else: ?><?php echo $this->_var['v']['ordertel']; ?><?php echo $this->_var['v']['ordermobile']; ?><?php endif; ?></td>
          <td><?php echo $this->_var['v']['order_amount']; ?></td>
          <td><?php if ($this->_var['v']['status'] == '已确认'): ?><span style="font:bold;color:#FF0000;"><?php echo $this->_var['v']['goods']; ?></span><?php else: ?><?php echo $this->_var['v']['goods']; ?><?php endif; ?></td>
          </tr>
		  <?php endforeach; else: ?>
		  <tr><td colspan="10" align="left">暂无订单</td></tr>
         <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </table>
		<?php if ($this->_var['count'] > 5): ?>
        <span style="float:right;padding-right:10px" ><a href="order.php?act=list&id=<?php echo $this->_var['user']['user_id']; ?>" target="_blank">更多</a></span>
		<?php endif; ?>
      </td>
    </tr>
  </table>
  <table width="100%" border="0" cellpadding="0" cellspacing="1" class="tablemargin table_02">
    <tr>
      <td width="9%"  align="center" class="bglime">最近服务</td>
      <td width="91%">
	         <table width="100%" border="0" cellpadding="0" cellspacing="1" class="tablemargin center table_02">
        <tr class="bglime">
          <td width="4%">详情</td>
		  <td width="4%">类型</td>
          <td width="50%">投诉内容</td>
          <td width="6%">状态</td>
          <td width="6%">受理人</td>
          <td width="14%">受理时间</td>
        </tr>
        <?php $_from = $this->_var['tousu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 't');if (count($_from)):
    foreach ($_from AS $this->_var['t']):
?>
        <tr class="<?php echo $this->cycle(array('values'=>'bggray,')); ?>">
          <td><a href="service.php?act=info&id=<?php echo $this->_var['t']['serv_id']; ?>" target="_blank">详情</a></td>
          <td><?php echo $this->_var['t']['serv_type']; ?></td>
          <td align="left"><?php echo $this->_var['t']['serv_desc']; ?></td>
          <td><?php if ($this->_var['t']['flag'] == '0'): ?>未处理<?php elseif ($this->_var['t']['flag'] == '1'): ?>已处理<?php else: ?>处理中<?php endif; ?></td>
          <td><?php echo $this->_var['t']['admin']; ?></td>
          <td><?php echo $this->_var['t']['add_time']; ?></td>
          </tr>
		  <?php endforeach; else: ?>
		  <tr><td colspan="9" align="left">暂无投诉记录</td></tr>
         <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </table>
         </td>
    </tr>
   
  </table>
     <table width="100%" border="0" cellpadding="0" cellspacing="1" class="tablemargin table_02">
      <tr>
      <td width="9%"  align="center" class="bglime">最近回访</td>
      <td width="91%"> 
      
      
            无回访记录
         
         </td>
    </tr>
  </table>
  <br/>
   <form action="do.php" method='get'>
  <div style="padding-left:20px;font-size:14px;font-weight:bold;">客户查询:&nbsp;<input type='text' name="tel"><input type="submit" value="go"/></div>
  </form><br />
  <a target="_blank" style="padding-left:20px;font-size:14px;font-weight:bold;" href="/callsearch/order_stat.php?act=">订单查询</a>
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>MES<?php if ($this->_var['ur_here']): ?> - <?php echo $this->_var['ur_here']; ?> <?php endif; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />
<link href="styles/Layer.css" rel="stylesheet" type="text/css" />
<link href="styles/Render.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="page">
<fieldset>
<legend>订单<?php if ($this->_var['step'] == 'done'): ?>添加成功！<?php elseif ($this->_var['step'] == 'update'): ?>修改成功！<?php else: ?>详情<?php endif; ?></legend>
</fieldset>
<hr />
<p style=" padding-left:10px;">
订单号:<font size="+1" color="#FF0000"><b><?php echo $this->_var['order']['order_sn']; ?></b></font><span style="margin-left:30px">订单状态:<?php if ($this->_var['order']['order_status'] == 0): ?>未确认<?php endif; ?>
          	    <?php if ($this->_var['order']['order_status'] == 1): ?>已确认<?php endif; ?>
				<?php if ($this->_var['order']['order_status'] == 2): ?>取消<?php endif; ?>
				<?php if ($this->_var['order']['order_status'] == 3): ?>无效<?php endif; ?>
				<?php if ($this->_var['order']['order_status'] == 4): ?>退订<?php endif; ?></span>
<span style="margin-left:30px">送货时间:<font size="+1" color="#FF0000"><b><?php echo $this->_var['order']['best_time']; ?></b></font></span>				
				<br />
</p>
<p style=" padding-left:10px;">
送货地址:<?php echo $this->_var['address']; ?><br />
收货信息:<?php echo $this->_var['order']['consignee']; ?> | <?php if ($this->_var['order']['mobile'] && $this->_var['order']['tel']): ?><?php echo $this->_var['order']['mobile']; ?>/<?php echo $this->_var['order']['tel']; ?><?php else: ?><?php echo $this->_var['order']['mobile']; ?><?php echo $this->_var['order']['tel']; ?><?php endif; ?><br />
<?php if ($this->_var['order']['pay_id'] == 1): ?>结款地址:<?php echo $this->_var['order']['money_address']; ?><?php endif; ?>
外送提示:<?php echo empty($this->_var['order']['wsts']) ? '无' : $this->_var['order']['wsts']; ?>
</p>
<p style="margin-left:10px;">
<table>
   <tr><td>
    <table border="0" cellpadding="0" cellspacing="1">
       <tr class="bglimeimg">
	    <th width="10">&nbsp;</th>
        <th align="left" width="220">商品名称</th>
        <th align="left" width="40">规格</th>
        <th align="left" width="40">单价</th>
        <th align="left" width="30">数量 </th>
        <th align="left" width="40">原价</th>
        <th align="left" width="50">折扣</th>
      </tr>
      <?php $_from = $this->_var['order_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['v']):
?>
      <?php if ($this->_var['v']['goods_price'] > 40 || $this->_var['v']['goods_discount'] == '-1'): ?>
       <tr class="fontred">
	    <td width="10">&nbsp;</td>
        <td height="25" class="fontred"><?php echo $this->_var['v']['goods_name']; ?></td>
        <td height="25" class="fontred"><?php echo $this->_var['v']['goods_attr']; ?></td>
        <td height="25" class="fontred"><?php echo $this->_var['v']['goods_price']; ?> </td>
        <td height="25" class="fontred"><?php echo $this->_var['v']['goods_number']; ?> </td>
        <td height="25" class="fontred"><?php echo $this->_var['v']['goods_price']; ?></td>
        <td height="25" class="fontred"><?php echo $this->_var['v']['goods_discount']; ?></td>
      </tr>
      <?php endif; ?>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	  </table>
	  </td><td>
    <table border="0" cellpadding="0" cellspacing="1">
       <tr class="bglimeimg">
        <th align="left" width="220">生日牌</th>
      </tr>
      <?php $_from = $this->_var['cards']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['v']):
?>
       <tr class="fontred">
        <td height="25" class="fontred"><?php echo $this->_var['v']; ?><?php echo $this->_var['msgs'][$this->_var['k']]; ?></td>
      </tr>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	  </table>
	  </td></tr></table>
</p>
<p style=" padding-left:10px;">
<?php if ($this->_var['order']['scts']): ?>生产提示:<?php echo $this->_var['order']['scts']; ?><span style="margin-left:30px"><?php endif; ?>餐具数:<?php echo $this->_var['order']['canju']; ?></span>
<span style="margin-left:30px"><?php if ($this->_var['order']['candle']): ?>蜡烛数:<?php echo $this->_var['order']['candle']; ?><?php endif; ?></span><br />
<?php if ($this->_var['order']['to_buyer']): ?>订单备注:<?php echo $this->_var['order']['to_buyer']; ?><br /><?php endif; ?>
<?php if ($this->_var['order']['postscript']): ?><font color="#FF0000">订单附言:<?php echo $this->_var['order']['postscript']; ?></font><br /><?php endif; ?>
<?php if ($this->_var['order']['referer']): ?>客服备注:<?php echo $this->_var['order']['referer']; ?><br /><?php endif; ?>
发票信息:<?php echo empty($this->_var['order']['inv_content']) ? '无发票' : $this->_var['order']['inv_content']; ?><?php if ($this->_var['order']['inv_content']): ?>|<?php endif; ?><?php echo $this->_var['order']['inv_payee']; ?><br /> 
</p>
<table border="1" cellpadding="0" cellspacing="1" class="tablemargin center table_02" id="pricesumtb">
    <tr>
      <td width="60" rowspan="2" align="left" class="bgyellowgreen">费用</td>
      <td width="90" class="bgyellowgreenimg">折后蛋糕费</td>
      <td width="40" class="bgyellowgreenimg">附件费</td>
      <td width="40" class="bgyellowgreenimg">服务费 </td>
      <td width="40" class="bgyellowgreenimg">配送费 </td>
      <td width="70" class="bgyellowgreenimg">订单总额</td>
      <td width="40"  class="bgyellowgreenimg">已付</td>
      <td width="40"  class="bgyellowgreenimg">现金券</td>
      <td width="40"  class="bgyellowgreenimg">积分额</td>
      <td width="40"  class="bgyellowgreenimg">折扣额</td>
      <td width="60"  class="bgyellowgreenimg">储值卡/大客户</td>
      <td width="60" class="bgyellowgreenimg">支付方式</td>
      <td width="200"  class="bgyellowgreenimg">支付信息</td>
    </tr>
    <tr>
      <td><?php echo $this->_var['order']['goods_amount']; ?></td>
      <td><?php echo $this->_var['order']['attr_amount']; ?></td>
      <td><?php echo $this->_var['order']['pay_fee']; ?></td>
	  <td><?php echo $this->_var['order']['shipping_fee']; ?></td>
      <td><span><?php echo $this->_var['order']['amount']; ?></span></td>
	  <td><?php echo $this->_var['order']['money_paid']; ?></td>
	  <td><span><?php echo $this->_var['order']['bonus']; ?></span></td>
	  <td><span><?php echo $this->_var['order']['integral']; ?></span></td>
	  <td><?php echo $this->_var['order']['discount']; ?></td>
	  <td><?php echo $this->_var['order']['surplus']; ?></td>
      <td><?php echo $this->_var['order']['pay_name']; ?></td>
      <td><?php echo $this->_var['order']['pay_note']; ?></td>
    </tr>
  </table>
  
  <p align="right">
  <input  type="button" style="margin-left:40px" value="返回"  onclick="javascript:location.href='mem_user.php?act=showuser&id=<?php echo $this->_var['order']['user_id']; ?>';"/></p>
</form>
</div>

</body>
</html>



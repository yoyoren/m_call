<?php if ($this->_var['full_page']): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户订单列表</title>
<link href="styles/Layer.css" rel="stylesheet" type="text/css" />
<link href="styles/Render.css" rel="stylesheet" type="text/css" />
<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,transport.js,utils.js,listtable.js,datepicker/WdatePicker.js')); ?>
</head>
<body>
<div class="page">
<fieldset>
<legend>订单列表</legend>
</fieldset>
</div>
<hr />
<div style="clear:both;"></div>
<div id="listDiv" style="overflow:scroll;float:left;">
<?php endif; ?>
<table border="0" cellpadding="0" cellspacing="1" width="150%">
    <tr class="bglimeimg">
    	<td width=40>详情</td>
		<td width=60>订单号</td>
		<td width=60>订单状态</td>
		<td width=40>配送单状态</td>
		<td width=40>生产单状态</td>
        <td width="130">送货时间</td>
		<td width="130">下单时间</td>
		<td width="60">订货人</td>
		<td width="100">电话</td>
		<td width="80">手机号</td>
		<td>配送站</td>
        <td>送货员</td>
		<td width="180">蛋糕</td>
		<td>生日牌</td>
		<td width=40>送货人</td>
		<td width=70>外送电</td>
		<td width=40>应收</td>
		<td width=40>坐席</td>
		<td width="30">详情</td>
		<td width=40>送货人</td>
		<td width=70>外送电</td>
		<td width=40>应收</td>
		<td width=40>坐席</td>
		<td width="30">详情</td>
    	</tr>
    <?php if ($this->_var['order']): ?>
      <?php $_from = $this->_var['order']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'order_0_33811000_1390136767');if (count($_from)):
    foreach ($_from AS $this->_var['order_0_33811000_1390136767']):
?>
      <tr align="center">
        <td><a href='order.php?act=show&order_id=<?php echo $this->_var['order_0_33811000_1390136767']['order_id']; ?>' target="_blank">详情</a></td>
        <td><?php echo $this->_var['order_0_33811000_1390136767']['order_sn']; ?></td>
        <td><?php if ($this->_var['order_0_33811000_1390136767']['order_status'] == 0): ?>未确认<?php elseif ($this->_var['order_0_33811000_1390136767']['order_status'] == 1): ?>已确认<?php elseif ($this->_var['order_0_33811000_1390136767']['order_status'] == 2): ?>取消<?php elseif ($this->_var['order_0_33811000_1390136767']['order_status'] == 3): ?>无效<?php elseif ($this->_var['order_0_33811000_1390136767']['order_status'] == 4): ?>退订<?php endif; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $this->_var['order_0_33811000_1390136767']['best_time']; ?></td>
		<td><?php echo $this->_var['order_0_33811000_1390136767']['addtime']; ?></td>
        <td><?php echo $this->_var['order_0_33811000_1390136767']['orderman']; ?></td>
        <td><?php echo $this->_var['order_0_33811000_1390136767']['ordertel']; ?></td>
        <td><?php echo $this->_var['order_0_33811000_1390136767']['ordermobile']; ?></td>
        <td><?php echo $this->_var['order_0_33811000_1390136767']['s_name']; ?></td>
        <td>&nbsp;</td>
        <td align="left"><?php echo $this->_var['order_0_33811000_1390136767']['goods']; ?></td>
        <td><?php echo $this->_var['order_0_33811000_1390136767']['card_name']; ?></td>
        <td><a href='order.php?act=detail&order_id=<?php echo $this->_var['order_0_33811000_1390136767']['order_id']; ?>' target="_blank">详情</a></td>
      </tr>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      <td colspan="15" align="center"><?php echo $this->fetch('page.htm'); ?></td>
      <?php else: ?>
      <tr ><td align="center" colspan="15">无满足的记录</td></tr>
      <?php endif; ?>
    </tbody>
</table>
<?php if ($this->_var['full_page']): ?>
</div>
</body>
<script language="javascript">
listTable.recordCount = <?php echo $this->_var['record_count']; ?>;
listTable.pageCount = <?php echo $this->_var['page_count']; ?>;

<?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>


function searchOrder()
{
	
        listTable.filter['page']  = 1;
        listTable.loadList();
}
</script>
<?php endif; ?>
</html>

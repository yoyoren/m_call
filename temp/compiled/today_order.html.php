<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>今日下单记录</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo $this->smarty_insert_scripts(array('files'=>'jquery-1.7.min.js,datepicker/WdatePicker.js,jquery.autocomplete.js')); ?>
</head>
<body bgcolor="#FFFFCC" style='font-size:13px;'>
<div style='line-height:40px;'><b>今日下单记录</b></div>

<div>
<form action='today_order.php' method='post' onsubmit='return checksousuo(1)'>
<table>
	<tr>
		<td width='120'>输入订单后五位：</td>
		<td width='350'><input type='text' name='order_num' id='order_num'value='<?php echo $this->_var['order_num']; ?>' />
			<input type='hidden' name='step' value='sousuo'/></td>
		<td width='120'><input type='submit' value='搜索'/> </td>
	</tr>
</table>
</form>
</div>

<div>
<form action='today_order.php' method='post' onsubmit='return checksousuo(2)'>
<table>
	<tr>
		<td width='120'>输入搜索时间：</td>
		<td width='350'>
			<input type='text' name='order_time1' id='order_time1' onclick="javascript:WdatePicker()" value='<?php echo $this->_var['time']; ?>' />
			<input type='hidden' name='step' value='shijian'/></td>
		<td width='120'><input type='submit' value='搜索'/> </td>
	</tr>
</table>		  
</form>
</div>
<?php if ($this->_var['act'] == 'sousuo' || $this->_var['act'] == 'shijian'): ?>
<table border='1' bordercolor='#E6A140' cellspacing='0' cellpadding='0' width='1200' style='line-height:30px;'>
<tr style='font-weight:bold;'>
		<td width='60'>订单号</td>
		<td width='40'>订货人</td>
		<td width='40'>订货人电话</td>
		<td width='40'>收货人电话</td>
		<td width='140'>送货时间</td>
		<td width='140'>下单时间</td>
	</tr>
<?php if ($this->_var['kong'] == kong): ?>
	<tr>
		<td colspan='6'>搜索的结果不存在</td>
	</tr>
	<?php else: ?>
<?php $_from = $this->_var['res']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
	<tr onmouseover='changecolor(<?php echo $this->_var['v']['order_id']; ?>)' id=<?php echo $this->_var['v']['order_id']; ?> onmouseout="changecolor2(<?php echo $this->_var['v']['order_id']; ?>)">
		<td><?php echo $this->_var['v']['order_sn']; ?></td>
		<td><?php echo $this->_var['v']['orderman']; ?></td>
		<td><?php echo $this->_var['v']['ordertel']; ?></td>
		<td><?php echo $this->_var['v']['consignee']; ?></td>
		<td><?php echo $this->_var['v']['best_time']; ?></td>
		<td><?php echo $this->_var['v']['addtime1']; ?></td>
	</tr>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	<?php endif; ?>
</table>
<?php else: ?>
<table border='1' bordercolor='#E6A140' cellspacing='0' cellpadding='0' width='1200' style='line-height:30px;'>
	<tr style='font-weight:bold;'>
		<td width='60'>订单号</td>
		<td width='40'>订货人</td>
		<td width='40'>订货人电话</td>
		<td width='40'>收货人电话</td>
		<td width='140'>送货时间</td>
		<td width='140'>下单时间</td>
	</tr>
<?php $_from = $this->_var['rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>	
	<tr onmouseover='changecolor(<?php echo $this->_var['v']['order_id']; ?>)' id=<?php echo $this->_var['v']['order_id']; ?> onmouseout="changecolor2(<?php echo $this->_var['v']['order_id']; ?>)">
		<td><?php echo $this->_var['v']['order_sn']; ?></td>
		<td><?php echo $this->_var['v']['orderman']; ?></td>
		<td><?php echo $this->_var['v']['ordertel']; ?></td>
		<td><?php echo $this->_var['v']['consignee']; ?></td>
		<td><?php echo $this->_var['v']['best_time']; ?></td>
		<td><?php echo $this->_var['v']['addtime1']; ?></td>
	</tr>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<tr>
	<td colspan='6' align='center'><a href='today_order.php?currentPage=1'>首页</a>
	<a href='today_order.php?currentPage=<?php echo $this->_var['prepage']; ?>'>上一页</a>
	<a href='today_order.php?currentPage=<?php echo $this->_var['nextpage']; ?>'>下一页</a>
	<a href='today_order.php?currentPage=<?php echo $this->_var['last']; ?>'>尾页</a></td>
</tr>
</table>
<?php endif; ?>
</body>
</html>
<script language='javascript'>
//检查订单号是否为空
function checksousuo(k)
{ 
	var num=document.getElementById('order_num').value;

    var time1=document.getElementById('order_time1').value;
    var time2=document.getElementById('order_time2').value;
	var pattern=/^\d{4}-\d{2}-\d{2}$/;
	if(k==1)
	{
		if(num =='')
		{
			alert('请先输入五位订单号');
			return false;
		}
	}
	if(k==2)
	{
		
		if(time1 =='')
		{
			alert('请选择下单时间');
			return false;
		}
		if(!pattern.test(time1))
		{
			alert('时间格式不对');
			return false;
		}
	}
}
function changecolor(n)
{
	 document.getElementById(n).style.background = '#D5F255';
}
function changecolor2(m)
{
	document.getElementById(m).style.background = '#FFFFCC';
}
</script>
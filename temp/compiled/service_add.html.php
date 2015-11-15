<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>MES<?php if ($this->_var['ur_here']): ?> - <?php echo $this->_var['ur_here']; ?> <?php endif; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/Layer.css" rel="stylesheet" type="text/css" />
<link href="styles/Render.css" rel="stylesheet" type="text/css" />
<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,transport.js,utils.js')); ?>
</head>
<body>
<div class="page">
<fieldset>
<legend><?php echo $this->_var['typeName']; ?>添加</legend>
</fieldset>
</div>
<hr />
<div class="page">
<form name="theForm" method="post" action="mem_user.php?act=addservice"  onsubmit="return validate()">
<table border="0" align="center" width="100%">
   	<tr id="desc"><td width="80px">类型</td><td><select name="serv_type">
	<option value="咨询" <?php if ($this->_var['typeName'] == '咨询'): ?>selected<?php endif; ?>>咨询</option>
	<option value="建议" <?php if ($this->_var['typeName'] == '建议'): ?>selected<?php endif; ?>>建议</option>
	<option value="投诉" <?php if ($this->_var['typeName'] == '投诉'): ?>selected<?php endif; ?>>投诉</option>
	<option value="推单" <?php if ($this->_var['typeName'] == '推单'): ?>selected<?php endif; ?>>推单</option>
	<option value="催单" <?php if ($this->_var['typeName'] == '催单'): ?>selected<?php endif; ?>>催单</option>
	<option value="其他" <?php if ($this->_var['typeName'] == '其他'): ?>selected<?php endif; ?>>其他</option>
	</select><input type="hidden" name="user_id" value="<?php echo $this->_var['user_id']; ?>" /></td></tr>
    <tr height="30" id="desc"><td width="80px">内容</td><td><textarea name="title" rows="3" cols="60"></textarea></td></tr>
    <tr height="30" id="desc"><td width="80px">订单</td><td><input type="text" id="order" style="width:150px;" />&nbsp;&nbsp;
	<a href='javascript:loadin();'>订单详情</a>&nbsp;&nbsp;
	<select name="list" onchange="useosn(this);">
	<option value="">选择客户订单</option>
	<?php $_from = $this->_var['orders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 's');if (count($_from)):
    foreach ($_from AS $this->_var['s']):
?>
	<option value="<?php echo $this->_var['s']['order_id']; ?>"><?php echo $this->_var['s']['order_sn']; ?></option>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</select></td></tr>
	<input type="hidden" name="order_id" id="orderid" value="" />
    <tr id="content"><td name="zixun">配送站</td>
	     <td><input type="text" name="station" />配送员<input type="text" name="sender" /></td></tr>
    <tr align="center"><td style="padding-top:10px" ></td><td><input type="submit" value="提交<?php echo $this->_var['typeName']; ?>"  /></td></tr>
    <tr><td colspan="2" id="msg"></td></tr>
</table>
</form>
</div>
<div class="page" id="detail"></div>
</body>
<script>
function validate()
{
	if(theForm.title.value=="")
	{
		document.getElementById("msg").innerHTML="<font color='red'>内容不能为空</font>";
		theForm.title.focus();
		return false;
	}
	return true;
}
function loadin()
{
  var sn = document.getElementById('order').value;
  if(sn.length < 12)
  {
     alert('请输入正确的订单号!');return;
  }
  else
  {
     Ajax.call('order_check.php?act=load_info', 'order_sn=' + sn, loadResponse, 'GET', 'JSON');
  }
}

function loadResponse(obj)
{
  if (obj.error)
  {
    alert(obj.error);
    return false;
  }
  else
  {
	  try
	  {
		var layer = document.getElementById("detail");
	
		layer.innerHTML = (typeof obj == "object") ? obj.content : obj;
	  }
	  catch (ex) {}
  }
}
function useosn(obj)
{
   document.getElementById('order').value = obj.options[obj.selectedIndex].text;
   document.getElementById('orderid').value = obj.options[obj.selectedIndex].value;
}
</script>
</html>



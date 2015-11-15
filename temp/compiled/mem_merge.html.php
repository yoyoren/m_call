<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MES<?php if ($this->_var['ur_here']): ?>-<?php echo $this->_var['ur_here']; ?><?php endif; ?></title>
<?php echo $this->smarty_insert_scripts(array('files'=>'jquery-1.7.min.js')); ?>
<script>
$.fn.ready(function() 
{
	$("input[type = 'radio']").change(function()
	{
		$("input[type = 'checkbox']").attr("disabled",false);
		$(this).parent().next().children().attr("disabled","disabled");
	});	
}); 
</script>
</head>
<body>
<form name="mergefrm"  method="post" action="mem_user.php?act=merge">
<table border="1" cellpadding="0" cellspacing="0">
    <tr align="center">
    	<th>设为主</th><th>选择合并</th><th>用户名</th><th>真实名</th>
	    <th>手机号</th><th>其他电话</th><th>普通备注</th><th>财务备注</th>
    </tr>     
    <?php $_from = $this->_var['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
    <tr align="center">
        <td><input type="radio" name="main" value="<?php echo $this->_var['v']['user_id']; ?>" /></td>
        <td><input type="checkbox" name="secondary[]" value="<?php echo $this->_var['v']['user_id']; ?>" /></td>
        <td><?php echo $this->_var['v']['user_name']; ?>&nbsp;</td>
        <td><?php echo $this->_var['v']['rea_name']; ?>&nbsp;</td>
        <td><?php echo $this->_var['v']['mobile_phone']; ?>&nbsp;</td>
        <td><?php echo $this->_var['v']['office_phone']; ?>&nbsp;</td>
        <td><?php echo $this->_var['v']['back_info']; ?>&nbsp;</td>
        <td><?php echo $this->_var['v']['question']; ?>&nbsp;</td>
    </tr>  
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <tr align="center"><td colspan="8">合并原因:<input type="text" name="reason" value="" /></td></tr>  
    <tr align="center"><td colspan="8"><input type="submit" value="不合并使用此客户">&nbsp;&nbsp;&nbsp;<input type="submit" value="合并用户"></td></tr>  
</table>
</form>    
</body>
</html>

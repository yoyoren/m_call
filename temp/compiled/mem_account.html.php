<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MES<?php if ($this->_var['ur_here']): ?> - <?php echo $this->_var['ur_here']; ?> <?php endif; ?></title>
<link href="styles/Layer.css" rel="stylesheet" type="text/css" />
<link href="styles/Render.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	function userEdit()
	{
		return true;
	}
</script>
</head>
<body>
<form name="formEdit" action="" method="post" onSubmit="return userEdit();">
<div class="page">
<fieldset>
    <legend>客户信息修改</legend>
    <table width="100%">
        <tr>
            <td  align="right" height="30"><strong>用户名称：</strong></td>
            <td align=left><?php echo $this->_var['user']['user_name']; ?></td>
            <td align="right"  height="30"><strong></strong></td>
            <td align=left></td>
        </tr>
        <tr>
            <td align="right" height="30"><strong>用户积分：</strong></td>
            <td align=left><input id="pay_points" name="pay_points" type="text" value="<?php echo $this->_var['user']['pay_points']; ?>" size="25"  />
            <span id="email_notice" style="color:#FF0000"> *</span> </td>
            <td align="right"><strong><input id="pay_points" name="fore_points" type="hidden" value="<?php echo $this->_var['user']['pay_points']; ?>" /></strong></td>
            <td align=left></td>
        </tr>
        <tr>
            <td align="right" height="30"><strong>用户余额：</strong></td>
            <td align=left><input name="user_money" type="text" value="<?php echo $this->_var['user']['user_money']; ?>" size="25" /></td>
            <td align="right"><strong><input name="fore_money" type="hidden" value="<?php echo $this->_var['user']['user_money']; ?>" /></strong></td>
            <td align=left></td>
        </tr>
        <tr>
            <td align="right" height="30"><strong>账户备注：</strong></td>
            <td align="left" colspan="3"><textarea name="question" cols="80"><?php echo $this->_var['user']['question']; ?></textarea></td>
        </tr>
        <tr>
            <td style="border-right:none"><?php echo $this->_var['msg']; ?></td>
            <td colspan="3" align="center" height="30" style="border-left:none">
            	<input name="submit" type="button" value="返回账户" onclick="location.href='mem_user.php?act=showuser&id=<?php echo $this->_var['user']['user_id']; ?>'" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="submit" type="submit" value="确认修改" />
            </td>
        </tr>
    </table>
</fieldset>
<hr />
<table cellpadding="3" width="100%" cellspacing="1">
  <tr class="bggray">
    <td colspan="5" align="left">账户变动日志</td>
  </tr>
  <tr class="bglimeimg">
    <td>޸余额变动</td>
    <td>޸积分变动</td>
    <td>完成时间</td>
    <td>操作客服</td>
    <td>操作备注</td>
  </tr>
  <?php $_from = $this->_var['log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
  <tr>
    <td><div align="center"><?php echo $this->_var['list']['user_money']; ?></div></td>
    <td><div align="center"><?php echo $this->_var['list']['pay_points']; ?></div></td>
    <td><div align="center"><?php echo $this->_var['list']['change_time']; ?></div></td>
    <td><div align="center"><?php echo $this->_var['list']['admin']; ?></div></td>
    <td><div align="center"><?php echo $this->_var['list']['change_desc']; ?></div></td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td colspan="5">无改单日志</td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</table>
</div>
</form>
</body>
</html>

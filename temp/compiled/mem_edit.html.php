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
            <td align="right"  height="30"><strong>真实姓名：</strong></td>
            <td align=left><input name="rea_name" type="text" value="<?php echo $this->_var['user']['rea_name']; ?>" size="25" /><input type="radio" name="sex" value="3" <?php if ($this->_var['user']['sex'] == 3): ?>checked="checked"<?php endif; ?>/>协议客户</td>
        </tr>
        <tr>
            <td align="right" height="30"><strong>出生日期：</strong></td>
            <td  align=left> <select name="birthdayYear">
            <?php echo $this->html_options(array('options'=>$this->_var['year'],'selected'=>$this->_var['birthday']['0'])); ?>
            </select>&nbsp;<select name="birthdayMonth">
            <?php echo $this->html_options(array('options'=>$this->_var['month'],'selected'=>$this->_var['birthday']['1'])); ?>
            </select>&nbsp;<select name="birthdayDay">
            <?php echo $this->html_options(array('options'=>$this->_var['day'],'selected'=>$this->_var['birthday']['2'])); ?>
            </select> </td>
            <td align="right" ><strong>客户性别：</strong></td>
            <td >
            <?php echo $this->html_radios(array('name'=>'sex','options'=>$this->_var['sex'],'checked'=>$this->_var['user']['sex'])); ?>
        </tr>
        <tr>
            <td align="right" height="30"><strong>手机号码：</strong></td>
            <td align=left><input id="mobile" name="mobile_phone" type="text" value="<?php echo $this->_var['user']['mobile_phone']; ?>" size="25" maxlength="11" />
            <span id="email_notice" style="color:#FF0000"> *</span> </td>
            <td align="right"><strong>客户积分：</strong></td>
            <td align=left><?php echo $this->_var['user']['pay_points']; ?></td>
        </tr>
        <tr>
            <td align="right" height="30"><strong>座机号码：</strong></td>
            <td align=left><input name="home_phone" type="text" value="<?php echo $this->_var['user']['home_phone']; ?>" size="25" /></td>
            <td align="right"><strong>其它电话：</strong></td>
            <td align=left><input name="office_phone" type="text" value="<?php echo $this->_var['user']['office_phone']; ?>" size="25" /></td>
        </tr>
        <tr>
            <td align="right" height="30"><strong>客户备注：</strong></td>
            <td align="left" colspan="3"><textarea name="back_info" cols="80"><?php echo $this->_var['user']['back_info']; ?></textarea></td>
        </tr>
        <tr>
            <td align="right" height="30"><strong>账户备注：</strong></td>
            <td align="left" colspan="3"><textarea name="question" cols="80"><?php echo $this->_var['user']['question']; ?></textarea></td>
        </tr>
        <tr>
            <td style="border-right:none"><?php echo $this->_var['msg']; ?></td>
            <td colspan="3" align="center" height="30" style="border-left:none">
            	<input name="submit" type="submit" value="确认修改" />
            </td>
        </tr>
    </table>
</fieldset>
<hr />
</div>
</form>
</body>
</html>

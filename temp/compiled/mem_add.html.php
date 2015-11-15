<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php if ($this->_var['ur_here']): ?> - <?php echo $this->_var['ur_here']; ?> <?php endif; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo $this->smarty_insert_scripts(array('files'=>'jquery-1.7.min.js')); ?>
<link href="styles/Layer.css" rel="stylesheet" type="text/css" />
<link href="styles/Render.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">


function showzixun()
{
	if (theForm.type.value!="0")
	{
		document.getElementById("desc").style.display="";
		document.getElementById("content").style.display="";		
	} 
	else 
	{
		document.getElementById("desc").style.display="none";	
		document.getElementById("content").style.display="none";
	}
}

function validate()
{
	if ($("#user_name").val()=="")
	{
		document.getElementById("msg").innerHTML="<font color='red'>姓名不能为空</font>";
		$("#user_name").focus();
		return false;
	} 
	else if ($("#mobile_phone").val()==""&&$("#home_phone").val()=="")
	{
		document.getElementById("msg").innerHTML="<font color='red'>联系电话不能为空</font>";			
		return false;
	} 
	else if ($("#mobile_phone").val().length>0)
	{
		if(!(/^(1[3-8])\d{9}$/.test($("#mobile_phone").val())))
		{
			document.getElementById("msg").innerHTML="<font color='red'>手机号码不正确</font>";
			$("#mobile_phone").focus();
			return false;	
		}
	}
	else if ($("#office_phone").val().length>0)
	{
		if (isNaN($("#office_phone").val()))
		{
			document.getElementById("msg").innerHTML="<font color='red'>请检查电话号码</font>";
			$("#office_phone").focus();
			return false;
		}
	}

	if ($("#type").val() !="0")
	{
		if ($("#title").val()=="")
		{
			document.getElementById("msg").innerHTML="<font color='red'>简述不能为空</font>";
			theForm.url.focus();
			return false;
		}
		else if ($("#content").val()=="")
		{
			document.getElementById("msg").innerHTML="<font color='red'>内容不能为空</font>";
			$("#content").focus();
			return false;
		}
	}
	return true;
}
</script>
</head>

<body style="padding-left:20px;font-size:14px;font-weight:bold;line-height:25px;">
<form name="theForm" id="theForm" method="post" action="mem_user.php?act=addands"  onsubmit="return validate();" >
<div class="page">
<fieldset>
<legend>客户添加</legend>
</fieldset>
<hr />
<table width="60%" border="0" cellpadding="0" cellspacing="1" class="tablemargin center table_02">
    <tr>
    	<td width="80px">真实姓名：</td>
    	<td align="left"><input type="text" name="rea_name" id="rea_name" size="11"/><input type="checkbox" name="sex" value="3"/>协议客户</td>
    </tr>
    <tr>
    	<td>手机电话：</td>
    	<td align="left"><input type="text" name="mobile_phone" id="mobile_phone" value="<?php echo $this->_var['mobile']; ?>" size="11" maxlength="11" /></td>
    </tr>
    <tr>
        <td>座机电话：</td>
        <td align="left"><input type="text" name="home_phone" id="home_phone" value="<?php echo $this->_var['tel1']; ?>"  size="11" maxlength="11" /></td>      
    </tr>
    <tr>
        <td>其他电话：</td>
        <td align="left"><input type="text" name="office_phone" id="office_phone" value=""  size="11" maxlength="11" /></td>      
    </tr>
    <tr>
    	<td>所属地区：</td>
		<td align="left"><select name="city"  id="city">
                <option value="bj">北京</option>
            </select>
		</td>
    </tr>
    <tr>
    	<td>服务类型：</td>
    	<td align="left"><select name="type"  id="type" onchange="showzixun()">
                <option value="0">订购</option>
                <option value="1">建议</option>
                <option value="2">咨询</option>
                <option value="3">投诉</option>
            </select>
	    </td>
    	
    </tr>
    <tr id="desc" style="display: none">
    	<td width="80px">简述</td>
    	<td align="left"><input type="text"  id="title" name="title" /></td>
    </tr>
    <tr id="content">
    	<td width="80px">客户备注：</td>
    	<td align="left"><textarea name="content" id="content" rows="5" cols="60"></textarea></td>
    	
    </tr>
    <tr align="center"><td colspan="2" style="padding-top:10px" ><input type="submit" id="btnsubmit" value="新建客户资料"  /><input type="hidden" name="agentuid" value="<?php echo $this->_var['agentuid']; ?>" /></td></tr>
    <tr><td colspan="2" id="msg"></td></tr>
</table>
</form>
<br/>
  <form action="do.php" method='get'>
  <div>客户查询:&nbsp;<input type='text' name="tel"><input type="submit" value="go"/></div>
  </form><br />
   <a target="_blank" href="/callsearch/order_stat.php?act=">订单查询</a>
</body>
</html>



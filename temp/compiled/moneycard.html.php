<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="styles/Layer.css" rel="stylesheet" type="text/css" />
<link href="styles/Render.css" rel="stylesheet" type="text/css" />
<?php echo $this->smarty_insert_scripts(array('files'=>'moneycard.js,jquery-1.7.min.js')); ?>
<style type="text/css">
.menu{width:100px;font-size:17px;height:30px;float:left;font-weight:bold;line-height:30px;vertical-align:middle;text-align:center;}
.menu:hover{cursor:pointer;background-color:#dc7865;}
</style>
<title>充值</title>
</head>

<body>

<div class="menu" id="cz" <?php if ($this->_var['ur_here'] == "充值"): ?>style="background-color:#dc7865;"<?php endif; ?>><a href="moneycard.php?act=charge&id=<?php echo $this->_var['id']; ?>">充值</a></div>
<div class="menu" id="ckjl" <?php if ($this->_var['ur_here'] == "查看记录"): ?>style="background-color:#dc7865;"<?php endif; ?>><a href="moneycard.php?act=list&id=<?php echo $this->_var['id']; ?>">查看记录</a></div>
<div style="clear:both;height:0px;content:'1';display:block;visibility:hidden;"></div>
<?php if ($this->_var['step'] == 'list'): ?>
<div class="mcard">
	  <div style="width:70%;">
		<div class='title2'style="border:1px solid #D7CEC0">
			<span style="height:38px;line-height:38px;font-size:14px;"><b>账户余额：</b>
			<font style="color:#53BF36; font-size:16px;">￥</font>
			<font style="color:#53BF36; font-size:24px;font-family:'黑体'"><?php if ($this->_var['nolist'] != 1): ?><?php echo $this->_var['ye']; ?><?php else: ?>0.00<?php endif; ?></font>
			<font style="color:#53BF36; font-size:16px;">元</font>
			</span>
			<span style="width:50px;margin-left:500px;font-size:14px;">
				<a href="mem_user.php?act=showuser&id=<?php echo $this->_var['user_id']; ?>">返回</a>
			</span>
		</div>
		<div class="information" style="margin-top:8px;position:relative;border:1px solid #D7CEC0;height:456px;">
		  <div class='title2'style="width:100%;position:absolute; top:0px;left:0px; z-index:999;">
		  	<table border=0 width=100% style="text-align:center;font-size:14px;font-weight:bolder">
		  		<tr style="height:38px; line-height:35px;">
		  			<td width="25%">日期时间</td>
		  			<td width="15%">操作</td>
		  			<td width="20%">金额(元)</td>
		  			<td width="40%">备注</td>
		  		</tr>
		  	</table>
		  </div>
		  <div style="height:414px;width:100%;overflow-y:auto; overflow-x:hidden;margin-top:42px;float:left;">
		  <?php if ($this->_var['nolist'] != 1): ?>
			<table border="0" cellpadding="3" cellspacing="1" style="width:99%;text-align:center; background:#ddd;margin:0px 5px 2px 1px;">
			  <?php $_from = $this->_var['record']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['value']):
?>
			  <tr style="background:#FFFFF5">
			    <td width="25%"><?php echo $this->_var['value']['change_time']; ?></td>
			    <td width="15%"><?php if ($this->_var['value']['change_type'] == 2): ?>充值<?php endif; ?><?php if ($this->_var['value']['change_type'] == 1): ?>消费<?php endif; ?><?php if ($this->_var['value']['change_type'] == 3): ?>退款<?php endif; ?></td>
			    <td width="20%" style="text-align:left;text-indent:30px;"><?php if ($this->_var['value']['change_type'] == 1): ?><font style="color:red"><?php echo $this->_var['value']['user_money']; ?></font><?php else: ?>+<?php echo $this->_var['value']['user_money']; ?><?php endif; ?>元</td>
			    <td width="40%" style="text-align:left;text-indent:30px;"><?php echo $this->_var['value']['short_change_desc']; ?></td>
			  </tr>
			  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</table>
			<?php else: ?>
			没有相关记录
			<?php endif; ?>
		  </div>	
		</div>
	  </div>	  
	  </div>
<?php elseif ($this->_var['step'] == charge): ?>
<div class="mcard">
	  		<div style="height:250px;width:635px;border:1px solid #D7CEC0">
	  		<form action="moneycard.php?act=chargecard_do&uid=<?php echo $this->_var['id']; ?>" name="myForm" id="myForm" method="post">
	  			<table border="0" width="100%" style="margin-top:20px;">
				  <tr>
				    <td width="30%" style="text-align:right; font-weight:bolder;font-size:14px;height:25px;line-height:25px;vertical-align:middle;">卡号：<br/><br/></td>
				    <td width="70%" style="color:#ddd;height:25px;line-height:25px;vertical-align:middle;">
				    	<input type="text" name="card_num1" id="card_num1" onfocus="clear_message(this);" onkeyup="javascript:if(this.value.length==4) this.nextSibling.nextSibling.focus();" maxlength="4" style="text-align:center;width:55px;"/>-
				    	<input type="text" name="card_num2" id="card_num2" onfocus="clear_message(this);" onkeyup="javascript:if(this.value.length==4) this.nextSibling.nextSibling.focus();" maxlength="4" style="text-align:center;width:55px;"/>&nbsp;-
				    	<input type="text" name="card_num3" id="card_num3" onfocus="clear_message(this);" onkeyup="javascript:if(this.value.length==4) this.nextSibling.nextSibling.focus();" maxlength="4" style="text-align:center;width:55px;"/>-
				    	<input type="text" name="card_num4" id="card_num4" onfocus="clear_message(this);" maxlength="4" style="text-align:center;width:55px;"/> 
				    	<input type="hidden" name="card_num" id="card_num" value=""/>
				    	<br/><span style="color:red;margin-top:3px;float:left" id="check_card_num">&nbsp;</span>
				    </td>
				  </tr>
				  <tr>
				    <td width="30%" style="text-align:right;font-weight:bolder;font-size:14px;height:25px;line-height:25px;vertical-align:middle;">密码：<br/><br/></td>
				    <td width="70%" style="color:#ddd;height:25px;line-height:25px;vertical-align:middle;">
				    	<input type="password" name="card_password" onfocus="clear_message(this);" id="card_password" style="width:255px;"/><br/>
				    	<span style="color:red;margin-top:3px;float:left" id="check_card">&nbsp;</span>
				    </td>
				  </tr>				  
				  <tr height="25px">
				    <td width="30%"></td>
				    <td width="70%" style="color:#ddd;text-indent:70px;">
				    	<input type="button" id="submits" name="submits" value="确认充值" onclick="chargecardCheck();" />
				    </td>
				  </tr>				  
				</table>
				</form>
	  		</div>	
	  			
	  	</div>
<?php endif; ?>
<script type="text/javascript">
function exchange(val){
	var uid=document.getElementById('uid').value;//alert(uid);
	var a=document.getElementById(val);
	a.style.backgroundColor="#dc7865";
	var act='';
	if(val=='cz'){
		document.getElementById('ckjl').style.backgroundColor="#ffffff";
		act="charge";
		}
	else if(val=='ckjl'){
		document.getElementById('cz').style.backgroundColor="#ffffff";
		act="list";
		}
	var url = "moneycard.php?act="+act+"&id="+uid;
    $.ajax({
        type: "Get",
        dataType: "json",
        cache: false,
        url: url,
        data: "",
        success: function(json) {
            region.provinceJson(json);
        }
    });
}
</script>
</body>
</html>
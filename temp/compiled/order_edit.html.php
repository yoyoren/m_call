<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>MES<?php if ($this->_var['ur_here']): ?> - <?php echo $this->_var['ur_here']; ?> <?php endif; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />
<?php echo $this->smarty_insert_scripts(array('files'=>'jquery-1.7.min.js,jquery.goods.js,orderFee.js,datepicker/WdatePicker.js,Address.js,useBonus.js,jquery.autocomplete.js')); ?>
<script>
	function validate()
	{
		if(theForm.best_time.value=="")
		{
			$("#msg").html("送货日期不能为空");
			return false;
		} else if($("#minute").val()=='0'){//时间验证
			$("#msg").html("请输入选择时间");
			return false;
		}else if(theForm.address.value==""){
			$("#msg").html("送货地址不能为空");
			return false;
		}else if($("#textspaicl").val()=='请输入原因' && ($("#order_status").val()=="2" || $("#order_status").val()==4)){
			$("#msg").html("请输入原因");
			return false;
		}else if(theForm.consignee.value==""){
			$("#msg").html("送货信息不能为空");
			return false;
		}else if(theForm.mobile.value.length>0){
			if(!(/^1[3-4]\d{9}$/.test(theForm.mobile.value)||(/^15[0-35-9]\d{8}$/.test(theForm.mobile.value))|| (/^18[0-9]\d{8}$/.test(theForm.mobile.value)))){
			$("#msg").html("手机号码不正确");
			return false;
			}
		}
		
		//日期判断
		var shipping_time=theForm.best_time.value +"-"+theForm.hours.value +"-"+ theForm.minute.value+"-00";
		var date1 = shipping_time.split("-");
		var myDate1 = new Date(date1[0],date1[1]-1,date1[2],date1[3],date1[4],0,0);
		var diff=Math.floor((myDate1.getTime()-new Date().getTime())/(1000*60*60));


		
		 //支付方式验证
		 if(!checkpay()) return false;
	 	
		//异地验证
		if($("#pay_pway").val()=="1")
		{
			if($("#balanceaddress").val()=="")
			{
				$("#msg").html("请输入结款地址");
				return false;
			}
		}
		if($("#caketable tr").length==1)
		{
			$("#msg").html("请选择物品");
				return false;
		}
		return true;
	}
	function showNote()
	{
		$("#user_note").show();
	}
	function checkpay()
	{
		var flag=true;
	    if(theForm.pay_pway.value=="1"||theForm.pay_pway.value=="4")
		{
			
			if(orderFee.unPayed){
				if($("#pay input[type='checkbox']:checked").length>0)
				{	
					$("#pay input[type='checkbox']:checked").each(function(i){
						
						  if($("#pay input[type='checkbox']:checked").next().eq(i).val()=="")
						  {
							  $("#msg").html("请输入支付金额");
							   flag=false;
								return false;
						  }
						
					});	
				}else{
					 $("#msg").html("请选择支付方式");
					flag=false;
				}
			}
			
		}else{
			if(theForm.pay_note.value=="")
			{
				 $("#msg").html("请选择支付方式");
					flag=false;
			}
		}
	    if(theForm.pay_pway.value=="5" && theForm.order_id.value.charAt(1)!='c')
	    {
		    $("#msg").html("请选择大客户以外的支付方式");
		    flag=false;
		}
		return flag;
	}
	function showbouns(){
		$("#usebonus").show();
	}
	function tijiao()
	{
		theForm.submit();
	}
	function unlock()
	{
		var tijiao=document.getElementById('btnSubmit');
		tijiao.disabled="";
		var unlock=document.getElementById('btnunlock');
		//unlock.disabled="disabled";
		unlock.style.display="none"; 
		var tip=document.getElementById('tip');
		tip.innerHTML='订单已解锁';
	}
</script>
</head>
<body>
<div class="main-div">
<form name="theForm" method="post" action="order.php?act=update&order_id=<?php echo $this->_var['order']['order_id']; ?>" enctype="multipart/form-data" onsubmit="return validate();">
    <div class="user_info">
	  <div class="bgyellowgreenimg">
	      <input id="pointbefore" type="hidden" value="<?php echo $this->_var['user']['pay_points']; ?>" name="pointbefore"/>
		  单号&nbsp;<?php echo $this->_var['order']['order_sn']; ?><label class="blank10"></label><input type="hidden" name="user_id" id="user_id" value="<?php echo $this->_var['user']['user_id']; ?>" />
		  订货人:<input type="text" value="<?php echo $this->_var['order']['orderman']; ?>" name="orderman" style="width:70px" />&nbsp;
		  手机号:<input type="text" value="<?php echo $this->_var['order']['ordermobile']; ?>" name="ordermobile" style="width:80px" maxlength="13"/>
		  电话:<input type="text" value="<?php echo $this->_var['order']['ordertel']; ?>" name="ordertel" style="width:80px"/>&nbsp;&nbsp;
		  积分:<?php echo $this->_var['user']['pay_points']; ?>&nbsp;
		  备注：<?php if ($this->_var['user']['back_info']): ?><input type="button" value="显示" onclick="showNote();" /><?php else: ?>无<?php endif; ?>状态&nbsp;<select name="order_status" id="order_status">
          	  <?php echo $this->html_options(array('options'=>$this->_var['order_status'],'selected'=>$this->_var['order']['order_status'])); ?>
		       </select>
           状态&nbsp;<select name="pay_status" id="pay_status">
          	  <option value="0"<?php if ($this->_var['order']['pay_status'] == '0'): ?>selected<?php endif; ?>>未支付</option>
          	  <option value="2"<?php if ($this->_var['order']['pay_status'] == '2'): ?>selected<?php endif; ?>>已支付</option>
		       </select>
        </div>
	  </div>
      <span id="user_note" style="display:none"><?php echo $this->_var['user']['back_info']; ?></span><font color="#FF0000" id="tip"><?php echo $this->_var['tip']; ?></font>
	<div class="blank2"></div>
    <p style="margin-top:5px;"><select name="goods" id="goodsel">
	                <option value="">选择商品</option>
                    <?php echo $this->html_options(array('options'=>$this->_var['sale_goods'])); ?>
		          </select><span class="fontred">*</span>
		          &nbsp;&nbsp;
                  <select id="weightsel">
				  <option value=0>选规格</option>
                  </select>
				  <select id="goodcount" style="height:20px">
                  <option selected="selected">1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                  <option>6</option>
                  <option>7</option>
                  <option>8</option>
                  <option>9</option>
                  <option>10</option>
                  </select>&nbsp;个&nbsp;原价:<span id="price">0.0</span>
				  &nbsp;
				  折扣:<select name="dissel" id="dissel">
                      <?php echo $this->html_options(array('options'=>$this->_var['discount_info'],'selected'=>'1')); ?>
					  </select>
                      <span class="fontred">*</span>&nbsp;&nbsp; 
                  折后金额:<span id="deprice">0.0</span> &nbsp;&nbsp;&nbsp;&nbsp;     
        <input type="button" id="addcake"  value="添加" class="" />
        <input type="button" id="updatecake"  value="更新" class="" />
        <input type="button" id="cencelcake"  value="取消" class="" />
      </p>
	  <table width="100%" border="1">
         <tr>
            <td width="791" height="103" valign="top"> 
				  <table border="0" cellpadding="0" cellspacing="1" id="caketable">
				   <tr class="bglimeimg">
					<th align="center" width="160">商品名称</th>
					<th align="left" width="40">规格</th>
					<th align="center" width="40">单价</th>
					<th align="center" width="30">数量 </th>
					<th align="left" width="40">原价</th>
					<th align="left" width="50">折扣</th>
					<th align="left" width="40">金额</th>
					<th align="left" width="200">生日牌</th>
					<th align="left" width="100">操作</th>
				  </tr>
				  <?php $_from = $this->_var['order_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['v']):
?>
				   <tr class="fontred" id="<?php echo $this->_var['v']['trname']; ?>" >
					<td height="25" class="fontred"><span  style="font-weight:bold"><?php echo $this->_var['v']['goods_name']; ?></span></td>
					<td height="25" class="fontred" ><span><?php echo $this->_var['v']['goods_attr']; ?></span></td>
					<td height="25" class="fontred" align="center"><span><?php echo $this->_var['v']['goods_price']; ?></span></td>
					<td height="25" class="fontred" align="center"><span><?php echo $this->_var['v']['goods_number']; ?></span></td>
					<td height="25" class="fontred" align="center"><span><?php echo $this->_var['v']['goods_sumprice']; ?></span></td>
					<td height="25" class="fontred" ><span><?php echo $this->_var['v']['discount_name']; ?></span></td>
					<td height="25" class="fontred"><span><?php echo $this->_var['v']['discount_price']; ?></span></td>
				   <td height="25" class="fontred"><input name="goods[]" class="pricehide" id="goods" type="hidden" value="<?php echo $this->_var['v']['value']; ?>" />
				    <?php if (! $this->_var['v']['is_card']): ?>
					<select name="cards[]" id="<?php echo $this->_var['v']['card']; ?>" value="<?php echo $this->_var['v']['cards']; ?>">
					<option value="无" <?php if ($this->_var['v']['cards'] == '无'): ?> selected="selected" <?php endif; ?>>无</option>
					<option value="中文" <?php if ($this->_var['v']['cards'] == '中文'): ?> selected="selected" <?php endif; ?>>中文</option>
					<option value="英文" <?php if ($this->_var['v']['cards'] == '英文'): ?> selected="selected" <?php endif; ?>>英文</option>
					<option value="其它" <?php if ($this->_var['v']['cards'] == '其它'): ?> selected="selected" <?php endif; ?>>其它</option>
					</select>
					<input type='text' id='<?php echo $this->_var['v']['msg']; ?>' name='msgs[]' value="<?php echo $this->_var['v']['cardm']; ?>"  size="12"
					<?php if ($this->_var['card_name'] [ $this->_var['k'] ] != '其它'): ?>
					style='display:none;border-color:red;'
					<?php endif; ?> />
					<?php else: ?>
					<?php if ($this->_var['v']['no_card']): ?>
					<input type="hidden" name="cards[]" value="" />
					<input type='hidden'  name='msgs[]' value="" />
					<?php endif; ?>
					<?php endif; ?>
					</td>
					<td height="25" class="fontred">
					 &nbsp;<input type="button" value="移除"  class="bgred" onclick="good.delgood(this);"/></td>
				  </tr>
				  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				 </table>
	        </td>
            <td width="187" rowspan="2" valign="top">	  
	           <table border="0" cellpadding="0" cellspacing="1" width="100%">
                  <tr class="bggrn2">
                   <th align="left" width="180">生产提示</th>
                  </tr>
	              <tr><td><textarea name="scts" rows="3"><?php echo $this->_var['order']['scts']; ?></textarea></td></tr>
               </table>
			</td>
         </tr>
         <tr>
            <th>
				<label class="blank30"></label>餐具<input type="hidden" value='<?php echo $this->_var['freecanju']; ?>' id="freecanju" />
				<input name="canju" id="canju" type="text" value="<?php echo $this->_var['canju']; ?>" style="width:20px;color:red;text-align:center;"  />
				<span id='pay_canju'><?php echo $this->_var['pay_canju']; ?></span>
				<label class="blank30"></label>蜡烛
				<input name="candle" id="candle" type="text" value="<?php echo $this->_var['candle']; ?>" style="width:20px;color:red;text-align:center;" />
				<span id='pay_candle'><?php echo $this->_var['pay_candle']; ?></span>&nbsp;生日牌<?php echo $this->_var['order']['card_name']; ?>&nbsp;&nbsp;<?php echo $this->_var['order']['card_message']; ?>
			    <div class="hdr" id="goods_msg" style="text-align:center;color:red"></div>
			</th>
         </tr>
      </table>	  
    <div class="blank2"></div>
	<div class="bgyellowgreen">
	     送货时间:<input type="text"  id="best_time" name="best_time" size="10" value="<?php echo $this->_var['order']['date']; ?>" style="width:70px;" onclick="javascript:WdatePicker({minDate:'%y-%M-{%d}'})" />         日<select name="hours" id="hours">
			<option value="08" <?php if ($this->_var['order']['hours'] == '08'): ?>selected<?php endif; ?>>08</option>
			<option value="09" <?php if ($this->_var['order']['hours'] == '09'): ?>selected<?php endif; ?>>09</option>
			<?php echo $this->html_options(array('values'=>$this->_var['hours'],'selected'=>$this->_var['order']['hours'],'output'=>$this->_var['hours'])); ?>
			</select>点
			<select name="minute" id="minute">
            	<?php echo $this->html_options(array('values'=>$this->_var['minute'],'selected'=>$this->_var['order']['minute'],'output'=>$this->_var['minute'])); ?>
			</select>分&nbsp;&nbsp;
			第<input type="text" name="turn" id="turn" style="width:10px;" value="<?php echo $this->_var['order']['route_id']; ?>"/>批
			<label class="blank10"></label>
		    <select id="seladdr" name="consignee_address" style="width:450px;">
			<option value="">请选择历史地址</option>
			<?php $_from = $this->_var['consignee_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'con');if (count($_from)):
    foreach ($_from AS $this->_var['con']):
?>
			<option value="<?php echo $this->_var['con']['address_id']; ?>"><?php echo $this->_var['con']['consignee']; ?>-<?php echo $this->_var['con']['address']; ?></option>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</select>
			<label class="blank30"></label>路区:<input type="text" id="route" name="route" value="<?php echo $this->_var['order']['route_code']; ?>" style="width:30px;" readonly />
	  </div>
      <table width="100%" border="0" cellspacing="5" cellpadding="0" class="tablenone" id="addre">
          <tr >
            <td colspan="3" width="750">收货地址:<select name="country" id="country">
					<option value="441" <?php if ($this->_var['order']['country'] == '441'): ?>selected<?php endif; ?>>北京</option>
            		</select>
              <select id="province" name="province">
					<option value="">选择位置</option>
                    <?php echo $this->html_options(array('options'=>$this->_var['province_list'],'selected'=>'501')); ?>
				</select>		  
			  </select>
              <select id="city" name="city">
                  
				  <?php if ($this->_var['order']['qu']): ?>
							<option value="<?php echo $this->_var['order']['city']; ?>"><?php echo $this->_var['order']['qu']; ?></option>
						<?php else: ?>
							<option value="0">选择位置</option>
						<?php endif; ?>
						<option value="0">选择位置</option>
                  <?php echo $this->html_options(array('options'=>$this->_var['city_list'])); ?>
			
            </select>
              <select id="district" name="district">
                  <option>选择区域</option>
              </select>
            <input id="address"  name="address" type="text" style="width:350px" value="<?php echo $this->_var['order']['address']; ?>" /><span class="fontred">*</span>
            <input  type="hidden" id="route_id" name="routeid" value="<?php echo $this->_var['order']['route_id']; ?>"/> 
            </td>
			<td rowspan="2" align="left" width="250">外送提示<textarea name="textsendinfo" rows="3"><?php echo $this->_var['order']['wsts']; ?></textarea></td>
          </tr>
		  <tr><td colspan="3">收货人:<input type="text" id="consignee" name="consignee" size="10" style="width:70px;" value="<?php echo $this->_var['order']['consignee']; ?>"/><label class="blank10"></label>
		      手机:<input type="text" name="mobile" id="mobile" size="13" maxlength="11" style="width:80px;" value="<?php echo $this->_var['order']['mobile']; ?>" /><label class="blank10"></label>
			  座机:<input type="text" name="tel" id="tel" size="13" maxlength="13" value="<?php echo $this->_var['order']['tel']; ?>" style="width:100px;" /><label class="blank10"></label>
			  
			  </td>
		  </tr>
           <tr> 
            <td colspan="3"  id="yijie" <?php if ($this->_var['order']['pay_id'] != 1): ?>style="display:none;"<?php endif; ?>>
              结款地址:<input id="balanceaddress"  name="balanceaddress" type="text" style="width:460px" value="<?php echo $this->_var['order']['money_address']; ?>" /><span class="fontred">*</span>
            </td>
          </tr>
    </table>		 
    <input type="hidden" value="K金" id="integral" name="pay_note1[]" ><input type="hidden"  value="<?php echo $this->_var['order']['integral']; ?>" name="way1[]"  />
    <input type="hidden" value="代金卡" id="bonus"  name="pay_note1[]" ><input type="hidden"  value="<?php echo $this->_var['order']['bonus']; ?>" name="way1[]"  /></span>
	<div class="bgyellowgreen">
	   <span class="left"  style="width:80px;"><select name="pay_pway" id="pay_pway"><?php if ($this->_var['order']['pay_id'] != 2 && $this->_var['order']['pay_id'] != 3): ?><?php echo $this->html_options(array('options'=>$this->_var['pway'],'selected'=>$this->_var['order']['pay_id'])); ?><?php else: ?><?php if ($this->_var['order']['pay_id'] == 2): ?><option selected="" value="2">线上支付宝</option><?php endif; ?><?php if ($this->_var['order']['pay_id'] == 3): ?><option value="3">线上快钱</option><?php endif; ?><?php endif; ?>
       </select></span>
	   <span class="left blank10" id="pay"><?php echo $this->_var['p']; ?></span>
	   <span class="left blank20" id="usebonus">
	       <!--代金卡<input name="bonus_cardnum" id="bonus_cardnum" type="text" value="" size="16"  />-->
		  <br />现金券<input name="bonus_sn" id="bonus_sn" type="text" value="" size="16" />
		  <img id="usecardimg" src="images/gif-0965.gif" width="18" height="16" /><span id="bmsg" style="color:red"></span>
       </span>
	   <span class="left" id="binfo">
	       <table id="bouns_info" width="220px"><tbody>
	         <?php $_from = $this->_var['bonus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
              <tr id="<?php echo $this->_var['v']['trname']; ?>"><td>券号:<?php echo $this->_var['v']['bonus_sn']; ?>,金额:<?php echo $this->_var['v']['type_money']; ?><input type='button' value='删除' onclick="delbonus('#<?php echo $this->_var['v']['trname']; ?>')">
			  <input type='hidden' value='<?php echo $this->_var['v']['value']; ?>' class='hidebonus' name='bonus[]'></td></tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	       </tbody></table></span>
	   <span class="left">特殊情况<textarea name="remark" id="remark" rows="3" cols="20" ><?php echo $this->_var['order']['to_buyer']; ?></textarea></span>
	</div>
	<div class="bggray left" style="width:1000px;">
	发票抬头:<input name="inv_payee" id="selectfapiao" type="text" value="<?php echo $this->_var['order']['inv_payee']; ?>" style="width:240px" />+&nbsp;&nbsp;
	项目:<select name="inv_content">
                  <option <?php if ($this->_var['order']['inv_content'] == ""): ?> selected="selected"<?php endif; ?>></option>
                  <option <?php if ($this->_var['order']['inv_content'] == "食品"): ?> selected="selected"<?php endif; ?>>食品</option>
                  <option <?php if ($this->_var['order']['inv_content'] == "蛋糕"): ?> selected="selected"<?php endif; ?>>蛋糕</option>
                  <option<?php if ($this->_var['order']['inv_content'] == ""): ?>selected="selected"<?php endif; ?>></option>  
              </select>
	</div>
	<?php if ($this->_var['order']['postscript']): ?>
 	<div class="payment left" style="width:1000px;"><font color="#FF0000">订单附言:<?php echo $this->_var['order']['postscript']; ?></font></div> 
	<?php endif; ?>  
 <table width="1000px" border="1" cellpadding="0" cellspacing="1" class="tablemargin center table_02" id="pricesumtb">
    <tr align="center">
      <td width="70px" rowspan="2" align="left" class="bgyellowgreen">结算信息</td>
      <td width="80px" class="bgyellowgreenimg">折后蛋糕费</td>
      <td width="60px" class="bgyellowgreenimg">附件费</td>
      <td width="60px" class="bgyellowgreenimg">异结费</td>
      <td width="60px" class="bgyellowgreenimg">配送费</td>
      <td width="65px" class="bgyellowgreenimg">订单总额</td>
      <td rowspan="2" class="bgyellowgreen" width="65px">付费信息</td>
      <td width="65px"  class="bgyellowgreenimg">已付金额</td>
      <td width="60px"  class="bgyellowgreenimg">现金券</td>
      <td width="70px"  class="bgyellowgreenimg">积分兑换</td>
      <td width="55px"  class="bgyellowgreenimg">折扣额</td>
      <td width="55px"  class="bgyellowgreenimg">礼金卡/大客户</td>
      <td width="" class="bgyellowgreenimg">应收</td>
	  <td rowspan="2" width="200px;"><textarea name="textspaicl" id="textspaicl" rows="3"><?php echo $this->_var['order']['referer']; ?></textarea></td>
    </tr>
    <tr align="center">
	  
      <td><span><?php echo $this->_var['order']['goods_amount']; ?></span><input type="hidden" id="hidegoodssum" value="<?php echo $this->_var['order']['goods_amount']; ?>" name="hidegoodssum" /></td>
      <td><span><?php echo $this->_var['order']['attr_amount']; ?></span><input type="hidden" value="<?php echo $this->_var['order']['attr_amount']; ?>" id="append" name="append" /></td>
      <td><span><?php echo $this->_var['order']['pay_fee']; ?></span></td>
	  <td><input name="shipping_fee" id="shipfee" type="text" class="fontred fontbig" value="<?php echo $this->_var['order']['shipping_fee']; ?>"  style="width:30px" /></td>
      <td><span style="font-weight:bold;"><?php echo $this->_var['order']['amount']; ?></span></td>
      
	  <td><input id="money_paid" name="money_paid" type="text" class="fontred fontbig" value="<?php echo $this->_var['order']['money_paid']; ?>"  style="width:30px" /></td>
	  <td><span><?php echo $this->_var['order']['bonus']; ?></span><input type="hidden" value="<?php echo $this->_var['order']['bonus']; ?>" id="mbonus" name="mbonus" /></td>
	  <td><span><?php echo $this->_var['order']['integral']; ?></span><input type="hidden" value="<?php echo $this->_var['order']['integral']; ?>" id="mintegral" name="integral" /></td>
	  <td><input id="discount" name="discount" type="text" class="fontred fontbig" value="<?php echo $this->_var['order']['discount']; ?>"  style="width:60px" /></td>
	  <td><span><?php echo $this->_var['order']['surplus']; ?></span><input type="hidden" value="<?php echo $this->_var['order']['surplus']; ?>" id="surplus" name="surplus" /></td>
	  <td><span style="font-weight:bold;">0</span></td>
    </tr>
  </table>
  <p align="right"><font style="float:left">下单客服：<?php echo $this->_var['order']['kfgh']; ?>&nbsp;&nbsp;下单时间：<?php echo $this->_var['order']['add_date']; ?></font>
  <span id="msg" align="left" style="color:red" >&nbsp;</span>
      <input id="btnSubmit" type="submit" class="" style="margin-left:40px" <?php if ($this->_var['tip']): ?> disabled="disabled"<?php endif; ?> value="确定修改" />
	 <?php if ($this->_var['tip']): ?><input id="btnunlock" type="button" class="" onclick="unlock();" style="margin-left:40px" value="解锁" /><?php endif; ?>
     <input id="btnback" type="button" class="" onclick="window.close()" style="margin-left:40px" value="关闭" />
   </p>
<input type="hidden" name="fore_goods" value="<?php echo $this->_var['fore_goods']; ?>" />
<input type="hidden" name="fore_fujian" value="<?php echo $this->_var['order']['attr_amount']; ?>,<?php echo $this->_var['candle']; ?>" />
<input type="hidden" name="fore_time" value="<?php echo $this->_var['order']['best_time']; ?>" />
<input type="hidden" name="fore_addr" value="<?php echo $this->_var['order']['address']; ?>" />
<input type="hidden" name="fore_contect" value="<?php echo $this->_var['order']['orderman']; ?><?php echo $this->_var['order']['consignee']; ?><?php echo $this->_var['order']['mobile']; ?><?php echo $this->_var['order']['tel']; ?>" />
<input type="hidden" name="fore_status" value="<?php echo $this->_var['order']['order_status']; ?>" />
<input type="hidden" name="fore_payment" value="<?php echo $this->_var['order']['pay_name']; ?><?php echo $this->_var['order']['pay_note']; ?>" />
<input type="hidden" name="fore_inv" value="<?php echo $this->_var['order']['inv_content']; ?><?php echo $this->_var['order']['inv_payee']; ?>" />
<input type="hidden" name="fore_bonus" value="<?php echo $this->_var['order']['bonus_str']; ?>" />
<input type="hidden" name="fore_integral" value="<?php echo $this->_var['order']['integral']; ?>" />
<input type="hidden" name="fore_surplus" value="<?php echo $this->_var['order']['surplus']; ?>" />
<input type="hidden" name="fore_province" value="<?php echo $this->_var['order']['province']; ?>" />
<input type="hidden" name="fore_city" value="<?php echo $this->_var['order']['city']; ?>" />
<input type="hidden" name="fore_district" value="<?php echo $this->_var['order']['district']; ?>" />
<input type="hidden" name="order_id" value="<?php echo $this->_var['order']['order_sn']; ?>"/>
</form>
<p ></p>
</div>
</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>MES<?php if ($this->_var['ur_here']): ?> - <?php echo $this->_var['ur_here']; ?> <?php endif; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />
<?php echo $this->smarty_insert_scripts(array('files'=>'jquery-1.7.min.js,jquery.goods.js,orderFee.js,datepicker/WdatePicker.js,Address.js,useBonus.js,jquery.autocomplete.js,check_from.js')); ?>
</head>
<body>
<div class="main-div">
<form name="theForm" method="post" action="order.php?act=done" enctype="multipart/form-data" onsubmit="return validate();">
    <div class="user_info">
	  <div class="bgyellowgreenimg">
	     <input id="pointbefore" type="hidden" value="<?php echo $this->_var['user']['pay_points']; ?>" name="pointbefore"/>
		  <div><input type="hidden" name="user_id" id="user_id" value="<?php echo $this->_var['user']['user_id']; ?>" />
		  <input type="hidden" name="user_money" id="user_money" value="<?php echo $this->_var['user']['user_money']; ?>" />
		  订货人:<input type="text" value="<?php echo $this->_var['user']['rea_name']; ?>" name="rea_name" style="width:70px" />&nbsp;
		  手机号:<input type="text" value="<?php echo $this->_var['user']['mobile_phone']; ?>" name="user_mobile" style="width:80px" maxlength="13"/>
		  其他电话:<input type="text" value="<?php echo $this->_var['user']['office_phone']; ?>" name="user_tel" style="width:80px"/>&nbsp;&nbsp;余额:<?php echo $this->_var['user']['user_money']; ?> 
		  <label class="blank30"></label>订单备注：<?php if ($this->_var['user']['back_info']): ?><input type="button" value="显示备注" onclick="showNote();" /><?php else: ?>无<?php endif; ?>
		  <label class="blank30"></label>订单状态&nbsp;<select name="order_status">
          	   <option value="0">未确认</option>
		       <option value="1" selected="selected">已确认</option>
		       </select>
        </div>
	  </div>
      <span id="user_note" style="display:none"><?php echo $this->_var['user']['back_info']; ?></span>
	</div>
	<div class="blank2"></div>
    <p style="margin-top:5px;"><select name="goods" id="goodsel">
	                <option value="">选择商品</option>
                    <?php echo $this->html_options(array('options'=>$this->_var['sale_goods'])); ?>
		          </select><span class="fontred">*</span>
		          &nbsp;&nbsp;规格:
                  <select id="weightsel">
				  <option value=0>选规格</option>
                  </select>&nbsp;&nbsp;数量:
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
                  </select>&nbsp;个&nbsp;
				  原价:<span id="price">0.0</span>
				  折扣:<select name="dissel" id="dissel">
                      <?php echo $this->html_options(array('options'=>$this->_var['discount_info'],'selected'=>'100')); ?>
					  </select>
                      <span class="fontred">*</span>&nbsp;&nbsp; 
                  折后金额:<span id="deprice">0.0</span> &nbsp;&nbsp;&nbsp;&nbsp;  
        <input type="button" id="addcake"  value="添加" class="bggrey" />
        <input type="button" id="updatecake"  value="更新" class="bggrey" />
        <input type="button" id="cencelcake"  value="取消" class="bggrey" />
      </p>
      <table width="100%" border="1">
         <tr>
            <td width="791" height="103" valign="top">      
	           <table border="0" cellpadding="0" cellspacing="1" id="caketable">
                   <tr class="bggrn2">
					  <th align="center" width="160">商品名称</th>
					  <th align="left" width="40">规格</th>
					  <th align="center" width="40">单价</th>
					  <th align="center" width="30">数量 </th>
					  <th align="center" width="40">原价</th>
					  <th align="left" width="50">折扣</th>
					  <th align="center" width="40">金额</th>
					  <th align="left" width="200">生日牌</th>
					  <th align="left" width="100">操作</th>
                   </tr>
	           </table>
	        </td>
            <td width="187" rowspan="2" valign="top">	  
	           <table border="0" cellpadding="0" cellspacing="1" width="100%">
                  <tr class="bggrn2">
                   <th align="left" width="180">生产提示</th>
                  </tr>
	              <tr><td><textarea name="scts" rows="3"></textarea></td></tr>
               </table>
			</td>
         </tr>
         <tr>
            <th>
			 <label class="blank10"></label>餐具<input type="hidden" value='0' id="freecanju" />
              <input name="canju" id="canju" type="text" value="0" style="width:20px;color:red;text-align:center;"  />
              <span id='pay_canju' class="blank10" style="width:30px;"></span>
		      <label class="blank10"></label>蜡烛
              <input name="candle" id="candle" type="text" value="0" style="width:20px;color:red;text-align:center;" />
              <span id='pay_candle' class="blank10" style="width:30px;"></span>
		      <div id="goods_msg" style="text-align:center;color:red"></div>
			</t>
         </tr>
      </table>	  
    <div class="blank2"></div>
	<div class="bgyellowgreen">
	      送货时间:<input type="text"  id="best_time" name="best_time" size="10" style="width:70px;" onclick="javascript:WdatePicker({minDate:'%y-%M-{%d}'})" value="<?php echo $this->_var['btime']; ?>" />
		  日	<select name="hours" id="hours">
			<option value="08">08</option>
			<option value="09">09</option>
			<?php echo $this->html_options(array('output'=>$this->_var['hours'],'values'=>$this->_var['hours'],'selected'=>$this->_var['hour'])); ?>
			</select>点
			<select name="minute" id="minute">
				<option value="00" <?php if ($this->_var['m'] == "00"): ?>selected<?php endif; ?>>00</option>
				<option value="30" <?php if ($this->_var['m'] == "30"): ?>selected<?php endif; ?>>30</option>
			</select>分&nbsp;&nbsp;
			第<input type="text" name="turn" id="turn" readonly style="width:10px;" />批
			<label class="blank10"></label>
		    <select id="seladdr" name="consignee_address" style="width:450px;">
			<option value="">请选择历史地址</option>
			<?php $_from = $this->_var['consignee_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'con');if (count($_from)):
    foreach ($_from AS $this->_var['con']):
?>
			<option value="<?php echo $this->_var['con']['address_id']; ?>"><?php echo $this->_var['con']['consignee']; ?>-<?php echo $this->_var['con']['address']; ?></option>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</select>
			<label class="blank30"></label>路区:<input type="text" id="route" name="route" style="width:50px;" readonly />
	  </div>
      <table width="100%" border="0" cellspacing="5" cellpadding="0" class="bggray" id="addre">
          <tr class="bggray">
            <td colspan="3" width="750">地址:<select name="country" id="country">
					<option value="441" <?php if ($this->_var['user']['province'] == '441'): ?>selected<?php endif; ?>>北京</option>
					
                    </select>
              <select id="province" name="province">
					<option value="">选择环线</option>
                    <?php echo $this->html_options(array('options'=>$this->_var['province_list'])); ?>
					</select>		  
			  </select>
              <select id="city" name="city">
                  <option value="">选择城区</option>	
              </select>
              <select id="district" name="district">
                  <option>选择区域</option>
              </select>
            <input id="address"  name="address" type="text" style="width:350px"  /><span class="fontred">*</span>
            <input  type="hidden" id="route_id" name="routeid" value="0"/> 
            </td>
			<td rowspan="2" align="left" width="250">外送提示<textarea name="textsendinfo" rows="3"></textarea></td>
          </tr>
		  <tr class="bggray">
		     <td colspan="3">收货人:<input type="text" id="consignee" name="consignee" style="width:70px;" size="10" value="<?php echo $this->_var['user']['rea_name']; ?>"/><label class="blank10"></label>
		      手机:<input type="text" name="mobile" id="mobile" size="13" style="width:80px;" maxlength="11" value="<?php echo $this->_var['user']['mobile_phone']; ?>" /><label class="blank10"></label>
			  座机:<input type="text" name="tel" id="tel" size="13" maxlength="13" style="width:100px;" value="<?php echo $this->_var['user']['office_phone']; ?>" /><label class="blank10"></label>
			  </td>
		  </tr>
          <tr class="bggray">
            <td colspan="4" style="display:none" id="yijie">
              结款地址:<input id="balanceaddress"  name="balanceaddress" type="text" style="width:460px"  /><span class="fontred">*</span>
            </td>
          </tr>
    </table>		  
    <input type="hidden" value="K金" id="integral" name="pay_note1[]" ><input type="hidden"  value="" name="way1[]"  />
    <input type="hidden" value="代金卡" id="bonus" name="pay_note1[]" ><input type="hidden"  value="" name="way1[]"  />
	<div class="bgyellowgreen">
	   <span class="left" style="width:80px;"><select name="pay_pway" id="pay_pway"><?php echo $this->html_options(array('options'=>$this->_var['pway'],'selected'=>'4')); ?></select>
	   <br><input type="checkbox" value="礼金卡" name="ljkway" id="ljkway" >礼金卡<input type="hidden"  value="0" name="ljk"  id="ljk"/><input type="hidden"  value="" name="pay_note_ljk"  id="ljk_note"/></span>
	   <span class="left blank10" id="pay">
	    <input type="checkbox" value="现金" name="pay_note[]" checked>现金<input type="text" style="width:50px;" value="0" name="way[]" class="fontred fontbig" id="xj"/><br />
        <input type="checkbox" value="POS机" name="pay_note[]" >POS机<input type="text" style="width:50px;display:none" value="" name="way[]" class="fontred fontbig" id="posj"/><br />
	   </span>
	   <span class="left blank20" id="usebonus">
	       <!--代金卡<input name="bonus_cardnum" id="bonus_cardnum" type="text" value="" size="16"  /><br />-->
		   <br />现金券号<input name="bonus_sn" id="bonus_sn" type="text" value="" size="16" />
		  <img id="usecardimg" src="images/gif-0965.gif" width="18" height="16" /><span id="bmsg" style="color:red"></span>
       </span>
	   <span class="left" id="binfo"><table id="bouns_info" width="220px"><tbody></tbody></table></span>
	   <span class="left">特殊情况<textarea name="remark" id="remark" rows="3" cols="20" ></textarea></span>
	</div>
	<div class="bggray left" style="width:1000px;">
	发票抬头:<input name="inv_payee" id="selectfapiao" type="text" style="width:240px" />+&nbsp;&nbsp;
	项目:<select name="inv_content" id="inv_content">
            	  <option value=""> </option>
                  <option>食品</option>
                  <option>蛋糕</option>
              </select>
	</div>
 <table width="1000px" border="1" cellpadding="0" cellspacing="1" class="tablemargin center table_02" id="pricesumtb">
    <tr align="center">
      <td width="65px" rowspan="2" align="left" class="bgyellowgreen">结算信息</td>
      <td width="75px" class="bgyellowgreenimg">折后蛋糕费</td>
      <td width="60px" class="bgyellowgreenimg">附件费</td>
      <td width="60px" class="bgyellowgreenimg">异结费</td>
      <td width="60px" class="bgyellowgreenimg">配送费</td>
      <td width="65px" class="bgyellowgreenimg">订单总额</td>
      <td rowspan="2" class="bgyellowgreen" width="65px">付费信息</td>
      <td width="65px"  class="bgyellowgreenimg">已付金额</td>
      <td width="60px"  class="bgyellowgreenimg">现金券</td>
      <td width="70px"  class="bgyellowgreenimg">K金</td>
      <td width="55px"  class="bgyellowgreenimg">折扣额</td>
      <td width="55px"  class="bgyellowgreenimg">储值卡/大客户</td>
      <td width="" class="bgyellowgreenimg">应收</td>
	  <td rowspan="2" width="200px;"><textarea name="textspaicl" id="textspaicl" rows="3"></textarea></td>
    </tr>
    <tr align="center">
	  
      <td><span>0</span><input type="hidden" value="" id="hidegoodssum" name="hidegoodssum" /></td>
      <td><span>0</span><input type="hidden" value="" id="append" name="append" /></td>
      <td><span>0</span></td>
	  <td><input name="shipping_fee" id="shipfee" type="text" class="fontred fontbig" value="0"  style="width:30px" /></td>
      <td><span style="font-weight:bold;">0</span></td>
      
	  <td><input id="money_paid" name="money_paid" type="text" class="fontred fontbig" value="0"  style="width:30px" /></td>
	  <td><span>0</span><input type="hidden" value="" id="mbonus" name="mbonus" /></td>
	  <td><span>0</span><input type="hidden" value="" id="mintegral" name="integral" /></td>
	  <td><input id="discount" name="discount" type="text" class="fontred fontbig" value="0"  style="width:60px" /></td>
	  <td><span>0</span><input type="hidden" value="" id="surplus" name="surplus" /></td>
	  <td><span style="font-weight:bold;">0</span></td>
    </tr>
  </table>
  <input type='hidden' name='timeKey' value='<?php echo $this->_var['timekey']; ?>' />
  <p align="right">
		 <span id="msg" align="left" style="color:red" >&nbsp;</span>
		 <span id="feed" style="color:red;display:none;" >&nbsp;</span>
		 <input type="button" onclick="tijiao()" value="强制下单" class="" type="submit"/>
		 <input id="btnSubmit" type="submit" class="" style="margin-left:40px" value="确定下单" />
   </p>
</form>
</div>
</body>
</html>
<script type="text/javascript">
	function showNote()
	{
		$("#user_note").show();
	}
	var invotops = <?php echo $this->_var['inv']; ?>;
    $.fn.ready(function() {
        $("#selectfapiao").autocomplete(invotops, {minChars: 0, max: 12, autoFill: true}); //发票自动填充初始化
        });	
</script>


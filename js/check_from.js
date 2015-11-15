function validate_null()
	{
		//alert();
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
		}
		else if(theForm.selectfapiao.value != "" && $("#inv_content").val() == ""){
			$("#msg").html("发票项目不能为空");
			return false;
		}else if(theForm.mobile.value.length>0){
			if(!(/^1[3-8]\d{9}$/.test(theForm.mobile.value))){
			$("#msg").html("手机号码不正确");
			return false;
			}
		}

		if($("#pay_pway").val()=="6" && theForm.selectfapiao.value != "" )
		{
				$("#msg").html("免费赠送不能开发票！");
				return false;
		}

    	//return false;
		//if(!check_shipping_time()) return false;
		
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
			if($("#pay_note").val()=="")
			{
				 $("#msg").html("请选择支付方式");
					flag=false;
			}
		}
		return flag;
	}
	function tijiao()
	{
		if(validate_null())
		{
			theForm.submit();
		}
	}
	
	
	function check_shipping_time()
	{
		//日期判断
		var shipping_time=theForm.best_time.value +"-"+theForm.hours.value +"-"+ theForm.minute.value+"-00";
		var date1 = shipping_time.split("-");
		var myDate1 = new Date(date1[0],date1[1]-1,date1[2],date1[3],date1[4],0,0);
		var diff=Math.floor((myDate1.getTime()-new Date().getTime())/(1000*60*60));
		var area=theForm.country.value;
		if(area=="440")
		{
			 if (diff<8)
			 {
				$("#msg").html("请提前8小时订购");
				return false;
			 }
		}else{
			if (diff<5)
			 {
				$("#msg").html("请提前5小时订购");
				return false;
			 }
		}
		 return true;
	}
	
	function in_array(needle, haystack) 
	{
		// 得到needle的类型
		var type = typeof needle;
	
		if(type == "string" || type =="number") 
		{
			for(var i in haystack) 
		   {
				if(haystack[i] == needle) 
			   {
				   return true;
				}
		   }
		}
	   return false;
	}
	function check_cake()
	{
		var flag=true;
		 var goods = $(".pricehide").each(function() {           
            var cake = $(this).val().split(',');
			var area=$("#country").val();
			var goodid=cake[1];
			var goods_attr=cake[2];
			var shipping_time=$("#best_time").val() +"-"+$("#hours").val() +"-"+ $("#minute").val()+"-00";
			var date1 = shipping_time.split("-");
			var myDate1 = new Date(date1[0],date1[1]-1,date1[2],date1[3],date1[4],0,0);
			var diff=Math.floor((myDate1.getTime()-new Date().getTime())/(1000*60*60));
			var one_day_goods="51,52,53,54,56,57,63,59";
			var re = new RegExp("^" + goodid + ",|," + goodid + ",|," + goodid + "$");
			var cake_no_bj = new Array('57','56','59');
			var cake_no_sh = new Array('56','57','59');
			var cake_no_hz = new Array('43','48','55','56','57','58','64','39','59');
			var msg='';
			
				if(diff<24)
				{
				   if(parseInt(goods_attr) > 5 && re.test(one_day_goods) )
				   {
					  msg = "该款蛋糕大磅需提前24小时订购，请您重新选择时间！";
				   }
				   if(goodid == 55 || goodid == 58 || goodid == 86 || goodid == 87)
				   {
					  msg = "该款蛋糕需提前24小时订购，请您重新选择时间！";			   
				   }
				   
				}
				if(in_array(goodid,cake_no_hz) && area == '440' )
				{
					  msg = "杭州暂无该款蛋糕！";
				}
				if(in_array(goodid,cake_no_bj) && area == '441' )
				{
					  msg = "北京暂无该款蛋糕！";
				} 
				if(msg!='')
				{
					$("#msg").html(msg);
					flag=false;
				}
		 });
		 if(!flag) return false;
		 return true;
	}
	function validate()
	{
		if(validate_null()&&check_shipping_time()&&check_cake()) 
		{
			
			return true;
		}
		return false;
	}
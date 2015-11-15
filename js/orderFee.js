var orderFee = {

    pay_name: "#pay_pway", //支付方式选择
    //fee_name: "#pay_note1", //收费方式选择
	feetypesel: "#pay_note",
    sumtable: "#pricesumtb", //汇总表。
    canjucount: "#canju", //餐具数
    candlecount: "#candle", //蜡烛数
    cardusehide: "#usegiftcard",  //代金卡使用信息
    balanceaddr: "#balanceaddr",  //异结地址
	balancetr: "#yijie",
    hidegoodsum: "#hidegoodssum", //商品费用保存
    pointaddtext: "#pointadd",
    pointspan: "#pointspan",
    pointbeforeSel: "#pointbefore", //当前积分隐藏域
    txtshipfee: "#shipfee",
    txtpaid: "#moneypaid",
    txtdiscount: "#discount",
    surplus:"#surplus",
	
    pointAdd: 0, 
    pointdis: 0, 
    userPoint: 0, 

    goodsAmount: 0,   
    appendAmount: 0,
	attr_amount: 0,    
    payFee: 0,     
    shippingFee: 0,            
    usedPoint: 0,
    usedBonus: 0,
	moneyPaid: 0,
	discount: 0,
	orderAmount: 0,
	unPayed: 0,
	usedsurplus:0,
		
    //变量
    pointAdd: 0, //积分增加
    pointdis: 0, //积分减少
    pointbefore: 0, //原有积分

    moneyservice: 0,   //服务费用
    moneyappend: 0,    //附件费用
    moneygoods: 0,      //商品费用
    fee: 0,              //支付方式  0 免费类 1 正常收费 2 K金兑换 3 代金卡

    feeshouldpay: 0,     //应付金额
    feefactpay: 0,           //实收金额
    feecanpoint: 0,         //可以积分的金额
    feegoodcanpoint: 0,   //商品可积分金额

    giftcardvalue: 0,    //代金卡总价值
    giftcardpoint: 0,    //代金卡可以积分的总价值

    frecancount: 0,  //免费餐具数量
    paycancount: 0,  //收费餐具数量
    paycnecount: 0,  //收费蜡烛数量

    //缓存对象
    obsumtable: null,   //费用汇总表
    obappendtable: null, //附件表
    obcjcount: null,    //餐具数量
    obcjfee: null,      //餐具费用
    oblzcount: null,    //蜡烛数量
    oblzfee: null,     //蜡烛费用
    obyjfee: null,      //异结费用
    obshipfee: null,     //配送费用
    obhaspay: null,       //已付费用
    obshouldpay: null,     //应付
    obrealpay: null,       //实收

    obspangoodsum: null,    //商品总价显示
    obspanappendsum: null,    //附件总价显示
    obspanservicesum: null,    //服务总价显示

    updatepoint: function() {
        var realpay = parseFloat($(this.obrealpay).val());

    },
    updatesum: function() {
		this.shippingFee=parseFloat($(this.sumtable + " tr:nth-child(2) td:nth-child(4) input[type='text']").val());
		
		this.appendAmount=parseFloat($(this.sumtable + " tr:nth-child(2) td:nth-child(2) span").text());
		this.payFee = parseInt($(this.sumtable + " tr:nth-child(2) td:nth-child(3) span").text());
		this.usedPoint=parseInt(this.usedPoint);
		this.frecancount=parseInt($("#freecanju").val());
		this.orderAmount = this.goodsAmount + this.appendAmount + this.payFee + this.shippingFee + this.usedPoint;
		this.moneyPaid= $(this.sumtable + " tr:nth-child(2) td:nth-child(6) input[type='text']").val();
		this.obBonus.html(this.usedBonus);
        this.usedBonus    = $(this.sumtable + " tr:nth-child(2) td:nth-child(7) span").text();
        this.usedPoint = $(this.sumtable + " tr:nth-child(2) td:nth-child(8) span").text();
		
        this.discount = $(this.sumtable + " tr:nth-child(2) td:nth-child(9) input[type='text']").val();
        this.obSurplus.html(this.usedsurplus);
		this.unPayed = this.orderAmount - this.moneyPaid - this.usedBonus - this.discount - this.usedPoint - this.usedsurplus ;
		if(this.unPayed<0) this.unPayed=0;

		if($("#pay input[type='checkbox']:checked").length==1)
		{
			$("#pay input[type='checkbox']:checked").next().val(orderFee.unPayed);
		}
		
        this.obAmount.html(this.orderAmount); 
		this.obUnpayed.html(this.unPayed);

		
    }, //更新汇总

    updateappend: function() {
		$("#goods_msg").html('');
		//this.paycnecount=parseInt(this.obcnecount.val());
		var curr=parseInt(this.obcancount.val());
		var cure=parseInt(this.obcnecount.val());
		//if(curr<orderFee.frecancount)
		if(curr<0)
		{
			this.obcancount.val('0');
			return;
		}
		if(cure<0)
		{
			this.obcnecount.val('0');
			cure=0;
			//return;
		}
		this.paycancount=curr-orderFee.frecancount;
		this.paycnecount=cure;
		if(this.paycancount>0)
		{
			var pay_canju=0.5*this.paycancount;
			$("#pay_canju").html(pay_canju);
		}else{
			this.paycancount=0;
			$("#pay_canju").html('');
		}
		if(this.paycnecount>0)
		{
			var candle=5*this.paycnecount;
			$("#pay_candle").html(candle);
		}else{
			$("#pay_candle").html('');
		}
		this.appendAmount = this.paycancount * 0.5 + this.paycnecount * 5+this.attr_amount;
        this.obAppdfee.html(this.appendAmount);
		$("#append").val(this.appendAmount);
        this.updatesum();
    }, 
    updategood: function() {
		
        var goodsamount = 0;
        var goodspointdis = 0;
        var is_integral = 0; 
        var goods = $(".pricehide").each(function() {           
            var cake = $(this).val().split(',');
			//alert(cake[0] + '+' + cake[1] + '+' +cake[2] + '+' +cake[3] + '+' +cake[4] + '+' +cake[5]);
            var goodcount = parseInt(cake[3]);    //数量
            var gooddisct = parseFloat(cake[4]);    //折扣
            var goodprice = parseInt(cake[5]);    //单价
            
            if (is_integral != 1) {
                is_integral = gooddisct == "-1" ? -1 : 1;
            }
			
            if (gooddisct > 0) {
                goodsamount = goodsamount + good.HandPrice(goodcount * gooddisct * goodprice );
            }
            if (gooddisct == 0) {} 
            if (gooddisct == -1) {
                goodspointdis = goodspointdis + goodcount * goodprice;
				//goodsamount = goodsamount + goodcount * goodprice;
            } 
			if (gooddisct < -9) {
				
                //goodsamount = goodsamount + goodcount * gooddisct + goodprice;
				goodsamount = goodsamount +  gooddisct + goodprice*goodcount;
            } 
			
        });
        this.goodsAmount = goodsamount; 
		
        this.usedPoint = goodspointdis;
		  
		
		
		if(orderFee.usedPoint=="0"){
			$("#integral").next().val('');
			$("#integral").attr("checked",false);
		}else{
			$("#integral").attr("checked",true);
			$("#integral").next().val(this.usedPoint);
		}
		//
		
        this.obCakefee.html(this.goodsAmount+this.usedPoint);
        this.obUsepoint.html(this.usedPoint);
		this.updatesum();
        $(this.hidegoodsum).val(this.goodsAmount);
		$("#mintegral").val(this.usedPoint);
	},
	//一级支付方式
    paymentchange: function() {		
			var url = "ajax.php?act=pay_note&id=";
			$.ajax({
				type: "Get",
				cache: false,
				url: url + $(this.pay_name).val()+"&uid="+$("#user_id").val(),
				data: "",
				success: function(pay) {

					$("#pay").html('');
					$("#pay").append(pay);
				
					//异地支付
					if ($(orderFee.pay_name).val() == "1") {
						$(orderFee.balancetr).show();
						$(orderFee.balanceaddr).focus();
						orderFee.obPayfee.html(10);
					}
					else 
					{
						$(orderFee.balancetr).hide();
						orderFee.obPayfee.html(0);
					}
					$(orderFee.surplus).val('0');
					orderFee.usedsurplus=$("#surplus").val();
					$(orderFee.txtdiscount).val('0');
					orderFee.updatesum();
					//免费支付方式
					 if ($(orderFee.pay_name).val() == "6") {
						$(orderFee.txtdiscount).val(orderFee.unPayed);
					 }else{
						 $(orderFee.txtdiscount).val('0');
					 }
					//大客户支付方式
					 if ($(orderFee.pay_name).val() == "5") {
						/*$(orderFee.surplus).val(orderFee.unPayed);
						orderFee.usedsurplus=$("#surplus").val();
					 }else{*/
						 $(orderFee.surplus).val('0');
						 orderFee.usedsurplus=$("#surplus").val();
					 }
		             orderFee.updatesum();
				}
			});		

    }, 
    
	//改变付费方式

    //feetype: function() {
     //   if ($(this.feetypesel).val() == "预付费"){
      //  	alert('ss');
		 
      //  }
       
    //}, //获取收费方式
	//计算附件金额添加时
	get_append:function(){
		orderFee.attr_amount=0;
		$(".attr_price").each(function (){
			  var price = $(this).val().split(",");
			  var attr_count=$(this).next().val();
			  orderFee.attr_amount=orderFee.attr_amount+parseFloat(price[1])*parseInt(attr_count);
			  $(this).nextAll("span").html(parseFloat(price[1])*parseInt(attr_count)+"元;");
		});
		orderFee.updateappend();
	},
	//计算附件金额修改时
	change_attr:function(){
		orderFee.attr_amount=0;
		$(".attr_count").each(function (){
			  var attr_count = $(this).val();
			  var price=$(this).prev().val().split(",");
			  orderFee.attr_amount=orderFee.attr_amount+parseFloat(price[1])*parseInt(attr_count);
			  $(this).nextAll("span").html(parseFloat(price[1])*parseInt(attr_count)+"元;");
			  //当金额数量为0时，还原select
			  if(attr_count==0)
			  {
				  var a=$(this).prev().val().split(',');
				  $("#attrs").append("<option value='"+a[0]+"'>"+$(this).parent().find("label").text()+"</option>");
				  $(this).parent().remove();
				  $("#attrs").show();
				  $("#addattrs").show();
			  }
			  
		});
		orderFee.updateappend();
	},
	changeorderamount:function(){
		if(!$("#xj").val())var xj=0;else var xj=$("#xj").val();
		if(!$("#posj").val())var posj=0;else var posj=$("#posj").val();
		var orderamount=xj*1 + posj*1;
		this.obUnpayed.html(orderamount);
	},
    //初始化
    init: function() {
	
        //付费方式改变
        $(this.pay_name).change(function() {
            orderFee.paymentchange();
        });
        // $(this.feetypesel).change(function() {
         //   orderFee.feetype();
        //});
        //给原积分赋值
        this.pointbefore = parseFloat($(this.pointbeforeSel).val());
        this.obsumtable = $(this.sumtable);
		
        this.obCakefee  = $(this.sumtable + " tr:nth-child(2) td:nth-child(1) span");
        this.obAppdfee  = $(this.sumtable + " tr:nth-child(2) td:nth-child(2) span");
        this.obPayfee   = $(this.sumtable + " tr:nth-child(2) td:nth-child(3) span");
        this.obShipfee  = $(this.sumtable + " tr:nth-child(2) td:nth-child(4) input[type='text']");
        this.obAmount   = $(this.sumtable + " tr:nth-child(2) td:nth-child(5) span");
        this.obMypaid   = $(this.sumtable + " tr:nth-child(2) td:nth-child(6) input[type='text']");
        this.obBonus    = $(this.sumtable + " tr:nth-child(2) td:nth-child(7) span");
        this.obUsepoint = $(this.sumtable + " tr:nth-child(2) td:nth-child(8) span");
        this.obDiscount = $(this.sumtable + " tr:nth-child(2) td:nth-child(9) input[type='text']");
        this.obSurplus  = $(this.sumtable + " tr:nth-child(2) td:nth-child(10) span");
        this.obUnpayed  = $(this.sumtable + " tr:nth-child(2) td:nth-child(11) span");
		
		this.obcancount = $(this.canjucount);
		this.obcnecount = $(this.candlecount);
		this.usedBonus =this.obBonus.text();
		this.usedsurplus=this.obSurplus.text();
	
		//餐具数量改变
        this.obcancount.change(function() {
		//$("#canju").change(function() {
		
            orderFee.updateappend();
			
            orderFee.updatepoint();
        });
		//蜡烛数量改变
        this.obcnecount.change(function() {
            orderFee.updateappend();
            orderFee.updatepoint();
        });
        this.obMypaid.change(function() {
            orderFee.moneyPaid = parseInt($(orderFee.obMypaid).val());
            orderFee.updatesum();
        });
        this.obDiscount.change(function() {
            orderFee.discount = parseInt($(orderFee.obDiscount).val());
            orderFee.updatesum();
        });
        this.obShipfee.change(function() {
            orderFee.shippingFee = parseInt($(orderFee.obShipfee).val());
            orderFee.updatesum();
        });
        $("input[name='way[]']").blur(function(){orderFee.changeorderamount();});
		//现金复选框
		 $("#pay input[type='checkbox']").click(function(){
			$("#pay input[type='checkbox']").next().hide();
			$("#pay input[type='checkbox']:checked").next().show();
			$("#pay input[type='checkbox']:not(:checked)").next().val('');
			$(this).next().focus();
			if($("#pay input[type='checkbox']:checked").length==1)
			{
				$("#pay input[type='checkbox']:checked").next().val(orderFee.unPayed);
			}
        });
		$("#ljkway").click(function(){
			if($("#user_money").val()>0){			
				orderFee.usedsurplus=orderFee.unPayed<$("#user_money").val()?orderFee.unPayed:$("#user_money").val();
				orderFee.obSurplus.html(orderFee.usedsurplus);
				$("#ljk").val(orderFee.usedsurplus);
				$("#ljk_note").val('礼金卡:'+orderFee.usedsurplus);
				orderFee.updatesum();
			}					
		});
        this.updategood();
		
    }
}
$.fn.orderFee = function(settings) {
    $.extend(orderFee, settings || {});
};
$.fn.ready(function() {
    orderFee.init();
	if($(".attr_price").length>0)
	{
		orderFee.get_append();
		$(".attr_count").change(function() {
			orderFee.change_attr();
		});
	}
	
});

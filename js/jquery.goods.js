/// <reference path="jquery-1.3.2-vsdoc.js" />  //智能提示
/// <reference path="jquery.balance.js" />  //引用
//折扣0对应K金兑礼。  这个时候需要检查用户积分是否足够。
//添加商品选项 可以添加礼品。
//当选择礼品的时候，要求折扣必需为K金兑礼。  服务器端 K金兑礼对应折扣=0
//修改隐藏字段结构 为 trname + ',' + 商品编号 + ',' + 规格 + ',' + 数量 + ',' + 折扣 + ',' + 原价+','+商品类型;
var good = {
    goodid: 0,                    //商品编号
    goodname: "",                 //商品名称
    weight: 0,                    //商品重量
    count: 1,                     //商品数量
    price: 0,                     //商品单价
    discount: 1,                //商品折扣  K金兑礼时 折扣为-1. 免费赠送时 折扣为 0
    sumprice: 0,                  //商品原总价钱  K金兑礼时 显示负数
    dissumprice: 0,               //商品折扣后的总价钱
    //元素名
    goodtable: "#caketable",
	addre: "#addre",
    cityselect: "#goodcity",
    goodtypeselect: "#goodtype",
    goodselect: "#goodsel",
    goodweightsel: "#weightsel",
    countsel: "#goodcount",
    priceid: "#price",
    disreicesel: "#dissel",
    disprice: "#deprice",
    addbuttion: "#addcake",
    updatebuttion: "#updatecake",
    delbuttion: "#buttondel",
    cencelbuttion: "#cencelcake",
	usernewaddrbutton:"#usernewaddrbutton",
    Typesurl: "ajax.php?act=selattr&id=",
    constandropselect: " option:selected",
    pricesumtable: "#pricesumtb",
    orderTime: "#recivertime",
	
    isupdate: false,
    isGoodchageAccept: true,
    tempappend: 0,
    trcount: 0,
    dbkey: 0, //数据库主键。 针对商品折扣变更

    //获取商品
    getgood: function() { 
	
        this.typename = $(this.goodtypeselect + this.constandropselect).text();
        this.goodname = $(this.goodselect + this.constandropselect).text();
        this.goodid = $(this.goodselect).val();
        this.weight = $(this.goodweightsel + this.constandropselect).text();
        this.price = parseFloat($(this.goodweightsel).val()); 
        this.count = parseInt($(this.countsel).val()); 
        this.discount = parseFloat($(this.disreicesel).val());
    },
    addgood: function() {
		$("#goods_msg").html('');
		good.getgood();
		//无生日牌goodid
		//var no_card="43,47,53,55,59,92,60,61,91,82,67,92,93,38";
		var no_card="60,61,91,67";
		var re = new RegExp("^" + this.goodid + ",|," + this.goodid + ",|," + this.goodid + "$");
		
		
        if (this.goodid == 0) {
            //alert("请选择商品");
			$("#goods_msg").html("请选择商品");
            return;
        }
        if ($(this.goodweightsel).val() == 0) {
            //alert("请选择商品规格");
			 $("#goods_msg").html("请选择商品规格");
            return;
        }
        if (this.discount == -1) {	
            var pay_points = parseFloat($("#pointbefore").val());
			var res = pay_points - this.price * this.count;
			if (res < 0) {
                $("#goods_msg").html("积分不足");
				//alert("积分不足");
                return;
            }
			$("#pointbefore").val(res);
			//$("#integral").next().val(this.price * this.count);
			$("#integral").attr("checked", true);
        }
		//this.trcount=$("#caketable tr").length-1;
		if(typeof($("#caketable tr:last").attr('id'))!="undefined") this.trcount=parseInt($("#caketable tr:last").attr('id').substr(6))+1;
        var trname = "trcake" + this.trcount;
        var tr = '<tr id=' + trname + ' class="fontred" ><td height="25" class="fontred"><span style="font-weight:bold">' + this.goodname + '</span></td><td>';
        var weigthstr = this.weight;
		var card = 'card' + this.trcount;
		var msg = 'msg' + this.trcount;
        tr = tr + '<span id="goodattr_'+this.trcount+'">' + weigthstr + '</span></td>';
		tr = tr + '<td align="center"><span id="goodprice_'+this.trcount+'">' + this.price + '</span></td>';
        tr = tr + '<td align="center"><span id="goodcount_'+this.trcount+'"><input type="text" name="gcount" id="gcount'+this.trcount+'" value="' + this.count + '" onblur="good.changehidevalue(this)"/></span>';
        tr = tr + '</td><td align="center"><span id="goodsumprice_'+this.trcount+'">' + this.sumprice + '</span></td><td><span>';
        tr = tr + $(this.disreicesel + this.constandropselect).text() + '</span></td><td><span id="dissumprice_'+this.trcount+'">' + this.dissumprice + '</span></td><td>';
        var hidevalue = trname + ',' + this.goodid + ',' + this.weight + ',' + this.count + ',' + this.discount + ',' + this.price + ','+ $(this.disreicesel + this.constandropselect).text() ;
        tr = tr + '<input type="hidden" class="pricehide" value="' + hidevalue + '" id="goods" name="goods[]" />';
		tr = tr + "<select name='cards[]' id='" + card + "'";
		if(re.test(no_card))
		{
			tr = tr + "style='display:none' ";	
		}
		tr =tr +"><option value='中文'>中文</option><option value='无'";
		tr = tr + " selected='selected'>无</option><option value='英文'>英文</option><option value='其它'>其它</option></select>";
		tr = tr + "<input type='text' id='" + msg + "' name='msgs[]' size='12' style='display:none;border-color:red;' /></td><td>";	
        //tr = tr + '<input type="button" value="修改"  class="bgred" onclick=good.editgood("' + trname + '");  />';
		tr = tr + ' &nbsp;<input type="button" value="移除"  class="bgred" onclick="good.delgood(this);"/></td></tr>';
		$(this.goodtable).children("tbody").append(tr);
		var weight_common=this.weight.substring(0,this.weight.length-1);
		//数字蜡烛规格是数字，后面没有磅
		if(this.goodid==67)
		{
			weight_common=this.weight;
		}
		$.ajax({
            type: "Get",
            dataType: "json",
            cache: false,
			async:false,
            url: this.Typesurl + this.goodid + "&trcount="+this.trcount+"&weight="+ weight_common,
            data: "",
            success: function(json) {
				var str='<select id="gattr'+json.trcount+'" onchange=good.changehidevalue(this)>';
		        for (var i = 0; i < json.attrs.length; i++) {
		            str+='<option value=' + parseInt(json.attrs[i][0]);
		            if(json.attrs[i][1].substring(0,json.attrs[i][1].length-1)==json.weight||json.attrs[i][1]==json.weight){str+=' selected=selected';}
		            str+='>' + json.attrs[i][1] + '</option>';
				}
		        str+='</select>';		        
		        $("#goodattr_"+json.trcount).text('');
		        $("#goodattr_"+json.trcount).append(str);
            }
        });
		
		
        this.trcount++;
        good.UpdateAppend(1);
        orderFee.updategood();

		$("#"+ card).change(function() {
			if($("#" + card).val() == '其它'){
               $("#" + msg).show();
			}else{
			 	$("#" + msg).val('');		
	           $("#" + msg).hide();		
			}
        });
		$(this.goodselect).val('');
		$(this.goodweightsel).val('0');
		$(this.countsel).val('1');
		$(this.disreicesel).val('1');		
    },
    changehidevalue:function(rows){
    	var row = $(rows).parents("td:first").parents("tr:first");
		var hide = row.find("input:hidden").val();
        var list = hide.split(",");//alert(list[0]+"/"+list[1]+'/'+list[2]+'/'+list[3]+'/'+list[4]+'/'+list[5]+'/'+list[6]);
        this.goodid = parseInt(list[1]);  
        this.count=list[3];
        var forefree = good.GetFreeAppendCount() * this.count;
        this.weight=$("#gattr"+list[0].substring(6)+" option:selected").text();
        this.count=$("#gcount"+list[0].substring(6)).val();
        var nowfree = good.GetFreeAppendCount() * this.count;
        this.price=$("#gattr"+list[0].substring(6)+" option:selected").val();        
        this.discount = parseFloat(list[4]);
        $("#goodprice_"+list[0].substring(6)).text(this.price);
        $("#goodsumprice_"+list[0].substring(6)).text(this.price*this.count);
        
        if (this.discount > 0)
            var dissumprice = this.HandPrice(this.price * this.count * this.discount);
        else if (list[4] < -1)
            var dissumprice = this.HandPrice(this.price * this.count + this.discount);
        else
            var dissumprice = 0;
        $("#dissumprice_"+list[0].substring(6)).text(dissumprice);
        var trname=list[0];
        var hidevalue = trname + ',' + this.goodid + ',' + this.weight + ',' + this.count + ',' + this.discount + ',' + this.price + ','+ list[6];
        $("#" + trname).find("input:hidden").eq(0).val(hidevalue);
       //alert(hidevalue);
        
        orderFee.frecancount = orderFee.frecancount - forefree + nowfree;
        orderFee.obcancount.val(orderFee.frecancount + orderFee.paycancount);	
		if(orderFee.frecancount<0)
		{
			orderFee.frecancount=0;
		}
		$("#freecanju").val(orderFee.frecancount);
        orderFee.updateappend();
       orderFee.updategood();
    },
    editgood: function(trname) {
     	var hide = $("#" + trname).find("input:hidden").val();
        var list = hide.split(",");
        this.updatemode(true);
        $("#" + trname).addClass("highlight");
        this.goodid = parseInt(list[1]); 
		$(this.goodselect).val(this.goodid);
        this.weight = list[2];
        this.count = parseInt(list[3]);
        this.discount = parseFloat(list[4]);
        this.price = parseFloat(list[5]);
        this.isGoodchageAccept = false;
        this.tempappend = this.GetFreeAppendCount() * this.count
        if (this.discount == -1) { //如果是K金兑礼 修改时先返回积分
            orderFee.pointdis = orderFee.pointdis - this.price * this.count; //返回积分!  因为K金兑礼的使用价格都是0 所以返回积分不成立
			
        }
        good.LoadGoods();

        $(this.updatebuttion).unbind(); //移除方法
        $(this.updatebuttion).click(function() {
            good.updategood(list[0]); //附加方法
        });
		//orderFee.frecancount=parseInt(orderFee.obcancount.val());

    },
    updategood: function(trname) {
        var vs = $("#" + trname).find("span");
		vs[0].innerText =$(this.goodselect).find("option:selected").text();
        vs[1].innerText = this.weight; 
		vs[2].innerText = this.price;
        vs[3].innerText = this.count; 
        vs[4].innerText = this.sumprice;
        vs[5].innerText = $(this.disreicesel + this.constandropselect).text(); 
        vs[6].innerText = this.dissumprice;
	    var hidevalue = trname + ',' + this.goodid + ',' + this.weight + ',' + this.count + ',' + this.discount + ',' + this.price + ','+ $(this.disreicesel + this.constandropselect).text();
        $("#" + trname).find("input:hidden").eq(0).val(hidevalue);
        this.updatemode(false);
        this.UpdateAppend(1);
        this.tempappend = 0;
        orderFee.updategood();
		$(this.goodselect).val('');
		$(this.goodweightsel).val('0');
		$(this.countsel).val('1');
		$(this.disreicesel).val('1');
    },
    updatemode: function(isup) {
        this.isupdate = isup;
        $(".highlight").removeClass("highlight");
        if (isup) {
            $(this.addbuttion).hide();
            $(this.updatebuttion).removeAttr("disabled");
            $(this.cencelbuttion).removeAttr("disabled");
        }
        else {
            $(this.addbuttion).show();
            $(this.updatebuttion).attr("disabled", "disabled");
            $(this.cencelbuttion).attr("disabled", "disabled");
        }
    },

    //删除商品
    delgood: function(rows) {
		if (orderFee.pointAdd == 0) {
            orderFee.updatepoint();
        }
        //调整到移除的对象     
        var row = $(rows).parents("td:first").parents("tr:first");
		var hide = row.find("input:hidden").val();
        var list = hide.split(",");
        this.goodid = parseInt(list[1]); //该项应该可以修改
        this.weight = list[2];
        this.count = parseInt(list[3]);
        this.discount = parseInt(list[4]);
        this.price = parseFloat(list[5]);
        this.type = list[6];   //调整到删除商品的状态
        //alert(this.discount);
		//检查积分是否足够。
		if(this.discount == -1)
		{
			var pay_points = parseFloat($("#pointbefore").val());
			$("#pointbefore").val(pay_points + this.price);
			//$("#integral").val($("#integral").val()-this.price);
			//alert(this.price);	
		}

        row.remove();
        this.updatemode(false);
        this.tempappend = 0;
        good.UpdateAppend(0);
        if (this.discount == -1) {
            orderFee.pointdis = orderFee.pointdis - this.price * this.count; //返回积分
        }
        orderFee.updategood();
    },
	
    //检查积分 用于客户选择使用K金兑礼的时候 检查用户积分是不是足够支付选择的商品
    //修改逻辑 使当前积分可以支付礼品。
    checkpoint: function(price) {
        return !(price > (orderFee.pointAdd + orderFee.pointbefore - orderFee.pointdis));
    },

	

    LoadGoods: function() {
		$("#goods_msg").html('');
        $(this.goodweightsel).empty();            //首先清除规格
        this.goodid = $(this.goodselect).val();
		
        $.ajax({
            type: "Get",
            dataType: "json",
            cache: false,
			async:false,
            url: this.Typesurl + this.goodid,
            data: "",
            success: function(json) {
                good.PraseJosn(json);
            }
        });
    },
    PraseJosn: function(json) {
		$("<option value=0>选规格</option>").appendTo($(this.goodweightsel));
        for (var i = 0; i < json.attrs.length; i++) {
            $("<option value=" + parseInt(json.attrs[i][0]) + ">" + json.attrs[i][1] + "</option>").appendTo($(this.goodweightsel));
		}
        $(this.countsel).val(this.count);
        $(this.disreicesel).val(this.discount);
		//alert($(this.goodweightsel).val());
        $(this.goodweightsel).val(this.price);
    },
    HandPrice: function(price) {
        var re = Math.floor(parseFloat(price));  //小数部分舍去。
        if (re == 444)
            re++;
        return re;
    },
    UpdateAppend: function(add) {
        var free = good.GetFreeAppendCount() * this.count;
		
		if (add == 1) {//加
            orderFee.frecancount = orderFee.frecancount + free - this.tempappend;
			//orderFee.frecancount = orderFee.frecancount + free;
            orderFee.obcancount.val(orderFee.frecancount + orderFee.paycancount);
        }
        if (add == 0) {//减
            orderFee.frecancount = orderFee.frecancount - free;
            orderFee.obcancount.val(orderFee.frecancount + orderFee.paycancount);
        }
		
		if(orderFee.frecancount<0)
		{
			orderFee.frecancount=0;
		}
		$("#freecanju").val(orderFee.frecancount);
        orderFee.updateappend();
    }, //更新附件数量
    AddAppend: function(te,num) {
		 
		if (te == 1) {
			$("#canju").val(parseInt($("#canju").val()) + num);
        }
        else {
			var curr = parseInt($("#candle").val()) + num;
			if(curr < 0){
				orderFee.paycnecount = 0;
				$("#dcne").attr("disabled", "disabled");
			}
			else{
				orderFee.paycnecount = curr;
				$("#dcne").removeAttr("disabled");
				$("#candle").val(curr);
			}
        }
		this.UpdateAppend();
		//orderFee.updateappend();
    }, 
	
    GetFreeAppendCount: function() {
		//if (this.type != 4) return 0;
        var weight = this.weight;
        /*if (this.weight.toLocaleString().indexOf("磅") > 0) {
         weight = parseFloat(this.weight.substring(0, this.weight.indexOf("磅")));
        }*/
		 if (this.weight.toLocaleString().indexOf("磅") > 0||this.weight.toLocaleString().indexOf("套") > 0) {
            weight = parseFloat(this.weight.substring(0, this.weight.indexOf("磅")));
			if(this.weight.toLocaleString().indexOf("套") > 0)
			{
				return 5;
			}
        }
		else
		{
			weight="";
		}
		//alert(weight);
        if (weight < 0 || weight =='')
            return 0;
        if (weight < 0.68)
            return 1;
        if (weight < 2.0)
            return 5;
        if (weight < 2.6)
            return 10;
        if (weight < 3.6)
            return 15;
        if (weight < 5.1)
            return 20;
        if (weight < 10.1)
            return 40;
        if (weight < 15.1)
            return 50;
        if (weight < 20.1)
            return 80;
        if (weight < 25.1)
            return 100;
        if (weight < 30.1)
            return 120;
        return 0;
    },
    changeprice: function() {
		if($(this.goodselect).val()==0)
		return;
		this.goodid = $(this.goodselect).val();
        if ($(this.goodweightsel).val() == 0)
            return;
        this.weight = $(this.goodweightsel + this.constandropselect).text();
        this.price = parseFloat($(this.goodweightsel).val()); //价格
        this.count = parseInt($(this.countsel).val()); //数量
        this.discount = parseFloat($(this.disreicesel).val()); //折扣
		
        if (this.discount == -1) {
            if (!good.checkpoint(this.price * this.count)) {
                $(this.disreicesel).val("1");
                //alert("积分不足");
				 $("#goods_msg").html("积分不足");
               // if (this.type == 41) {
                   // $(this.goodtypeselect).val(4);
                   // good.LoadGoods();
               // }
                good.changeprice();
            }
        }
        this.sumprice = this.price * this.count;
        if (this.discount > 0)
            this.dissumprice = this.HandPrice(this.price * this.count * this.discount);
        else if (this.discount < -1)
            this.dissumprice = this.HandPrice(this.price * this.count + this.discount);
        else
            this.dissumprice = 0;
			
        $(this.priceid).html(this.sumprice);
        $(this.disprice).html(this.dissumprice)
        
    },

	
	textcard: function()
	{
		this.trcount=$("#caketable tr").length-1;
		for(var i=0;i<this.trcount;i++)
		{
			$("#card"+i).change(function() {
				if($("#card" +(i-1)).val() == '其它'){
				   $("#msg" + (i-1)).show();
				}else{
					$("#" + msg).val('');	
				 	$("#msg" + (i-1)).hide();		
				}
			});
			
		}
		
	},
	addattrs:function()
	{
		 $.ajax({
            type: "Get",
            cache: false,
            url: "ajax.php?act=getattr&id=" + $("#attrs option:selected").val(),
            data: "",
            success: function(msg) {
                var price=msg;
				var agoods="<label class='blank30'><label>"+$("#attrs option:selected").text()+"</label>:<input type='hidden' class='attr_price' name='attr_goods[]' value='"+price+"' /><input type='text' class='attr_count' name='attr_count[]' value='1' style='width:20px;margin-left:10px'/><span style='margin-left:5px'></span></label>";
				$("#attr_goods").append(agoods);
				$("#attrs option:selected").remove();
				$(".attr input[type='text']").last().focus();
				$(".attr input[type='text']").last().select();
				orderFee.get_append();
				
					$(".attr_count").change(function() {
						orderFee.change_attr();
					});
				if($("#attrs option").length==0)
				{
					$("#attrs").hide();
					$("#addattrs").hide();
					return;
				}	
            }
        });
		
		
	},
	
    init: function() {
		$("#order_status").change(function(){
			if($(this).val() !="1") 
			{
				$("#textspaicl").focus();$("#textspaicl").val('请输入原因');$("#textspaicl").select();
			}
		});
        $(this.goodselect).change(function() {
            good.LoadGoods();
        })
        $(this.addbuttion).click(function() {
			good.addgood();
        });
        $(this.goodweightsel).change(function() {
            good.changeprice();
        });
        $(this.countsel).change(function() {
            good.changeprice();
        });
        $(this.disreicesel).change(function() {
            good.changeprice();
        });
        $(this.cencelbuttion).click(function() {
            good.UpdateAppend(1);
            good.updatemode(false);
        });
        $("#mcan").click(function() {
            good.AddAppend(1,1);
        });
        $("#dcan").click(function() {
            good.AddAppend(1,-1);
        });
        $("#mcne").click(function() {
            good.AddAppend(2,1);
        });
        $("#dcne").click(function() {
            good.AddAppend(2,-1);
        });
		$("#addattrs").click(function() {
            good.addattrs();
        });
		$("#caketable select").change(function() {	 
			if($(this).val() == '其它'){
				$(this).next().show();
			}else{
				$(this).next().val('');
				$(this).next().hide();		
			}		  
        });
        good.updatemode(false);
    }

}
/* Attach  to a jQuery selection. */
$.fn.goods = function(settings) {
    $.extend(good, settings || {});
};

$.fn.ready(function() {
    good.init();
});
 

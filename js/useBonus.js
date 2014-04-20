/// <reference path="jquery-1.3.2-vsdoc.js" />  //智能提示
/// <reference path="jquery.balance.js" />  //智能提示
$.fn.ready(function() {
	if($("#bonus").next().val() !="") orderFee.usedBonus=$("#bonus").next().val();
	
	if(orderFee.usedBonus==0) 
	{
		$("#bonus").next().val('');
		$("#bonus").attr("checked",false); 
    }else{
		$("#bonus").attr("checked",true);
	}
	//url="order_add.php?act=check_bonus&cardnum="+$("#bonus_cardnum").val();
	url="ajax.php?act=check_bonus";
//    $("#bonus_cardnum").autocomplete(url,{
//        minChars: 5,
//        autoFill: false,
//        dataType: "json",
//		extraParams: {cardnum:function(){return $('#bonus_cardnum').val();}}, 
//        formatItem: function(row, i, max) {
//			
//            if (row.cardused == "1") {
//                return row.CardNumber + "(<span style=\"color:red\">已使用</span>)";
//            }else if(row.cardused == "2"){
//				return row.CardNumber + "(<span style=\"color:red\">已过期</span>)";
//			}else {
//                return row.CardNumber + "(<span style=\"color:green\">未使用</span>)";
//            }
//        },
//        formatResult: function(row) {
//            return row.CardNumber;
//        },
//        parse: function(josn) {
//			
//            var parsed = [];
//            var rows = josn;
//            if (rows.length == 0)
//                return 123;
//            for (var i = 0; i < rows.length; i++) {
//                var row = rows[i];
//                if (row) {
//                    parsed[parsed.length] = {
//                        data: row,
//                        value: row.CardNumber,
//                        result: row.CardNumber
//                    };
//                }
//            }
//			
//            return parsed;
//        },
//        selectFirst: false
//    });
//
//    $("#bonus_cardnum").result(function(event, data, formatted) {
//		if(data=="") return;
//        if (data.cardused == "1") {
//            $(this).val("");
//			window.open("order.php?act=show&id="+data.order_id,"pop");
//			//location.href="order.php?act=detail&order_id="+data.order_id;
//            return;
//        } //不允许选择已经使用过的代金卡
//		if (data.cardused == "2") {//过期
//            $(this).val("");
//            return;
//        } 
//        if (data)
//            $("#usecardimg").click(function() {
//                UseGiftCard(data);
//                $(this).unbind("click");
//            }
//		     );
//        $("#bonus_sn").val(data.CheckCode);
//    });

    $("#bonus_sn").autocomplete(url, {
        minChars: 5,
        autoFill: false,
        dataType: "json",
		extraParams: {sn:function(){return $('#bonus_sn').val();}}, 
        formatItem: function(row, i, max) {
            if (row.cardused == "1") {
                return row.CardNumber + "(<span style=\"color:red\">已使用</span>)";
            }else if(row.cardused == "2"){
				return row.CardNumber + "(<span style=\"color:red\">已过期</span>)";
			}else {
                return row.CardNumber + "(<span style=\"color:green\">未使用</span>)";
            }
        },
        formatResult: function(row) {
            return row.CheckCode;
        },
        parse: function(josn) {
            var parsed = [];
            var rows = josn;
            if (rows.length == 0)
                return null;
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                if (row) {
                    parsed[parsed.length] = {
                        data: row,
                        value: row.CheckCode,
                        result: row.CheckCode
                    };
                }
            }
            return parsed;
        },
        selectFirst: false
    });

    $("#bonus_sn").result(function(event, data, formatted) {
		if(data=="") return;
        if (data.cardused == "1") {
            $(this).val("");
			window.open("order.php?act=show&id="+data.order_id,"pop");
            return;
        } //不允许选择已经使用过的代金卡
		if (data.cardused == "2") {//过期
            $(this).val("");
            return;
        } 
        if (data)
            $("#usecardimg").click(function() {
                UseGiftCard(data);
                $(this).unbind("click");
            }
		     );
        $("#bonus_sn").val(data.CardNumber);
    });
});
var trcount  = $("#bouns_info tr").length-1;
function UseGiftCard(card) {
	//检查该卡是否被使用。
	var flag=false;
	$(".hidebonus").each(function() {
		var b=$(this).val().split(':');
		if(b[0]==card.cardid) {
			$("#bmsg").html('该卡本单已使用'); 
			flag=true;
		}
	});
	if(flag) return;
    orderFee.usedBonus = parseFloat(orderFee.usedBonus) + parseFloat(card.value);
	//选择代金卡
    $("#bonus").attr("checked",true); 
	$("#bonus").next().val(orderFee.usedBonus);
	$("#remark").val($("#remark").val()+"券号:"+card.CardNumber+";");
    $("#bonus_sn").val("");
    //$("#bonus_cardnum").val("");
	var trname = "trbonus" + trcount;
	var bonus=card.cardid+":"+card.value+":"+card.CardNumber+":"+card.reusable;
	//alert(bonus);
	var tr="<tr id="+trname+"><td>券号:"+card.CardNumber+",金额:"+card.value+"<input type='image' src='images/icon_drop.gif' onclick=\"delbonus('#"+trname+"')\"><input type='hidden' value='"+bonus+"' class='hidebonus' name='bonus[]'></td></tr>";
	$("#bouns_info").children("tbody").append(tr);
	trcount++;
	$("#mbonus").val(orderFee.usedBonus);
    orderFee.updatesum();
	
}
function delbonus(rows){
		  var row = $(rows);
		  var hide = row.find("input:hidden").val();
          var list = hide.split(":");
		  var cardnum = list[2];
		  var money=parseFloat(list[1]);
		  orderFee.usedBonus = parseFloat(orderFee.usedBonus) - money;
		  $("#remark").val($("#remark").val().replace("券号:"+cardnum+";", ""));
		  $("#mbonus").val(orderFee.usedBonus);
		  /*$("#bonus").next().val(orderFee.usedBonus);
		  if(orderFee.usedBonus==0) 
		  {
			  $("#bonus").next().val('');
			  $("#bonus").attr("checked",false); ;
		  }*/
		  orderFee.updatesum();
		  row.remove();
	}
	

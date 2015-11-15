var region = {
	
    rrsel: "#seladdr",
    textaddr: "#textaddr",
    shipfee: "#shipfee",
	route_code:"",
	pici:"",
    buttionnew: "#newaddr",
    constandropselect: " option:selected",
	
	
	province:function()
	{
		
		$("#province").empty();
		$("#city").empty();    
		$("#shipfee").val('0');
		orderFee.shippingFee =0;
		orderFee.updatesum();            
		var country_id = $("#country").val();
		var url = "local_ajax.php?act=selregion&te=1&id=";
        $.ajax({
            type: "Get",
            dataType: "json",
            cache: false,
            url: url + country_id,
            data: "",
            success: function(json) {
                region.provinceJson(json);
            }
        });
			
	},
	 provinceJson: function(json) {
        $("<option value='0'>选择环线</option>").appendTo($("#province"));
        for (var i = 0; i < json.length; i++) {
            $("<option value=" + json[i].region_id + ">" + json[i].region_name + "</option>").appendTo($("#province"));
		}
		
    },
	
    city: function()
	{
		$("#city").empty(); 
		$("#shipfee").val('0');
		orderFee.shippingFee =0;
		orderFee.updatesum();            
		var province_id = $("#province").val();
		var url = "local_ajax.php?act=selregion&te=2&id=";
        $.ajax({
            type: "Get",
            dataType: "json",
            cache: false,
            url: url + province_id,
            data: "",
            success: function(json) {
                region.cityJson(json);
            }
        });
			
	},
    cityJson: function(json) {

        $("<option value='0'>选择城区</option>").appendTo($("#city"));
        for (var i = 0; i < json.length; i++) {
            $("<option value=" + json[i].region_id + ">" + json[i].region_name + "</option>").appendTo($("#city"));
		}
		
    },

	district: function()
	{
	   $("#district").empty();          

		var d_id = $("#city").val();
		var url = "local_ajax.php?act=selregion&te=3&id=";
        $.ajax({
            type: "Get",
            dataType: "json",
            cache: false,
            url: url + d_id,
            data: "",
            success: function(json) {
                region.districtJson(json);
            }
        });
			
	},
    districtJson: function(json) {

        if(json.length >0)
		{
			$("<option value=0>选择区域</option>").appendTo($("#district"));
			for (var i = 0; i < json.length; i++) {
				$("<option value=" + json[i].region_id + ">" + json[i].region_name + "</option>").appendTo($("#district"));
			}
			$("#district").show();
		}
    },
	
	selAddr: function(){
        var aid = $("#seladdr").val();
		var url = "ajax.php?act=seladdr&aid=";
        $.ajax({
            type: "Get",
            dataType: "json",
            cache: false,
            url: url + aid,
            data: "",
            success: function(json) {
                region.selJson(json);
            }
        });	
	},
    selJson: function(json) {
		
		$("#country").val(json['con'].country);
		$("#province").empty();
		$("#city").empty();
		$("<option value=''>选择环线</option>").appendTo($("#province"));
		for (var i = 0; i < json.province.length; i++) {
		    $("<option value=" + json.province[i].region_id + ">" + json.province[i].region_name + "</option>").appendTo($("#province"));
		}
        $("#province").val(json['con'].province);
		
		$("<option value=''>选择城区</option>").appendTo($("#city"));
		for (var i = 0; i < json.cities.length; i++) {
		    $("<option value=" + json.cities[i].region_id + ">" + json.cities[i].region_name + "</option>").appendTo($("#city"));
		}
		$("#city").val(json['con'].city);
		
        if(json['con'].district > '0')
		{
			$("<option value=0>选择区域</option>").appendTo($("#district"));
			for (var i = 0; i < json.district.length; i++) {
				$("<option value=" + json.district[i].region_id + ">" + json.district[i].region_name + "</option>").appendTo($("#district"));
			}
			$("#district").val(json['con'].district);
			$("#district").show();
		}
		else
		{
			$("#district").hide();
			$("#district").empty();
		}		
		
		$("#address").val(json['con'].address);
		$("#consignee").val(json['con'].consignee);
		$("#mobile").val(json['con'].mobile);
		$("#tel").val(json['con'].tel);
		
		this.route_code = json.route_code;
		$("#route_id").val(json.route_id);
		$("#route").val(json.route_code);
		orderFee.shippingFee = parseInt(json.fee);
		$(this.shipfee).val(orderFee.shippingFee);
		orderFee.updatesum();
		
		
    },
    newaddr: function() {

        $("#province").val('');
		$("#address").val('');
		$("#consignee").val('');
		$("#mobile").val('');
		$("#tel").val('');
		$("#city").val(''); 
		$("#seladdr").val(''); 
    },
	get_pici: function(){
        var h = $("#hours").val();
		var m = $("#minute").val();
		var c = $("#country").val();
		
		
		if(h.substr(0,1)==0)
		{
			h = h.substring(1);
		}
		var time = parseInt(h+m);
		if(c == '441')
		{
			if(time>=800 && time<1400){
				region.pici = 1;
			}else if(time>=1400 && time<1630){
				region.pici = 2;
			}else if(time>=1630 && time<1930){
				region.pici = 3;
			}else{
				region.pici = 4;
			}
			$("#turn").val(region.pici);
		}
		else if(c == '442')
		{
			if(time>=800 && time<1330){
				region.pici = 1;
			}else if(time>=1330 && time<=1540){
				region.pici = 2;
			}else if(time>1540 && time<=1950){
				region.pici = 3;
			}else if(time>1750 && time<=2000){
				region.pici = 4;
			}else{
				region.pici = 5;
			}
			$("#turn").val(region.pici);
		}
		else if(c == '440')
		{
			if(time>=800 && time<1600){
				region.pici = 1;
			}else{
				region.pici = 2;
			}
			$("#turn").val(region.pici);
		}
		else if(c == '443')
		{
			if(time>=800 && time<1530){
				region.pici = 1;
			}else{
				region.pici = 2;
			}
			$("#turn").val(region.pici);
		}
	},
	get_route:function(){
		var address = $("#address").val();
		var url = "ajax.php?act=route";
        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
			url:url,
            data: "address="+address,
            success: function(json) {
               if(json!="no")
			   {
				   var route=json.split(",");
				 
				   if(typeof(route[1])=="undefined")
				   {
					   region.route_code="";
					   return;
				   }
				
					$("#route_id").val(route[2]);
					region.route_code=route[1];
					$("#route").val(region.route_code);
					orderFee.shippingFee = parseInt(route[0]);
					$(region.shipfee).val(orderFee.shippingFee);
			   }
			   orderFee.updatesum();
            }
        });	
	},
	get_fee:function(){
		
		var area = $("#district").val() > 0 ? $("#district").val() : $("#city").val();
		var url = "local_ajax.php?act=getfee";
        $.ajax({
            type: "post",
            dataType: "text",
            cache: false,
			url:url,
            data: "area=" + area,
            success: function(json) {
				orderFee.shippingFee = parseInt(json);
				$(region.shipfee).val(orderFee.shippingFee);
				orderFee.updatesum();
            }
        });	
	},
   
	
		
    init: function() {
    
 
		$(this.rrsel).change(function() {
            region.selAddr();
        });
		$("#country").change(function(){
			region.province();
			region.get_pici();
		});
		$("#province").change(function(){
			region.city();
		});
		$("#city").change(function(){
			region.district();
			region.get_fee();
		});
		$("#district").change(function(){
			region.get_fee();
		});
		$("#hours").change(function(){
			region.get_pici();
		});
		$("#minute").change(function(){
			region.get_pici();
		});
		$("#address").change(function(){
			region.get_route();
		});
		if($("#route").val()!="")
		{
			if($("#route").val().lengh>2)
			{
				this.route_code=$("#route").val().substr(0,4);
				this.pici=$("#route").val().substr(4,2);
			}else{
				this.pici=$("#route").val();
			}
		}
		region.get_pici();
		$("#district").hide();
    }
}
$.fn.address = function(settings) {
    $.extend(region, settings || {});
};

$.fn.ready(function() {
    region.init();
	
});
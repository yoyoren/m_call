function cardNumCheck(){
	var card_num1=document.getElementById("card_num1").value;
	var card_num2=document.getElementById("card_num2").value;
	var card_num3=document.getElementById("card_num3").value;
	var card_num4=document.getElementById("card_num4").value;
	var card_num=card_num1+card_num2+card_num3+card_num4;
	if(isNaN(card_num)||card_num.length!=16){
		return false;
	}else{
		return true;
	}
}
function chargecardCheck(){
	var card_num1		= document.getElementById("card_num1").value;
	var card_num2		= document.getElementById("card_num2").value;
	var card_num3		= document.getElementById("card_num3").value;
	var card_num4		= document.getElementById("card_num4").value;
	var card_num		= card_num1+card_num2+card_num3+card_num4;
	var card_password	= document.getElementById("card_password").value;
	var check_card		= document.getElementById("check_card");
	var check_card_num	= document.getElementById("check_card_num");	
	if(card_num==""){
		check_card_num.innerHTML="* 卡号不能为空";
		return false;
	}
	if(!cardNumCheck()){
		check_card_num.innerHTML="* 卡号格式错误，请重新输入";
		return false;
	}
	if(card_password==""){
		check_card.innerHTML="* 请输入密码";
		return false;
	}
	
	document.getElementById("card_num").value=card_num;
	
	var url = "moneycard.php?act=check_card&card_num="+ card_num+"&card_password="+card_password;
    $.ajax({
        type: "Post",
        dataType: "TEXT",
        cache: true,
        url: url,
        data: "",
        success: function(res) {
            returnCheckCard(res);
        }
    });
}
function returnCheckCard(result){
	var check_card= document.getElementById("check_card");
	if(result==1){
		check_card.innerHTML="* 卡号与密码不符合，请重新输入";
		return false;
	}else if(result==2){
		check_card.innerHTML="* 您的储值卡还未生效，详情请联系客服";
		return false;
	}else if(result==3){
		check_card.innerHTML="* 您的储值卡已使用";
		return false;
	}else if(result==4){
		check_card.innerHTML="* 您的储值卡已过期";
		return false;
	}else if(result==5){
		check_card.innerHTML="* 您的储值卡还未到有效期，详情请联系客服";
		return false;
	}else{		
		myForm.submit();
	}
}
function clear_message(obj){
	var check_card_num	=document.getElementById("check_card_num");
	var check_card		= document.getElementById("check_card");
	var check_mobile	= document.getElementById("check_mobile");
	if(obj.id=="card_num1"||obj.id=="card_num2"||obj.id=="card_num3"||obj.id=="card_num4"){
		check_card_num.innerHTML="&nbsp;";
	}else if(obj.id=="card_password"){
		check_card.innerHTML="&nbsp;";
	}else if(obj.id=="mobile"){
		check_mobile.innerHTML="&nbsp;";
	}
}
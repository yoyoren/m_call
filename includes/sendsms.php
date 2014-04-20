<?php
function sms_send($mobile,$type,$c='')
{
	if($type==1) $a="尊敬的用户，您在每实订购的蛋糕已经确认，我们将按时为您送达。如有问题请与每实客服中心联系，电话4000 600 700，再次感谢您对每实蛋糕的钟爱与支持！";
	if($type==2) $a="尊敬的用户，您的订单".$c."，已经为您完成取消操作。已付款项将于？个工作日内退回至您账户。如有问题请与每实客服中心联系，电话4000 600 700，希望您能继续支持每实蛋糕！";
	if($type==3) $a="尊敬的用户您好，感谢您致电每实，您的每实网站初始登陆密码为".$c.",您可凭手机号码，输入初始密码登陆每实网站选购商品，为了您账户安全，建议您及时登陆网站www.mescake.com修改密码。再次感谢您对每实蛋糕的支持！";
	
	$cont=urlencode($a);
	file_get_contents('http://sdk.kuai-xin.com:8888/sms.aspx?action=send&userid=4333&account=s120018&password=wangjianming123&mobile='.$mobile.'&content='.$cont.'&sendTime=');
}
//$mobile='13811000692,13488724577';
//sms_send($mobile,1);
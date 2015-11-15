<?php
header("Content-Type: text/html;charset=utf-8");
$admin = trim($_GET['agentuid']);
$time = time() + 3600 * 24;
if($admin){
setcookie("Callagentsn", $admin, $time, "/", "");
	echo '工号:'.$admin.'登录成功！';exit;
}else{
	echo '请登录';
}
?>
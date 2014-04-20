<?php
require(dirname(__FILE__) . '/includes/init.php');
$agentuid=$_GET['agentuid'];
$_SESSION['agentuid']=$agentuid;
$smarty->display("order_all.html");	
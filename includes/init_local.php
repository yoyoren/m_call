<?php
/**
 * 管理中心公用文件文件
 * $Author: bisc $
 * $Id: init.php 0002
*/
session_start();
//error_reporting(E_ALL);

if (__FILE__ == '')
{
    die('Fatal error code: 0');
}

/* 初始化设置 */
@ini_set('memory_limit',          '64M');
@ini_set('session.cache_expire',  180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies',   1);
@ini_set('session.auto_start',    0);
@ini_set('display_errors',        1);

if (DIRECTORY_SEPARATOR == '\\')
{
    @ini_set('include_path',      '.;' . ROOT_PATH);
}
else
{
    @ini_set('include_path',      '.:' . ROOT_PATH);
}

include('config.php');
include('global_inc.php');

define('ROOT_PATH', str_replace('includes/init_local.php', '', str_replace('\\', '/', __FILE__)));

if (defined('DEBUG_MODE') == false)
{
    define('DEBUG_MODE', 0);
}

if (PHP_VERSION >= '5.1' && !empty($timezone))
{
    date_default_timezone_set($timezone);
}

if (isset($_SERVER['PHP_SELF']))
{
    define('PHP_SELF', $_SERVER['PHP_SELF']);
}
else
{
    define('PHP_SELF', $_SERVER['SCRIPT_NAME']);
}

require(ROOT_PATH . 'includes/lib_time.php');

/* 对用户传入的变量进行转义操作。*/
if (!get_magic_quotes_gpc())
{
    if (!empty($_GET))
    {
        $_GET  = addslashes_deep($_GET);
    }
    if (!empty($_POST))
    {
        $_POST = addslashes_deep($_POST);
    }

    $_COOKIE   = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
}

/* 对路径进行安全处理 */
if (strpos(PHP_SELF, '.php/') !== false)
{
    los_header("Location:" . substr(PHP_SELF, 0, strpos(PHP_SELF, '.php/') + 4) . "\n");
    exit();
}

/* 初始化数据库类 */
require(ROOT_PATH . 'includes/cls_mysql.php');
$db = new cls_mysql('210.51.166.148', 'yucheng', 'yucheng', 'shop');

/* 初始化 action */
if (!isset($_REQUEST['act']))
{
    $_REQUEST['act'] = '';
}
elseif (($_REQUEST['act'] == 'login' || $_REQUEST['act'] == 'logout' || $_REQUEST['act'] == 'signin') &&
    strpos(PHP_SELF, '/privilege.php') === false)
{
    $_REQUEST['act'] = '';
}
elseif (($_REQUEST['act'] == 'forget_pwd' || $_REQUEST['act'] == 'reset_pwd' || $_REQUEST['act'] == 'get_pwd') &&
    strpos(PHP_SELF, '/get_password.php') === false)
{
    $_REQUEST['act'] = '';
}

if (!file_exists('temp/caches'))
{
    @mkdir('temp/caches', 0777);
    @chmod('temp/caches', 0777);
}

if (!file_exists('temp/compiled'))
{
    @mkdir('temp/compiled', 0777);
    @chmod('temp/compiled', 0777);
}

clearstatcache();

/* 创建 Smarty 对象。*/
require(ROOT_PATH . 'includes/cls_template.php');
$smarty = new cls_template;

$smarty->template_dir  = ROOT_PATH .'/templates';
$smarty->compile_dir   = ROOT_PATH . 'temp/compiled';
if ((DEBUG_MODE & 2) == 2)
{
    $smarty->force_compile = true;
}



//header('Cache-control: private');
header('content-type: text/html; charset=' . EC_CHARSET);
header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

if ((DEBUG_MODE & 1) == 1)
{
    error_reporting(E_ALL);
}
else
{
    error_reporting(E_ALL ^ E_NOTICE);
}
if ((DEBUG_MODE & 4) == 4)
{
    include(ROOT_PATH . 'includes/lib.debug.php');
}
function addslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}
?>

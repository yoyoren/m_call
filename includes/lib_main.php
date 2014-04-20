<?php

/**
 * ģԼӵַ
 *
 * @access      public
 * @param       string      $directory      ŵĿ¼
 * @return      array
 */
function read_modules($directory = '.')
{
    global $_LANG;

    $dir         = @opendir($directory);
    $set_modules = true;
    $modules     = array();

    while (false !== ($file = @readdir($dir)))
    {
        if (preg_match("/^.*?\.php$/", $file))
        {
            include_once($directory. '/' .$file);
        }
    }
    @closedir($dir);
    unset($set_modules);

    foreach ($modules AS $key => $value)
    {
        ksort($modules[$key]);
    }
    ksort($modules);

    return $modules;
}
/**
 *  ָ׺ģ建ļ
 *
 * @access  public
 * @param  bool       $is_cache  Ƿ滹ļ
 * @param  string     $ext       Ҫɾļ׺
 *
 * @return int        ļ
 */
function clear_tpl_files($is_cache = true, $ext = '')
{
    $dirs = array();

    if ($is_cache)
    {
        $dirs[] = ROOT_PATH . 'templates/caches/';
    }
    else
    {
        $dirs[] = ROOT_PATH . 'templates/compiled/';
        $dirs[] = ROOT_PATH . 'templates/compiled/admin/';
    }

    $str_len = strlen($ext);
    $count   = 0;

    foreach ($dirs AS $dir)
    {
        $folder = @opendir($dir);

        if ($folder === false)
        {
            continue;
        }

        while ($file = readdir($folder))
        {
            if ($file == '.' || $file == '..' || $file == 'index.htm' || $file == 'index.html')
            {
                continue;
            }
            if (is_file($dir . $file))
            {
                /* ļжǷƥ */
                $pos = ($is_cache) ? strrpos($file, '_') : strrpos($file, '.');

                if ($str_len > 0 && $pos !== false)
                {
                    $ext_str = substr($file, 0, $pos);

                    if ($ext_str == $ext)
                    {
                        if (@unlink($dir . $file))
                        {
                            $count++;
                        }
                    }
                }
                else
                {
                    if (@unlink($dir . $file))
                    {
                        $count++;
                    }
                }
            }
        }
        closedir($folder);
    }

    return $count;
}

/**
 * ģļ
 *
 * @access  public
 * @param   mix     $ext    ģļ ׺
 * @return  void
 */
function clear_compiled_files($ext = '')
{
    return clear_tpl_files(false, $ext);
}

/**
 * ļ
 *
 * @access  public
 * @param   mix     $ext    ģļ ׺
 * @return  void
 */
function clear_cache_files($ext = '')
{
    return clear_tpl_files(true, $ext);
}

/**
 * ģͻļ
 *
 * @access  public
 * @param   mix     $ext    ģļ׺
 * @return  void
 */
function clear_all_files($ext = '')
{
    return clear_tpl_files(false, $ext) + clear_tpl_files(true,  $ext);
}

/**
 *  SQL ļ
 *
 * @access  public
 * @param   mix     $clearall    Ƿһлļ
 * @return  void
 */
function clear_sqlcache_files($clearall = false)
{
    $dir = ROOT_PATH . 'templates/caches/';
    $folder = @opendir($dir);
    if ($folder === false)
    {
        return false;
    }

    $count = 0;
    $time = time();
    while ($file = readdir($folder))
    {
        if (substr($file, 0, 9) != 'sqlcache_')
        {
            continue;
        }
        if (filemtime($dir . $file) < $time - 300)
        {
            @unlink($dir . $file);

            if ($clearall == false && $count++ > 3000)
            {
                break;
            }
        }
    }
    closedir($folder);

    return true;
}
/**
 * ȡUTF-8ַĺ
 *
 * @param   string      $str        ȡַ
 * @param   int         $length     ȡĳ
 * @param   bool        $append     Ƿ񸽼ʡԺ
 *
 * @return  string
 */
function sub_str($str, $length = 0, $append = true)
{
    $str = trim($str);
    $strlength = strlen($str);

    if ($length == 0 || $length >= $strlength)
    {
        return $str;
    }
    elseif ($length < 0)
    {
        $length = $strlength + $length;
        if ($length < 0)
        {
            $length = $strlength;
        }
    }

    if (function_exists('mb_substr'))
    {
        $newstr = mb_substr($str, 0, $length, EC_CHARSET);
    }
    elseif (function_exists('iconv_substr'))
    {
        $newstr = iconv_substr($str, 0, $length, EC_CHARSET);
    }
    else
    {
        //$newstr = trim_right(substr($str, 0, $length));
        $newstr = substr($str, 0, $length);
    }

    if ($append && $str != $newstr)
    {
        $newstr .= '...';
    }

    return $newstr;
}
/**
 * ַĳȣְַ㣩
 *
 * @param   string      $str        ַ
 *
 * @return  int
 */
function str_len($str)
{
    $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));

    if ($length)
    {
        return strlen($str) - $length + intval($length / 3) * 2;
    }
    else
    {
        return strlen($str);
    }
}

/**
 * JSONݵĲת
 *
 * @param string $str
 * @return string
 */
function json_str_iconv($str)
{
    if (EC_CHARSET != 'utf-8')
    {
        if (is_string($str))
        {
            return ecs_iconv('utf-8', EC_CHARSET, $str);
        }
        elseif (is_array($str))
        {
            foreach ($str as $key => $value)
            {
                $str[$key] = json_str_iconv($value);
            }
            return $str;
        }
        elseif (is_object($str))
        {
            foreach ($str as $key => $value)
            {
                $str->$key = json_str_iconv($value);
            }
            return $str;
        }
        else
        {
            return $str;
        }
    }
    return $str;
}
function ecs_iconv($source_lang, $target_lang, $source_string = '')
{
    static $chs = NULL;

    /* ַΪջַҪתֱӷ */
    if ($source_lang == $target_lang || $source_string == '' || preg_match("/[\x80-\xFF]+/", $source_string) == 0)
    {
        return $source_string;
    }

    if ($chs === NULL)
    {
        require_once(ROOT_PATH . 'includes/cls_iconv.php');
        $chs = new Chinese(ROOT_PATH);
    }

    return $chs->Convert($source_lang, $target_lang, $source_string);
}
function sys_msg($msg_detail, $msg_type = 0, $links = array(), $auto_redirect = false)
{
    if (count($links) == 0)
    {
        $links[0]['text'] = 'һҳ';
        $links[0]['href'] = 'javascript:history.go(-1)';
    }

    assign_query_info();

    $GLOBALS['smarty']->assign('ur_here',     'ϵͳʾ');
    $GLOBALS['smarty']->assign('msg_detail',  $msg_detail);
    $GLOBALS['smarty']->assign('msg_type',    $msg_type);
    $GLOBALS['smarty']->assign('links',       $links);
    $GLOBALS['smarty']->assign('default_url', $links[0]['href']);
    $GLOBALS['smarty']->assign('auto_redirect', $auto_redirect);

    $GLOBALS['smarty']->display('message.htm');

    exit;
}
function sys_msgn($msg_detail, $msg_type = 0, $links = array(), $auto_redirect = false)
{
    if (count($links) == 0)
    {
        $links[0]['text'] = 'һҳ';
        $links[0]['href'] = 'javascript:history.go(-1)';
    }

    assign_query_info();

    $GLOBALS['smarty']->assign('ur_here',     'ϵͳʾ');
    $GLOBALS['smarty']->assign('msg_detail',  $msg_detail);
    $GLOBALS['smarty']->assign('msg_type',    $msg_type);
    $GLOBALS['smarty']->assign('links',       $links);
    $GLOBALS['smarty']->assign('default_url', $links[0]['href']);
    $GLOBALS['smarty']->assign('auto_redirect', $auto_redirect);

    $GLOBALS['smarty']->display('message.htm');

    exit;
}
/**
 * ¼ԱĲ
 *
 * @access  public
 * @param   string      $sn         ݵΨһֵ
 * @param   string      $action     
 * @param   string      $content    
 * @return  void
 */
function admin_log($sn = '', $action, $content)
{
    $log_info = $GLOBALS['_LANG']['log_action'][$action] . $GLOBALS['_LANG']['log_action'][$content] .': '. addslashes($sn);

    $sql = 'INSERT INTO ' . $GLOBALS['ecs']->table('admin_log') . ' (log_time, user_id, log_info, ip_address) ' .
            " VALUES ('" . gmtime() . "', $_SESSION[admin_id], '" . stripslashes($log_info) . "', '" . real_ip() . "')";
    $GLOBALS['db']->query($sql);
}

/**
 * ͨ���ύձϳΪ"2004-05-10"ĸʽ
 *
 * ˺ͨsmartyhtml_select_dateɵڡ
 *
 * @param  string $prefix      ձĹͬǰ׺
 * @return date                ڱ
 */
function sys_joindate($prefix)
{
    /* --յڸʽ */
    $year  = empty($_POST[$prefix . 'Year']) ? '0' :  $_POST[$prefix . 'Year'];
    $month = empty($_POST[$prefix . 'Month']) ? '0' : $_POST[$prefix . 'Month'];
    $day   = empty($_POST[$prefix . 'Day']) ? '0' : $_POST[$prefix . 'Day'];

    return $year . '-' . $month . '-' . $day;
}

/**
 * ùԱsession
 *
 * @access  public
 * @param   integer $user_id        Ա
 * @param   string  $username       Ա
 * @param   string  $action_list    Ȩб
 * @param   string  $last_time      ¼ʱ
 * @return  void
 */
function set_admin_session($user_id, $username, $action_list, $last_time)
{
    $_SESSION['admin_id']    = $user_id;
    $_SESSION['admin_name']  = $username;
    $_SESSION['action_list'] = $action_list;
    $_SESSION['last_check']  = $last_time; // ڱһμ鶩ʱ
}

/**
 * жϹԱĳһǷȨޡ
 *
 * ݵǰӦaction_codeȻٺûsessionaction_listƥ䣬ԴǷԼִС
 * @param     string    $priv_str    Ӧpriv_str
 * @param     string    $msg_type       ص
 * @return true/false
 */
function admin_priv($priv_str, $msg_type = '' , $msg_output = true)
{

    if ($_SESSION['action_list'] == 'all')
    {
        return true;
    }

    if (strpos(',' . $_SESSION['action_list'] . ',', ',' . $priv_str . ',') === false)
    {
        $link[] = array('text' => 'һҳ', 'href' => 'javascript:history.back(-1)');
        if ( $msg_output)
        {
            sys_msg('ûиȨޣ', 0, $link);
        }
        return false;
    }
    else
    {
        return true;
    }
}

/**
 * ԱȨ
 *
 * @access  public
 * @param   string  $authz
 * @return  boolean
 */
function check_authz($authz)
{
    return (preg_match('/,*'.$authz.',*/', $_SESSION['action_list']) || $_SESSION['action_list'] == 'all');
}

/**
 * ԱȨޣJSONʽ
 *
 * @access  public
 * @param   string  $authz
 * @return  void
 */
function check_authz_json($authz)
{
    if (!check_authz($authz))
    {
        make_json_error('޴Ȩޣ');
    }
}



/**
 *  ַб
 *
 * @access  public
 * @param
 *
 * @return void
 */
function get_charset_list()
{
    return array(
        'UTF8'   => 'UTF-8',
        'GB2312' => 'GB2312/GBK',
        'BIG5'   => 'BIG5',
    );
}


/**
 * һJSONʽ
 *
 * @access  public
 * @param   string      $content
 * @param   integer     $error
 * @param   string      $message
 * @param   array       $append
 * @return  void
 */
function make_json_response($content='', $error="0", $message='', $append=array())
{
    include_once(ROOT_PATH . 'includes/cls_json.php');

    $json = new JSON;
    $res = array('error' => $error, 'message' => $message, 'content' => $content);

    if (!empty($append))
    {
        foreach ($append AS $key => $val)
        {
            $res[$key] = $val;
        }
    }
   // print_r($val); 
    $val = $json->encode($res);
	   
    exit($val);
}

/**
 *
 *
 * @access  public
 * @param
 * @return  void
 */
function make_json_result($content, $message='', $append=array())
{
	make_json_response($content, 0, $message, $append);
}

/**
 * һJSONʽĴϢ
 *
 * @access  public
 * @param   string  $msg
 * @return  void
 */
function make_json_error($msg)
{
    make_json_response('', 1, $msg);
}


/**
 * ҳϢ
 *
 * @access  public
 * @return  array
 */
function page_and_size($filter)
{
    if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0)
    {
        $filter['page_size'] = intval($_REQUEST['page_size']);
    }
    elseif (isset($_COOKIE['LOS']['page_size']) && intval($_COOKIE['LOS']['page_size']) > 0)
    {
        $filter['page_size'] = intval($_COOKIE['LOS']['page_size']);
    }
    else
    {
        $filter['page_size'] = 15;
    }

    /* ÿҳʾ */
    $filter['page'] = (empty($_REQUEST['page']) || intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

    /* page  */
    $filter['page_count'] = (!empty($filter['record_count']) && $filter['record_count'] > 0) ? ceil($filter['record_count'] / $filter['page_size']) : 1;

    /* ߽紦 */
    if ($filter['page'] > $filter['page_count'])
    {
        $filter['page'] = $filter['page_count'];
    }

    $filter['start'] = ($filter['page'] - 1) * $filter['page_size'];

    return $filter;
}

/**
 *  еλתֽ
 *
 * @access  public
 * @param   string      $val        λ
 *
 * @return  int         $val
 */
function return_bytes($val)
{
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    switch($last)
    {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

/**
 * Ӻ׺
 */
function list_link_postfix()
{
    return 'uselastfilter=1';
}

/**
 * 
 * @param   array   $filter     
 * @param   string  $sql        ѯ
 * @param   string  $param_str  ַlistĲ
 */
function set_filter($filter, $sql, $param_str = '')
{
    $filterfile = basename(PHP_SELF, '.php');
    if ($param_str)
    {
        $filterfile .= $param_str;
    }
    setcookie('LOS[lastfilterfile]', sprintf('%X', crc32($filterfile)), time() + 600);
    setcookie('LOS[lastfilter]',     urlencode(serialize($filter)), time() + 600);
    setcookie('LOS[lastfiltersql]',  urlencode($sql), time() + 600);
}

/**
 * ȡϴεĹ
 * @param   string  $param_str  ַlistĲ
 * @return  Уarray('filter' => $filter, 'sql' => $sql)򷵻false
 */
function get_filter($param_str = '')
{
    $filterfile = basename(PHP_SELF, '.php');
    if ($param_str)
    {
        $filterfile .= $param_str;
    }
    if (isset($_GET['uselastfilter']) && isset($_COOKIE['LOS']['lastfilterfile'])
        && $_COOKIE['LOS']['lastfilterfile'] == sprintf('%X', crc32($filterfile)))
    {
        return array(
            'filter' => unserialize(urldecode($_COOKIE['LOS']['lastfilter'])),
            'sql'    => urldecode($_COOKIE['LOS']['lastfiltersql'])
        );
    }
    else
    {
        return false;
    }
}

/**
 * URL
 * @param   string  $url  ַһurldַ,urlַУ
 * @return  Уurl;
 */
function sanitize_url($url , $check = 'http://')
{
    if (strpos( $url, $check ) === false)
    {
        $url = $check . $url;
    }
    return $url;
}

/**
 * ȡϢ
 * @param   int  $type طʽ
 * @return  Уurl;
 */
function get_timeplan($type)
{
	$sql = "SELECT id, shipping_timeplan_name FROM view_shipping_timeplan ORDER BY id ASC ";
    $rs = $GLOBALS['db']->query($sql);

    $res = array();
    while ($row = $GLOBALS['db']->FetchRow($rs))
    {
        if ($type == 1)
		{
		    $res[$row['id']] = $row['shipping_timeplan_name'];		
		}
		else
		{
		    $res[$row['shipping_timeplan_name']] = $row['shipping_timeplan_name'];		
		}
    }
    return $res;
}

function get_sender($st)
{
	$sql = "SELECT id,shipping_station_id,employee_name FROM view_shipping_deliveryplan where date = '".date('Y-m-d')."' ";
	$sql .= $st ? " and shipping_station_id = '$st'" : '';
    $rs = $GLOBALS['db']->getAll($sql);

    return $rs;
}
function route_info($route_id)
{
   $sql = "select route_name from ship_route where route_id = " . $route_id;
   return $rs = $GLOBALS['db']->getOne($sql);
}
?>